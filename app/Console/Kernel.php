<?php

namespace App\Console;

use App\Jobs\SendMailJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();


            // Run this job every day at midnight
            $schedule->job(new SendMailJob)->daily();

            // // Run this job every day at a certain time (here, 3 p.m.)
            // $schedule->job(new SendMailJob)->dailyAt('15:00');

            // // You can also set a time zone for the cron job
            // $schedule->job(new SendMailJob)
            //     ->timezone('America/New_York')
            //     ->dailyAt('15:00');
            // }
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
