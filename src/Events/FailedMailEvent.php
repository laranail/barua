<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Events;

use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;

class FailedMailEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public BaruaException|Exception $exception;
    public MailBuilder $mailBuilder;
    public DataBuilder $dataBuilder;
    public Mailable $mailable;
    public ErrorBuilder $errorBuilder;


    /**
     * Create a new event instance.
     */
    public function __construct(MailBuilder $mailBuilder, DataBuilder $dataBuilder, Mailable $mailable, ErrorBuilder $errorBuilder, BaruaException|Exception $exception)
    {
        $this->errorBuilder = $errorBuilder;
        $this->mailBuilder  = $mailBuilder;
        $this->dataBuilder  = $dataBuilder;
        $this->mailable     = $mailable;
        $this->exception    = $exception;

       // $this->exception->getMessage();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }

}
