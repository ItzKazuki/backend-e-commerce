<?php

namespace App\Notifications\Order;

use App\Models\User;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderChanged extends Notification
{
    use Queueable;

    protected $user;
    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, Order $order)
    {
        $this->order = $order;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'user' => $this->user,
            'order' => $this->order,
            'title' => 'Your Order on ' . $this->order->order_status,
            'status' => 'success',
            'message' => 'Your order with id: ' . $this->order->id . ' now on process, please wait for delivery from seller'
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
