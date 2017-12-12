<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class EmailScheduldeTest extends TestCase
{
    public function testShouldFailIFNotInstanceOf()
    {
        $this->assertInstanceOf(
            \App\Src\Distribution\EmailSchedule::class,
            new \App\Src\Distribution\EmailSchedule([], 0, 0)
        );
    }

    public function testNoEvents()
    {
        $c = [
            'sites' => [],
            'timeStart' => time(),
            'timeEnd' => time(),
        ];

        $instance = new \App\Src\Distribution\EmailSchedule(
            $c['sites'],
            $c['timeStart'],
            $c['timeEnd']
        );
        $instance->createSchedule();
        $r = $instance->getEventsOrdered();

        $this->assertTrue(
            $instance->error === [],
            "No events, should no have errors"
        );
        $this->assertTrue(
            $r['data'] === [],
            "No events, should return no ordered events"
        );
    }

    public function testIntervalIsGreatherThanSitesCommonUsersNumberPlusOne()
    {
        $c = [
            'sites' => [
                '1' => [
                    'time'        => time() + (120 * 60),
                    'commonUsersWith' => [],
                ],
            ],
            'timeStart' => time(),
            'timeEnd' => time(),
        ];

        $instance = new \App\Src\Distribution\EmailSchedule(
            $c['sites'],
            $c['timeStart'],
            $c['timeEnd']
        );
        $instance->createSchedule();
        $r = $instance->getEventsOrdered();

        $this->assertTrue(
            $instance->error !== [],
            "must have error when interval is less than number of sites with common user + 1"
        );
    }

    public function testExcludeEventsThatStartBeforeScheduleTimeEnd()
    {
        $c = [
            'sites' => [
                '1' => [
                    'time'        => time() + (49 * 60),
                    'commonUsersWith' => [],
                ],
            ],
            'timeStart' => time(),
            'timeEnd' => time() + (59 * 60),
        ];

        $instance = new \App\Src\Distribution\EmailSchedule(
            $c['sites'],
            $c['timeStart'],
            $c['timeEnd']
        );
        $instance->createSchedule();
        $r = $instance->getEventsOrdered();

        $this->assertEquals(
            count($c['sites']) -1, count($r['data']),
            "Sites that have events hwo start before schedule timeEnd muxt be excluded"
        );
    }
}

