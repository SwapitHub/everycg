<?php
#app/Http/Admin/Controllers/AdminLogController.php
namespace App\Admin\Controllers;

use App\Admin\Models\AdminLog;
use App\Admin\Models\AdminUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Countdownloads;
use App\Models\ProductFiles;
use App\Models\ShopProduct;

class DownloadController extends Controller
{

    public function index()
    {

        $data = [
            'title' => 'Downloads',
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
            'id' => trans('log.id'), 
            'product' => 'product', 
            'file' => 'file',
            'email' => 'email',           
            'created_at' => trans('log.created_at'),
        ];

        $sort_order = request('sort_order') ?? 'id_desc';
        $keyword = request('keyword') ?? '';
        $arrSort = [
            'id__desc' => 'ID DESC',
            'id__asc' => 'ID ASC'           

        ];
        $obj = new Countdownloads;

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
            $file = ProductFiles::find($row['file_id']);
            $product = ShopProduct::find($file->pro_id);
            $fname = substr($file->name, strrpos($file->name, '/') + 1);
            $fname = substr($fname, 13);
            $dataTr[] = [
                'check_row' => '<input type="checkbox" class="grid-row-checkbox" data-id="' . $row['id'] . '">',
                'id' => $row['id'],      
                'product' => $product->name,      
                'file' => $fname,      
                'email' => $row['email'],     
                'created_at' => $row['created_at'],
                
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links('admin.component.pagination');
        $data['result_items'] = trans('log.admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'item_total' => $dataTmp->total()]);

//menu_left
        $data['menu_left'] = '<div class="pull-left">

                  
                      <a class="btn   btn-flat btn-primary grid-refresh" title="Refresh"><i class="fa fa-refresh"></i><span class="hidden-xs"> ' . trans('log.admin.refresh') . '</span></a> &nbsp;
                      </div>';
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
      var url = '" . route('admin_downloadcnt.index') . "?sort_order='+$('#order_sort option:selected').val();
      $.pjax({url: url, container: '#pjax-container'})
    });";

//=menu_sort

        $data['url_delete_item'] = route('admin_downloadcnt.delete');

        return view('admin.screen.list')
            ->with($data);
    }

/*
Delete list item
Need mothod destroy to boot deleting in model
 */
    

}
