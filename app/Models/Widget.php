<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Widget extends Model
{
    public $timestamps = false;
    public $table      = 'widget';
    protected $guarded = [];

     public static function getSinglewidget($alias)
    {       
            return $query = Widget::where('alias',$alias)->where('status',1)->first();
    }

    

}