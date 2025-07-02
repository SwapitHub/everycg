<?php
#app/Http/Admin/Controllers/ShopCustomerController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ShopCountry;
use App\Models\ShopLanguage;
use App\Models\ShopUser;
use App\Models\MailerContact;
use Illuminate\Http\Request;
use Validator;

class ContactListController extends Controller
{
    public $languages, $countries;

    public function __construct()
    {
        $this->languages = ShopLanguage::getList();
        $this->countries = ShopCountry::getList();

    }

    public function index()
    {
        $data = [
            'title' => 'Contact list ',
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
            'id' => trans('customer.id'),
            'name' =>'List Name',
			'email' => trans('customer.email'),
            'created_at' => trans('customer.created_at'),
            'updated_at' => trans('customer.updated_at'),
            'action' => trans('customer.admin.action'),
        ];
        $sort_order = request('sort_order') ?? 'id_desc';
        $keyword = request('keyword') ?? '';
        $arrSort = [
            'id__desc' => trans('customer.admin.sort_order.id_desc'),
            'id__asc' => trans('customer.admin.sort_order.id_asc'),
            'first_name__desc' => trans('customer.admin.sort_order.first_name_desc'),
            'first_name__asc' => trans('customer.admin.sort_order.first_name_asc'),
            'last_name__desc' => trans('customer.admin.sort_order.last_name_desc'),
            'last_name__asc' => trans('customer.admin.sort_order.last_name_asc'),
        ];
        $obj = new MailerContact;

        if ($keyword) {
            $obj = $obj->whereRaw('(id = ' . (int) $keyword . ' OR email like "%' . $keyword . '%" OR state like "%' . $keyword . '%" OR company like "%' . $keyword . '%"  )');
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
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
                'action' => '
                    <a href="' . route('mailer_contact.edit', ['id' => $row['id']]) . '"><span title="' . trans('customer.admin.edit') . '" type="button" class="btn btn-flat btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

                    <span onclick="deleteItem(' . $row['id'] . ');"  title="' . trans('admin.delete') . '" class="btn btn-flat btn-danger"><i class="fa fa-trash"></i></span>'
                ,
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links('admin.component.pagination');
        $data['result_items'] = trans('customer.admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'item_total' => $dataTmp->total()]);
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
                           <a href="' . route('mailer_contact.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
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
      var url = '" . route('mailer_contact.index') . "?sort_order='+$('#order_sort option:selected').val();
      $.pjax({url: url, container: '#pjax-container'})
    });";

//=menu_sort

//menu_search

        $data['menu_search'] = '
                <form action="' . route('mailer_contact.index') . '" id="button_search">
                   <div onclick="$(this).submit();" class="btn-group pull-right">
                           <a class="btn btn-flat btn-primary" title="Refresh">
                              <i class="fa  fa-search"></i><span class="hidden-xs"> ' . trans('admin.search') . '</span>
                           </a>
                   </div>
                   <div class="btn-group pull-right">
                         <div class="form-group">
                           <input type="text" name="keyword" class="form-control" placeholder="' . trans('customer.admin.search_place') . '" value="' . $keyword . '">
                         </div>
                   </div>
                </form>';
//=menu_search

        $data['url_delete_item'] = route('mailer_contact.delete');

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
            'title' => "Add Mailer Contact",
            'sub_title' => '',
            'title_description' => trans('customer.admin.add_new_des'),
            'icon' => 'fa fa-plus',
            'countries' => (new ShopCountry)->getList(),
            'customer' => [],
            'url_action' => route('mailer_contact.create'),

        ];

        return view('admin.screen.mailer_contact')
            ->with($data);
    }

/**
 * Post create new order in admin
 * @return [type] [description]
 */
    public function postCreate()
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'email' => 'required|email|unique:shop_user,email',
            'name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dataInsert = [
            'email' => $data['email'],
            'Company' => $data['company'],
            'name' => $data['name'],
            'state' => $data['state'],
            'status' => empty($data['status']) ? 0 : 1,
        ];

        MailerContact::create($dataInsert);

        return redirect()->route('mailer_contact.index')->with('success', trans('customer.admin.create_success'));

    }

/**
 * Form edit
 */
    public function edit($id)
    {
        $customer = MailerContact::find($id);
        if ($customer === null) {
            return 'no data';
        }
        $data = [
            'title' => 'Edit Mailer Contact',
            'sub_title' => '',
            'title_description' => '',
            'icon' => 'fa fa-pencil-square-o',
            'customer' => $customer,
            'countries' => (new ShopCountry)->getList(),
            'url_action' => route('mailer_contact.edit', ['id' => $customer['id']]),
        ];
        return view('admin.screen.mailer_contact')
            ->with($data);
    }

/**
 * update status
 */
    public function postEdit($id)
    {
        $customer = ShopUser::find($id);
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'email' => 'required',
            'name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
//Edit

        $dataUpdate = [
            'email' => $data['email'],
            'name' => $data['name'],
            'Company' => $data['company'],
            'state' => $data['state'],
            'status' => empty($data['status']) ? 0 : 1,
        ];
        MailerContact::where('id', $id)->update($dataUpdate);
//
        return redirect()->route('mailer_contact.index')->with('success', trans('customer.admin.edit_success'));

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
            MailerContact::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

}
