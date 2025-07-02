<?php
#app/Http/Admin/Controllers/ShopPaymentStatusController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Validator;

class ImageUploadController extends Controller
{
    public function fileStore(Request $request)
    {
        $image = $request->file('file');
        $imageName = $image->getClientOriginalName();
        $image->move(public_path('data/images'),$imageName);
        
        $imageUpload = new Gallery();
        $imageUpload->filename = $imageName;
        $imageUpload->save();
        echo '<input type="hidden" name="hiddenfile[]" value="'.$imageName.'">';
        //return response()->json(['success'=>$imageName]);
    }

    public function fileDestroy(Request $request)
    {
        $filename =  $request->get('filename');
        Gallery::where('filename',$filename)->delete();
        $path=public_path().'/data/images/'.$filename;
        if (file_exists($path)) {
            unlink($path);
        }
        return $filename;  
    }

}
