<?php

namespace App\Notifications\super_client_notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeOrderStatus extends Notification
{
    use Queueable;

    private $order_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    private $text='the status of the order that number :';
    private $text2='is changed to cancelled';

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
            'text'=>$this->text . ' ' . $this->order_id . ' ' . $this->text2,
        ];
    }
}
