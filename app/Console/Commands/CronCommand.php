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
                $info = [
                    'error' => (sprintf('CRON %s (ID %s) is probably deadn new cron was started.', $cron->type, $cron->id)),
                ];
                $cron->info = json_encode($info);
                $cron->save();
            } else {
                $info = [
                    'warning' => (sprintf('Previous cron (ID %s) is still running', $cron->id)),
                ];
                $cron->info = json_encode($info);
                $cron->save();
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
