<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailOpen extends Model
{
    use HasFactory;
    public $table      = 'email_opens';
    protected $guarded = [];
}
