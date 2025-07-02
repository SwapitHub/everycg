<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
use App\Models\ShopEmailTemplate;

//Class which implements Illuminate\Contracts\Auth\Authenticatable
use Illuminate\Foundation\Auth\User as Authenticatable;

//Notification for Seller
//use App\Notifications\SellerResetPasswordNotification;

//Trait for sending notifications in laravel
//use Illuminate\Notifications\Notifiable;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Seller extends Authenticatable
{
    // This trait has notify() method defined
    use Notifiable;

    //Mass assignable attributes
    protected $fillable = [
      'name', 'email', 'password','affiliate_code','user_verify','token',
    ];

    //hidden attributes
    protected $hidden = [
       'password', 'remember_token',
    ];

    //Send password reset notification
     public function sendPasswordResetNotification($token)
    {
        $checkContent = (new ShopEmailTemplate)->where('group', 'forgot_password')->where('status', 1)->first();
        if ($checkContent) {
            $content = $checkContent->text;
            $dataFind = [
                '/\{\{\$title\}\}/',
                '/\{\{\$reason_sednmail\}\}/',
                '/\{\{\$note_sendmail\}\}/',
                '/\{\{\$note_access_link\}\}/',
                '/\{\{\$reset_link\}\}/',
                '/\{\{\$reset_button\}\}/',
            ];
            $dataReplace = [
                trans('email.forgot_password.title'),
                trans('email.forgot_password.reason_sednmail'),
                trans('email.forgot_password.note_sendmail', ['site_admin' => config('mail.from.name')]),
                trans('email.forgot_password.note_access_link', ['reset_button' => trans('email.forgot_password.reset_button')]),
                route('password.reset', ['token' => $token]),
                trans('email.forgot_password.reset_button'),
            ];
            $content = preg_replace($dataFind, $dataReplace, $content);
            $data = [
                'content' => $content,
            ];

            $config = [
                'to' => $this->getEmailForPasswordReset(),
                'subject' => trans('email.forgot_password.reset_button'),
            ];

            sc_send_mail('mail.forgot_password', $data, $config, []);
        }

    }
}
