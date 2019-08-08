<?php

namespace App\Notifications;

use App\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;

class publishedToTelegram extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toTelegram($estate)
    {
//        $url = action('Api\EstateController@show',$estate->id);
//        $url = url('/invoice/' . $estate->code);

        $content = $estate->getDetails();
        $images = Image::where('estate_id', $estate->id)->where('status',1)->get();

        if (count($images) > 0) {
            $image = $images->where('avatar',1)->first();
            if(!$image){
                $image = $images->where('status',1)->first();
            }
            $uri = $image->uri;
            $photo_src = public_path("/public_data/images/thumbs600x450/$uri");
            return TelegramFile::create()
                ->to('@elham_salimi_65')
                ->content($content)// Markdown supported.
                ->file($photo_src, "photo")// local photo
                ->button('مشاهده جزئیات ', "amlak79.ir/estate/$estate->id");
        }
        return TelegramMessage::create()
            ->to('@elham_salimi_65')
            ->content($content)// Markdown supported.
            ->button('مشاهده جزئیات ', "amlak79.ir/estate/$estate->id");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
