<?php

namespace App\Src\Subscription;

class ActivationNowCheck
{
    private $events;
    public $isValid;

    public function __construct($events)
    {
        $this->events = $events;
    }

    public function validateEvents()
    {
        if (!$this->events) {
            $this->isValid = true;
            return;
        }

        foreach ($this->events as $event) {
            if ($event->isPublish == 1) {
                $this->isValid = false;
                return;
            }
        }

        $this->isValid = true;
    }
}
