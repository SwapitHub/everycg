<?php
#app/Http/Admin/Controllers/ShopCategoryController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\ShopLanguage;
use Illuminate\Http\Request;
use Validator;

class SellerController extends Controller
{
    public $languages;

    public function __construct()
    {
        $this->languages = ShopLanguage::getList();

    }

    public function index()
    {
         


       $data = [
            'title' => 'Affiliate User list',
            'sub_title' => '',
            'icon' => 'fa fa-indent',
            'menu_left' => '',
            'menu_right' => '',
            'menu_sort' => '',
            'script_sort' => '',
            'menu_search' => '',
            'script_search' => '',
            'listTh' => '',
            'dataTr' => '',
            'pagination' => '',
            'result_items' => '',
            'url_delete_item' => '',
        ];

        $listTh = [
            'check_row' => '',
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'status' =>'status',
            'created_at' =>'created_at',
            'action' => 'action',
        ];
        $sort_order = request('sort_order') ?? 'id_desc';
        $keyword = request('keyword') ?? '';
        $arrSort = [
            'id__desc' => trans('id_desc'),
            'id__asc' => trans('id_asc'),
            'name__desc' => trans('name_desc'),
            'name__asc' => trans('name_asc'),
        ];
        $obj = new Seller;

        
        if ($keyword) {
            $obj = $obj->whereRaw('(id = ' . (int) $keyword . ' OR name like "%' . $keyword . '%" )');
        }

        if ($sort_order && array_key_exists($sort_order, $arrSort)) {
            $field = explode('__', $sort_order)[0];
            $sort_field = explode('__', $sort_order)[1];
            $obj = $obj->orderBy($field, $sort_field);

        } else {
            $obj = $obj->orderBy('id', 'desc');
        }
        $dataTmp = $obj->paginate(20);


        $dataTr = [];
        foreach ($dataTmp as $key => $row) {           
            $dataTr[] = [
                'check_row' => '<input type="checkbox" class="grid-row-checkbox" data-id="' . $row['id'] . '">',
                'id' => $row['id'],
                'name' => $row['name'],                     
                'email' => $row['email'],                     
                'status' => $row['user_verify'] ? '<span class="label label-success">Verified</span>' : '<span class="label label-danger">Pending</span>',
                'created_at' => $row['created_at'],
                'action' => '
                    <a href="' . route('admin_suser.edit', ['id' => $row['id']]) . '"><span title="' . trans('edit') . '" type="button" class="btn btn-flat btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

                    <span onclick="deleteItem(' . $row['id'] . ');"  title="' . trans('delete') . '" class="btn btn-flat btn-danger"><i class="fa fa-trash"></i></span>'
                ,
            ];
        }

       


        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links('admin.component.pagination');
        $data['result_items'] = trans('order.admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'item_total' => $dataTmp->total()]);
//menu_left
        $data['menu_left'] = '<div class="pull-left">
                    <button type="button" class="btn btn-default grid-select-all"><i class="fa fa-square-o"></i></button> &nbsp;

                    <a class="btn   btn-flat btn-danger grid-trash" title="Delete"><i class="fa fa-trash-o"></i><span class="hidden-xs"> ' . trans('admin.delete') . '</span></a> &nbsp;

                    <a class="btn   btn-flat btn-primary grid-refresh" title="Refresh"><i class="fa fa-refresh"></i><span class="hidden-xs"> ' . trans('admin.refresh') . '</span></a> &nbsp;</div>
                    ';
//=menu_left

//menu_right
        /*$data['menu_right'] = '
                        <div class="btn-group pull-right" style="margin-right: 10px">
                           <a href="' . route('admin_suser.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
                           <i class="fa fa-plus"></i><span class="hidden-xs">' . trans('admin.add_new') . '</span>
                           </a>
                        </div>

                        ';*/
//=menu_right

//menu_sort

        $optionSort = '';
        foreach ($arrSort as $key => $status) {
            $optionSort .= '<option  ' . (($sort_order == $key) ? "selected" : "") . ' value="' . $key . '">' . $status . '</option>';
        }

        $data['menu_sort'] = '
                       <div class="btn-group pull-left">
                        <div class="form-group">
                           <select class="form-control" id="order_sort">
                            ' . $optionSort . '
                           </select>
                         </div>
                       </div>

                       <div class="btn-group pull-left">
                           <a class="btn btn-flat btn-primary" title="Sort" id="button_sort">
                              <i class="fa fa-sort-amount-asc"></i><span class="hidden-xs"> ' . trans('admin.sort') . '</span>
                           </a>
                       </div>';

        $data['script_sort'] = "$('#button_sort').click(function(event) {
      var url = '" . route('admin_suser.index') . "?sort_order='+$('#order_sort option:selected').val();
      $.pjax({url: url, container: '#pjax-container'})
    });";

//=menu_sort

//menu_search

        $data['menu_search'] = '
                <form action="' . route('admin_suser.index') . '" id="button_search">
                   <div onclick="$(this).submit();" class="btn-group pull-right">
                           <a class="btn btn-flat btn-primary" title="Refresh">
                              <i class="fa  fa-search"></i><span class="hidden-xs"> ' . trans('admin.search') . '</span>
                           </a>
                   </div>
                   <div class="btn-group pull-right">
                         <div class="form-group">
                           <input type="text" name="keyword" class="form-control" placeholder="' . trans('search') . '" value="' . $keyword . '">
                         </div>
                   </div>
                </form>';
//=menu_search

        $data['url_delete_item'] = route('admin_suser.delete');

        return view('admin.screen.list')
            ->with($data);
    }

/**
 * Form create new order in admin
 * @return [type] [description]
 */
   /* public function create()
    {
        
        $data = [
            'title' => 'Add new tag',
            'sub_title' => '',
            'title_description' => 'Create Tag',
            'icon' => 'fa fa-plus',
            'tag' => [],
            'languages' => $this->languages,
            'url_action' => route('admin_suser.create'),
           

        ];

        return view('admin.screen.tag')
            ->with($data);
    }*/

/**
 * Post create new order in admin
 * @return [type] [description]
 */
   /* public function postCreate()
    {
        $data = request()->all();

        $data['alias'] = !empty($data['alias'])?$data['alias']:$data['name'];
         $data['alias'] = sc_word_format_url($data['alias']);
        $data['alias'] = sc_word_limit($data['alias'], 100);
        
        $validator = Validator::make($data, [
            'name' => 'required|unique:tags,name',
            'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:tags,alias|string|max:100',           
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }

        $dataInsert = [
            'name' => $data['name'],       
            'alias' => $data['alias'],   
            'status' => !empty($data['status']) ? 1 : 0,
            'sort' => (int) $data['sort'],

        ];
         Tags::create($dataInsert);
        

        return redirect()->route('admin_suser.index')->with('success', 'New Tag created successfully!');

    }*/

/**
 * Form edit
 */
    public function edit($id)
    {
       
        $seller = Seller::find($id);
        if ($seller === null) {
            return 'no data';
        }
        $data = [
            'title' => 'Edit Affiliate User',
            'sub_title' => '',
            'title_description' => '',
            'icon' => 'fa fa-pencil-square-o',            
            'seller' => $seller,
            'url_action' => route('admin_suser.edit', ['id' => $seller['id']]),
        ];
        return view('admin.screen.suser')
            ->with($data);
    }

/**
 * update status
 */
    public function postEdit($id)
    {
        $seller = Seller::find($id);
        $data = request()->all(); 

        $status  = !empty($data['status']) ? 1 : 0;
        if($seller->user_verify==0 AND $status==1)
        {
            $msg = '<html><head></head><body style="margin:0px; background:#eae9ea; width:100%; padding:0; text-decoration:none;">
            <div style="margin:0 auto; width:600px; background:#fff;">
            <table style="width:100%; padding:0px 40px; float:left; margin:0px; background:#fff;">
            <tr style="float:left; margin-top:10px;">
            <td style=" width:100%"></td>
            </tr>
            <tr>
            <td style="border-bottom:1px solid#d7d7d7;"> <p style=" width:75%; margin:20px 0 20px 0; float:left; color:#000; font-size:20px; font-family:arial; font-weight:bold;">Email Verify</p>
            
                
            </td>
            </tr>

            <tr>
            <td><p>We have Verified your account. Now you can login to your account.</p>
            <a href="'.url('/seller_login').'">Login Account</a><p>If you have any questions, just contact us, we\'re always happy to help out. <br><br>
                Cheers,<br> Every CG<br></p>';   

        
         $msg .= '</td></tr></table><!---cont-->
            <div class="footer" style="width:100%; float:left; padding-bottom:30px;">
            <div class="foot_logo" style="margin:0; padding:0;display:inline-block;text-align:center;width:100%;">
            </div>
            </div>
            </div>
            </body></html>'; 

            $header = "From: Every Cg <".sc_store('email')."> \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            $to = $seller['email'];
            $subject = 'Confirmation';
            mail($to,$subject,$msg,$header); 

          
            
        }    
          

        $validator = Validator::make($data, [
          'name' => 'required'                
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
//Edit

        $dataUpdate = [
            'name' => $data['name'],                
            'user_verify' => !empty($data['status']) ? 1 : 0,
        ];        

        $seller->update($dataUpdate);   

//
        return redirect()->route('admin_suser.index')->with('success', trans('User updated successfully!'));

    }

/*
Delete list Item
Need mothod destroy to boot deleting in model
 */
    public function deleteList()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => 'method not allowed!']);
        } else {
            $ids = request('ids');
            $arrID = explode(',', $ids);
            Seller::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

}
