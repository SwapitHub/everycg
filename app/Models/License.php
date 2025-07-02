<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class License extends Model
{
    public $timestamps = false;
    public $table      = 'license';
    protected $guarded = [];

}