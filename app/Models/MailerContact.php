<?php

namespace App\Models;

use App\Models\ShopEmailTemplate;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;

class MailerContact extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $table = 'mailer_contact';
   protected $guarded = [];

 
 
}
