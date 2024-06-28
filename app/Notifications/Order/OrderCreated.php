<?php

namespace App\Notifications\Order;

use App\Models\User;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderCreated extends Notification
{
    use Queueable;

    protected $user;
    protected $order;
    protected $payment_url;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, Order $order, $payment_url)
    {
        $this->order = $order;
        $this->user = $user;
        $this->payment_url = $payment_url;
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

    public function toDatabase(object $notifiable)
    {
        return [
            'user' => $this->user,
            'order' => $this->order,
            'payment_url' => $this->payment_url,
            'title' => 'Order success created',
            'status' => 'success',
            'message' => 'Your order with id: ' . $this->order->id . ' has been created, please complete your payment.'
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
