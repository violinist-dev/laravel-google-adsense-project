<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Revolution\NotificationChannels\Chatwork\ChatworkChannel;
use Revolution\NotificationChannels\Chatwork\ChatworkInformation;

use Illuminate\Notifications\Messages\SlackMessage;

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
        return [ChatworkChannel::class, 'slack'];
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

        return (new ChatworkInformation())->informationTitle($title)
                                          ->informationMessage(implode(PHP_EOL, $message));
    }

    /**
     * @param  mixed $notifiable
     *
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $title = $this->reports->endDate;

        return (new SlackMessage)->success()
                                 ->content($title)
                                 ->attachment(function ($attachment) {
                                     $attachment->fields([
                                         'PAGE_VIEWS'     => $this->reports->totals[1],
                                         'CLICKS'         => $this->reports->totals[2],
                                         'EARNINGS'       => $this->reports->totals[4],
                                         'COST_PER_CLICK' => $this->reports->totals[3],
                                     ]);
                                 });
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
