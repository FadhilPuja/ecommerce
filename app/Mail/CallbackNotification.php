<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CallbackNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;
    public $transactionStatus;
    public $isUser;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Order $order, string $transactionStatus, bool $isUser = true)
    {
        $this->user = $user;
        $this->order = $order;
        $this->transactionStatus = $transactionStatus;
        $this->isUser = $isUser;
    }

    public function build()
    {
        // Email subject based on transaction status
        $subject = $this->isUser 
            ? 'Status Pembayaran Pesanan Anda' 
            : 'Pesanan Pembayaran Diperbarui';

        // Email template based on whether it's the user or admin
        $view = $this->isUser ? 'emails.user_callback' : 'emails.admin_callback';

        return $this->from($this->user->email, $this->user->name)
            ->subject($subject)
            ->view($view)
            ->with([
                'orderId' => $this->order->id,
                'transactionStatus' => $this->transactionStatus,
                'totalPrice' => number_format($this->order->total_price, 0, ',', '.'),
                'userName' => $this->order->user->name,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Callback Notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
