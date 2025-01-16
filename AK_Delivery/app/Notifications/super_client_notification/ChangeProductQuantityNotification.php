<?php

namespace App\Notifications\super_client_notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeProductQuantityNotification extends Notification
{
    use Queueable;

    private $product_name;
    private $old_quantity;
    private $new_quantity;

    /**
     * Create a new notification instance.
     */
    public function __construct($old_quantity, $new_quantity, $product_name)
    {
        $this->old_quantity = $old_quantity;
        $this->new_quantity = $new_quantity;
        $this->product_name = $product_name;
    }

    private $text='the product :';
    private $text2='is changed it quantity from :';
    private $text3='to : ';

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
            'text' => $this->text . ' ' . $this->product_name . ' ' .
            $this->text2 . ' ' . $this->old_quantity . ' ' .
            $this->text3 . ' ' . $this->new_quantity,
        ];
    }
}
