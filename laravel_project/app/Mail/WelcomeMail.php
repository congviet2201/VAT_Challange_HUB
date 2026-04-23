<?php
/**
 * File purpose: app/Mail/WelcomeMail.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
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
     * HĂ m __construct(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * HĂ m build(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function build()
    {
        return $this->subject('Chào mừng bạn đến với Challenge Hub!')
                    ->view('emails.welcome'); // Nó sẽ tìm file này để lấy giao diện
    }
}
