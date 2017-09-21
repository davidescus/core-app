<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use PHPMailerAutoload;
use PHPMailer;

class SendMail extends Controller
{
    public function __construct($args)
    {
        $mail = new PHPMailer;

        // notice the \ you have to use root namespace here
        try {
            $mail->isSMTP(); // tell to use smtp
            $mail->CharSet = “utf-8”; // set charset to utf8
            $mail->Host = $_SERVER[‘MAIL_HOST_NAME’];
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = false;
            $mail->Port = 25; // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = “”;
            $mail->Password = “”;
            $mail->setFrom(“examle@examle.com”, “examle Team”);
            $mail->Subject = “examle”;
            $mail->MsgHTML(“This is a test new test”);
            $mail->addAddress(“kundan.roy@examle.net”, “admin”);
            $mail->addReplyTo(‘examle@examle.net’, ‘Information’);
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            $mail->send();
        } catch (phpmailerException $e) {
            dd($e);
        } catch (Exception $e) {
            dd($e);
        }

        dd(‘success’);}}))
    }
}

