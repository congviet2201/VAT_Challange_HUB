<?php
/**
 * Mục đích file: app/Mail/WelcomeMail.php
 * Lớp xử lý gửi email chào mừng tới người dùng sau khi đăng ký thành công.
 */

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable gửi email chào mừng sau khi user đăng ký thành công.
 */
class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Dòng này quan trọng để hiển thị tên user trong mail

    /**
     * Hàm __construct(): xử lý nghiệp vụ theo tên hàm.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Hàm build(): xử lý nghiệp vụ theo tên hàm.
     */
    public function build()
    {
        return $this->subject('Chào mừng bạn đến với Challenge Hub!')
                    ->view('emails.welcome'); // Nó sẽ tìm file này để lấy giao diện
    }
}
