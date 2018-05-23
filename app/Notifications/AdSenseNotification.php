<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Revolution\NotificationChannels\Chatwork\ChatworkChannel;
use Revolution\NotificationChannels\Chatwork\ChatworkInformation;

class AdSenseNotification extends Notification
{
    use Queueable;

    /**
     * @var object $reports
     */
    protected $reports;

    /**
     * Create a new notification instance.
     *
     * @param object $reports
     *
     * @return void
     */
    public function __construct($reports)
    {
        $this->reports = $reports;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return [ChatworkChannel::class];
    }

    public function toChatwork($notifiable)
    {
        $title = $this->reports->endDate;
        $message = [
            'PAGE_VIEWS : ' . $this->reports->totals[1],
            'CLICKS : ' . $this->reports->totals[2],
            'COST_PER_CLICK : ' . $this->reports->totals[3],
            'EARNINGS : ' . $this->reports->totals[4],
        ];

        return (new ChatworkInformation())->token(config('ads.cw_token'))
                                          ->roomId(config('ads.cw_room'))
                                          ->informationTitle($title)
                                          ->informationMessage(implode(PHP_EOL, $message));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
