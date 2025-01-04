<?php 
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmail extends VerifyEmailBase
{
    use Queueable;

    /**
     * Get the verification mail message for the given URL.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function verificationUrl($notifiable)
    {
        // Sử dụng phương thức route để tạo URL xác minh
        $url = url("/email/verify/{$notifiable->getKey()}/" . sha1($notifiable->getEmailForVerification()));

        return $this->buildMailMessage($url);
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
                    ->subject('Xác minh địa chỉ email của bạn')
                    ->line('Nhấp vào nút bên dưới để xác minh địa chỉ email của bạn.')
                    ->action('Xác minh email', $url)
                    ->line('Nếu bạn không tạo tài khoản, không cần thực hiện thêm hành động nào.');
    }
}
