<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCheckoutNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;
    public $orderItems;
    public $totalProducts;
    public $totalQuantity;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, User $user)
    {
        
        $this->user = $user;
        $this->order = $order;
        $this->orderItems = $order->orderDetails()->with('product')->get();
        
        $this->totalProducts = $this->orderItems->count();
        $this->totalQuantity = $this->orderItems->sum('quantity');
    }

    /**
     * Build the email message.
     */
    public function build()
    {

        return $this->from($this->user->email, $this->user->name)            
            ->to('admin@example.com')
            ->subject('New Order Checkout - Order #' . $this->order->id)
            ->view('emails.order_checkout')
            ->with([
                'userName'       => $this->order->user->name,
                'userEmail'      => $this->order->user->email,
                'orderId'        => $this->order->id,
                'totalPrice'     => number_format($this->order->total_price, 0, ',', '.'),
                'totalProducts'  => $this->totalProducts,
                'totalQuantity'  => $this->totalQuantity,
                'orderItems'     => $this->orderItems,
                'paymentMethod'  => $this->order->payment_method,
                'paymentStatus'  => $this->order->payment_status,
                'adminDashboardUrl' => url('/admin/orders/' . $this->order->id),
            ]);
    }
}
