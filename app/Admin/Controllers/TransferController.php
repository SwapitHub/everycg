<?php
#app/Http/Admin/Controllers/ShopCategoryController.php
namespace App\Admin\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Transfers;
use App\Models\ShopLanguage;
use Illuminate\Http\Request;
use Validator;
class TransferController extends Controller
{
    public $languages;
    public function __construct()
    {
        $this->languages = ShopLanguage::getList();
    }
    public function index()
    {
       $data = [
            'title' => 'Transfer list',
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
            'tranferid' => 'tranferid',       
            'balance_transaction' => 'balance_transaction',
            'created_at' => 'created_at',
        ];
        $sort_order = request('sort_order') ?? 'id_desc';
        $keyword = request('keyword') ?? '';
        $arrSort = [
            'id__desc' => 'id desc',
            'id__asc' => 'id asc'
        ];
        $obj = new Transfers;        
        if ($keyword) {
            $obj = $obj->whereRaw('(id = ' . (int) $keyword . ' OR tranferid like "%' . $keyword . '%" )');
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
                'tranferid' => $row['tranferid'],                  
                'balance_transaction' => $row['balance_transaction'],                  
                'created_at' => $row['created_at'],                
            ];
        }
        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links('admin.component.pagination');
        $data['result_items'] = trans('order.admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'item_total' => $dataTmp->total()]);
//menu_left
        $data['menu_left'] = '<div class="pull-left">
                                     
                    <a class="btn   btn-flat btn-primary grid-refresh" title="Refresh"><i class="fa fa-refresh"></i><span class="hidden-xs"> ' . trans('admin.refresh') . '</span></a> &nbsp;</div>
                    ';
//=menu_left


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
      var url = '" . route('admin_widget.index') . "?sort_order='+$('#order_sort option:selected').val();
      $.pjax({url: url, container: '#pjax-container'})
    });";
//=menu_sort
//menu_search
        $data['menu_search'] = '
                <form action="' . route('admin_widget.index') . '" id="button_search">
                   <div onclick="$(this).submit();" class="btn-group pull-right">
                           <a class="btn btn-flat btn-primary" title="Refresh">
                              <i class="fa  fa-search"></i><span class="hidden-xs"> ' . trans('admin.search') . '</span>
                           </a>
                   </div>
                   <div class="btn-group pull-right">
                         <div class="form-group">
                           <input type="text" name="keyword" class="form-control" placeholder="Search id, tranferid" value="' . $keyword . '">
                         </div>
                   </div>
                </form>';
//=menu_search
        $data['url_delete_item'] = route('admin_widget.delete');
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