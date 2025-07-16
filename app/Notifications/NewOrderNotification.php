<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;
class NewOrderNotification extends Notification
{
    use Queueable;
    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        //
        $this->order=$order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via( $notifiable): array
    {
        //stroing in database
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase( $notifiable)
    {
       return [
      'title' => 'New Order Placed',
            'message' => 'Order #' . $this->order->id . ' has been placed.',
            'order_id' => $this->order->id,
            'user_id' => $this->order->user_id,
       ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
