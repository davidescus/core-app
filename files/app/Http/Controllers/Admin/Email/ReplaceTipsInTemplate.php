<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Controller;

class ReplaceTipsInTemplate extends Controller
{
    public $template;

    private $isNoTip;
    private $events;
    private $tags = [
        'events' => [
            'from' => '{{events}}',
            'to'   => '{{/events}}',
        ],
    ];

    public function __construct($template, $events, $isNoTip)
    {
        $this->template = $template;
        $this->events   = $events;
        $this->isNoTip  = $isNopTip;

        $this->replaceTipsInTemplate();

        return $this;
    }

    // this function will replace placeholders with events info
    private function replaceTipsInTemplate()
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

