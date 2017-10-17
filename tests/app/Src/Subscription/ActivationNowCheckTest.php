<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ActivationNowCheckTest extends TestCase
{
    public function testShouldFailIFNotInstanceOf()
    {
        $this->assertInstanceOf(
            \App\Src\Subscription\ActivationCheck::class,
            new \App\Src\Subscription\ActivationCheck()
        );
    }
}


