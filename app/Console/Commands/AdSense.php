<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use PulkitJalan\Google\Facades\Google;

use App\Notifications\AdSenseNotification;

use Notification;

class AdSense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $token = [
            'access_token'  => config('ads.access_token'),
            'refresh_token' => config('ads.refresh_token'),
            'expires_in'    => 3600,
            'created'       => now()->subDay()->getTimestamp(),
        ];

        Google::setAccessToken($token);

        Google::fetchAccessTokenWithRefreshToken();

        $ads = Google::make('AdSense');

        $startDate = 'today-1d';
        $endDate = 'today-1d';

        $optParams = [
            'metric'    => [
                'PAGE_VIEWS',
                //                'AD_REQUESTS',
                //                'AD_REQUESTS_COVERAGE',
                'CLICKS',
                //                'AD_REQUESTS_CTR',
                'COST_PER_CLICK',
                //                'AD_REQUESTS_RPM',
                'EARNINGS',
            ],
            'dimension' => 'DATE',
            'sort'      => '+DATE',
        ];

        $reports = $ads->reports->generate($startDate, $endDate, $optParams)->toSimpleObject();
        //        dd($reports);

        Notification::route('chatwork', config('ads.cw_room'))
                    ->route('chatwork-token', config('ads.cw_token'))
                    ->route('slack', config('ads.slack_webhook'))
                    ->route('discord', config('ads.discord_channel'))
                    ->notify(new AdSenseNotification($reports));
    }
}
