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
        Commands\ImportTTDumps::class,
        Commands\ImportBBDumps::class,
        Commands\ImportVCDumps::class,
        Commands\ImportGYDumps::class,
        Commands\ImportLCDumps::class,
        Commands\ImportAllFiles::class,
        Commands\UpdateCouponStatus::class,
        Commands\ImportTTPriceChangeDump::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        // $schedule->command('import:categories')->timezone('America/Barbados')->dailyAt('03:31')->withoutOverlapping();
        // $schedule->command('import:departments')->timezone('America/Barbados')->dailyAt('03:32')->withoutOverlapping();
        // $schedule->command('import:subDepartments')->timezone('America/Barbados')->dailyAt('03:33')->withoutOverlapping();
        // $schedule->command('import:departmentsubdepartmentmap')->timezone('America/Barbados')->dailyAt('03:51')->withoutOverlapping();
        // $schedule->command('import:subdepartmentcategorymap')->timezone('America/Barbados')->dailyAt('03:52')->withoutOverlapping();
        // $schedule->command('import:productimages')->timezone('America/Barbados')->dailyAt('18:33')->withoutOverlapping();
        // $schedule->command('import:productcategorymap')->timezone('America/Barbados')->dailyAt('12:01')->withoutOverlapping();
        // $schedule->command('import:altcodes')->timezone('America/Barbados')->dailyAt('18:01')->withoutOverlapping();
        // $schedule->command('import:products')->timezone('America/Barbados')->dailyAt('00:01')->withoutOverlapping();

        $schedule->command('import:all_files')->timezone('America/Barbados')->dailyAt('04:15')->withoutOverlapping();
        $schedule->command('import:tt_dumps')->timezone('America/Barbados')->dailyAt('04:26')->withoutOverlapping();
        $schedule->command('import:lc_dumps')->timezone('America/Barbados')->dailyAt('04:27')->withoutOverlapping();
        $schedule->command('import:gy_dumps')->timezone('America/Barbados')->dailyAt('04:28')->withoutOverlapping();
        $schedule->command('import:bb_dumps')->timezone('America/Barbados')->dailyAt('04:29')->withoutOverlapping();
        $schedule->command('import:vc_dumps')->timezone('America/Barbados')->dailyAt('04:30')->withoutOverlapping();
        $schedule->command('import:tt_price_change_dump')->timezone('America/Barbados')->dailyAt('06:00')->withoutOverlapping();
        $schedule->command('update:couponstatus')->timezone('America/Barbados')->everyFifteenMinutes()->withoutOverlapping();
        // $schedule->command('test:cron')->everyMinute();
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
