<?php

namespace App\Notifications;

use App\Estate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendSabtEmail extends Notification
{
    use Queueable;
    public $estate;
  /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Estate $estate)
    {
        $this->estate= $estate;
//        $this->delay(1000);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('ثبت ملک')
            ->view('emails.sabt', ['estate' => $this->estate]);
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

}
