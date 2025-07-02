<?php
#app/Http/Admin/Controllers/ShopCategoryController.php
namespace App\Admin\Controllers;
use App\Http\Controllers\Controller;
use App\Models\MailTemplate;
use App\Models\ShopLanguage;
use App\Models\ShopUser;
use App\Models\ShopSubscribe;
use Illuminate\Http\Request;
use Validator;
use Mail; 
class CustomerEmailController extends Controller
{
    public $languages;
    public function __construct()
    {
        $this->languages = ShopLanguage::getList();
    }
    public function index()
    {
       $data = [
            'title' => 'Mail list',
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
            'action' => 'action',
        ];
        $sort_order = request('sort_order') ?? 'id_desc';
        $keyword = request('keyword') ?? '';
        $arrSort = [
            'id__desc' => 'id desc',
            'id__asc' => 'id asc',
            'name__desc' => 'name desc',
            'name__asc' => 'name desc',
        ];
        $obj = new MailTemplate;        
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
                
                'action' => '
                    <a href="' . route('admin_customeremail.edit', ['id' => $row['id']]) . '"><span title="' . trans('banner.widget.edit') . '" type="button" class="btn btn-flat btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;
                    <span onclick="deleteItem(' . $row['id'] . ');"  title="' . trans('admin.delete') . '" class="btn btn-flat btn-danger"><i class="fa fa-trash"></i></span>&nbsp;
                    <a href="' . route('admin_customeremail.maillist', ['id' => $row['id']]) . '"><span title="Send Mail" type="button" class="btn btn-flat btn-primary"><i class="fa fa-envelope"></i></span></a>'
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
        $data['menu_right'] = '
                        <div class="btn-group pull-right" style="margin-right: 10px">
                           <a href="' . route('admin_customeremail.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
                           <i class="fa fa-plus"></i><span class="hidden-xs">' . trans('admin.add_new') . '</span>
                           </a>
                        </div>
                        ';
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
      var url = '" . route('admin_customeremail.index') . "?sort_order='+$('#order_sort option:selected').val();
      $.pjax({url: url, container: '#pjax-container'})
    });";
//=menu_sort
//menu_search
        $data['menu_search'] = '
                <form action="' . route('admin_customeremail.index') . '" id="button_search">
                   <div onclick="$(this).submit();" class="btn-group pull-right">
                           <a class="btn btn-flat btn-primary" title="Refresh">
                              <i class="fa  fa-search"></i><span class="hidden-xs"> ' . trans('admin.search') . '</span>
                           </a>
                   </div>
                   <div class="btn-group pull-right">
                         <div class="form-group">
                           <input type="text" name="keyword" class="form-control" placeholder="Search name" value="' . $keyword . '">
                         </div>
                   </div>
                </form>';
//=menu_search
        $data['url_delete_item'] = route('admin_customeremail.delete');
        return view('admin.screen.list')
            ->with($data);
    }
/**
 * Form create new order in admin
 * @return [type] [description]
 */
    public function create()
    {
        $data = [
            'title' => 'Create mail template',
            'sub_title' => 'Add new template',
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'languages' => $this->languages,
            'template' => [],
            'url_action' => route('admin_customeremail.create'),
        ];
        return view('admin.screen.mailtemplate')
            ->with($data);
    }
/**
 * Post create new order in admin
 * @return [type] [description]
 */
    public function postCreate()
    {
        $data = request()->all();
        $langFirst = array_key_first(sc_language_all()->toArray()); //get first code language active
        
        $validator = Validator::make($data, [      
            'name' => 'required|string|max:100',
            'description' => 'required',
        ], [
            'name.required' => trans('validation.required', ['attribute' => trans('banner.widget.name')])            
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        $dataInsert = [            
            'name' => $data['name'],
            'description' => $data['description'],
            'created_at' => date('Y-m-d H:i:s'),

        ];
        $id = MailTemplate::insertGetId($dataInsert);
       
        return redirect()->route('admin_customeremail.index')->with('success', 'New Template created successfully!');
    }
/**
 * Form edit
 */
    public function edit($id)
    {
        $template = MailTemplate::find($id);
        if ($template === null) {
            return 'no data';
        }
        $data = [
            'title' => 'Edit Template',
            'sub_title' => '',
            'title_description' => '',
            'icon' => 'fa fa-pencil-square-o',
            'languages' => $this->languages,
            'template' => $template,
            'url_action' => route('admin_customeremail.edit', ['id' => $template['id']]),
        ];
        return view('admin.screen.mailtemplate')
            ->with($data);
    }
/**
 * update status
 */
    public function postEdit($id)
    {
        $template = MailTemplate::find($id);
        $data = request()->all();
        $langFirst = array_key_first(sc_language_all()->toArray()); //get first code language active
        
        $validator = Validator::make($data, [           
            'name' => 'required|string|max:100',
            'description' => 'required',
        ], [
            'name.required' => trans('validation.required', ['attribute' => trans('banner.widget.name')])
           
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
//Edit
        $dataUpdate = [           
            'name' => $data['name'],
            'description' => $data['description'], 
            'updated_at' => date('Y-m-d H:i:s')          
        ];
        $template->update($dataUpdate);
        return redirect()->route('admin_customeremail.index')->with('success', 'Template updated successfully!');
    }
/*
Delete list Item
Need mothod destroy to boot deleting in model
 */
    public function deleteList()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => 'Method not allow!']);
        } else {
            $ids = request('ids');
            $arrID = explode(',', $ids);
            MailTemplate::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    public function maillist($id)
    {
        $template = MailTemplate::find($id);
        if ($template === null) {
            return 'no data';
        }
        $obj = new ShopSubscribe; 
        $users = $obj->where('status', 1)->get();
         $data = [
            'title' => 'Send mail',
            'sub_title' => '',
            'title_description' => '',
            'icon' => 'fa fa-pencil-square-o',
            'languages' => $this->languages,
            'template' => $template,
            'users' => $users,
            'url_action' => route('admin_customeremail.sendmail', ['id' => $template['id']]),
        ];
        return view('admin.screen.maillist')
            ->with($data);
    }

     public function sendmail($id)
    {
        $template = MailTemplate::find($id);
        $message['content'] = $template->description;
        $data = request()->all();

        $true=0;
         if(!empty($data['emails']))
         {
            $input['subject'] = $data['name'];
            foreach($data['emails'] as $value)
            {
                 $input['email'] = $value;
                Mail::send('mail.custom', $message, function($message) use($input){
                $message->to($input['email'])
                    ->subject($input['subject']);
            });
            }
             $true=1;
         }
          
        if(!empty($data['custom_emails']))
         {
            $input['subject'] = $data['name'];

            $email =  str_replace(array( '[', ']', '"' ), '', $data['custom_emails']);
            $emails = explode(',', $email);
            foreach($emails as $value)
            {
                 $input['email'] = $value;
                Mail::send('mail.custom', $message, function($message) use($input){
                $message->to($input['email'])
                    ->subject($input['subject']);
            });
            }
            $true=1;
         }
         
         if($true==1)
         {
            return redirect()->route('admin_customeremail.index')->with('success', 'Mail sent successfully!'); 
         }
         else
         {
            return redirect()->route('admin_customeremail.maillist', ['id' => $id])->with('error', 'Please enter email to sendmail!');
         }        
    }
}