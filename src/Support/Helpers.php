<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Support;

use stdClass;
use Illuminate\Support\Str;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Simtabi\Laranail\Barua\Enums\ViewType;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;

final class Helpers
{
    /** The view and translation namespace: `view('laranail/barua::...')`. */
    public const string VIEW_NAMESPACE = 'laranail/barua';

    /** The Blade component prefix: `<x-laranail-barua::text />`. Blade tags cannot hold a slash. */
    public const string COMPONENT_PREFIX = 'laranail-barua';

    /** The config key the package registers under. */
    public const string CONFIG_KEY = 'laranail.barua';

    public static function url(?string $path = null): string
    {
        return rtrim(Request::getSchemeAndHttpHost(), '/') . (empty($path) ? '' : '/' . ltrim($path, '/'));
    }

    public static function isDevModeEnable(): bool
    {
        return (bool) config(self::CONFIG_KEY . '.dev_mode', false);
    }

    public static function isSendMailEnabled(): bool
    {
        return (bool) config(self::CONFIG_KEY . '.enable_send_mail', true);
    }

    public static function isThrowErrors(): bool
    {
        return (bool) config(self::CONFIG_KEY . '.throw_errors', false);
    }

    public static function isCssInliningEnabled(): bool
    {
        return (bool) config(self::CONFIG_KEY . '.inline_css', true);
    }

    /**
     * The configured maximum attachment size, in bytes; 0 when unset or unreadable.
     */
    public static function getDefaultMaximumFileSize(): int
    {
        $size = self::humanReadableToBytes((string) config(self::CONFIG_KEY . '.max_file_size', ''));

        return $size === false ? 0 : (int) $size;
    }

    /**
     * @return list<string>
     */
    public static function getAllowedMimeTypes(): array
    {
        $types = config(self::CONFIG_KEY . '.allowed_mime_types', []);

        if (is_string($types)) {
            $types = TextFormatter::text2array($types);
        }

        return array_values(array_map(
            static fn (mixed $type): string => strtolower(trim((string) $type)),
            array_filter((array) $types, static fn (mixed $type): bool => is_string($type) && trim($type) !== ''),
        ));
    }

    public static function getSender(): stdClass
    {
        $data = (array) config(self::CONFIG_KEY . '.sender', []);

        return (object) [
            'email' => $data['email'] ?? null,
            'name'  => $data['name'] ?? null,
        ];
    }

    /**
     * @return class-string<Model>
     *
     * @throws BaruaException
     */
    public static function getUserModel(): string
    {
        $configKey = self::CONFIG_KEY . '.user_class';
        $userClass = config($configKey);

        if (! is_string($userClass) || $userClass === '') {
            throw new BaruaException("The '{$configKey}' configuration is not set or not a valid class name.");
        }

        if (! class_exists($userClass)) {
            throw new BaruaException("The configured class {$userClass} does not exist.");
        }

        if (! is_subclass_of($userClass, Model::class)) {
            throw new BaruaException("The configured class {$userClass} is not an Eloquent model.");
        }

        return $userClass;
    }

    /**
     * @return array{path: string, name: string, mime: string|false, size: int|false}
     */
    public static function getFileInfo(string $path): array
    {
        return [
            'path' => $path,
            'name' => basename($path),
            'mime' => is_file($path) ? mime_content_type($path) : false,
            'size' => is_file($path) ? filesize($path) : false,
        ];
    }

    /**
     * "12MB", "1.5 GB", "2048" (bytes) and so on, to bytes; false for anything else.
     */
    public static function humanReadableToBytes(string $size): float|false
    {
        $unitMultipliers = [
            'B'  => 1,
            'KB' => 1024,
            'MB' => 1024 ** 2,
            'GB' => 1024 ** 3,
            'TB' => 1024 ** 4,
            'PB' => 1024 ** 5,
            'EB' => 1024 ** 6,
            'ZB' => 1024 ** 7,
            'YB' => 1024 ** 8,
        ];

        $size = strtoupper(str_replace(' ', '', $size));

        if (is_numeric($size)) {
            return (float) $size;
        }

        if (preg_match('/^([0-9.]+)([A-Z]+)$/', $size, $matches) && isset($unitMultipliers[$matches[2]])) {
            return (float) $matches[1] * $unitMultipliers[$matches[2]];
        }

        return false;
    }

    /**
     * @param array<array-key, string>|string $allowedMimeTypes
     */
    public static function isValidMimeType(string $mimeType, array|string $allowedMimeTypes): bool
    {
        $mimeType = strtolower($mimeType);

        if (is_string($allowedMimeTypes)) {
            $allowedMimeTypes = TextFormatter::text2array($allowedMimeTypes);
        }

        foreach ($allowedMimeTypes as $type) {
            $type = strtolower($type);

            if ($mimeType === $type || str_contains($type, "/{$mimeType}") || "application/{$mimeType}" === $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<array-key, mixed> $data
     * @param list<string> $keys
     * @param array<array-key, mixed> $value
     *
     * @return array<array-key, mixed>
     */
    public static function setNestedData(array $data, array $keys, array $value): array
    {
        if ($keys === []) {
            return array_merge($data, $value);
        }

        $key = array_shift($keys);

        if (! isset($data[$key]) || ! is_array($data[$key])) {
            $data[$key] = [];
        }

        $data[$key] = self::setNestedData($data[$key], $keys, $value);

        return $data;
    }

    public static function bladeFileExists(string $view): bool
    {
        return View::exists($view);
    }

    /**
     * Attach the builder's files to the mailable.
     */
    public static function addAttachmentToMail(Mailable $email, MailBuilder $builderService): Mailable
    {
        foreach ($builderService->getAttachments() as $attachment) {
            $email->attach($attachment['path'], [
                'mime' => $attachment['mime'],
                'as'   => $attachment['name'] ?: Str::slug($attachment['mime'] . '-' . time()),
            ]);
        }

        return $email;
    }

    /**
     * Render the builder's view into the mailable as HTML, Markdown or plain text.
     *
     * @throws BaruaException
     */
    public static function addView(MailBuilder $mailBuilder, Mailable $mailable, ErrorBuilder $errorBuilder, string $className, ?ViewType $viewType, DataBuilder $dataBuilder): Mailable|false
    {
        if (! $viewType instanceof ViewType) {
            $errorBuilder->setErrors("A valid 'View Type' is required for the {$className}.", 'view');

            return false;
        }

        $view = $mailBuilder->getView();

        if (empty($view)) {
            $errorBuilder->setErrors("A valid 'View' file is required for the {$className}.", 'view');

            return false;
        }

        $data = $dataBuilder->getData();

        $mailable = match ($viewType) {
            ViewType::MARKDOWN => $mailable->markdown($view, $data),
            ViewType::HTML     => $mailable->view($view, $data),
            ViewType::TEXT     => $mailable->text($view, $data),
        };

        return self::addAttachmentToMail(email: $mailable, builderService: $mailBuilder);
    }

    public static function getViewPath(string $path): string
    {
        return self::VIEW_NAMESPACE . "::{$path}";
    }
}
