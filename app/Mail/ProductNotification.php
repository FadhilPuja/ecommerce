<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $customerName;
    /**
     * Create a new message instance.
     */
    public function __construct($product, $customerName)
    {
        $this->product = $product;
        $this->customerName = $customerName;
    }

    public function build()
    {
        return $this->subject('Product baru telah ditambahkan')
                    ->view('emails.product_notification')
                    ->with([
                        'productName' => $this->product->name,
                        'productCategory' => $this->product->category->name,
                        'customerName' => $this->customerName
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Product Notification',
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
