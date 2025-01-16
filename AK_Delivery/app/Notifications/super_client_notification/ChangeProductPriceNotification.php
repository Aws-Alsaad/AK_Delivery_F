<?php

namespace App\Notifications\super_client_notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeProductPriceNotification extends Notification
{
    use Queueable;

    private $old_price;
    private $new_price;
    private $product_name;

    /**
     * Create a new notification instance.
     */
    public function __construct($old_price, $new_price, $product_name)
    {
        $this->old_price = $old_price;
        $this->new_price = $new_price;
        $this->product_name = $product_name;
    }

    private $text='the product :';
    private $text3='is changed it price from :';
    private $text2='to :';

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
                $this->text3 . ' ' . $this->old_price . ' ' .
                $this->text2 . ' ' . $this->new_price,
        ];
    }
}
