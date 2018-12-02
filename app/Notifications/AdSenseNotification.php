<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Revolution\NotificationChannels\Chatwork\ChatworkChannel;
use Revolution\NotificationChannels\Chatwork\ChatworkInformation;

use Illuminate\Notifications\Messages\SlackMessage;

use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;

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
        return [ChatworkChannel::class, 'slack', DiscordChannel::class];
    }

    /**
     * @param mixed $notifiable
     *
     * @return ChatworkInformation
     */
    public function toChatwork($notifiable)
    {
        $title = $this->reports->endDate;
        $message = [
            'PAGE_VIEWS : ' . $this->reports->totals[1],
            'CLICKS : ' . $this->reports->totals[2],
            'COST_PER_CLICK : ' . $this->reports->totals[3],
            'EARNINGS : ' . $this->reports->totals[4],
        ];

        return (new ChatworkInformation)->informationTitle($title)
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
     * @param $notifiable
     *
     * @return DiscordMessage
     */
    public function toDiscord($notifiable)
    {
        $title = $this->reports->endDate;

        $embed = [
            'title'  => $title,
            'fields' => [
                [
                    'name'   => 'PAGE_VIEWS',
                    'value'  => $this->reports->totals[1],
                    'inline' => true,
                ],
                [
                    'name'   => 'CLICKS',
                    'value'  => $this->reports->totals[2],
                    'inline' => true,
                ],
                [
                    'name'   => 'COST_PER_CLICK',
                    'value'  => $this->reports->totals[3],
                    'inline' => true,
                ],
                [
                    'name'   => 'EARNINGS',
                    'value'  => $this->reports->totals[4],
                    'inline' => true,
                ],
            ],
        ];

        return DiscordMessage::create('', $embed);
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
