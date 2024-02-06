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
        // // insert setiap tanggal 4, pada menit ke 1
        // $schedule->command('insert:querypljprkao')
        //          ->cron('1 * 4 * *');

        // // insert setiap tanggal 4, pada menit ke 31
        // $schedule->command('insert:querypljprkai')
        //          ->cron('31 * 4 * *');

        // // insert setiap tanggal 4, pada menit ke 61
        // $schedule->command('insert:querypljprkaipln')
        //          ->cron('1 1 4 * *');

        // insert setiap tanggal 4, pada menit ke 1
        $schedule->command('insert:querypljprkao')
         //        ->cron('59 15 11 * *');
				   ->cron('15 10 16 * *');

        // insert setiap tanggal 4, pada menit ke 31
        $schedule->command('insert:querypljprkai')
                 ->cron('45 10 16 * *');

        // insert setiap tanggal 4, pada menit ke 61
        $schedule->command('insert:querypljprkaipln')
                 ->cron('10 11 16 * *');

        //20210226 change request implementasi query PBC AO
        $schedule->command('insert:querypbcao')
                 ->cron('20 11 13 * *');

        //20210226 change request implementasi query MSF900
        $schedule->command('insert:querymsf900')
                 ->cron('20 11 13 * *');

        // end change request implementasi query PBC AO
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
