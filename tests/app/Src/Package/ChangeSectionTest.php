<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ChangeSectionTest extends TestCase
{
    public function testShouldFailIFNotInstanceOf()
    {
        $this->assertInstanceOf(
            \App\Src\Package\ChangeSection::class,
            new \App\Src\Package\ChangeSection([], [], 'nu')
        );
    }

    /** Tests for getSection() **/

    public function testShoulReturnSameSectionWhenMinOneEventIsPublished()
    {
        $e = new \App\Distribution();
        $e->isPublish = '1';
        $currentSection = 'nu';
        $expectedSection = 'nu';

        $instance = new \App\Src\Package\ChangeSection([$e], [], $currentSection);
        $instance->evaluateSection();
        $this->assertEquals($instance->getSection(), $expectedSection);
    }

    public function testShoulReturnSameSectionWhenMinOneEventIsSendByEmail()
    {
        $e = new \App\Distribution();
        $e->isEmailSend = '1';
        $currentSection = 'nu';
        $expectedSection = 'nu';

        $instance = new \App\Src\Package\ChangeSection([$e], [], $currentSection);
        $instance->evaluateSection();
        $this->assertEquals($instance->getSection(), $expectedSection);
    }

    public function testManyConfigurationsSubscriptionsEventsCurrentSection()
    {
        // email not send, subscriptions active, noUsers
        // expect real users
        $e = new \App\Distribution();
        $e->isEmailSend = '0';

        $s = new \App\Subscription();
        $s->status = 'active';

        $currentSection = 'nu';
        $expectedSection = 'ru';

        $instance = new \App\Src\Package\ChangeSection([$e], [$s], $currentSection);
        $instance->evaluateSection();
        $this->assertEquals($instance->getSection(), $expectedSection);

        // email not sent, no subscriptions active, package is in real users
        // expent no users
        $e = new \App\Distribution();
        $e->isEmailSend = '0';

        $s = new \App\Subscription();
        $s->status = 'archive';

        $currentSection = 'ru';
        $expectedSection = 'nu';

        $instance = new \App\Src\Package\ChangeSection([$e], [$s], $currentSection);
        $instance->evaluateSection();
        $this->assertEquals($instance->getSection(), $expectedSection);
    }

    public function testShoulReturnNoUsersNoSubscriptionsNoEvents()
    {
        $currentSection = 'ru';
        $expectedSection = 'nu';

        $instance = new \App\Src\Package\ChangeSection([], [], $currentSection);
        $instance->evaluateSection();
        $this->assertEquals($instance->getSection(), $expectedSection);
    }

    public function testShoulReturnNoUsersWhensectionIsNullAndNotHaveEvents()
    {
        $currentSection = null;
        $expectedSection = 'nu';

        $instance = new \App\Src\Package\ChangeSection([], [], $currentSection);
        $instance->evaluateSection();
        $this->assertEquals($instance->getSection(), $expectedSection);
    }

    public function testShoulReturnRealUsersWhenSectionIsNullAndThereIsSubscriptions()
    {
        $s = new \App\Subscription();
        $s->status = 'active';

        $currentSection = null;
        $expectedSection = 'ru';

        $instance = new \App\Src\Package\ChangeSection([], [$s], $currentSection);
        $instance->evaluateSection();
        $this->assertEquals($instance->getSection(), $expectedSection);
    }

    public function testShoulReturnNoUsersWhenSectionIsNullAndNoSubscriptions()
    {
        $currentSection = null;
        $expectedSection = 'nu';

        $instance = new \App\Src\Package\ChangeSection([], [], $currentSection);
        $instance->evaluateSection();
        $this->assertEquals($instance->getSection(), $expectedSection);
    }
}
