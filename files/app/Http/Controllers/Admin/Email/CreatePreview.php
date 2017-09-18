<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Controller;

class CreatePreview extends Controller
{
    public $template;
    public $events;
    private $noTip;
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
    // @param obect  $events
    // @param int    $noTip
    // @return this
    public function __construct($template, $events, $noTip = 0)
    {
        $this->template = $template;
        $this->events = $events;
        $this->noTip = $noTip;

        // remove sectiion tip or noTip based on $this->noTip
        $this->removeSection();

        // clear all sections tags
        $this->removeSectionsTags();

        // set events in template
        $this->putEventsInTemplate();

        //print_r($this->template);

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
        if ($this->noTip == 1) {
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
        $from = $this->tags['sections'][$this->noTip]['from'];
        $to = $this->tags['sections'][$this->noTip]['to'];
        $data = $this->splitString($this->template, $from, $to);
        $this->template = $data['header'] . $data['footer'];
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

    // this function will remove all sections tags
    private function removeSectionsTags()
    {
        foreach ($this->tags['sections'] as $sections)
            foreach ($sections as $tag)
                $this->template = str_replace($tag, '', $this->template);
    }

}
