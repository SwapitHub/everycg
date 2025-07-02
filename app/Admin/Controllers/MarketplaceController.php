<?php
#app/Http/Admin/Controllers/ShopCategoryController.php
namespace App\Admin\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Marketplace;
use App\Models\ShopLanguage;
use Illuminate\Http\Request;
use Validator;

class MarketplaceController extends Controller
{
    public $languages;
    public function __construct()
    {
        $this->languages = ShopLanguage::getList();
    }
    public function index()
    {
       $data = [
            'title' => 'Marketlace fees',
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
            'fees' => 'fees', 
            'action' => 'action',
        ];
        $sort_order = request('sort_order') ?? 'id_desc';
        $keyword = request('keyword') ?? '';
        $arrSort = [
            'id__desc' => 'id desc',
            'id__asc' => 'id asc'
        ];
        $obj = new Marketplace;        
        if ($keyword) {
            $obj = $obj->whereRaw('(id = ' . (int) $keyword . ' OR fees like "%' . $keyword . '%" )');
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
                'name' => $row['market_name'],                
                'fees' => $row['fees'] .' %',               
                'action' => '
                    <a href="' . route('admin_marketplace.edit', ['id' => $row['id']]) . '"><span title="' . trans('banner.widget.edit') . '" type="button" class="btn btn-flat btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;'
                ,
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
      var url = '" . route('admin_marketplace.index') . "?sort_order='+$('#order_sort option:selected').val();
      $.pjax({url: url, container: '#pjax-container'})
    });";
//=menu_sort
//menu_search
        $data['menu_search'] = '
                <form action="' . route('admin_marketplace.index') . '" id="button_search">
                   <div onclick="$(this).submit();" class="btn-group pull-right">
                           <a class="btn btn-flat btn-primary" title="Refresh">
                              <i class="fa  fa-search"></i><span class="hidden-xs"> ' . trans('admin.search') . '</span>
                           </a>
                   </div>
                   <div class="btn-group pull-right">
                         <div class="form-group">
                           <input type="text" name="keyword" class="form-control" placeholder="Search fees" value="' . $keyword . '">
                         </div>
                   </div>
                </form>';
//=menu_search
        $data['url_delete_item'] = route('admin_marketplace.delete');
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
            'title' => 'Create widget',
            'sub_title' => 'Add new widget',
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'languages' => $this->languages,
            'widget' => [],
            'url_action' => route('admin_marketplace.create'),
        ];
        return view('admin.screen.widget')
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
        $data['alias'] = !empty($data['alias'])?$data['alias']:$data['name'];
        $data['alias'] = sc_word_format_url($data['alias']);
        $data['alias'] = sc_word_limit($data['alias'], 100);
        $validator = Validator::make($data, [         
            'sort' => 'numeric|min:0',
            'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:widget,alias|string|max:100',
            'name' => 'required|string|max:100',
        ], [
            'name.required' => trans('validation.required', ['attribute' => trans('banner.widget.name')]),
            'alias.regex' => trans('banner.widget.alias_validate'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        $dataInsert = [
            'alias' => $data['alias'],
            'name' => $data['name'],
            'description' => $data['description'],
            'status' => !empty($data['status']) ? 1 : 0,
            'sort' => (int) $data['sort'],            
        ];
        $id = Widget::insertGetId($dataInsert);
       
        return redirect()->route('admin_marketplace.index')->with('success', 'Widget created successfully!');
    }
/**
 * Form edit
 */
    public function edit($id)
    {
        $marketplace = Marketplace::find($id);
        if ($marketplace === null) {
            return 'no data';
        }
        $data = [
            'title' => 'Edit Fees',
            'sub_title' => '',
            'title_description' => '',
            'icon' => 'fa fa-pencil-square-o',
            'languages' => $this->languages,
            'marketplace' => $marketplace,
            'url_action' => route('admin_marketplace.edit', ['id' => $marketplace['id']]),
        ];
        return view('admin.screen.marketplace')
            ->with($data);
    }
/**
 * update status
 */
    public function postEdit($id)
    {
        $marketplace = Marketplace::find($id);
        $data = request()->all();
        $langFirst = array_key_first(sc_language_all()->toArray()); //get first code language active        
        $validator = Validator::make($data, [
            'fees' => 'numeric',            
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
//Edit
        $dataUpdate = [
            'fees' => $data['fees'],
        ];
        $marketplace->update($dataUpdate);
        return redirect()->route('admin_marketplace.index')->with('success', 'Fees updated successfully!');
    }
/*
Delete list Item
Need mothod destroy to boot deleting in model
 */
    public function deleteList()
    {
        /*if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => 'Method not allow!']);
        } else {
            $ids = request('ids');
            $arrID = explode(',', $ids);
            Widget::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }*/
    }
}