<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Builders;

use Simtabi\Laranail\Barua\Enums\ViewType;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;
use Simtabi\Laranail\Barua\Support\Helpers;

class MailBuilder
{


    protected array $sender = [];

    protected array $recipients = [];

    protected array $attachments = [];

    protected ?string $subject = null;

    protected string $view;

    protected array $variables = [];

    protected ?string $htmlContent = null;

    protected ?string $plainTextContent = null;

    protected ErrorBuilder $errorBuilder;

    protected DataBuilder $dataBuilder;

    protected ?ViewType $viewType = null;

    public function __construct(ErrorBuilder $errorBuilder)
    {
        $this->errorBuilder = $errorBuilder;
        $this->viewType     = ViewType::HTML;
    }

    public function setSender(string $email, ?string $name = null): static
    {
        $this->sender = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

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
     *
     * @param string $email
     * @param string|null $name
     * @return $this
     */
    public function setTo(string $email, ?string $name = null): self
    {
        $this->recipients['to'][] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    public function getTo(): array
    {
        return $this->recipients['to'] ?? [];
    }

    /**
     * Set CC recipient(s) of the email with optional names.
     *
     * @param string $email
     * @param string|null $name
     * @return $this
     */
    public function setCc(string $email, ?string $name = null): self
    {
        $this->recipients['cc'][] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    public function getCc(): array
    {
        return $this->recipients['cc'] ?? [];
    }

    /**
     * Set BCC recipient(s) of the email with optional names.
     *
     * @param string $email
     * @param string|null $name
     * @return $this
     */
    public function setBcc(string $email, ?string $name = null): self
    {
        $this->recipients['bcc'][] = [
            'email' => $email,
            'name'  => $name,
        ];

        return $this;
    }

    public function getBcc(): array
    {
        return $this->recipients['bcc'] ?? [];
    }

    /**
     * @return array
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * Set the email subject.
     *
     * @param string $subject The email subject.
     * @return $this
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
     * @param string $view
     * @param bool $customPath
     * @return MailBuilder
     * @throws BaruaException
     */
    public function setView(string $view, bool $customPath = false): static
    {

        if (!$customPath) {
            $view = Helpers::NAMESPACE . "::messages.{$view}";
        }

        if (!Helpers::bladeFileExists($view)) {
            $this->errorBuilder->setErrors("View file not found: {$view}", 'view');
        }

        $this->view = $view;

        return $this;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function setVariables(array $variables): static
    {
        $this->variables = $variables;
        return $this;
    }

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

    public function getErrorBuilder(?string $key = null): array
    {
        return $this->errorBuilder->getErrors(key: $key);
    }

    /**
     * Add a single attachment to the email.
     *
     * @param string $path Path to the attachment.
     * @param string|null $name Optional new name for the attachment.
     * @param string|null $mime Optional MIME type. If not provided, it will be auto-detected.
     * @param int|float|string|null $maxFileSize Optional maximum file size. If not provided, the default is used.
     * @return $this
     * @throws BaruaException
     */
    public function setAttachments(string $path, ?string $name = null, ?string $mime = null, int|float|string|null $maxFileSize = null): self
    {
        $key      = 'attachments';
        $info     = Helpers::getFileInfo($path);
        $path     = $info['path'];
        $mime     = $mime ?? $info['mime'];
        $name     = $name ?? $info['name'];
        $fileSize = $info['size'];

        // Validate file size
        if (empty($maxFileSize)) {
            $maxFileSize = Helpers::getDefaultMaximumFileSize();
        }

        // Validate the maximum file size
        if ($maxFileSize < 1) {
            $this->errorBuilder->setErrors("The maximum file size of: '{$maxFileSize}' must be a valid number, and in bytes.", $key);
        }

        // Convert human-readable file sizes to bytes
        $maxFileSize = Helpers::humanReadableToBytes($maxFileSize);
        $fileSize    = Helpers::humanReadableToBytes($fileSize);

        // Check if the file size is too large
        if ($fileSize > $maxFileSize) {
            $this->errorBuilder->setErrors("Attachment skipped due to large size: {$path}", $key);
            return $this;
        }

        // Validate the MIME type
        if (!in_array($mime, Helpers::getAllowedMimeTypes())) {
            $this->errorBuilder->setErrors("Attachment skipped due to invalid MIME type: {$mime}", $key);
            return $this;
        }

        $this->attachments = [
            'path' => $path,
            'name' => $name,
            'mime' => $mime,
        ];

        return $this;
    }

    public function getAttachments(): array
    {
        $attachments = [];

        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $info = Helpers::getFileInfo($attachment['path']);

                $attachments[] = [
                    'name' => ($attachment['name'] ?? $info['name']),
                    'path' => $info['path'],
                    'mime' => $info['mime'],
                ];
            }
        }

        return $attachments;
    }

    /**
     * A utility method to handle addressing for to, cc, and bcc to reduce code duplication.
     *
     * @param string $type The addressing type ('to', 'cc', 'bcc').
     * @throws BaruaException
     */
    private function getAddressing(string $type): array
    {
        $type = strtolower(trim($type));

        // Validate addressing type
        if (!in_array($type, ['to', 'cc', 'bcc'], true)) {
            $this->errorBuilder->setErrors(error: "The addressing type '{$type}' is not supported.", key: 'addressing');
        }

        // Validate method or property existence
        if (!method_exists($this, $type) || !property_exists($this, $type)) {
            $this->errorBuilder->setErrors(error: "The method {$type} does not exist in the class.", key: 'addressing');
        }

        // Get the recipients
        $recipients = $this->recipients[$type] ?? [];
        if (empty($recipients)) {
            $this->errorBuilder->setErrors(error: "No {$type} recipients found.", key: 'addressing');
        }

        $addresses = [];
        foreach ($recipients as $email => $name) {
            $email = trim($email);
            $name  = trim($name);

            if (is_numeric($email)) {
                $addresses[] = $name;
            } else {
                $addresses[] = [
                    'email' => $email,
                    'name'  => $name,
                ];
            }
        }

        return $addresses;
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