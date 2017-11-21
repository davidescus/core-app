<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class SendMail extends Controller
{

    private $host;
    private $port;
    private $user;
    private $password;
    private $encryption;

    private $errMessage = '';
    private $email;

    public function __construct()
    {
        $emails = \App\EmailSchedule::where('status', 'waiting')->get();
        if ($emails) {

            // update emails status, if we have many servers running, for not send an email many times from many servers
            foreach ($emails as $email) {
                $email->status = 'send';
                $email->info   = 'Sended with success';
                $email->update();
            }

            foreach ($emails as $email) {

                $this->email = $email;

                $this->sendEmail();

                if ($this->errMessage != '') {
                    $email->status = 'error';
                    $email->info   = $this->errMessage;
                }

                $email->update();
            }
        }
    }

    private function sendEmail()
    {

        // reset errors.
        $this->errMessage = '';

        if ($this->email->provider == 'site') {
            $site = \App\Site::find($this->email->sender);

            $this->host = $site->smtpHost;
            $this->port = $site->smtpPort;
            $this->user = $site->smtpUser;
            $this->password = $site->smtpPassword;
            $this->encryption = $site->smtpEncription;
        }

        $mail = new PHPMailer(true);

        try {
            //$mail->SMTPDebug = 3;
            $mail->isSMTP();
            $mail->CharSet = 'utf-8';
            $mail->Host = $this->host;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = $this->encryption;
            $mail->Port = $this->port;
            $mail->Username = $this->user;
            $mail->Password = $this->password;
            $mail->setFrom($site->email, $this->email->fromName);
            $mail->addAddress($this->email->to);
            $mail->addReplyTo($site->email);
            $mail->Subject = $this->email->subject;
            $mail->MsgHTML($this->email->body);
            $mail->isHtml(true);
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            if (!$mail->send()) {
                $this->errMessage .= "Mailer Error: " . $mail->ErrorInfo . PHP_EOL;
            }

        } catch (phpmailerException $e) {
            $this->errMessage .= $e->errorMessage() . PHP_EOL;
        } catch (Exception $e) {
            $this->errMessage .= $e->getMessage() . PHP_EOL;
        }
    }
}

