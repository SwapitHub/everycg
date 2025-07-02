<?php
#app/Http/Admin/Controllers/ShopCategoryController.php
namespace App\Admin\Controllers;
use App\Http\Controllers\Controller;
use App\Models\ShopLanguage;
use Illuminate\Http\Request;
use Validator;
use App\Models\ShopCountry;
use App\Models\Agreement;
use App\Admin\Admin;

class AgreementController extends Controller
{
    public $languages;
    public function __construct()
    {
        $this->languages = ShopLanguage::getList();
    }
    public function index()
    {
        $user = Admin::user();
        $agreement = Agreement::where('adminid', $user->id)->first();
         $data = [
            'countries' => (new ShopCountry)->getList(),
            'url_action' => route('admin_agreement.create'),
            'agreement' => $agreement ?? [],
        ];
        
      return view('admin.screen.agreement')->with($data);;
    }

   
/**
 * Post create new order in admin
 * @return [type] [description]
 */
    public function postCreate(Request $request)
    {
        $user = Admin::user();

        $data = request()->all();
        $langFirst = array_key_first(sc_language_all()->toArray()); //get first code language active
       $validator = Validator::make($data, [ 
            'name' => 'required|string|max:100',        
            'surname' => 'required|string|max:100',        
            'dob' => 'required',        
            'idnumber' => 'required',
            'vat' => 'required',
            'country' => 'required',
            'city' => 'required',
            'attachment' => 'required',
            'paymethod' => 'required',
            'address' => 'required',
            
        ], [
            'name.required' => 'The name field is required.',
            
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        if($request->hasFile('attachment')){ 
            $image = $request->file('attachment');
            $imageName = uniqid().$image->getClientOriginalName();
            $image->move(public_path('data/attachment'),$imageName);
        }

        $dataInsert = [
            'adminid' => $user->id,
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dob' => $data['dob'],
            'idnumber' => $data['idnumber'],
            'vat' => $data['vat'],
            'country' => $data['country'],
            'city' => $data['city'],
            'address' => $data['address'],
            'attachment' => $imageName,
            'paypalemail' => $data['paypalemail'],
            'paymethod' => $data['paymethod'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),           
        ];
        $id = Agreement::insertGetId($dataInsert);
       
        return redirect()->route('admin_agreement.index')->with('success', 'Agreement successfully submitted');
    }


 
  
}