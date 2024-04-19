<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Enums\ViewType;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;
use Simtabi\Laranail\Barua\Support\Helpers;

class MailBase extends Mailable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Model|null $user;

    protected ErrorBuilder $errorBuilder;

    protected MailBuilder $mailBuilder;

    protected DataBuilder $dataBuilder;

    protected ViewType|null $viewType;

    protected string $className;

    protected array $options = [];

    /**
     * Create a new message instance.
     *
     * @param MailBuilder $mailBuilder
     * @param ErrorBuilder $errorBuilder
     * @param string $className
     * @param DataBuilder $dataBuilder
     */
    public function __construct(MailBuilder $mailBuilder, DataBuilder $dataBuilder, ErrorBuilder $errorBuilder, string $className)
    {
        $this->mailBuilder  = $mailBuilder;
        $this->errorBuilder = $errorBuilder;
        $this->dataBuilder  = $dataBuilder;
        $this->className    = $className;

        $this->viewType     = $this->mailBuilder->getViewType() ?? ViewType::HTML;
    }

    /**
     * Build the message.
     *
     * @return Mailable|bool
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
            dataBuilder: $this->dataBuilder
        );
    }

    public function setSubject(string $subject): static
    {
        $this->options['subject'] = $subject;

        return $this;
    }

    public function getSubject(): string|null
    {
        return $this->options['subject'] ?? null;
    }

    public function setView(string $view): static
    {
        $this->options['view'] = $view;

        return $this;
    }

    public function getView(): string|null
    {
        return $this->options['view'] ?? null;
    }

}
