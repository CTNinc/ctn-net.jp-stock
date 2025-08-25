<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected function schedule(Schedule $schedule)
    {
        // 毎晩1時にメーカー数量キャッシュを更新
        $schedule->command('cache:update-maker-counts')
                 ->dailyAt('01:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // 毎晩1時30分にメーカー車型キャッシュを更新
        $schedule->command('cache:update-maker-models')
                 ->dailyAt('01:30')
                 ->withoutOverlapping()
                 ->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
