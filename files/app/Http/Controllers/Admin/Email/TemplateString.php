<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Controller;

class TemplateString extends Controller
{
    public $template;
    public $isNoTip;

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

    // this class will remove section tip or noTip
    // @param string template
    // @param string $isNoTip
    // return $this
    public function __construct($template, $isNoTip)
    {
        $this->template = $template;
        $this->isNoTip  = $isNoTip;

        $this->removeSection();
        $this->removeSectionsTags();

        return $this;
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
}
