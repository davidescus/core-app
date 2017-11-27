<?php

namespace App\Src\Distribution;

class EmailSchedule
{
    private $events = [];
    private $siteCommonUsers = [];
    private $time = [];

    public function __construct($events, array $siteCommonUsers, int $timeStart, int $timeEnd)
    {
        $this->events = $events;
        $this->siteCommonUsers = $siteCommonUsers;
        $this->time['start'] = $timeStart;
        $this->time['end'] = $timeEnd;
    }

    public function createSchedule()
    {
        $smd = []; // siteId => date
        foreach ($this->events as $e) {
            if (!isset($smd[$e->siteId]))
                $smd[$e->siteId] = date('Y-m-d H:i:s', rand($this->time['start'], $this->time['end']));
        }

        // make full minutes, not work with secconds
        foreach ($smd as $k => $v)
            $smd[$k] = substr($v, 0, -2) . '00';

        foreach ($this->events as $e)
            $e->mailingDate = $smd[$e->siteId];
    }

    public function getEvents()
    {
        return $this->events;
    }
}


