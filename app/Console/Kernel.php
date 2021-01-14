<?php

namespace App\Console;

use App\Schedule\MigrateArticleCache;
use App\Schedule\MigrateCommentCache;
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
        // 将redis中的数据入库
        //  $schedule->call(new MigrateCacheData)->dailyAt('3:00');
        $schedule->call(new MigrateCommentCache)
            ->everyMinute()
            ->name('migrateRedisData')
            ->withoutOverlapping();

        $schedule->call(new MigrateArticleCache)
            ->everyMinute()
            ->name('migrateRedisData')
            ->withoutOverlapping();
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
