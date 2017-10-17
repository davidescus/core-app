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
        $one = new \stdClass();
        $one->isPublish = '1';
        $two = new \stdClass();
        $two->isPublish = '0';
        $activation = new \App\Src\Subscription\ActivationNowCheck([
            0 => $one,
            1 => $two
        ]);
        $activation->checkPublishEvents();
        $this->assertFalse($activation->isValid);
    }

    public function testIsValidShouldBeFalseIfMoreEventsIsPublish()
    {
        $one = new \stdClass();
        $one->isPublish = '1';
        $two = new \stdClass();
        $two->isPublish = '1';
        $activation = new \App\Src\Subscription\ActivationNowCheck([
            0 => $one,
            1 => $two
        ]);
        $activation->checkPublishEvents();
        $this->assertFalse($activation->isValid);
    }

    public function testIsValidShouldBeTrueIfNoEventsArePublished()
    {
        $one = new \stdClass();
        $one->isPublish = '0';
        $two = new \stdClass();
        $two->isPublish = '0';
        $activation = new \App\Src\Subscription\ActivationNowCheck([
            0 => $one,
            1 => $two
        ]);
        $activation->checkPublishEvents();
        $this->assertTrue($activation->isValid);
    }
}


