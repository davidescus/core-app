<?php namespace App\Console\Commands;

class PublishArchives extends CronCommand
{
    protected $name = 'publish:archives';
    protected $description = 'Publish archives in sites';

    public function fire()
    {
        $cron = $this->startCron();

        $info = [
            'published' => 0,
        ];

        print_r($info);

        $this->info(json_encode($info));
        $this->stopCron($cron, $info);
        return true;
    }
}

