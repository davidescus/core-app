<?php

namespace App\Src\Subscription;

class RestrictedTip
{
    private $restrictedEvents = [];
    private $events = [];

    public function __construct($events, $restrictedEvents)
    {
        $this->events = $events;
        $this->restrictedEvents = $restrictedEvents;
    }

    public function unsetRestrictedEvents()
    {
    }

    public function getEvents()
    {
        return $this->events;
    }
}

