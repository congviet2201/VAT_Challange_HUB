<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Dòng này quan trọng để hiển thị tên user trong mail

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Chào mừng bạn đến với Challenge Hub!')
                    ->view('emails.welcome'); // Nó sẽ tìm file này để lấy giao diện
    }
}
