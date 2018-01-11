<?php namespace App\Console\Commands;

use App\Cron;
use Illuminate\Console\Command;

class CronCommand extends Command {
    protected $name;
    protected $description;

    protected function startCron() : Cron
    {
        $cron = Cron::where('type', $this->name)->orderBy('id', 'desc')->first();

        if ($cron && !$cron->date_end) {
            //if last cron has been running for more than 30m, probably dead
            if (($cron->date_start + 30 * 60) < time()) {
                $this->error(sprintf('CRON %s (ID %s) is probably dead', $cron->type, $cron->id));
                $cron->info = $this->error;
                $cron->save();
            } else {
                $this->info(sprintf('Previous cron (ID %s) is still running', $cron->id));
                exit;
            }
        }

        $cron = new Cron;
        $cron->type = $this->name;
        $cron->date_start = time();
        $cron->date_end = 0;
        $cron->save();
        return $cron;
    }

    protected function stopCron(Cron $cron, $info = [])
    {
        $cron->info = json_encode($info);
        $cron->date_end = time();
        $cron->save();
    }
}
