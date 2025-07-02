<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Tags extends Model
{
    public $timestamps = false;
    public $table      = 'tags';
    protected $guarded = [];

    public static function productTags($ids)
    {
        $tags = (new Tags)->where('status', 1)->whereIn('id', $ids)->get();
        return $tags;
    }

}