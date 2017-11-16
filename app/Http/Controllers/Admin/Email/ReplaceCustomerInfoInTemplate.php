<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Controller;

class ReplaceCustomerInfoInTemplate extends Controller
{
    public $template;
    private $customer;

    // @param string $template
    // @param array $events
    public function __construct($template, $customer)
    {
        $this->template = $template;
        $this->customer = $customer;

        $this->replaceCustomerInfoInTemplate();

        return $this;
    }

    // this will replace customer information in email template
    private function replaceCustomerInfoInTemplate()
    {

        if (! $this->customer)
            return;

        $find = [
            '{{email}}',
            '{{name}}',
        ];
        $replace = [
            $this->customer->email,
            $this->customer->name,
        ];

        $this->template = str_replace($find, $replace, $this->template);
    }
}


