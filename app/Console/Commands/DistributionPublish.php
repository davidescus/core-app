<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Cron;

class DistributionPublish extends Command {
    protected $name = 'distribution:publish';
    protected $description = 'Checks, schedules and publishes distribution events';

    public function fire()
    {
        $systemDate = [
            'today' => gmdate('Y-m-d'),
            'yesterday' => gmdate('Y-m-d', time() - (24 * 60 * 60))
        ];

        $cron = $this->startCron();

        $publishedEvents = [];
        if ($publishedEvents) {
            //select rest of events and publish em
        } else {
            $minute = intval(gmdate('i'));
            $hour = intval(gmdate('G'));
            if ($hour >= 19) {
                // if all events done + winrate ok;
            } else if ($hour === 9 && $minute <= 30) {
                // today - 1;
            }
        }

        // CODE LOGIC
        sleep(5);
        $this->info(implode(PHP_EOL, $systemDate));

        $this->stopCron($cron, ['1' => '2']);
    }

    private function startCron() : Cron
    {
        $cron = Cron::where('type', $this->name)->orderBy('id', 'desc')->first();

        if ($cron && !$cron->date_end) {
            //if last cron has been running for more than 30m, probably dead
            if (($cron->date_start + 30 * 60) < time()) {
                $this->error(sprintf('CRON %s (ID %s) is probably dead', $cron->type, $cron->id));
            } else {
                $this->info(sprintf('Previous cron (ID %s) is still running', $cron->id));
            }
            exit;
        }

        $cron = new Cron;
        $cron->type = $this->name;
        $cron->date_start = time();
        $cron->date_end = 0;
        $cron->save();
        return $cron;
    }

    private function stopCron(Cron $cron, $info = [])
    {
        $cron->info = json_encode($info);
        $cron->date_end = time();
        $cron->save();
    }
}