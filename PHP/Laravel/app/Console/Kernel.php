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
        $schedule->command('cv:process-bulk-uploaded')->everyFiveMinutes()->appendOutputTo(env('CV_FILES_BULK_LOG','storage/app/cv_files/logs/bulk_uploads.log'));
        $schedule->command('cv:parse-next-uploaded')->everyFifteenMinutes()->appendOutputTo(env('CV_FILES_PJ_PARSE_LOG','storage/app/cv_files/logs/pj_parse.log'));
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
