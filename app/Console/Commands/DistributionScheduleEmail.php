<?php namespace App\Console\Commands;

class DistributionScheduleEmail extends CronCommand
{
    protected $name = 'distribution:scheduleEmails';
    protected $description = 'Associate events with subscriptions and move email content to email_schedule table';

    public function fire()
    {
        $cron = $this->startCron();

        $events = $this->loadData();
        if (!$events) {
            $this->stopCron($cron, []);
            return true;
        }
        $info = [
            'scheduled' => 0,
            'errors' => []
        ];
        foreach($events as $siteId => $values) {
            $site = Site::find($siteId);
            if (!$site) {
                $info['errors'][] = "Couldn't find site with id $siteId";
                continue;
            }
        }
        $this->info(json_encode($info));
        $this->stopCron($cron, $info);
        return true;
    }

    protected function loadData()
    {
        $data = [];

        return $data;
    }
}

