<?php

namespace App\Notifications\admin_notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeleteProductNotification extends Notification
{
    use Queueable;

    private $product_name;

    /**
     * Create a new notification instance.
     */
    public function __construct($product_name)
    {
        $this->product_name = $product_name;
    }

    private $text='the product :';
    private $text2='is deleted from admin.';

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
//    public function toMail(object $notifiable): MailMessage
//    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
//    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'text' => $this->text . ' ' . $this->product_name . ' ' . $this->text2,
        ];
    }
}
