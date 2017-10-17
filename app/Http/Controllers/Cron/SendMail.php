<?php

namespace App\Http\Controllers\Cron\Email;

use App\Http\Controllers\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

error_reporting(E_ALL);
ini_set('display_errors', 1);

private $hasError = false;
private $error = '';
private $email;

class SendMail extends Controller
{
    public function __construct()
    {
        return "test";

        $emails = \App\EmailSchedule::where('status', 'waiting')->get();

        if ($emails) {
            foreach ($emails as $email) {

                $this->email = $email

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
            $this->port = $this->smtpPort;
            $this->user = $site->smtpUser;
            $this->password = $site->smtpPassword;
            $this->encryption = $site->smtpEncription;
        }

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 3;
            $mail->isSMTP();
            $mail->CharSet = 'utf-8';
            $mail->Host = $this->host;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = $this->encryption;
            $mail->Port = $this->port;
            $mail->Username = $this->user;
            $mail->Password = $this->pass;
            $mail->setFrom($this->email->from, $this->email->fromName);
            $mail->addAddress($this->email->to);
            $mail->addReplyTo($this->email->from);
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
                echo "Mailer Error: " . $mail->ErrorInfo . PHP_EOL;
            }

        } catch (phpmailerException $e) {
            dd($e->errorMessage());
        } catch (Exception $e) {
            dd($e->getMessage());
        }

        dd('success');
    }
}

