<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $oldStatus;

    public function __construct(Order $order, string $oldStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
    }

    public function build()
    {
        return $this->subject('Your order #' . $this->order->id . ' status was updated')
            ->view('emails.orders.status-updated')
            ->with([
                'order' => $this->order,
                'oldStatus' => $this->oldStatus,
            ]);
    }
}
