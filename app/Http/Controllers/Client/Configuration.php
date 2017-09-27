<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Configuration extends Controller
{

    // @param integer $id
    // get general configuration for site
    // @return array()
    public function index($id)
    {
        $site = \App\Site::find($id);
        if (!$site)
            return false;

        return [
            'key'        => $site->token,
            'name'       => $site->name,
            'url'        => $site->url,
            'dateFormat' => $site->dateFormat,
            'imap'       => [
                'host'       => $site->imapHost,
                'port'       => $site->imapPort,
                'user'       => $site->imapUser,
                'password'   => $site->imapPassword,
                'encryption' => $site->imapEncryption,
            ],
        ];
    }

    public function get() {}

    public function store() {}

    public function update() {}

    public function destroy() {}

}
