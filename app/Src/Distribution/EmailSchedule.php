<?php

namespace App\Src\Distribution;

class EmailSchedule
{
    private $sites = [];
    private $timeStart;
    private $timeEnd;
    private $positionsNumber = 0;
    private $sitesNumber = 0;
    private $sitesCommonUsers = [];
    private $times = [];
    private $ordered = [];

    public $error = [];

    public function __construct(array $sites, int $timeStart, int $timeEnd)
    {
        $this->sites = $sites;
        $this->timeStart = $timeStart;
        $this->timeEnd = $timeEnd;
    }


    // if no sites will not check anything, return []
    // if number of positions less than number of sites with common users will return error
    public function createSchedule()
    {
        $this->sitesNumber = count($this->sites);

        if (! $this->sitesNumber > 0)
            return;

        $this->positionsNumber = intval(($this->timeEnd - $this->timeStart) / 60);

        foreach($this->sites as $k => $v) {

            if ($v['time'] <= $this->timeEnd) {
                unset($this->sites[$k]);
                continue;
            }

            if (count($v['commonUsersWith']))
                $this->sitesCommonUsers[$k] = $v;
        }

        if ($this->positionsNumber < count($this->sitesCommonUsers) + 1) {
            $this->error[] = "Schedule interval (minutes) must be greather than number of sites that have common users " . count($this->sitesCommonUsers) . " + 1";
            return;
        }

        $noCommonUsersTime = $this->randUniqueTime();

        foreach ($this->sites as $k => $v) {
            if (! array_key_exists($k, $this->sitesCommonUsers)) {
                $this->sites[$k]['schedule'] = $noCommonUsersTime;
                continue;
            }

            $this->sites[$k]['schedule'] = $this->randUniqueTime();
        }
    }

    // recursive function that generate random time
    public function randUniqueTime() {
        $date = date('Y-m-d H:i', rand($this->timeStart, $this->timeEnd)) . ':00';

        if (! in_array($date, $this->times)) {
            $this->times[] = $date;
            return $date;
        }

        return $this->randUniqueTime();
    }

    public function getEventsOrdered()
    {
        return [
            'data' => $this->sites,
            'positionsNumber' => $this->positionsNumber,
            'sitesNumber' => $this->sitesNumber,
        ];
    }
}


