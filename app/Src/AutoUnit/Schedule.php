<?php

namespace App\Src\AutoUnit;

Class Schedule
{
    private $settings = null;
    private $schedule = [];
    private $numberOfDays = 0;
    private $range = [];

    private $totalTips = 0;

    public function __construct(\App\Models\AutoUnit\MonthlySetting $settings)
    {
        $this->settings = $settings;
        /* print_r($settings->toArray()); */
    }

    public function createSchedule() :void
    {
        // set range of each day for all month
        $this->setRangeOfDays();

        $predictions = [
            '1x2',
            'ah',
            'o/u',
            'gg',
        ];


        // days type
        if ($this->settings->configType == 'days') {

            $this->totalTips = $this->settings->tipsPerDay * count($this->range);

            if ($this->totalTips > 0) {

                $p = [
                    '1x2' => 0,
                    'OU'  => 0,
                    'AH'  => 0,
                    'GG'  => 0,
                ];

                $t = $this->totalTips;
                $exit = false;
                while (!$exit) {

                    $_1x2 = $this->incrementEventsNumber($p, '1x2');
                    if ($_1x2)
                        $p['1x2']++;

                    $_ah = $this->incrementEventsNumber($p, 'AH');
                    if ($_ah)
                        $p['AH']++;

                    $_ou = $this->incrementEventsNumber($p, 'OU');
                    if ($_ou)
                        $p['OU']++;

                    $_gg = $this->incrementEventsNumber($p, 'GG');
                    if ($_gg)
                        $p['GG']++;

                    if ($_1x2 == false && $_ah == false && $_ou == false && $_gg == false)
                        $exit = true;
                }

                for ($x = $this->settings->tipsPerDay; $x > 0; $x--) {

                    foreach ($this->range as $day) {

                        $predictionGroup = $this->getRandomPrediction($p);
                        $p[$predictionGroup]--;

                        if ($predictionGroup === null)
                            continue;

                        $statusId = $this->getRandomStatus($predictionGroup);
                        if ($statusId === null)
                            continue;

                        $this->schedule[] = [
                            'siteId'          => $this->settings->siteId,
                            'date'            => $this->settings->date,
                            'tipIdentifier'   => $this->settings->tipIdentifier,
                            'tableIdentifier' => $this->settings->tableIdentifier,
                            'predictionGroup' => $predictionGroup,
                            'statusId'        => $statusId,
                            'status'          => 'waiting',
                            'info'            => json_encode([]),
                            'systemDate'      => $day,
                        ];
                    }
                }
            }
        }
    }

    private function getRandomStatus($predictionGroup, $count = 0)
    {
        if ($count > 1000)
            return null;

        $w = $this->settings->win > 0 ? $this->settings->win : 1;
        $l = $this->settings->loss > 0 ? $this->settings->loss : 1;
        $d = $this->settings->draw > 0 ? $this->settings->draw : 1;

        $wp = round(($w * 100) / ($w + $l + $d));
        $lp = round(($w * 100) / ($w + $l + $d));
        $dp = round(($w * 100) / ($w + $l + $d));

        $rand = rand(1,99);

        $statusId = ($rand < $wp) ? 1 : 2;

        /* $statusId = rand(1,2); */

        // for draw
        if ($predictionGroup == 'AH' || $predictionGroup == 'OU')
           $statusId = rand(1,3);

        if ($statusId == 1) {
            if ($this->settings->win > 0) {
                $this->settings->win--;
                return $statusId;
            }
        }

        if ($statusId == 2) {
            if ($this->settings->loss > 0) {
                $this->settings->loss--;
                return $statusId;
            }
        }

        if ($statusId == 3) {
            if ($this->settings->draw > 0) {
                $this->settings->draw--;
                return $statusId;
            }
        }

        return $this->getRandomStatus($predictionGroup, $count++);
    }

    private function getRandomPrediction($p, $count = 0)
    {
        $v = ['1x2', 'OU', 'AH', 'GG'];
        $prediction = $v[rand(0,3)];

        if ($p[$prediction] > 0)
            return $prediction;

        if ($count > 200)
            return null;

        return $this->getRandomPrediction($p, $count++);
    }

    private function incrementEventsNumber(array $p, string $type)
    {
        $identifier = 'prediction' . $type;

        if ($this->settings->{$identifier} < 1)
            return false;

        if ($p[$type] >= round(($this->totalTips * $this->settings->{$identifier}) / 100))
            return false;

        return true;
    }

    private function setRangeOfDays() : void
    {
        $stringDate = $this->settings->date . '-01';

        $startDate = new \DateTime($stringDate);
        $endDate = new \DateTime($startDate->format('Y-m-t'));
        $endDate->modify('+1 day');

        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($startDate, $interval, $endDate);

        foreach ($dateRange as $date) {
            $this->range[] = $date->format('Y-m-d');
        }
    }

    public function getSchedule() :array
    {
        return $this->schedule;
    }
}
