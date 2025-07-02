<?php
#app/Http/Admin/Controllers/ShopCategoryController.php
namespace App\Admin\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\ShopLanguage;
use Illuminate\Http\Request;
use Validator;
class FaqController extends Controller
{
    public $languages;
    public function __construct()
    {
        $this->languages = ShopLanguage::getList();
    }
    public function index()
    {
       $data = [
            'title' => 'FAQ list',
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
            'alias' => 'alias',
            'status' => 'status',
            'sort' => 'sort',
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
        $obj = new Faq;        
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
                'alias' => $row['alias'],                  
                'status' => $row['status'] ? '<span class="label label-success">ON</span>' : '<span class="label label-danger">OFF</span>',
                'sort' => $row['sort'],
                'action' => '
                    <a href="' . route('admin_faq.edit', ['id' => $row['id']]) . '"><span title="Edit" type="button" class="btn btn-flat btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;
                    <span onclick="deleteItem(' . $row['id'] . ');"  title="' . trans('admin.delete') . '" class="btn btn-flat btn-danger"><i class="fa fa-trash"></i></span>'
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
                           <a href="' . route('admin_faq.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
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
      var url = '" . route('admin_faq.index') . "?sort_order='+$('#order_sort option:selected').val();
      $.pjax({url: url, container: '#pjax-container'})
    });";
//=menu_sort
//menu_search
        $data['menu_search'] = '
                <form action="' . route('admin_faq.index') . '" id="button_search">
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
        $data['url_delete_item'] = route('admin_faq.delete');
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
            'title' => 'Create FAQ',
            'sub_title' => 'Add new FAQ',
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'languages' => $this->languages,
            'faq' => [],
            'url_action' => route('admin_faq.create'),
        ];
        return view('admin.screen.faq')
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
            'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:faq,alias|string|max:100',
            'name' => 'required|string|max:100',
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
        $id = Faq::insertGetId($dataInsert);
       
        return redirect()->route('admin_faq.index')->with('success', 'FAQ created successfully!');
    }
/**
 * Form edit
 */
    public function edit($id)
    {
        $faq = Faq::find($id);
        if ($faq === null) {
            return 'no data';
        }
        $data = [
            'title' => 'Edit FAQ',
            'sub_title' => '',
            'title_description' => '',
            'icon' => 'fa fa-pencil-square-o',
            'languages' => $this->languages,
            'faq' => $faq,
            'url_action' => route('admin_faq.edit', ['id' => $faq['id']]),
        ];
        return view('admin.screen.faq')
            ->with($data);
    }
/**
 * update status
 */
    public function postEdit($id)
    {
        $faq = Faq::find($id);
        $data = request()->all();
        $langFirst = array_key_first(sc_language_all()->toArray()); //get first code language active
        $data['alias'] = !empty($data['alias'])?$data['alias']:$data['name'];
        $data['alias'] = sc_word_format_url($data['alias']);
        $data['alias'] = sc_word_limit($data['alias'], 100);
        $validator = Validator::make($data, [
            'sort' => 'numeric|min:0',
            'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:faq,alias,' . $faq->id . ',id|string|max:100',
            'name' => 'required|string|max:100',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
//Edit
        $dataUpdate = [
            'alias' => $data['alias'],
            'name' => $data['name'],
            'description' => $data['description'],
            'sort' => $data['sort'],
            'status' => empty($data['status']) ? 0 : 1,
        ];
        $faq->update($dataUpdate);
        return redirect()->route('admin_faq.index')->with('success', 'FAQ updated successfully!');
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
            Faq::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }
}