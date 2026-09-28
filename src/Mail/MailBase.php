<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Simtabi\Laranail\Barua\Enums\ViewType;
use Illuminate\Foundation\Bus\Dispatchable;
use Simtabi\Laranail\Barua\Support\Helpers;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;

/**
 * Base for barua's mailables.
 *
 * Not ShouldQueue: that made every barua mailable queue even when sent with
 * `sendEmail(queued: false)` or `Mail::send()`. Queue explicitly instead, with
 * `sendEmail(queued: true)` or `Mail::queue()`; Queueable keeps that working.
 */
class MailBase extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ?Model $user = null;

    protected ?ViewType $viewType;

    /**
     * @var array{subject?: string, view?: string}
     */
    protected array $options = [];

    /**
     * Create a new message instance.
     */
    public function __construct(protected MailBuilder $mailBuilder, protected DataBuilder $dataBuilder, protected ErrorBuilder $errorBuilder, protected string $className)
    {
        $this->viewType = $this->mailBuilder->getViewType() ?? ViewType::HTML;
    }

    /**
     * Build the message.
     *
     * @throws BaruaException
     */
    public function build(): Mailable|bool
    {
        return Helpers::addView(
            mailBuilder: $this->mailBuilder,
            mailable: $this,
            errorBuilder: $this->errorBuilder,
            className: $this->className,
            viewType: $this->viewType,
            dataBuilder: $this->dataBuilder,
        );
    }

    public function setSubject(string $subject): static
    {
        $this->options['subject'] = $subject;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->options['subject'] ?? null;
    }

    public function setView(string $view): static
    {
        $this->options['view'] = $view;

        return $this;
    }

    public function getView(): ?string
    {
        return $this->options['view'] ?? null;
    }
}
