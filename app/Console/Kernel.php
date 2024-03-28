<?php

namespace App\Console;


use Sosmaller\Crontab;

class Kernel extends Crontab
{
    /**
     *  Define commands alert.
     */
    public function alert()
    {
        echo "\n";
        echo "tip: can only be run in CLI mode\n\n";
        echo "egg：php artisan command  param1 param2\n\n";

        echo "php artisan schedule\n\n";
        echo "php artisan queue\n\n";
        $schedule = $this->schedule();
        if ($schedule) {
            foreach ($schedule as $command => $rule) {
                echo "php artisan " . $command . "\n\n";
            }
        }
    }

    /**
     * Define the application's command schedule.
     *
     * @return array
     */
    public function schedule()
    {
        $schedule['IndexCommand'] = '* * * * *';
        return $schedule;
    }

}
