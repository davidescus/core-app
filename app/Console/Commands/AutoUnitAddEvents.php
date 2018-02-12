<?php namespace App\Console\Commands;

class AutoUnitAddEvents extends CronCommand
{
    protected $name = 'autounit:add-events';
    protected $description = 'Add events according to autounit schedule.';

    private $systemDate;
    private $todayFinishedEvents;

    public function fire()
    {
        $cron = $this->startCron();
        $this->systemDate = gmdate('Y-m-d');

        $info = [
            'created' => 0,
            'message' => []
        ];

        // get all schedule for today
        $schedules = $this->getAutoUnitTodaySchedule();

        // load today finished events
        $this->todayFinishedEvents = $this->loadTodayFinishedEvents();

        /* foreach ($schedules as $schedule) { */
        /* } */

        /* print_r($events); */

        $this->info(json_encode($info));
        $this->stopCron($cron, $info);
        return true;
    }

    // this will choose event from all today schedule events
    private function chooseEvent($schedule)
    {

    }

    private function setTodayFinishedEvents()
    {
        $this->todayFinishedEvents\App\Match::where('eventDate', 'like', '%' . $this->systemDate . '%')
            ->where('result', '<>', '')
            ->get()
            ->toArray();
    }

    private function getAutoUnitTodaySchedule() : array
    {
        return \App\Models\AutoUnit\DailySchedule::where('systemDate', $this->systemDate)
            ->get()
            ->toArray();
    }
}

