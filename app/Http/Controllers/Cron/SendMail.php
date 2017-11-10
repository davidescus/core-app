<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class SendMail extends Controller
{

    private $hasError = false;
    private $error = '';
    private $email;

    public function __construct()
    {
        $emails = \App\EmailSchedule::where('status', 'waiting')->get();
        if ($emails) {
            foreach ($emails as $email) {

                $this->email = $email;

                $this->sendEmail();

                if ($this->hasError) {
                    echo "we have an error here";
                }
            }
        }
    }

    private function sendEmail()
    {

        // reset errors.
        $this->hasError = false;

        if ($this->email->provider == 'site') {
            $site = \App\Site::find($this->email->sender);

            $this->host = $site->smtpHost;
            $this->port = $site->smtpPort;
            $this->user = $site->smtpUser;
            $this->password = $site->smtpPassword;
            $this->encryption = $site->smtpEncription;
        }

        $mail = new PHPMailer(true);

        //try {
        //    //$mail->SMTPDebug = 3;
        //    //$mail->isSMTP();
        //    //$mail->CharSet = 'utf-8';
        //    //$mail->Host = $this->host;
        //    //$mail->SMTPAuth = true;
        //    //$mail->SMTPSecure = 'STARTTLS';
        //    //$mail->Port = $this->port;
        //    //$mail->Username = $this->user;
        //    //$mail->Password = $this->password;
        //    //$mail->setFrom($site->email, $this->email->fromName);
        //    //$mail->addAddress($this->email->to);
        //    //$mail->addReplyTo($site->email);
        //    //$mail->Subject = $this->email->subject;
        //    //$mail->MsgHTML($this->email->body);
        //    //$mail->isHtml(true);
        //    //$mail->SMTPOptions = [
        //    //    'ssl' => [
        //    //        'verify_peer' => false,
        //    //        'verify_peer_name' => false,
        //    //        'allow_self_signed' => true
        //    //    ]
        //    //];

        //    $mail->SMTPDebug = 3;
        //    $mail->isSMTP();
        //    $mail->CharSet = 'utf-8';
        //    $mail->Host = 'mail.ahwinners.com';
        //    $mail->SMTPAuth = true;
        //    //$mail->SMTPSecure = 'ssl';
        //    $mail->Port = 587;
        //    $mail->Username = 'info@ahwinners.com';
        //    $mail->Password = 'dITlEoZ5(M0u';
        //    $mail->setFrom('info@ahwinners.com', 'contact email');
        //    $mail->addAddress('rahthman.s@gmail.com');
        //    $mail->addReplyTo('info@ahwinners.com');
        //    $mail->Subject = 'subject';
        //    $mail->MsgHTML('This is the email body');
        //    $mail->isHtml(true);
        //    $mail->SMTPOptions = [
        //        'ssl' => [
        //            'verify_peer' => false,
        //            'verify_peer_name' => false,
        //            'allow_self_signed' => true
        //        ]
        //    ];

        //    if (!$mail->send()) {
        //        echo "Mailer Error: " . $mail->ErrorInfo . PHP_EOL;
        //    }

        //} catch (phpmailerException $e) {
        //    dd($e->errorMessage());
        //} catch (Exception $e) {
        //    dd($e->getMessage());
        //}


        dd('success');
    }
}

