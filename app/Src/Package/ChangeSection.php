<?php

namespace App\Src\Package;

class ChangeSection
{
    private $events;
    private $subscriptions;
    private $currentSection;
    private $destination;

    public function __construct($events, $subscriptions, $currentSection)
    {
        $this->events = $events;
        $this->subscriptions = $subscriptions;
        $this->currentSection = $currentSection;
    }

    public function evaluateSection()
    {
        foreach ($this->events as $event) {
            if ($event->isPublish == '1' || $event->isEmailSend) {
                $this->destination = $this->currentSection;
                return;
            }
        }

        foreach ($this->subscriptions as $subscription) {
            if ($subscription->status == 'active') {
                $this->destination = 'ru';
                return;
            }
        }

        $this->destination = 'nu';
        return;
    }

    public function getSection()
    {
        return $this->destination;
    }
}

