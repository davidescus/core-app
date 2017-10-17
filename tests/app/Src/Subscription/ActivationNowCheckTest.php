<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ActivationNowCheckTest extends TestCase
{
    public function testShouldFailIFNotInstanceOf()
    {
        $this->assertInstanceOf(
            \App\Src\Subscription\ActivationNowCheck::class,
            new \App\Src\Subscription\ActivationNowCheck([])
        );
    }

    public function testIsValidShouldBeTrueIfThereInNoEvents()
    {
        $activation = new \App\Src\Subscription\ActivationNowCheck([]);
        $activation->checkPublishEvents();
        $this->assertTrue($activation->isValid);
    }

    public function testIsValidShouldBeFalseIfOneEventIsPublish()
    {
        $ev1 = new \stdClass();
        $ev1->isPublish = '1';

        $activation = new \App\Src\Subscription\ActivationNowCheck([$ev1]);
        $activation->checkPublishEvents();
        $this->assertFalse($activation->isValid);
    }

    public function testIsValidShouldBeFalseIfMoreEventsIsPublish()
    {
        $ev1 = new \stdClass();
        $ev1->isPublish = '1';
        $ev2 = new \stdClass();
        $ev2->isPublish = '1';

        $activation = new \App\Src\Subscription\ActivationNowCheck([$ev1, $ev2]);
        $activation->checkPublishEvents();
        $this->assertFalse($activation->isValid);
    }

    public function testIsValidShouldBeTrueIfNoEventsArePublished()
    {
        $ev1 = new \stdClass();
        $ev1->isPublish = '0';
        $ev2 = new \stdClass();
        $ev2->isPublish = '0';

        $activation = new \App\Src\Subscription\ActivationNowCheck([$ev1, $ev2]);
        $activation->checkPublishEvents();
        $this->assertTrue($activation->isValid);
    }
}


