<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RestrictedTipTest extends TestCase
{
    public function testShouldFailIFNotInstanceOf()
    {
        $this->assertInstanceOf(
            \App\Src\Subscription\RestrictedTip::class,
            new \App\Src\Subscription\RestrictedTip([], [])
        );
    }

}

