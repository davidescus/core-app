<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class EmailScheduldeTest extends TestCase
{
    public function testShouldFailIFNotInstanceOf()
    {
        $this->assertInstanceOf(
            \App\Src\Distribution\EmailSchedule::class,
            new \App\Src\Distribution\EmailSchedule([], [], 0, 0)
        );
    }

    public function testTableWithManyCases()
    {
        $cases = [];

        // case array template
        $case = [
            'events' => [],
            'siteCommonUsers' => [],
            'timeStart' => time() + (10 * 60),
            'timeEnd' => time() + (130 * 60),
            'expect' => [],
        ];

        // --- no events
        $c = $case;
        $cases[] = $c;
        // --- /. end test case

        // --- two events in same site
        $c = $case;

        $e = new \App\Distribution();
        $e->siteId = 1;
        $e->eventDate = gmdate('Y-m-d H:i:s', time() + (20 * 60));
        $c['events'][] = $e;

        $e = new \App\Distribution();
        $e->siteId = 1;
        $e->eventDate = gmdate('Y-m-d H:i:s', time() + (30 * 60));
        $c['events'][] = $e;

        $cases[] = $c;
        // --- /. end test case

        // --- two events 2 sites
        $c = $case;

        $e = new \App\Distribution();
        $e->siteId = 1;
        $e->eventDate = gmdate('Y-m-d H:i:s', time() + (20 * 60));
        $c['events'][] = $e;

        $e = new \App\Distribution();
        $e->siteId = 2;
        $e->eventDate = gmdate('Y-m-d H:i:s', time() + (30 * 60));
        $c['events'][] = $e;

        $cases[] = $c;
        // --- /. end test case

        // --- 3 events 2 sites
        $c = $case;

        $e = new \App\Distribution();
        $e->siteId = 1;
        $e->eventDate = gmdate('Y-m-d H:i:s', time() + (20 * 60));
        $c['events'][] = $e;

        $e = new \App\Distribution();
        $e->siteId = 1;
        $e->eventDate = gmdate('Y-m-d H:i:s', time() + (30 * 60));
        $c['events'][] = $e;

        $e = new \App\Distribution();
        $e->siteId = 2;
        $e->eventDate = gmdate('Y-m-d H:i:s', time() + (30 * 60));
        $c['events'][] = $e;

        $cases[] = $c;
        // --- /. end test case

        // --- 3 events 2 sites
        $c = $case;

        $e = new \App\Distribution();
        $e->siteId = 1;
        $e->eventDate = gmdate('Y-m-d H:i:s', time() + (20 * 60));
        $c['events'][] = $e;

        $e = new \App\Distribution();
        $e->siteId = 1;
        $e->eventDate = gmdate('Y-m-d H:i:s', time() + (30 * 60));
        $c['events'][] = $e;

        $e = new \App\Distribution();
        $e->siteId = 2;
        $e->eventDate = gmdate('Y-m-d H:i:s', time() + (30 * 60));
        $c['events'][] = $e;

        $cases[] = $c;
        // --- /. end test case
//
        // --- 3 events 3 sites
        $c = $case;

        $e = new \App\Distribution();
        $e->siteId = 1;
        $e->eventDate = gmdate('Y-m-d H:i:s', time() + (10 * 60));
        $c['events'][] = $e;

        $e = new \App\Distribution();
        $e->siteId = 2;
        $e->eventDate = gmdate('Y-m-d H:i:s', time() + (20 * 60));
        $c['events'][] = $e;

        $e = new \App\Distribution();
        $e->siteId = 3;
        $e->eventDate = gmdate('Y-m-d H:i:s', time() + (30 * 60));
        $c['events'][] = $e;

        $cases[] = $c;
        // --- /. end test case

        foreach ($cases as $c) {
            $instance = new \App\Src\Distribution\EmailSchedule(
                $c['events'],
                $c['siteCommonUsers'],
                $c['timeStart'],
                $c['timeEnd']
            );
            $instance->createSchedule();
            $results = $instance->getEvents();

            $h = []; // $h[siteId] = '2017-11-11 23:43:23';
            foreach ($results as $k => $e) {
                if (!isset($h[$e->siteId]))
                    $h[$e->siteId] = $e->mailingDate;

                $this->assertTrue(
                    $e->mailingDate !== null,
                    "Null mailnigDate"
                );

                // all events for a site have same mailingDate
                $this->assertTrue(
                    $e->mailingDate === $h[$e->siteId],
                    "Event from same site have diff mailingdate. Exp: " . $h[$e->siteId] . " Got: " . $e->mailingDate
                );

                // mailingDate is greather then timeStart and lower than timeEnd
                $md = strtotime($e->mailingDate);
                $this->assertTrue(
                    $md >= $c['timeStart'],
                    "mailingDate < timeStart \n mailingDate: " . $md . " timeStart: " . $c['timeStart']
                );
                $this->assertTrue(
                    $md <= $c['timeEnd'],
                    "mailingDate > timeEnd \n mailingDate: " . $md . " timeEnd: " . $c['timeEnd']
                );
            }
        }
    }
}

