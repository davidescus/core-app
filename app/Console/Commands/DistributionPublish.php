<?php namespace App\Console\Commands;

use App\Distribution;
use App\Site;
use Ixudra\Curl\Facades\Curl;

class DistributionPublish extends CronCommand {
    protected $name = 'distribution:publish';
    protected $description = 'Checks, schedules and publishes distribution events';
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

        $this->minute = intval(gmdate('i'));
        $this->hour = intval(gmdate('G'));

        $cron = $this->startCron();

        $events = $this->loadData();
        if (!$events) {
            $this->stopCron($cron, []);
            return true;
        }
        $info = [
            'sent' => 0,
            'errors' => []
        ];
        foreach($events as $siteId => $values) {
            $site = Site::find($siteId);
            if (!$site) {
                $info['errors'][] = "Couldn't find site with id $siteId";
                continue;
            }
            foreach($values as $systemDate => $info) {
                if ($info['allEventsPublished'])
                    continue;

                // process any events that have a publish time lower than actual time
                // OR has already events published in the respective day

                foreach($info['events'] as $event) {
                    if (!$info['hasPublishedEvents'] && ($event->publishTime < $this->timestamp))
                        continue;

                    if (!$event->isPublished && $event->result && $event->status) {
                        if (!$this->publish($site, $event))
                            $info['errors'][] = "Couldn't publish eventId {$event->id} to siteId {$site->id}";
                        else
                            $info['sent']++;
                    }
                }

                if (!$info['hasPublishedEvents']) {
                    if ($info['hasPendingEvents'])
                        continue;

                    if ($systemDate === $this->systemDate['yesterday']) {
                        // process events that weren't finished yesterday

                        if ($info['winRate'] >= 50) {
                            foreach($info['events'] as $event) {
                                if ($event->isPublished)
                                    continue;

                                if (!$this->publish($site, $event))
                                    $info['errors'][] = "Couldn't publish eventId {$event->id} to siteId {$site->id}";
                                else
                                    $info['sent']++;
                            }
                        } else {
                           if ($info['publishTime'] && $info['publishTime'] >= $this->timestamp) {
                               foreach($info['events'] as $event) {
                                   if ($event->isPublished)
                                       continue;

                                   if (!$this->publish($site, $event))
                                       $info['errors'][] = "Couldn't publish eventId {$event->id} to siteId {$site->id}";
                                   else
                                       $info['sent']++;
                               }
                           } else {
                               if (!$info['publishTime'])
                                   $info['publishTime'] = strtotime('today 09:00:00') + mt_rand(0, 30 * 60);

                               foreach ($info['events'] as $event) {
                                   if ($event->isPublished)
                                       continue;

                                   if (!$event->publishTime) {
                                       $event->publishTime = $info['publishTime'];
                                       $event->save();
                                   }
                               }
                           }
                        }
                    } else {
                        // process events that for today
                        if ($this->hour < 19 || $info['hasPendingEvents'])
                            continue;

                        if (!$info['publishTime']) {
                            if ($info['winRate'] >= 50)
                                $info['publishTime'] = strtotime('today 19:00:00') + mt_rand(0, 4 * 60 * 60);
                            else
                                $info['publishTime'] = strtotime('tomorrow 09:00:00') + mt_rand(0, 30 * 60);

                        }
                        foreach ($info['events'] as $event) {
                            if ($event->isPublished)
                                continue;

                            if (!$event->publishTime) {
                                $event->publishTime = $info['publishTime'];
                                $event->save();
                            }
                        }
                    }
                }
            }
        }
        $this->info(json_encode($info));
        $this->stopCron($cron, $info);
    }

    protected function loadData()
    {
        $data = [];
        foreach (Distribution::whereIn('systemDate', array_values($this->systemDate))->get() as $value) {
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

            if ($value->statusId === 1)
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

    protected function publish(Site $site, Distribution $event) : bool
    {
        $response = Curl::to($site->url)
            ->withData([
                'route' => 'api',
                'key' => $site->token,
                'method' => '????',
                'url' => env('APP_HOST') . '/client/get-configuration/' . $site->id,
                'body' => $event->toArray(),
            ])
            ->post();

        $response = json_decode($response, true);
        if (!$response)
            return null;
        else {
            $event->publishTime = $this->timestamp;
            $event->isPublished = 1;
            $event->save();
        }
        return true;
    }
}