<?php
#app/Http/Admin/Controllers/ShopCategoryController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plans;
use App\Models\ShopLanguage;
use Illuminate\Http\Request;
use Validator;
use Stripe;

class PlanController extends Controller
{
    public $languages;

    public function __construct()
    {
        $this->languages = ShopLanguage::getList();

    }

    public function index()
    {       
        $data = [
            'title' => 'Plans list',
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
            'interval' => 'interval',
            'price' => 'price',
            'status' =>'status',
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
        $obj = new Plans;

        
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
                'interval' => $row['interval'],                 
                'price' => $row['price'],                 
                 'status' => $row['status'] ? '<span class="label label-success">ON</span>' : '<span class="label label-danger">OFF</span>',
                'action' => '
                    <a href="' . route('admin_plan.edit', ['id' => $row['id']]) . '"><span title="' . trans('edit') . '" type="button" class="btn btn-flat btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;
                   '
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
                           <a href="' . route('admin_plan.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
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
      var url = '" . route('admin_plan.index') . "?sort_order='+$('#order_sort option:selected').val();
      $.pjax({url: url, container: '#pjax-container'})
    });";

//=menu_sort

//menu_search

        $data['menu_search'] = '
                <form action="' . route('admin_plan.index') . '" id="button_search">
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

        $data['url_delete_item'] = route('admin_plan.delete');

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
            'title' => 'Add new plan',
            'sub_title' => '',
            'title_description' => 'Create plan',
            'icon' => 'fa fa-plus',
            'plan' => [],
            'languages' => $this->languages,
            'url_action' => route('admin_plan.create'),
           

        ];

        return view('admin.screen.plan')
            ->with($data);
    }

/**
 * Post create new order in admin
 * @return [type] [description]
 */
    public function postCreate()
    {
        $data = request()->all();       
        
        $validator = Validator::make($data, [
            'name' => 'required',
            'price' => 'required|numeric|min:0',                    
            'model_limit' => 'required|numeric|min:0',                   
            'interval' => 'required',                    
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $plan = Stripe\Plan::create(array( 
            "product" => [ 
                "name" => $data['name']
            ], 
            "amount" => $data['price']*100, 
            "currency" => 'usd', 
            "interval" => $data['interval'], 
            "interval_count" => 1 
        ));
        if($plan->id)
        {
            $dataInsert = [ 
            'name' => $data['name'],       
            'price' => $data['price'],   
            'interval' => $data['interval'],   
            'plan_id' => $plan->id,   
            'model_limit' => $data['model_limit'],   
            'status' => !empty($data['status']) ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ];
            Plans::create($dataInsert);
            return redirect()->route('admin_plan.index')->with('success', 'New Plan created successfully!');
        }
        else
        {
            return redirect()->route('admin_plan.index')->with('error', 'Something went wrong!');
        }

        
        

       

    }

/**
 * Form edit
 */
    public function edit($id)
    {
       
        $plan = Plans::find($id);
        if ($plan === null) {
            return 'no data';
        }
        $data = [
            'title' => 'Edit Plan',
            'sub_title' => '',
            'title_description' => '',
            'icon' => 'fa fa-pencil-square-o',            
            'plan' => $plan,
            'url_action' => route('admin_plan.edit', ['id' => $plan['id']]),
        ];
        return view('admin.screen.plan')
            ->with($data);
    }

/**
 * update status
 */
    public function postEdit($id)
    {
        $plan = Plans::find($id);
        $data = request()->all(); 

        $validator = Validator::make($data, [
            'model_limit' => 'required|numeric|min:0',                   
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        } 
      
        $dataUpdate = [                    
            'status' => !empty($data['status']) ? 1 : 0, 
            'model_limit' => $data['model_limit'],           
        ];

        $plan->update($dataUpdate);   

//
        return redirect()->route('admin_plan.index')->with('success', trans('Plan updated successfully!'));

    }

/*
Delete list Item
Need mothod destroy to boot deleting in model
 */
    public function deleteList()
    {
       
    }

}
