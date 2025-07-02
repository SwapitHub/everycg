<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductFiles extends Model
{
    public $timestamps = false;
    public $table      = 'product_files';
    protected $guarded = [];

    public static function getFileExt($proid)
    {
         $pname = '';
        $files = ProductFiles::where('pro_id',$proid)->groupBy('content')->get();
        if($files) {  
           $i=0;
            foreach ($files as $file) {
              $i++;
                $pname .= $file->content;
                if(count($files) != $i)
                {
                  $pname .= ' | ';
                }
            }
        }
        return $pname;
    }

}