<?php

namespace App\Src\AutoUnit;

Class Schedule
{
    private $settings = null;
    private $schedule = [];
    private $numberOfDays = 0;
    private $range = [];

    public function __construct(\App\Models\AutoUnit\MonthlySetting $settings)
    {
        $this->settings = $settings;
    }

    public function createSchedule() :void
    {
        // set range of each day for all month
        $this->setRangeOfDays();

        // set range of each day
        foreach ($this->range as $day) {
            $this->schedule[] = [
                'siteId'          => $this->settings->siteId,
                'date'            => $this->settings->date,
                'tipIdentifier'   => $this->settings->tipIdentifier,
                'tableIdentifier' => $this->settings->tableIdentifier,
                'predictionGroup' => 'To Implement',
                'statusId'        => rand(1, 4),
                'systemDate'      => $day,
            ];
        }
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
