<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'title' => 'New Order Received',
            'message' => 'New order #'.$this->order->id.' has been placed by ' . $this->order->user->name,
            'total' => $this->order->total_amount,
            'type' => 'success', // For notification styling
            'icon' => 'shopping-cart' // Font Awesome icon
        ];
    }
}
