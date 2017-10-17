<?php

namespace App\Src\Subscription;

class ActivationNowCheck
{
    private $events;
    public isValid;

    public function __construct($events)
    {
        $this->events = $events;

        var_dump($this->isValid);
    }
}
