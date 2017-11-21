<?php namespace App\Console\Commands;

use App\Distribution;

class SubscriptionPublish extends CronCommand
{
    protected $name = 'publish:subscription';
    protected $description = 'Checks, schedules and publishes subscription events';
    /** @var array $distribution */
    protected $distribution = [];
    /** @var array $systemDate */
    protected $systemDate = [];
    /** @var int $minute */
    protected $minute;
    /** @var int $hour */
    protected $hour;
    /** @var int $timestamp */
    protected $timestamp;

    public function fire()
    {
        $this->timestamp = time();
        $this->systemDate = [
            'today' => gmdate('Y-m-d'),
            'yesterday' => gmdate('Y-m-d', time() - (24 * 60 * 60))
        ];

        $this->systemDate = [
            'today' => '2017-11-13',
            'yesterday' => '2017-11-15'
        ];

        $this->minute = intval(gmdate('i'));
        $this->hour = intval(gmdate('G'));

        $cron = $this->startCron();

        $this->loadData();
        $this->info('Hello');
        $this->stopCron($cron);
    }

    protected function loadData()
    {
        $data = [];
        foreach (Distribution::whereIn('systemDate', array_values($this->systemDate))->get() as $value) {
            var_dump($value);

            if (!isset($data[$value->siteId]))
                $data[$value->siteId] = [];
            if (!isset($data[$value->siteId][$value->systemDate]))
                $data[$value->siteId][$value->systemDate] = [
                    'allEventsPublished' => false,
                    'hasPublishedEvents' => false,
                    'hasPendingEvents' => false,
                    'publishTime' => 0,
                    'events' => [],
                    'winRate' => 0,
                    'tmp' => [
                        'all' => 0,
                        'good' => 0,
                        'published' => 0
                    ]
                ];

            if ($value->isPublished) {
                $data[$value->siteId][$value->systemDate]['hasPublishedEvents'] = true;
                $data[$value->siteId][$value->systemDate]['tmp']['published']++;
            }

            if
            (
                !$data[$value->siteId][$value->systemDate]['hasPendingEvents'] &&
                (!$value->result || !$value->status)
            )
                $data[$value->siteId][$value->systemDate]['hasPendingEvents'] = true;
            if
            (
                !$data[$value->siteId][$value->systemDate]['publishTime'] ||
                $data[$value->siteId][$value->systemDate]['publishTime'] < $value->publishTime
            )
                $data[$value->siteId][$value->systemDate]['publishTime'] = $value->publishTime;

            if ($value->statusId === '1')
                $data[$value->siteId][$value->systemDate]['tmp']['good']++;

            $data[$value->siteId][$value->systemDate]['tmp']['all']++;
            $data[$value->siteId][$value->systemDate]['events'][] = $value;
        }

        foreach ($data as $siteId => $dates) {
            foreach ($dates as $date => $value) {
                $data[$siteId][$date]['winRate'] = round(100 * ($value['tmp']['good'] / $value['tmp']['all']), 2);
                $data[$siteId][$date]['allEventsPublished'] =  $value['tmp']['all'] === $value['tmp']['published'];
                unset($data[$siteId][$date]['tmp']);
            }
        }

        return $data;
    }
}