<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ScheduleTest extends TestCase
{
    public function testShouldFailIFNotInstanceOf()
    {
        $monthlySetting = new \App\Models\AutoUnit\MonthlySetting;
        $this->assertInstanceOf(
            \App\Src\AutoUnit\Schedule::class,
            new \App\Src\AutoUnit\Schedule($monthlySetting)
        );
    }

    public function testBasic()
    {
        $monthlySetting = new \App\Models\AutoUnit\MonthlySetting();
        $monthlySetting->date = '2018-02';
        $monthlySetting->siteId = 1;
        $monthlySetting->tipIdentifier = 'tip_1';

        $instance = new \App\Src\AutoUnit\Schedule($monthlySetting);
        $instance->createSchedule();


        // TODO implement some test  and logic here

        /* $r = $instance->getEventsOrdered(); */

        /* $this->assertTrue( */
        /*     $instance->error === [], */
        /*     "No events, should no have errors" */
        /* ); */
        /* $this->assertTrue( */
        /*     $r['data'] === [], */
        /*     "No events, should return no ordered events" */
        /* ); */
    }
}
