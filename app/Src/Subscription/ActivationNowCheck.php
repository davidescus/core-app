<?php

namespace App\Src\Subscription;

/*
 * Check if there is events already published in archive
 * and return false if there is, true if there is'not
 * only for no users.
 */

class ActivationNowCheck
{
    private $events;
    public $isValid;

    public function __construct($events)
    {
        $this->events = $events;
    }

    public function checkPublishEventsInNoUsers()
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
