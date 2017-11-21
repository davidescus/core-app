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
        $activation->checkPublishEventsInNoUsers();
        $this->assertTrue($activation->isValid);
    }

    // 1 event published in nun
    public function testIsValidShouldBeFalseIfOneEventIsPublishOnNunTable()
    {
        $ev1 = new \stdClass();
        $ev1->isPublish = '1';
        $ev1->tableIdentifier = 'nun';

        $activation = new \App\Src\Subscription\ActivationNowCheck([$ev1]);
        $activation->checkPublishEventsInNoUsers();
        $this->assertFalse($activation->isValid);
    }

    // 1 event published in nuv
    public function testIsValidShouldBeFalseIfOneEventIsPublishOnNuvTable()
    {
        $ev1 = new \stdClass();
        $ev1->isPublish = '1';
        $ev1->tableIdentifier = 'nuv';

        $activation = new \App\Src\Subscription\ActivationNowCheck([$ev1]);
        $activation->checkPublishEventsInNoUsers();
        $this->assertFalse($activation->isValid);
    }

    public function testIsValidShouldBeFalseIfMoreEventsIsPublishOnNuvTable()
    {
        $ev1 = new \stdClass();
        $ev1->isPublish = '1';
        $ev1->tableIdentifier = 'nuv';
        $ev2 = new \stdClass();
        $ev2->isPublish = '1';
        $ev2->tableIdentifier = 'nuv';

        $activation = new \App\Src\Subscription\ActivationNowCheck([$ev1, $ev2]);
        $activation->checkPublishEventsInNoUsers();
        $this->assertFalse($activation->isValid);
    }

    public function testIsValidShouldBeTrueIfNoEventsArePublishedOnNunTable()
    {
        $ev1 = new \stdClass();
        $ev1->isPublish = '0';
        $ev1->tableIdentifier = 'nun';
        $ev2 = new \stdClass();
        $ev2->isPublish = '0';
        $ev2->tableIdentifier = 'nun';

        $activation = new \App\Src\Subscription\ActivationNowCheck([$ev1, $ev2]);
        $activation->checkPublishEventsInNoUsers();
        $this->assertTrue($activation->isValid);
    }

    public function testIsValidShouldBeTrueIfIsPublishedOnlyOneEventInRunTable()
    {
        $ev1 = new \stdClass();
        $ev1->isPublish = '1';
        $ev1->tableIdentifier = 'run';

        $activation = new \App\Src\Subscription\ActivationNowCheck([$ev1]);
        $activation->checkPublishEventsInNoUsers();
        $this->assertTrue($activation->isValid);
    }

    public function testIsValidShouldBeTrueIfIsPublishedOnlyOneEventInRuvTable()
    {
        $ev1 = new \stdClass();
        $ev1->isPublish = '1';
        $ev1->tableIdentifier = 'run';

        $activation = new \App\Src\Subscription\ActivationNowCheck([$ev1]);
        $activation->checkPublishEventsInNoUsers();
        $this->assertTrue($activation->isValid);
    }
}


