<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Builders;

use Simtabi\Laranail\Barua\Enums\ViewType;
use Simtabi\Laranail\Barua\Support\Helpers;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;

class MailBuilder
{
    /**
     * @var array{email?: string, name?: string|null}
     */
    protected array $sender = [];

    /**
     * @var array<string, list<array{email: string, name: string|null}>> keyed by to, cc, bcc
     */
    protected array $recipients = [];

    /** @var list<array{path: string, name: string, mime: string}> */
    protected array $attachments = [];

    protected ?string $subject = null;

    protected ?string $view = null;

    /**
     * @var array<string, mixed>
     */
    protected array $variables = [];

    protected ?string $htmlContent = null;

    protected ?string $plainTextContent = null;

    protected DataBuilder $dataBuilder;

    protected ?ViewType $viewType = null;

    public function __construct(protected ErrorBuilder $errorBuilder)
    {
        $this->viewType = ViewType::HTML;
    }

    public function setSender(string $email, ?string $name = null): static
    {
        $this->sender = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * @return array{email?: string, name?: string|null}
     */
    public function getSender(): array
    {
        return $this->sender;
    }

    public function getFromEmail(): ?string
    {
        return $this->sender['email'] ?? null;
    }

    public function getFromName(): ?string
    {
        return $this->sender['name'] ?? null;
    }

    /**
     * Set the recipient(s) of the email with optional names.
     */
    public function setTo(string $email, ?string $name = null): self
    {
        $this->recipients['to'][] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * @return list<array{email: string, name: string|null}>
     */
    public function getTo(): array
    {
        return $this->recipients['to'] ?? [];
    }

    /**
     * Set CC recipient(s) of the email with optional names.
     */
    public function setCc(string $email, ?string $name = null): self
    {
        $this->recipients['cc'][] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * @return list<array{email: string, name: string|null}>
     */
    public function getCc(): array
    {
        return $this->recipients['cc'] ?? [];
    }

    /**
     * Set BCC recipient(s) of the email with optional names.
     */
    public function setBcc(string $email, ?string $name = null): self
    {
        $this->recipients['bcc'][] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * @return list<array{email: string, name: string|null}>
     */
    public function getBcc(): array
    {
        return $this->recipients['bcc'] ?? [];
    }

    /**
     * @return array<string, list<array{email: string, name: string|null}>>
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * Set the email subject.
     *
     * @param string $subject The email subject.
     */
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * Add a view to the email.
     *
     * @param string $view a bundled message (`onboarding.welcome_user`), or any view name with $customPath
     * @param bool $customPath take $view as given instead of under `laranail/barua::messages.`
     *
     * @throws BaruaException
     */
    public function setView(string $view, bool $customPath = false): static
    {

        if (! $customPath) {
            $view = Helpers::getViewPath("messages.{$view}");
        }

        if (! Helpers::bladeFileExists($view)) {
            $this->errorBuilder->setErrors("View file not found: {$view}", 'view');
        }

        $this->view = $view;

        return $this;
    }

    public function getView(): ?string
    {
        return $this->view;
    }

    /**
     * @param array<string, mixed> $variables
     */
    public function setVariables(array $variables): static
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    public function setHtmlContent(?string $htmlContent): self
    {
        $this->htmlContent = $htmlContent;

        return $this;
    }

    public function getHtmlContent(): ?string
    {
        return $this->htmlContent;
    }

    public function setPlainTextContent(?string $plainTextContent): self
    {
        $this->plainTextContent = $plainTextContent;

        return $this;
    }

    public function getPlainTextContent(): ?string
    {
        return $this->plainTextContent;
    }

    /**
     * @return array<string, list<string>>|list<string>
     */
    public function getErrorBuilder(?string $key = null): array
    {
        return $this->errorBuilder->getErrors(key: $key);
    }

    /**
     * Add an attachment. Call it once per file; each call appends.
     *
     * A file that is missing, larger than the limit, or of a MIME type outside
     * `laranail.barua.allowed_mime_types` is skipped and reported to the error builder.
     *
     * @param string $path Path to the file.
     * @param string|null $name The name it is attached as; the file's own name when null.
     * @param string|null $mime The MIME type; detected from the file when null.
     * @param int|float|string|null $maxFileSize Bytes, or a size such as "5MB"; the configured limit when null.
     *
     * @throws BaruaException
     */
    public function setAttachments(string $path, ?string $name = null, ?string $mime = null, int|float|string|null $maxFileSize = null): self
    {
        $key = 'attachments';
        $info = Helpers::getFileInfo($path);

        if ($info['size'] === false) {
            $this->errorBuilder->setErrors("Attachment skipped because the file does not exist: {$path}", $key);

            return $this;
        }

        $mime ??= $info['mime'] === false ? 'application/octet-stream' : $info['mime'];
        $name ??= $info['name'];

        $limit = $maxFileSize === null || $maxFileSize === ''
            ? Helpers::getDefaultMaximumFileSize()
            : Helpers::humanReadableToBytes((string) $maxFileSize);

        if ($limit === false || $limit < 1) {
            $this->errorBuilder->setErrors("The maximum file size '{$maxFileSize}' must be a positive number of bytes or a size such as 5MB.", $key);

            return $this;
        }

        if ($info['size'] > $limit) {
            $this->errorBuilder->setErrors("Attachment skipped due to large size: {$path}", $key);

            return $this;
        }

        if (! in_array(strtolower($mime), Helpers::getAllowedMimeTypes(), true)) {
            $this->errorBuilder->setErrors("Attachment skipped due to invalid MIME type: {$mime}", $key);

            return $this;
        }

        $this->attachments[] = [
            'path' => $info['path'],
            'name' => $name,
            'mime' => $mime,
        ];

        return $this;
    }

    /**
     * @return list<array{path: string, name: string, mime: string}>
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function isMarkdownViewType(): MailBuilder
    {
        $this->viewType = ViewType::MARKDOWN;

        return $this;
    }

    public function isHtmlViewType(): MailBuilder
    {
        $this->viewType = ViewType::HTML;

        return $this;
    }

    public function isTextViewType(): MailBuilder
    {
        $this->viewType = ViewType::TEXT;

        return $this;
    }

    public function getViewType(): ?ViewType
    {
        return $this->viewType;
    }
}
