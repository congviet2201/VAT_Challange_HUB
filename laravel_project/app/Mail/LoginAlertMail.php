<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $loginTime;
    public $ipAddress;

    public function __construct($user, string $ipAddress, string $loginTime)
    {
        $this->user = $user;
        $this->ipAddress = $ipAddress;
        $this->loginTime = $loginTime;
    }

    public function build()
    {
        return $this->subject('Thông báo đăng nhập Challenge Hub')
            ->view('emails.login-alert');
    }
}
