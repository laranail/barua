<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Enums\ViewType;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class  Helpers
{

    public const string NAMESPACE = 'barua';

    public function __construct()
    {
        //
    }

    public static function url(?string $path = null): string
    {
        return rtrim(Request::getSchemeAndHttpHost(), '/') . (!empty($path) ? '/' . ltrim($path, '/') : '');
    }

    public static function isDevModeEnable(): bool
    {
        return config('barua.dev_mode', false);
    }

    public static function isSendMailEnabled(): bool
    {
        return config('barua.enable_send_mail', true);
    }

    public static function isThrowErrors(): bool
    {
        return config('barua.throw_errors', false);
    }

    public static function getDefaultMaximumFileSize(): bool
    {
        return config('barua.max_file_size', false);
    }

    public static function getAllowedMimeTypes(): array
    {
        return config('barua.allowed_mime_types', []);
    }

    public static function getSender(): object
    {
        $data = config('barua.sender', []);

        return (object) [
            'email' => $data['email'] ?? null,
            'name'  => $data['name'] ?? null,
        ];
    }

    /**
     * @throws BaruaException
     */
    public static function getUserModel(): Model|string
    {

        // Retrieve the user class from the configuration.
        $configKey = 'barua.user_class';
        $userClass = config($configKey);

        // Ensure the class name is retrieved and is a string.
        if (is_string($userClass)) {
            // Check if the class exists.
            if (class_exists($userClass)) {
                // Verify the class is indeed an Eloquent model.
                // This is a more reliable check if you specifically want to ensure it's an Eloquent model.
                if (new $userClass instanceof Model) {
                    // The class exists and is an Eloquent model, so proceed with your logic.
                    return $userClass;
                } else {
                    // The class does not extend Eloquent Model.
                    throw new BaruaException("The configured class {$userClass} is not an Eloquent model.");
                }
            } else {
                // The class does not exist.
                throw new BaruaException("The configured class {$userClass} does not exist.");
            }
        } else {
            // Configuration is not set or not a string.
            throw new BaruaException("The '{$configKey}' configuration is not set or not a valid class name.");
        }

    }

    /**
     * Update the namespace in all PHP files within a given directory.
     *
     * @param  string $directory The directory to scan.
     * @param  string $oldNamespace The old namespace to be replaced.
     * @param  string $newNamespace The new namespace to replace with.
     * @return array
     */
    public static function updateNamespaceInDirectory(string $directory, string $oldNamespace, string $newNamespace): array
    {
        $updated = null;
        $skipped = null;
        $data    = [];

        try {

            // Check if the directory exists
            if (!File::exists($directory)) {
                $data['errors'][] = "The directory does not exist: {$directory}";
            }

            // Check if the directory is not empty
            if (empty(File::files($directory))) {
                $data['errors'][] = "The directory is empty: {$directory}";
            }

            if (!empty($data['errors'])) {
                foreach (File::allFiles($directory) as $file) {
                    $filePath = $file->getRealPath();
                    $content = File::get($filePath);

                    // Check if the file contains the old namespace
                    if (str_contains($content, $oldNamespace)) {
                        // Replace the old namespace with the new namespace
                        $updatedContent = str_replace($oldNamespace, $newNamespace, $content);

                        // Save the updated content back to the file
                        File::put($filePath, $updatedContent);

                        $data['updated'][] = $filePath;
                    } else {
                        $data['skipped'][] = $filePath;
                    }
                }
            }

            $array2string = function (array $data, string $key) {
                $array = $data[trim($key)] ?? null;
                if (!empty($array) && is_array($array)) {
                    $array = implode(',', $array);
                }
                return $array;
            };

            $updated = $array2string($data, 'updated');
            $skipped = $array2string($data, 'skipped');

        } catch (DirectoryNotFoundException $e) {
            // Handle the exception
        }

        return [
            'errors'  => $data['errors']  ?? [],
            'updated' => "Updated namespace in [{$updated}]",
            'skipped' => "Skipped [{$skipped}], does not contain the old namespace.",
        ];
    }

    public static function getFileInfo(string $path): array
    {

        return [
            'path' => $path,
            'name' => basename($path),
            'mime' => mime_content_type($path),
            'size' => filesize($path),
        ];
    }

    public static function humanReadableToBytes(string $size): float|bool
    {
        // Define the unit multipliers in powers of 1024
        $unitMultipliers = [
            'B'  => 1,
            'KB' => 1024,
            'MB' => pow(1024, 2),
            'GB' => pow(1024, 3),
            'TB' => pow(1024, 4),
            'PB' => pow(1024, 5),
            'EB' => pow(1024, 6),
            'ZB' => pow(1024, 7),
            'YB' => pow(1024, 8),
        ];

        // Remove spaces and convert to uppercase for uniformity
        $size = strtoupper(str_replace(' ', '', $size));

        // Check if the size is purely numeric, which means it's already in bytes
        if (is_numeric($size)) {
            return (float) $size;
        }

        // Extract the numeric value and unit from the size string if not purely numeric
        if (preg_match('/([0-9.]+)([A-Z]+)/', $size, $matches)) {
            $value = (float) $matches[1];
            $unit  = $matches[2];

            // Calculate and return the size in bytes
            if (isset($unitMultipliers[$unit])) {
                return $value * $unitMultipliers[$unit];
            }
        }

        // Return false if the format is not recognized
        return false;
    }

    public static function isValidMimeType(string $mimeType, array|string $allowedMimeTypes): bool
    {
        // Normalize the input to lower case for case-insensitive comparison
        $mimeType = strtolower($mimeType);

        // Ensure $allowedTypes is an array to accommodate multiple MIME types
        if (is_string($allowedMimeTypes)) {
            $allowedMimeTypes = TextFormatter::text2array($allowedMimeTypes);
        }

        if (empty($allowedMimeTypes)) {
            return false; // No allowed MIME types
        }

        foreach ($allowedMimeTypes as $type) {
            $type = strtolower($type); // Normalize allowed type to lower case

            // Check if the input matches an allowed MIME type or subtype
            if (($mimeType === $type) || str_contains($type, "/$mimeType") || ("application/$mimeType" === $type)) {
                return true; // Valid MIME type
            }
        }

        return false; // No valid MIME type found
    }

    public static function setNestedData(array $data, array $keys, array $value): array
    {
        if (empty($keys)) {
            // If no more keys, merge the value
            return array_merge($data, $value);
        }

        $key = array_shift($keys);
        if (!isset($data[$key]) || !is_array($data[$key])) {
            $data[$key] = [];
        }

        // Recurse into the next level
        $data[$key] = self::setNestedData($data[$key], $keys, $value);

        return $data;
    }

    /**
     * Check if a given Blade file exists in a package namespace.
     *
     * @param string $view The view name, including the package namespace (e.g., 'package::view.name').
     * @return bool True if the view exists, false otherwise.
     */
    public static function bladeFileExists(string $view): bool
    {
        return View::exists($view);
    }

    /**
     * Attach files with automatic MIME type detection and optional renaming to the mail.
     *
     * @param Mailable $email
     * @param MailBuilder $builderService
     * @return Mailable
     */
    public static function addAttachmentToMail(Mailable $email, MailBuilder $builderService): Mailable
    {
        $attachments = $builderService->getAttachments();
        if (is_array($attachments) && !empty($attachments)) {
            foreach ($attachments as $attachment) {
                $mime = $attachment['mime'] ?? null;
                $name = $attachment['name'] ?? Str::slug($mime . "-" . time() . "-" . rand(0, 9999));
                $path = $attachment['path'] ?? null;

                if (!empty($name) || !empty($path) || !empty($mime)) {
                    $email->attach($path, [
                        'mime' => $mime,
                        'as'   => $name,
                    ]);
                }

            }
        }

        return $email;
    }

    /**
     * Add a view to the email.
     *
     * @param MailBuilder $mailBuilder
     * @param Mailable $mailable
     * @param ErrorBuilder $errorBuilder
     * @param string $className
     * @param ViewType|null $viewType
     * @param DataBuilder $dataBuilder
     * @return Mailable|bool
     * @throws BaruaException
     */
    public static function addView(MailBuilder $mailBuilder, Mailable $mailable, ErrorBuilder $errorBuilder, string $className, ?ViewType $viewType, DataBuilder $dataBuilder): Mailable|bool
    {
        if (!$viewType instanceof ViewType) {
            $errorBuilder->setErrors("A valid 'View Type' is required for the {$className}.", 'view');
            return false;
        }

        // Get the view type
        $viewType = $viewType->getType();
        $view     = $mailBuilder->getView();

        // Check if the view file exists
        if (empty($view)) {
            $errorBuilder->setErrors("A valid 'View' file is required for the {$className}.", 'view');
            return false;
        }

        // Get the data to be passed to the view
        $data = $dataBuilder->getData();

        // Add the view to the mailable
        if (ViewType::MARKDOWN->value === $viewType) {
            $view = $mailable->markdown($view, $data);
        } elseif (ViewType::HTML->value === $viewType) {
            $view = $mailable->view($view, $data);
        } elseif (ViewType::TEXT->value === $viewType) {
            $view = $mailable->text($view, $data);
        } else {
            $errorBuilder->setErrors("Invalid view type '{$viewType}' for the {$className}.", 'view');
            return false;
        }

        // Add the attachments to the email
        return self::addAttachmentToMail(email: $view, builderService: $mailBuilder);
    }

    public static function getViewPath(string $path): string
    {
        return Helpers::NAMESPACE . "::{$path}";
    }

}