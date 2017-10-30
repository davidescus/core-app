<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Login extends Controller
{

    // each login will generate a new token
    // @param string $email
    // @param string $password
    // @return array()
    public function index(Request $r)
    {
        $email = $r->input('email');
        $password = $r->input('password');

        $user = \App\User::where('email', $email)
            ->where('password', sha1($password))->first();

        if (!$user)
            return [
                'success' => 0,
            ];

        //$token = bin2hex(random_bytes(16));

        //$user->token = $token;
        //$user->save();

        return [
            'success' =>1,
            'token'   => $token,
        ];
    }

}
