<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Plugins;

use Throwable;
use DOMDocument;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mime\Part\TextPart;
use Illuminate\Mail\Events\MessageSending;
use Symfony\Component\Mime\Part\AbstractPart;
use Symfony\Component\Mailer\Event\MessageEvent;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Symfony\Component\Mime\Part\AbstractMultipartPart;

/**
 * Inlines CSS into outgoing HTML mail.
 *
 * Two sources: the configured stylesheets, always; and any `<link rel="stylesheet">`
 * in the message body, which is removed from the markup and its file inlined.
 *
 * A link's href is only followed when it names a `.css` file inside the public
 * directory. Previously any href went to `file_get_contents()`, so a template, or data
 * rendered into one, could inline a local file (`/etc/passwd`) or make the server fetch
 * a URL. Anything else is dropped and logged.
 */
final readonly class CssInlinerPlugin
{
    private CssToInlineStyles $converter;

    private string $cssToAlwaysInclude;

    private string $publicPath;

    /**
     * @param list<string> $filesToInline absolute paths, always inlined
     */
    public function __construct(array $filesToInline = [], ?string $publicPath = null, ?CssToInlineStyles $converter = null)
    {
        $this->converter = $converter ?? new CssToInlineStyles;
        $this->publicPath = rtrim((string) realpath($publicPath ?? public_path()), DIRECTORY_SEPARATOR);
        $this->cssToAlwaysInclude = $this->readFiles($filesToInline);
    }

    public function handle(MessageSending $event): void
    {
        $this->handleSymfonyEmail($event->message);
    }

    public function handleSymfonyEvent(MessageEvent $event): void
    {
        $message = $event->getMessage();

        if ($message instanceof Email) {
            $this->handleSymfonyEmail($message);
        }
    }

    private function handleSymfonyEmail(Email $message): void
    {
        try {
            $body = $message->getBody();

            if ($body instanceof TextPart) {
                $message->setBody($this->processPart($body));
            } elseif ($body instanceof AbstractMultipartPart) {
                $message->setBody($this->processPart($body));
            }
        } catch (Throwable $e) {
            // Degrade: send the message uninlined rather than not at all.
            Log::warning('laranail/barua: CSS inlining skipped: ' . $e->getMessage());
        }
    }

    private function processPart(AbstractPart $part): AbstractPart
    {
        if ($part instanceof TextPart && $part->getMediaType() === 'text' && $part->getMediaSubtype() === 'html') {
            return $this->processHtmlTextPart($part);
        }

        if ($part instanceof AbstractMultipartPart) {
            $class = $part::class;

            return new $class(...array_map($this->processPart(...), $part->getParts()));
        }

        return $part;
    }

    private function processHtmlTextPart(TextPart $part): TextPart
    {
        [$linked, $html] = $this->extractLinkedStylesheets($part->getBody());

        $html = $this->converter->convert($html, $this->cssToAlwaysInclude . "\n" . $this->readFiles($linked));

        $charset = $part->getPreparedHeaders()->getHeaderParameter('Content-Type', 'charset') ?: 'utf-8';

        return new TextPart($html, $charset, 'html');
    }

    /**
     * @return array{0: list<string>, 1: string} the resolved local files, and the body without the link tags
     */
    private function extractLinkedStylesheets(string $html): array
    {
        if (stripos($html, '<link') === false) {
            return [[], $html];
        }

        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML($html);
        libxml_use_internal_errors($previous);

        $tags = [];

        foreach ($document->getElementsByTagName('link') as $tag) {
            if (strtolower($tag->getAttribute('rel')) === 'stylesheet') {
                $tags[] = $tag;
            }
        }

        if ($tags === []) {
            return [[], $html];
        }

        $files = [];

        foreach ($tags as $tag) {
            $href = $tag->getAttribute('href');
            $file = $this->resolvePublicStylesheet($href);

            if ($file === null) {
                Log::warning("laranail/barua: stylesheet not inlined, not a .css file in the public directory: {$href}");
            } else {
                $files[] = $file;
            }

            $tag->parentNode?->removeChild($tag);
        }

        return [$files, (string) $document->saveHTML()];
    }

    /**
     * The absolute path of a `.css` file inside the public directory that `$href` names,
     * or null. Accepts a path relative to the public directory, or an absolute path.
     */
    private function resolvePublicStylesheet(string $href): ?string
    {
        if ($this->publicPath === '' || $href === '' || preg_match('#^[a-z][a-z0-9+.-]*://#i', $href) === 1) {
            return null;
        }

        $path = parse_url($href, PHP_URL_PATH);

        if (! is_string($path) || ! str_ends_with(strtolower($path), '.css')) {
            return null;
        }

        $candidate = str_starts_with($path, $this->publicPath) ? $path : $this->publicPath . '/' . ltrim($path, '/');
        $real = realpath($candidate);

        if ($real === false || ! is_file($real) || ! str_starts_with($real, $this->publicPath . DIRECTORY_SEPARATOR)) {
            return null;
        }

        return $real;
    }

    /**
     * @param list<string> $files
     */
    private function readFiles(array $files): string
    {
        $css = '';

        foreach ($files as $file) {
            if (is_file($file) && is_readable($file)) {
                $css .= file_get_contents($file) . "\n";
            }
        }

        return $css;
    }
}
