<?php

namespace App\Notifications\Product;

use App\Models\User;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProductCreated extends Notification
{
    use Queueable;

    protected $user;
    protected $product;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, Product $product, $status = 'success')
    {
        $this->user = $user;
        $this->product = $product;
        $this->status = $status;
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
            'product' => $this->product,
            'status' => $this->status,
            'title' => $this->status == 'success' ? 'Success Create new product' : 'Failed Create new product',
            'message' => 'Product with id: ' . $this->product->id . ($this->status == 'success' ? ' success created' : ' failed created')
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
