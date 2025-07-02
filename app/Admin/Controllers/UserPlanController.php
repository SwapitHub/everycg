<?php
#app/Http/Admin/Controllers/ShopCategoryController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use App\Models\Plans;
use App\Models\ShopLanguage;
use Illuminate\Http\Request;
use Validator;
use Stripe;

class UserPlanController extends Controller
{
    public $languages;

    public function __construct()
    {
        $this->languages = ShopLanguage::getList();

    }

    public function index()
    {       
        $data = [
            'title' => 'User Plan list',
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
            'plan' => 'plan',
            'email' => 'email',
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
        $obj = new UserSubscription;

        
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
       $plan =  Plans::where('plan_id',$row['stripe_plan_id'])->first();        
            $dataTr[] = [
                'check_row' => '<input type="checkbox" class="grid-row-checkbox" data-id="' . $row['id'] . '">',
                'id' => $row['id'],
                'plan' => $plan['name'],
                'email' => $row['payer_email'],                 
                'interval' => $row['plan_interval'],                 
                'price' => $row['plan_amount'],                 
                 'status' => ($row['status']=='active') ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>',
                'action' => '
                    <a href="' . route('admin_userplan.edit', ['id' => $row['id']]) . '"><span title="' . trans('edit') . '" type="button" class="btn btn-flat btn-primary"><i class="fa fa-eye"></i></span></a>&nbsp;
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
        /*$data['menu_right'] = '
                        <div class="btn-group pull-right" style="margin-right: 10px">
                           <a href="' . route('admin_userplan.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
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
      var url = '" . route('admin_userplan.index') . "?sort_order='+$('#order_sort option:selected').val();
      $.pjax({url: url, container: '#pjax-container'})
    });";

//=menu_sort

//menu_search

        $data['menu_search'] = '
                <form action="' . route('admin_userplan.index') . '" id="button_search">
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

        $data['url_delete_item'] = route('admin_userplan.delete');

        return view('admin.screen.list')
            ->with($data);
    }

/**
 * Form create new order in admin
 * @return [type] [description]
 */
    public function create()
    {
       
       
    }

/**
 * Post create new order in admin
 * @return [type] [description]
 */
    public function postCreate()
    {      

       

    }

/**
 * Form edit
 */
    public function edit($id)
    {
       
        $plan = UserSubscription::find($id);
        if ($plan === null) {
            return 'no data';
        }
         $plandetail =  Plans::where('plan_id',$plan['stripe_plan_id'])->first();
        $data = [
            'title' => 'View plan',
            'sub_title' => '',
            'title_description' => '',
            'icon' => 'fa fa-pencil-square-o',            
            'plan' => $plan,
            'planname' => $plandetail->name,
            'url_action' => route('admin_userplan.edit', ['id' => $plan['id']]),
        ];
        return view('admin.screen.userplan')
            ->with($data);
    }

/**
 * update status
 */
    public function postEdit($id)
    {
        

    }

/*
Delete list Item
Need mothod destroy to boot deleting in model
 */
    public function deleteList()
    {
        
    }

}
