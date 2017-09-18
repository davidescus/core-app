<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CreatePreview extends Controller
{
    public $packageId   = 0;
    public $siteName    = '';
    public $packageName = '';
    public $ids         = [];
    public $isNoTip     = ''; // empty string for check
    public $error       = false;
    public $message     = '';

    private $events = [];
    private $tags = [
        'sections' => [
            // noTip == 0, mean is tip
            0 => [
                'from' => '{{section NO TIP}}',
                'to'   => '{{/section NO TIP}}',
            ],
            // noTip == 1 , mean is noTip
            1 => [
                'from'   => '{{section TIP}}',
                'to'     => '{{/section TIP}}',
            ],
        ],
        'events' => [
            'from' => '{{events}}',
            'to'   => '{{/events}}',
        ],
    ];


    // create preview content
    // auto select section tip | no Tip
    //   - tip: replace placeholders with evetn info
    // @param string $template
    //   - user when have request from preview and send.
    // @param array $ids
    //   - ids of distriibuted events.
    // @return this
    public function __construct($ids, $template = null)
    {
        if (!$ids)
            return [
                'type' => 'error',
                'message' => 'No events selected',
            ];

        // check if event make part fron same package
        foreach ($ids as $id) {

            $event = \App\Distribution::find($id);
            if (!$event) {
                $this->error = true;
                $this->message = "Event with id $id not found. Maybe was deleted.";
                return $this;
            }

            // set packageId in first loop
            if ($this->packageId === 0)
                $this->packageId = $event->packageId;

            // check if all events make part from same package
            if ((int)$event->packageId !== $this->packageId) {
                $this->error = true;
                $this->message = "Preview and Send can use events form same package, You choose events from many packages.";
                return $this;
            }

            // set type tip | noTip on first loop
            if ($this->isNoTip === '')
                $this->isNoTip = $event->isNoTip;

            // check if all events have same type tip | noTip
            if ($this->isNoTip !== (int)$event->isNoTip) {
                $this->error = true;
                $this->message = "You can not select TIP and NO TIP at same time.";
                return $this;
            }

            // event must have more than 5 minutes to start.
            if ($event->eventDate < Carbon::now('UTC')->addMinutes(5)) {
                $this->error = true;
                $this->message = "Event with id: $id will start in less than 5 minutes. You can not use this event.";
                return $this;
            }

            $this->events[] = $event;
        }

        $package = \App\Package::find($this->packageId);
        $this->packageName = $package->name;
        $this->siteName = \App\Site::find($package->siteId)->name;

        if ($template !== null) {
            $this->template = $template;
            return $this;
        }

        $this->template = $package->template;

        // remove sectiion tip or noTip based on $this->noTip
        $this->removeSection();

        // clear all sections tags
        $this->removeSectionsTags();

        // set events in template
        $this->putEventsInTemplate();

        return $this;
    }

    // this function will replace placeholders with events info
    private function putEventsInTemplate()
    {
        $from = $this->tags['events']['from'];
        $to = $this->tags['events']['to'];

        $split = $this->splitString($this->template, $from, $to);
        $this->template = $split['header'];

        // case NoTip
        if ($this->isNoTip == 1) {
            return;
        }

        foreach ($this->events as $event) {

            $find = [
                '{{eventDate}}',
                '{{country}}',
                '{{league}}',
                '{{homeTeam}}',
                '{{awayTeam}}',
                '{{predictionName}}',
            ];
            $replace = [
                $event->eventDate,
                $event->country,
                $event->league,
                $event->homeTeam,
                $event->awayTeam,
                $event->predictionName,
            ];

            $this->template .= str_replace($find, $replace, $split['body']);
        }

        $this->template .= $split['footer'];
    }

    // this function will remove section
    //   - no tip == 0 remove noTip section
    //   - no tip == 1 remove tip section
    private function removeSection()
    {
        $from = $this->tags['sections'][$this->isNoTip]['from'];
        $to = $this->tags['sections'][$this->isNoTip]['to'];
        $data = $this->splitString($this->template, $from, $to);
        $this->template = $data['header'] . $data['footer'];
    }

    // this function will remove all sections tags
    private function removeSectionsTags()
    {
        foreach ($this->tags['sections'] as $sections)
            foreach ($sections as $tag)
                $this->template = str_replace($tag, '', $this->template);
    }

    // this function will split a string in 3 sections
    // @param string $str
    // @param string $from
    // @param string $to
    // @return array()
    private function splitString($str, $from, $to)
    {
        $data = [
            'header' => '',
            'body'   => '',
            'footer' => '',
        ];

        $data['header'] = substr($str, 0, strpos($str, $from));
        $sub = substr($str, strpos($str, $from)+strlen($from),strlen($str));
        $data['body'] = substr($sub,0,strpos($sub,$to));
        $data['footer'] = substr($sub, strpos($sub, $to) + strlen($to), strlen($sub));

        return $data;
    }
}
