<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class UserCart extends Model
{
    public $timestamps = false;
    public $table      = 'cart';
    protected $guarded = [];

   

}