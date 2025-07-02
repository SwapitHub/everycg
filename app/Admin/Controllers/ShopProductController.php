<?php
	#app/Http/Admin/Controllers/ShopProductController.php
	
	namespace App\Admin\Controllers;
	use Illuminate\Support\Facades\DB;
	use App\Http\Controllers\Controller;
	use App\Models\ShopAttributeGroup;
	use App\Models\ShopBrand;
	use App\Models\ShopCategory;
	use App\Models\ShopLanguage;
	use App\Models\ShopProduct;
	use App\Models\ShopProductAttribute;
	use App\Models\ShopProductBuild;
	use App\Models\ShopProductDescription;
	use App\Models\ShopProductGroup;
	use App\Models\ShopProductImage;
	use App\Models\ShopProductCategory;
	use App\Models\ShopProductPromotion;
	use App\Models\ShopVendor;
	use App\Models\Tags;
	use App\Models\ProductFiles; 
	use App\Models\License;
	use Illuminate\Support\Str;
	use Illuminate\Http\Request;
	use Validator;
	use App\Admin\Admin;
	use Stripe; 
	use Carbon\Carbon;
	
	class ShopProductController extends Controller
	{
		public $languages, $types, $kinds, $virtuals, $attributeGroup;
		
		public function __construct()
		{
			$this->languages = ShopLanguage::getList();
			$this->attributeGroup = ShopAttributeGroup::getList();
			$this->types = [
            SC_PRODUCT_NORMAL => trans('product.types.normal'),
            SC_PRODUCT_NEW => trans('product.types.new'),
            SC_PRODUCT_HOT => trans('product.types.hot'),
			];
			$this->kinds = [
            SC_PRODUCT_SINGLE => trans('product.kinds.single'),
            SC_PRODUCT_BUILD => trans('product.kinds.build'),
            SC_PRODUCT_GROUP => trans('product.kinds.group'),
			];
			$this->virtuals = [
            SC_VIRTUAL_PHYSICAL => trans('product.virtuals.physical'),
            SC_VIRTUAL_DOWNLOAD => trans('product.virtuals.download'),
            SC_VIRTUAL_ONLY_VIEW => trans('product.virtuals.only_view'),
            SC_VIRTUAL_SERVICE => trans('product.virtuals.service'),
			];
			
		}
		
		public function index()
		{
			$user = Admin::user()->isAdministrator();
			$userid = Admin::user();
			
			
			$data = [
            'title' => trans('product.admin.list'),
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
            'id' => trans('product.id'),
            'image' => trans('product.image'),            
            'name' => trans('product.name'),
            'date_added' => 'Date added',
            'date_modified' => 'Date Modified',
			];
			if(sc_config('product_price')){
				$listTh['price'] = trans('product.price');
			}
			$listTh['views'] = 'views';
			if(sc_config('product_cost')){
				$listTh['cost'] = trans('product.cost');
			}
			
			if(sc_config('product_type')){
				$listTh['type'] = trans('product.type');
			}
			if(sc_config('product_kind')){
				$listTh['kind'] = trans('product.kind');
			}
			if(sc_config('product_virtual')){
				$listTh['virtual'] = trans('product.virtual');
			}
			$listTh['status'] = trans('product.status');
			$listTh['action'] = trans('product.admin.action');
			
			
			$sort_order = request('sort_order') ?? 'id_desc';
			$keyword = request('keyword') ?? '';
			$category_id = sc_clean(request('category_id') ?? '');       
			
			
			$arrSort = [
            'id__desc' => trans('product.admin.sort_order.id_desc'),
            'id__asc' => trans('product.admin.sort_order.id_asc'),
            'name__desc' => trans('product.admin.sort_order.name_desc'),
            'name__asc' => trans('product.admin.sort_order.name_asc'),  
            'price__asc' => 'Price asc',  
            'price__desc' => 'Price desc',  
			];

			$obj = new ShopProduct;

			if(!$user){
				$obj = $obj->where('adminid',$userid->id);
			}
			
			/* $obj = $obj
            ->leftJoin('shop_product_description', 'shop_product_description.product_id', 'shop_product.id')
            ->where('shop_product_description.lang', sc_get_locale()); */
			

			if ($category_id) {
				$obj = $obj->leftJoin('shop_product_category', 'shop_product_category.product_id', 'shop_product.id');
				$arr = array($category_id);
				$obj = $obj->whereIn('shop_product_category.category_id', $arr);
				
			}
			if ($keyword) {
				$obj = $obj->whereRaw('(id = ' . (int) $keyword . ' OR shop_product_description.name like "%' . $keyword . '%"  OR shop_product.sku like "%' . $keyword . '%")');
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
				$kind = $this->kinds[$row['kind']] ?? $row['kind'];
				if ($row['kind'] == SC_PRODUCT_BUILD) {
					$kind = '<span class="label label-success">' . $kind . '</span>';
					} elseif ($row['kind'] == SC_PRODUCT_GROUP) {
					$kind = '<span class="label label-danger">' . $kind . '</span>';
				}
				$type = $this->types[$row['type']] ?? $row['type'];
				if ($row['type'] == SC_PRODUCT_NEW) {
					$type = '<span class="label label-success">' . $type . '</span>';
					} elseif ($row['type'] == SC_PRODUCT_HOT) {
					$type = '<span class="label label-danger">' . $type . '</span>';
				}
				$dataMap = [
                'check_row' => '<input type="checkbox" class="grid-row-checkbox" data-id="' . $row['id'] . '">',
                'id' => $row['id'],
                'image' => sc_image_render($row->getThumb(), '50px', '50px'),                
                'name' => $row['name'],
                'date_added' => $row['created_at'],
                'date_modified' => $row['updated_at'],                
                
				];
				if(sc_config('product_price')){
					$dataMap['price'] = $row['price'];
				}
				$dataMap['views'] = $row['view'];
				if(sc_config('product_cost')){
					$dataMap['cost'] = $row['cost'];
				}
				
				if(sc_config('product_type')){
					$dataMap['type'] = $type;
				}
				if(sc_config('product_kind')){
					$dataMap['kind'] = $kind;
				}
				if(sc_config('product_virtual')){
					$dataMap['virtual'] = $this->virtuals[$row['virtual']] ?? $row['virtual'];
				}
				$dataMap['status'] = $row['status'] ? '<span class="label label-success">Published</span>' : '<span class="label label-danger">Draft</span>';            
				$dataMap['action'] = '
				<a href="' . route('admin_product.edit', ['id' => $row['id']]) . '">
				<span title="' . trans('product.admin.edit') . '" type="button" class="btn btn-flat btn-primary">
				<i class="fa fa-edit"></i>
				</span>
				</a>&nbsp;
				<a href="' . route('admin_product.duplicate', ['id' => $row['id']]) . '">
				<span title="Duplicate" type="button" class="btn btn-flat btn-primary">
				<i class="fa fa-clone"></i>
				</span>
				</a>&nbsp;
				
				<span onclick="deleteItem(' . $row['id'] . ');"  title="' . trans('admin.delete') . '" class="btn btn-flat btn-danger">
				<i class="fa fa-trash"></i>
				</span>';
				$dataTr[] = $dataMap;
			}
			
			$data['listTh'] = $listTh;
			$data['dataTr'] = $dataTr;
			$data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links('admin.component.pagination');
			$data['result_items'] = trans('product.admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'item_total' => $dataTmp->total()]);
			
			$optionCategory = '';
			$categories = (new ShopCategory)->getTreeCategories();
			if ($categories) {
				foreach ($categories as $k => $v) {
					$optionCategory .= "<option value='{$k}' ".(($category_id == $k) ? 'selected' : '').">{$v}</option>";
				}
			}
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
			<a href="' . route('admin_product.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
			<i class="fa fa-plus"></i><span class="hidden-xs">' . trans('admin.add_new') . '</span>
			</a>
			</div>
			';
			if(sc_config('ImportProduct')) {
				$data['menu_right'] .= '
				<div class="btn-group pull-right" style="margin-right: 10px">
				<a href="' . route('admin_import_product.index') . '" class="btn  btn-success  btn-flat" title="New">
				<i class="fa fa fa-floppy-o"></i> <span class="hidden-xs">' . trans('admin.add_new_multi') . '</span>
				</a>
				</div>
				';
				
			}
			
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
			var url = '" . route('admin_product.index') . "?sort_order='+$('#order_sort option:selected').val();
			$.pjax({url: url, container: '#pjax-container'})
			});";
			
			//=menu_sort
			
			//menu_search
			
			$data['menu_search'] = '
			<form action="' . route('admin_product.index') . '" id="button_search">
			<div onclick="$(this).submit();" class="btn-group pull-right">
			<a class="btn btn-flat btn-primary" title="Refresh">
			<i class="fa  fa-search"></i><span class="hidden-xs"> ' . trans('admin.search') . '</span>
			</a>
			</div>
			<div class="btn-group pull-right">
			<div class="form-group custom-search-cat" style="display:flex;">
			<select class="form-control rounded-0 select2" name="category_id" id="category_id">
			<option value="">Select category</option>
			'.$optionCategory.'
			</select> &nbsp;
			<input type="text" name="keyword" class="form-control" placeholder="' . trans('product.admin.search_place') . '" value="' . $keyword . '">
			</div>
			</div>
			</form>';
			//=menu_search
			
			$data['url_delete_item'] = route('admin_product.delete');
			/*  if(!$user){
				Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
				if(!$userid->account_id) {
				return view('admin.screen.connected')
                ->with('url','/ecomm/connected_account');
				}
				else
				{
                $account = \Stripe\Account::retrieve($userid->account_id);
                if($account->charges_enabled)
                {
				return view('admin.screen.list')
				->with($data);
                }
                else
                {
				return view('admin.screen.connected')
				->with('url',$userid->express_url);
                }
				}
				
				}
				else
				{
				return view('admin.screen.list')
				->with($data);
			}*/
			return view('admin.screen.list')
			->with($data);
			
			
		}
		
		
		
		
		
		/**
			* Form create new order in admin
			* @return [type] [description]
		*/
		public function create()
		{
			$listProductSingle = (new ShopProduct)->getListSigle();
			$tags = Tags::where('status',1)->get();
			$license = License::where('status',1)->get();
			$related = ShopProduct::where('status',1)->get();
			// html select product group
			$htmlSelectGroup = '<div class="select-product">';
			$htmlSelectGroup .= '<table width="100%"><tr><td width="80%"><select class="form-control productInGroup select2" data-placeholder="' . trans('product.admin.select_product_in_group') . '" style="width: 100%;" name="productInGroup[]" >';
			$htmlSelectGroup .= '';
			foreach ($listProductSingle as $k => $v) {
				$htmlSelectGroup .= '<option value="' . $k . '">' . $v['name'] . '</option>';
			}
			$htmlSelectGroup .= '</select></td><td><span title="Remove" class="btn btn-flat btn-danger removeproductInGroup"><i class="fa fa-times"></i></span></td></tr></table>';
			$htmlSelectGroup .= '</div>';
			//End select product group
			
			// html select product build
			$htmlSelectBuild = '<div class="select-product">';
			$htmlSelectBuild .= '<table width="100%"><tr><td width="70%"><select class="form-control productInGroup select2" data-placeholder="' . trans('product.admin.select_product_in_build') . '" style="width: 100%;" name="productBuild[]" >';
			$htmlSelectBuild .= '';
			foreach ($listProductSingle as $k => $v) {
				$htmlSelectBuild .= '<option value="' . $k . '">' . $v['name'] . '</option>';
			}
			$htmlSelectBuild .= '</select></td><td style="width:100px"><input class="form-control"  type="number" name="productBuildQty[]" value="1" min=1></td><td><span title="Remove" class="btn btn-flat btn-danger removeproductBuild"><i class="fa fa-times"></i></span></td></tr></table>';
			$htmlSelectBuild .= '</div>';
			//end select product build
			
			// html select attribute
			$htmlProductAtrribute = '<tr><td><br><input type="text" name="attribute[attribute_group][]" value="attribute_value" class="form-control input-sm" placeholder="' . trans('product.admin.add_attribute_place') . '" /></td><td><br><span title="Remove" class="btn btn-flat btn-sm btn-danger removeAttribute"><i class="fa fa-times"></i></span></td></tr>';
			//end select attribute
			
			// html add more images
			$htmlMoreImage = '<div class="input-group"><input type="text" id="id_sub_image" name="sub_image[]" value="image_value" class="form-control input-sm sub_image" placeholder=""  /><span class="input-group-btn"><a data-input="id_sub_image" data-preview="preview_sub_image" data-type="product" class="btn btn-sm btn-primary lfm"><i class="fa fa-picture-o"></i> Choose</a></span></div><div id="preview_sub_image" class="img_holder"></div>';
			//end add more images
			
			$data = [
            'title' => trans('product.admin.add_new_title'),
            'sub_title' => '',
            'title_description' => trans('product.admin.add_new_des'),
            'icon' => 'fa fa-plus',
            'languages' => $this->languages,
            'categories' => (new ShopCategory)->getCategoriesTop(),
            'brands' => (new ShopBrand)->getList(),
            'vendors' => (new ShopVendor)->getList(),
            'types' => $this->types,
            'virtuals' => $this->virtuals,
            'kinds' => $this->kinds,
            'attributeGroup' => $this->attributeGroup,
            'htmlSelectGroup' => $htmlSelectGroup,
            'htmlSelectBuild' => $htmlSelectBuild,
            'listProductSingle' => $listProductSingle,
            'htmlProductAtrribute' => $htmlProductAtrribute,
            'htmlMoreImage' => $htmlMoreImage,
            'tags' => $tags ?? [],
            'license' => $license ?? [],
            'related' => $related ?? [],
			];
			
			return view('admin.screen.product_add')
            ->with($data);
		}
		
		/**
			* Post create new order in admin
			* @return [type] [description]
		*/
		
		
		
		// generate unique slug for product basic of title 
		public function createAlias($productName)
		{
			$slug = Str::slug($productName);
			$count = 1;
			$newSlug = $slug;
			while (ShopProduct::where('alias', $newSlug)->exists()) {
				$count++;
				$newSlug = $slug . '-' . $count;
			}
			return $newSlug;
		}
		
		// choose 6 random product form product table 
		public function getRandomProductsByCategoryId($categoryId)
		{
			$productIds = DB::table('shop_product')
			->where('cat_id', $categoryId)
			->inRandomOrder()
			->limit(6)
			->pluck('id')->toArray();
			return $productIds;
			// var_dump($productIds);
		}
		
		public function postCreate()
		{
			$data = request()->all();
			$user = Admin::user();
			$data = request()->all();
			if(!empty($data['category'][0])){
				$response = $this->getRandomProductsByCategoryId($data['category'][0]);
				$data['related'] = $response;
			}
			
			
			// $related = implode(',', $data['related']);
			
			// var_dump($related);
			// exit;
			
			// dd($response);
			
			$name_arr = $data['descriptions'];
			$final = $name_arr['en'];
			$alais = $this->createAlias($final['name']);
			
			$langFirst = array_key_first(sc_language_all()->toArray()); //get first code language active
			
			if(isset($data['draft']) && !empty($data['draft'])){
				
				/*$data['alias'] = !empty($data['alias'])?$data['alias']:$data['descriptions'][$langFirst]['name'];
					$data['alias'] = sc_word_format_url($data['alias']);
					$data['alias'] = sc_word_limit($data['alias'], 100);
					
					$validator = Validator::make($data, [         
					'descriptions.*.name' => 'required|string|max:100',
					'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,alias|string|max:100',            
					], [
					'descriptions.*.name.required' => trans('validation.required', ['attribute' => trans('product.name')]),
					]);
					if ($validator->fails()) {
					return redirect()->back()
					->withErrors($validator)
					->withInput($data);
				}*/
				
				$related = '';
				if(!empty($data['related'])){
					$related = implode(',', $data['related']);
				}
				/* $tags = !empty($data['tags']) ? implode(',', $data['tags']) : '';*/
				$sku = !empty($data['sku']) ?  $data['sku'] : rand();
				
				
				if (isset($data['subcategory'][0])) { $category = $data['subcategory']; }
				else { $category = $data['category']; }
				
				$attribute = $data['attribute'] ?? [];
				$descriptions = $data['descriptions'];
				$productInGroup = $data['productInGroup'] ?? [];
				$productBuild = $data['productBuild'] ?? [];
				$productBuildQty = $data['productBuildQty'] ?? [];
				$subImages = $data['sub_image'] ?? [];
				$dataInsert = [
                'brand_id' => $data['brand_id']??0,
                'adminid' => $user->id ?? 0,
                'vendor_id' => $data['vendor_id']??0,
                'price' => $data['price']??0,
                'sku' => $sku,
                'cost' => $data['cost']??0,
                'stock' => $data['stock']??0,
                'type' => $data['type'] ?? SC_PRODUCT_NORMAL,
                'kind' => $data['kind']??SC_PRODUCT_SINGLE,
                'alias' => $alais,
                'virtual' => $data['virtual'] ?? SC_VIRTUAL_PHYSICAL,
                'date_available' => !empty($data['date_available']) ? $data['date_available'] : null,
                'image' => $data['image']??'',
                'status' =>  0,
                'featured' => (!empty($data['featured']) ? 1 : 0),
                'noprice' => (!empty($data['noprice']) ? 1 : 0),
                'sort' => (int) $data['sort'],
                'license' => $data['license']??'',
                'tags' => $data['tags']??'',
                'related' => $related,
                'cat_id' => $data['category'][0]??'',
				];
				
				//insert product
				$product = ShopProduct::create($dataInsert);
				
				if (isset($data['price_promotion']) && in_array($data['kind'], [SC_PRODUCT_SINGLE, SC_PRODUCT_BUILD])) {
					$arrPromotion['price_promotion'] = $data['price_promotion'];
					$arrPromotion['date_start'] = $data['price_promotion_start'] ? $data['price_promotion_start'] : null;
					$arrPromotion['date_end'] = $data['price_promotion_end'] ? $data['price_promotion_end'] : null;
					$product->promotionPrice()->create($arrPromotion);
				}
				
				if (isset($category[0])) {
					$product->categories()->attach($category);
					
				}
				
				//Insert description
				$dataDes = [];
				$languages = $this->languages;
				foreach ($languages as $code => $value) {
					$dataDes[] = [
					'product_id' => $product->id,
					'lang' => $code,
					'name' => $descriptions[$code]['name'],
					'keyword' => $descriptions[$code]['keyword'],
					'description' => $descriptions[$code]['description'],
					'content' => $descriptions[$code]['content'] ?? '',
					'meta_title'=>$data['meta_title'],
					'meta_keyword'=>$data['meta_keyword'],
					'meta_description'=>$data['meta_description'],
					];
				}
				
				ShopProductDescription::insert($dataDes);
				
				//Insert sub mages
				if ($subImages && in_array($data['kind'], [SC_PRODUCT_SINGLE, SC_PRODUCT_BUILD])) {
					$arrSubImages = [];
					foreach ($subImages as $key => $image) {
						if ($image) {
							$arrSubImages[] = new ShopProductImage(['image' => $image]);
						}
					}
					$product->images()->saveMany($arrSubImages);
				}
				if(isset($data['hiddenfile']))
				{
					$imageid =  count($data['hiddenfile']);
					for($i=0; $i<$imageid; $i++) {              
						$dataImage = [
                        'name' => '/data/images/'.$data['hiddenfile'][$i],
                        'content' => $data['content'][$i],
                        'pro_id' => $product->id,                              
						];
						
						ProductFiles::create($dataImage);              
						
					}
				}   
				
				return redirect()->route('admin_product.index')->with('success', trans('product.admin.create_success'));
				
			}
			
			$data['alias'] = !empty($data['alias'])?$data['alias']:$data['descriptions'][$langFirst]['name'];
			$data['alias'] = sc_word_format_url($data['alias']);
			$data['alias'] = sc_word_limit($data['alias'], 100);
			
			switch ($data['kind']) {
				case SC_PRODUCT_SINGLE: // product single
                $arrValidation = [
				'kind' => 'required',
				'hiddenfile' => 'required',
				'image' => 'required',
				'image' => 'required',
				'content' => 'required',
				'tags' => 'required',
				'license' => 'required',
				'sort' => 'numeric|min:0',
				'descriptions.*.name' => 'required|string|max:100',
				'descriptions.*.content' => 'required|string',
				'category' => 'required',
				'sku' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,sku',
				'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,alias|string|max:100',
                ];
                $arrMsg = [
				'descriptions.*.name.required' => trans('validation.required', ['attribute' => trans('product.name')]),
				'descriptions.*.content.required' => trans('validation.required', ['attribute' => trans('product.content')]),
				'category.required' => trans('validation.required', ['attribute' => trans('product.category')]),
				'sku.regex' => trans('product.sku_validate'),
				'alias.regex' => trans('product.alias_validate'),
				'hiddenfile.required' => 'File is required',
				'content.required' => 'File format is required',
                ];
                break;
				
				case SC_PRODUCT_BUILD: //product build
                $arrValidation = [
				'kind' => 'required',
				'sort' => 'numeric|min:0',
				'descriptions.*.name' => 'required|string|max:100',
				'category' => 'required',
				'sku' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,sku',
				'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,alias|string|max:100',
				'productBuild' => 'required',
				'productBuildQty' => 'required',
				
                ];
                $arrMsg = [
				'descriptions.*.name.required' => trans('validation.required', ['attribute' => trans('product.name')]),
				'category.required' => trans('validation.required', ['attribute' => trans('product.category')]),
				'sku.regex' => trans('product.sku_validate'),
				'alias.regex' => trans('product.alias_validate'),
                ];
                break;
				
				case SC_PRODUCT_GROUP: //product group
                $arrValidation = [
				'kind' => 'required',
				'productInGroup' => 'required',
				'sku' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,sku',
				'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,alias|string|max:100',
				'sort' => 'numeric|min:0',
				'descriptions.*.name' => 'required|string|max:100',
                ];
                $arrMsg = [
				'descriptions.*.name.required' => trans('validation.required', ['attribute' => trans('product.name')]),
				'sku.regex' => trans('product.sku_validate'),
				'alias.regex' => trans('product.alias_validate'),
                ];
                break;
				
				default:
                $arrValidation = [
				'kind' => 'required',
                ];
                break;
			}
			
			$validator = Validator::make($data, $arrValidation, $arrMsg ?? []);
			
			if ($validator->fails()) {
				return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
			}
			$related = '';
			if(!empty($data['related'])){
				$related = implode(',', $data['related']);
			}
			/* $tags = implode(',', $data['tags']);*/
			
			if (isset($data['subcategory'][0])) { $category = $data['subcategory']; }
			else { $category = $data['category']; }
			$sku = !empty($data['sku']) ?  $data['sku'] : rand();
			
			$attribute = $data['attribute'] ?? [];
			$descriptions = $data['descriptions'];
			$productInGroup = $data['productInGroup'] ?? [];
			$productBuild = $data['productBuild'] ?? [];
			$productBuildQty = $data['productBuildQty'] ?? [];
			$subImages = $data['sub_image'] ?? [];
			$dataInsert = [
            'brand_id' => $data['brand_id']??0,
            'adminid' => $user->id ?? 0,
            'vendor_id' => $data['vendor_id']??0,
            'price' => $data['price']??0,
            'sku' => $sku,
            'cost' => $data['cost']??0,
            'stock' => $data['stock']??0,
            'type' => $data['type'] ?? SC_PRODUCT_NORMAL,
            'kind' => $data['kind']??SC_PRODUCT_SINGLE,
            'alias' => $data['alias'],
            'virtual' => $data['virtual'] ?? SC_VIRTUAL_PHYSICAL,
            'date_available' => !empty($data['date_available']) ? $data['date_available'] : null,
            'image' => $data['image']??'',
            'status' => (!empty($data['status']) ? 1 : 0),
            'featured' => (!empty($data['featured']) ? 1 : 0),
            'noprice' => (!empty($data['noprice']) ? 1 : 0),
            'sort' => (int) $data['sort'],
            'license' => $data['license'],
            'tags' => $data['tags'],
            'related' => $related,
            'cat_id' => $data['category'][0],
			];
			//insert product
			$product = ShopProduct::create($dataInsert);
			
			//Promoton price
			if (isset($data['price_promotion']) && in_array($data['kind'], [SC_PRODUCT_SINGLE, SC_PRODUCT_BUILD])) {
				$arrPromotion['price_promotion'] = $data['price_promotion'];
				$arrPromotion['date_start'] = $data['price_promotion_start'] ? $data['price_promotion_start'] : null;
				$arrPromotion['date_end'] = $data['price_promotion_end'] ? $data['price_promotion_end'] : null;
				$product->promotionPrice()->create($arrPromotion);
			}
			
			//Insert category
			if ($category && in_array($data['kind'], [SC_PRODUCT_SINGLE, SC_PRODUCT_BUILD])) {
				$product->categories()->attach($category);
			}
			//Insert group
			if ($productInGroup && $data['kind'] == SC_PRODUCT_GROUP) {
				$arrDataGroup = [];
				foreach ($productInGroup as $pID) {
					if ((int) $pID) {
						$arrDataGroup[$pID] = new ShopProductGroup(['product_id' => $pID]);
					}
				}
				$product->groups()->saveMany($arrDataGroup);
			}
			
			//Insert Build
			if ($productBuild && $data['kind'] == SC_PRODUCT_BUILD) {
				$arrDataBuild = [];
				foreach ($productBuild as $key => $pID) {
					if ((int) $pID) {
						$arrDataBuild[$pID] = new ShopProductBuild(['product_id' => $pID, 'quantity' => $productBuildQty[$key]]);
					}
				}
				$product->builds()->saveMany($arrDataBuild);
			}
			
			//Insert attribute
			if ($attribute && $data['kind'] == SC_PRODUCT_SINGLE) {
				$arrDataAtt = [];
				foreach ($attribute as $group => $rowGroup) {
					if (count($rowGroup)) {
						foreach ($rowGroup as $key => $nameAtt) {
							if ($nameAtt) {
								$arrDataAtt[] = new ShopProductAttribute(['name' => $nameAtt, 'attribute_group_id' => $group]);
							}
						}
					}
					
				}
				$product->attributes()->saveMany($arrDataAtt);
			}
			
			//Insert description
			$dataDes = [];
			$languages = $this->languages;
			foreach ($languages as $code => $value) {
				$dataDes[] = [
                'product_id' => $product->id,
                'lang' => $code,
                'name' => $descriptions[$code]['name'],
                'keyword' => $descriptions[$code]['keyword'],
                'description' => $descriptions[$code]['description'],
                'content' => $descriptions[$code]['content'] ?? '',
				'meta_title'=>$data['meta_title'],
				'meta_keyword'=>$data['meta_keyword'],
				'meta_description'=>$data['meta_description'],
				];
			}
			
			ShopProductDescription::insert($dataDes);
			
			//Insert sub mages
			if ($subImages && in_array($data['kind'], [SC_PRODUCT_SINGLE, SC_PRODUCT_BUILD])) {
				$arrSubImages = [];
				foreach ($subImages as $key => $image) {
					if ($image) {
						$arrSubImages[] = new ShopProductImage(['image' => $image]);
					}
				}
				$product->images()->saveMany($arrSubImages);
			}
			if(isset($data['hiddenfile']))
			{
				$imageid =  count($data['hiddenfile']);
				for($i=0; $i<$imageid; $i++) {              
					$dataImage = [
					'name' => '/data/images/'.$data['hiddenfile'][$i],
					'content' => $data['content'][$i],
					'pro_id' => $product->id,                              
                    ];
					
					ProductFiles::create($dataImage);              
					
				}
			}
			
			
			
			
			
			return redirect()->route('admin_product.index')->with('success', trans('product.admin.create_success'));
			
		}
		
		/**
			* Form edit
		*/
		public function edit($id)
		{
			$product = ShopProduct::find($id);
			$files = ProductFiles::where('pro_id',$id)->get();
			$ShopProductDescription = ShopProductDescription::where('product_id',$id)->get();
			$related = ShopProduct::where('status',1)->get();
			
			// dd($product);
			
			
			if ($product === null) {
				return 'no data';
			}
			
			$listProductSingle = (new ShopProduct)->getListSigle();
			$tags = Tags::where('status',1)->get();
			$license = License::where('status',1)->get();
			
			// html select product group
			$htmlSelectGroup = '<div class="select-product">';
			$htmlSelectGroup .= '<table width="100%"><tr><td width="80%"><select class="form-control productInGroup select2" data-placeholder="' . trans('product.admin.select_product_in_group') . '" style="width: 100%;" name="productInGroup[]" >';
			$htmlSelectGroup .= '';
			foreach ($listProductSingle as $k => $v) {
				$htmlSelectGroup .= '<option value="' . $k . '">' . $v['name'] . '</option>';
			}
			$htmlSelectGroup .= '</select></td><td><span title="Remove" class="btn btn-flat btn-danger removeproductInGroup"><i class="fa fa-times"></i></span></td></tr></table>';
			$htmlSelectGroup .= '</div>';
			//End select product group
			
			// html select product build
			$htmlSelectBuild = '<div class="select-product">';
			$htmlSelectBuild .= '<table width="100%"><tr><td width="70%"><select class="form-control productInGroup select2" data-placeholder="' . trans('product.admin.select_product_in_build') . '" style="width: 100%;" name="productBuild[]" >';
			$htmlSelectBuild .= '';
			foreach ($listProductSingle as $k => $v) {
				$htmlSelectBuild .= '<option value="' . $k . '">' . $v['name'] . '</option>';
			}
			$htmlSelectBuild .= '</select></td><td style="width:100px"><input class="form-control"  type="number" name="productBuildQty[]" value="1" min=1></td><td><span title="Remove" class="btn btn-flat btn-danger removeproductBuild"><i class="fa fa-times"></i></span></td></tr></table>';
			$htmlSelectBuild .= '</div>';
			//end select product build
			
			// html select attribute
			$htmlProductAtrribute = '<tr><td><br><input type="text" name="attribute[attribute_group][]" value="attribute_value" class="form-control input-sm" placeholder="' . trans('product.admin.add_attribute_place') . '" /></td><td><br><span title="Remove" class="btn btn-flat btn-sm btn-danger removeAttribute"><i class="fa fa-times"></i></span></td></tr>';
			//end select attribute
			
			$data = [
            'title' => trans('product.admin.edit'),
            'sub_title' => '',
            'title_description' => '',
            'icon' => 'fa fa-pencil-square-o',
            'languages' => $this->languages,
            'product' => $product,
            'categories' => (new ShopCategory)->getTreeCategories(),
            // 'shop_product_description' => (new ShopProductDescription)->get($product->id),
            'subcats' => (new ShopCategory)->getCategories($product->cat_id),
            'newcats' => (new ShopCategory)->getCategoriesTop(),
            'brands' => (new ShopBrand)->getList(),
            'vendors' => (new ShopVendor)->getList(),
            'types' => $this->types,
            'virtuals' => $this->virtuals,
            'kinds' => $this->kinds,
            'attributeGroup' => $this->attributeGroup,
            'htmlSelectGroup' => $htmlSelectGroup,
            'htmlSelectBuild' => $htmlSelectBuild,
            'listProductSingle' => $listProductSingle,
            'htmlProductAtrribute' => $htmlProductAtrribute,
            'tags' => $tags ?? [],
            'license' => $license ?? [],
            'files' => $files ?? [],
            'related' => $related ?? [],
			'shopProductDescription'=>$ShopProductDescription,
			];
			return view('admin.screen.product_edit')
            ->with($data);
		}
		
		/**
			* update status
		*/
		public function postEdit($id)
		{
			$product = ShopProduct::find($id);
			
			$data = request()->all();
			// dd($data);

			
			
			$langFirst = array_key_first(sc_language_all()->toArray()); //get first code language active
			if(isset($data['draft']) && !empty($data['draft'])){
				
				$related = '';
				if(!empty($data['related'])){
					$related = implode(',', $data['related']);
				}
				/*$tags = !empty($data['tags']) ? implode(',', $data['tags']) : '';*/
				$sku = !empty($data['sku']) ?  $data['sku'] : rand();
				
				if (isset($data['subcategory'][0])) { $category = $data['subcategory']; }
				else { $category = $data['category']; }
				
				$attribute = $data['attribute'] ?? [];
				$productInGroup = $data['productInGroup'] ?? [];
				$productBuild = $data['productBuild'] ?? [];
				$productBuildQty = $data['productBuildQty'] ?? [];
				$subImages = $data['sub_image'] ?? [];
				$dataUpdate = [
				'image' => $data['image'] ?? '',
				'brand_id' => $data['brand_id'] ?? 0,
				'vendor_id' => $data['vendor_id'] ?? 0,
				'price' => $data['price'] ?? 0,
				'cost' => $data['cost'] ?? 0,
				'stock' => $data['stock'] ?? 0,
				'type' => $data['type'] ?? SC_PRODUCT_NORMAL,
				'virtual' => $data['virtual'] ?? SC_VIRTUAL_PHYSICAL,
				'date_available' => !empty($data['date_available']) ? $data['date_available'] : null,
				'sku' =>  $sku,
				'alias' => $data['alias']??'',
				'status' => 0,
				'featured' => (!empty($data['featured']) ? 1 : 0),
				'noprice' => (!empty($data['noprice']) ? 1 : 0),
				'sort' => (int) $data['sort'],
				'license' => $data['license']??'',
				'tags' => $data['tags']??'',
				'related' => $related,
				'cat_id' => $data['category'][0]??'',
				];
				// echo "<pre>";
				// dd($dataUpdate);
				// exit;
				
				$product->update($dataUpdate);
				
				//Promoton price
				$product->promotionPrice()->delete();
				if (isset($data['price_promotion']) && in_array($product['kind'], [SC_PRODUCT_SINGLE, SC_PRODUCT_BUILD])) {
					$arrPromotion['price_promotion'] = $data['price_promotion'];
					$arrPromotion['date_start'] = $data['price_promotion_start'] ? $data['price_promotion_start'] : null;
					$arrPromotion['date_end'] = $data['price_promotion_end'] ? $data['price_promotion_end'] : null;
					$product->promotionPrice()->create($arrPromotion);
				}
				
				$product->descriptions()->delete();
				$dataDes = [];
				foreach ($data['descriptions'] as $code => $row) {
					$dataDes[] = [
					'product_id' => $id,
					'lang' => $code,
					'name' => $row['name'],
					'keyword' => $row['keyword'],
					'description' => $row['description'],
					'content' => $row['content'] ?? '',
					'meta_title'=>$data['meta_title'],
					'meta_keyword'=>$data['meta_keyword'],
					'meta_description'=>$data['meta_description'],
					];
				}
				ShopProductDescription::insert($dataDes);
				
				//Update category
				$product->categories()->detach();
				if (isset($category[0])) {            
					$product->categories()->attach($category);
				}
				
				//Update sub mages
				
				$product->images()->delete();
				$arrSubImages = [];
				foreach ($subImages as $key => $image) {
					if ($image) {
						$arrSubImages[] = new ShopProductImage(['image' => $image]);
					}
				}
				$product->images()->saveMany($arrSubImages);      
				
				
				
				if(isset($data['hiddenfile']))
				{
					$imageid =  count($data['hiddenfile']);
					for($i=0; $i<$imageid; $i++) {              
						$dataImage = [
                        'name' => '/data/images/'.$data['hiddenfile'][$i],
                        'content' => $data['content'][$i],
                        'pro_id' => $id,                              
						];
						
						ProductFiles::create($dataImage);              
						
					}
				}
				
				if(isset($data['file_image']))
				{
					$imgupd =  count($data['file_image']);
					for($i=0; $i<$imgupd; $i++) { 
						$filimg = ProductFiles::find($data['fileid'][$i]);              
						$dataImageup = [
                        'name' => $data['file_image'][$i],
                        'content' => $data['filecontent'][$i]
						];
						
						$filimg->update($dataImageup);             
						
					}
				}
				
				if(isset($data['rmfile']))
				{
					$rmimage =  count($data['rmfile']);
					for($i=0; $i<$rmimage; $i++) {          
						
						ProductFiles::destroy($data['rmfile'][$i]);                           
						
					}
				}
				
				//
				return redirect()->route('admin_product.index')->with('success', trans('product.admin.edit_success'));
				
				
			}
			$data['alias'] = !empty($data['alias'])?$data['alias']:$data['descriptions'][$langFirst]['name'];
			$data['alias'] = sc_word_format_url($data['alias']);
			$data['alias'] = sc_word_limit($data['alias'], 100);
			
			switch ($product['kind']) {
				case SC_PRODUCT_SINGLE: // product single
                $arrValidation = [
				'sort' => 'numeric|min:0',
				'descriptions.*.name' => 'required|string|max:100',
				'descriptions.*.content' => 'required|string',
				'category' => 'required',
				'tags' => 'required',
				'license' => 'required',
				'sku' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,sku,' . $product->id . ',id',
				'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,alias,' . $product->id . ',id|string|max:100',
                ];
                $arrMsg = [
				'descriptions.*.name.required' => trans('validation.required', ['attribute' => trans('product.name')]),
				'descriptions.*.content.required' => trans('validation.required', ['attribute' => trans('product.content')]),
				'category.required' => trans('validation.required', ['attribute' => trans('product.category')]),
				'sku.regex' => trans('product.sku_validate'),
				'alias.regex' => trans('product.alias_validate'),
                ];
                break;
				case SC_PRODUCT_BUILD: //product build
                $arrValidation = [
				'sort' => 'numeric|min:0',
				'descriptions.*.name' => 'required|string|max:100',
				'category' => 'required',
				'sku' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,sku,' . $product->id . ',id',
				'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,alias,' . $product->id . ',id|string|max:100',
				'productBuild' => 'required',
				'productBuildQty' => 'required',
                ];
                $arrMsg = [
				'descriptions.*.name.required' => trans('validation.required', ['attribute' => trans('product.name')]),
				'category.required' => trans('validation.required', ['attribute' => trans('product.category')]),
				'sku.regex' => trans('product.sku_validate'),
				'alias.regex' => trans('product.alias_validate'),
                ];
                break;
				
				case SC_PRODUCT_GROUP: //product group
                $arrValidation = [
				'sku' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,sku,' . $product->id . ',id',
				'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,alias,' . $product->id . ',id|string|max:100',
				'productInGroup' => 'required',
				'sort' => 'numeric|min:0',
				'descriptions.*.name' => 'required|string|max:100',
                ];
                $arrMsg = [
				'sku.regex' => trans('product.sku_validate'),
				'alias.regex' => trans('product.alias_validate'),
				'descriptions.*.name.required' => trans('validation.required', ['attribute' => trans('product.name')]),
                ];
                break;
				
				default:
                break;
			}
			
			$validator = Validator::make($data, $arrValidation, $arrMsg ?? []);
			
			if ($validator->fails()) {
				return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
			}
			//Edit
			$related = '';
			if(!empty($data['related'])){
				$related = implode(',', $data['related']);
			}
			
			if (isset($data['subcategory'][0])) { $category = $data['subcategory']; }
			else { $category = $data['category']; }
			
			//$tags = implode(',', $data['tags']);
			
			$attribute = $data['attribute'] ?? [];
			$productInGroup = $data['productInGroup'] ?? [];
			$productBuild = $data['productBuild'] ?? [];
			$productBuildQty = $data['productBuildQty'] ?? [];
			$subImages = $data['sub_image'] ?? [];
			$dataUpdate = [
            'image' => $data['image'] ?? '',
            'brand_id' => $data['brand_id'] ?? 0,
            'vendor_id' => $data['vendor_id'] ?? 0,
            'price' => $data['price'] ?? 0,
            'cost' => $data['cost'] ?? 0,
            'stock' => $data['stock'] ?? 0,
            'type' => $data['type'] ?? SC_PRODUCT_NORMAL,
            'virtual' => $data['virtual'] ?? SC_VIRTUAL_PHYSICAL,
            'date_available' => !empty($data['date_available']) ? $data['date_available'] : null,
            'sku' => $data['sku'],
            'alias' => $data['alias'],
            'status' => (!empty($data['status']) ? 1 : 0),
            'featured' => (!empty($data['featured']) ? 1 : 0),
            'noprice' => (!empty($data['noprice']) ? 1 : 0),
            'sort' => (int) $data['sort'],
            'license' => $data['license'],
            'tags' => $data['tags'],
            'related' => $related,
            'cat_id' => $data['category'][0],
			];
			
			$product->update($dataUpdate);
			
			//Promoton price
			$product->promotionPrice()->delete();
			if (isset($data['price_promotion']) && in_array($product['kind'], [SC_PRODUCT_SINGLE, SC_PRODUCT_BUILD])) {
				$arrPromotion['price_promotion'] = $data['price_promotion'];
				$arrPromotion['date_start'] = $data['price_promotion_start'] ? $data['price_promotion_start'] : null;
				$arrPromotion['date_end'] = $data['price_promotion_end'] ? $data['price_promotion_end'] : null;
				$product->promotionPrice()->create($arrPromotion);
			}
			
			$product->descriptions()->delete();
			$dataDes = [];
			foreach ($data['descriptions'] as $code => $row) {
				$dataDes[] = [
                'product_id' => $id,
                'lang' => $code,
                'name' => $row['name'],
                'keyword' => $row['keyword'],
                'description' => $row['description'],
                'content' => $row['content'] ?? '',
				'meta_title'=>$data['meta_title'],
				'meta_keyword'=>$data['meta_keyword'],
				'meta_description'=>$data['meta_description'],
				];
			}
			ShopProductDescription::insert($dataDes);
			
			//Update category
			if (in_array($product['kind'], [SC_PRODUCT_SINGLE, SC_PRODUCT_BUILD])) {
				$product->categories()->detach();
				if (count($category)) {
					$product->categories()->attach($category);
				}
				
			}
			
			//Update group
			if ($product['kind'] == SC_PRODUCT_GROUP) {
				$product->groups()->delete();
				if (count($productInGroup)) {
					$arrDataGroup = [];
					foreach ($productInGroup as $pID) {
						if ((int) $pID) {
							$arrDataGroup[$pID] = new ShopProductGroup(['product_id' => $pID]);
						}
					}
					$product->groups()->saveMany($arrDataGroup);
				}
				
			}
			
			//Update Build
			if ($product['kind'] == SC_PRODUCT_BUILD) {
				$product->builds()->delete();
				if (count($productBuild)) {
					$arrDataBuild = [];
					foreach ($productBuild as $key => $pID) {
						if ((int) $pID) {
							$arrDataBuild[$pID] = new ShopProductBuild(['product_id' => $pID, 'quantity' => $productBuildQty[$key]]);
						}
					}
					$product->builds()->saveMany($arrDataBuild);
				}
				
			}
			
			//Update attribute
			if ($product['kind'] == SC_PRODUCT_SINGLE) {
				$product->attributes()->delete();
				if (count($attribute)) {
					$arrDataAtt = [];
					foreach ($attribute as $group => $rowGroup) {
						if (count($rowGroup)) {
							foreach ($rowGroup as $key => $nameAtt) {
								if ($nameAtt) {
									$arrDataAtt[] = new ShopProductAttribute(['name' => $nameAtt, 'attribute_group_id' => $group]);
								}
							}
						}
						
					}
					$product->attributes()->saveMany($arrDataAtt);
				}
				
			}
			
			//Update sub mages
			
            $product->images()->delete();
            $arrSubImages = [];
            foreach ($subImages as $key => $image) {
                if ($image) {
                    $arrSubImages[] = new ShopProductImage(['image' => $image]);
				}
			}
            $product->images()->saveMany($arrSubImages);
			
			
			
			
			if(isset($data['hiddenfile']))
			{
				$imageid =  count($data['hiddenfile']);
				for($i=0; $i<$imageid; $i++) {              
					$dataImage = [
					'name' => '/data/images/'.$data['hiddenfile'][$i],
					'content' => $data['content'][$i],
					'pro_id' => $id,                              
                    ];
					
					ProductFiles::create($dataImage);              
					
				}
			}
			
			if(isset($data['file_image']))
			{
				$imgupd =  count($data['file_image']);
				for($i=0; $i<$imgupd; $i++) { 
					$filimg = ProductFiles::find($data['fileid'][$i]);              
					$dataImageup = [
					'name' => $data['file_image'][$i],
					'content' => $data['filecontent'][$i]
                    ];
					
					$filimg->update($dataImageup);             
					
				}
			}
			
			if(isset($data['rmfile']))
			{
				$rmimage =  count($data['rmfile']);
				for($i=0; $i<$rmimage; $i++) {          
					
					ProductFiles::destroy($data['rmfile'][$i]);                           
					
				}
			}
			
			//
			return redirect()->route('admin_product.index')->with('success', trans('product.admin.edit_success'));
			
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
				$arrCantDelete = [];
				foreach ($arrID as $key => $id) {
					if (ShopProductBuild::where('product_id', $id)->first() || ShopProductGroup::where('product_id', $id)->first()) {
					$arrCantDelete[] = $id;}
				}
				if (count($arrCantDelete)) {
					return response()->json(['error' => 1, 'msg' => trans('product.admin.cant_remove_child') . ': ' . json_encode($arrCantDelete)]);
					} else {
					ShopProduct::destroy($arrID);
					return response()->json(['error' => 0, 'msg' => '']);
				}
				
			}
		}
		
		public function duplicate($id)
		{
			$letter = chr(rand(97,122));
			$sku = rand();
			$post = ShopProduct::find($id);
			$newPost = $post->replicate();
			$newPost->created_at = Carbon::now();
			$newPost->sku = $post->sku.$sku;
			$newPost->status = 0;
			$newPost->featured = 0;
			$newPost->view = 0;
			$newPost->sold = 0;
			$newPost->alias = $post->alias.$letter;
			$newPost->save();
			
			$postd = ShopProductDescription::where('product_id',$id)->first();
			$postdDes = [
            'product_id' => $newPost->id,
            'lang' => 'en',
            'name' => $postd->name.' Copy',
            'keyword' => $postd->keyword,
            'description' => $postd->description,
            'content' => $postd->content,
			];
			
			ShopProductDescription::insert($postdDes);
			$copyPfiles = ProductFiles::where('pro_id',$id)->get();
			
			foreach($copyPfiles as $key => $value)
			{
				$new_unit = ProductFiles::find($value->id)->replicate();
				$new_unit->pro_id = $newPost->id;
				$new_unit->save();
			}
			
			$copyImages = ShopProductImage::where('product_id',$id)->get();
			
			foreach($copyImages as $key => $value)
			{
				$new_image = ShopProductImage::find($value->id)->replicate();
				$new_image->product_id = $newPost->id;
				$new_image->save();
			}
			$copyCats = ShopProductCategory::where('product_id',$id)->get();
			
			foreach($copyCats as $key => $value)
			{
				$catdata = [
				'product_id' => $newPost->id,            
				'category_id' => $value->category_id,
				];
				
				ShopProductCategory::insert($catdata);
			}
			$copyPromoprice = ShopProductPromotion::where('product_id',$id)->first();
			if(!empty($copyPromoprice)){
				$prmoData = [
				'product_id' => $newPost->id,            
				'price_promotion' => $copyPromoprice->price_promotion,
				'date_start' => $copyPromoprice->date_start,
				'date_end' => $copyPromoprice->date_end,
				'status_promotion' => $copyPromoprice->status_promotion,
				];
				
				ShopProductPromotion::insert($prmoData);
			}
			
			return redirect()->route('admin_product.index')->with('success', 'Duplicate product created successfully!');
		}
		
		public function getSubcat(Request $request)
		{ 
			
			$subcats = (new ShopCategory)->getTreeCategories($request['cat']);
			if(!empty($subcats)){
				echo '<option value=""></option>';
				foreach($subcats as $k => $v)
				{ ?>
				<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
				<?php }
			}
		}
		public function getSubMultiple(Request $request)
		{   
			$cats = ShopCategory::whereIn('parent', $request['cat'])->get();
			if(!empty($cats)){
				echo '<option value=""></option>';
				foreach($cats as $k)
				{ ?>
				<option value="<?php echo $k->id; ?>"><?php echo $k->name; ?></option>
			<?php } }
			
		}
		public function getRelated(Request $request)
		{   
			
			$cat = ShopCategory::whereIn('parent', $request['cat'])->get();
			
			if (count($cat)) {
				$csv_data = $cat->pluck('id')->implode(',');
				$cats = explode (",", $csv_data); 
				$cid = array_merge($cats,$request['cat']);
			}
			else { 
				$cid = $request['cat'];
			}          
			
			$productsToCategory = (new ShopProduct)->getProductsToCategory($cid, $limit = sc_config('product_relation'), $opt = 'random');
			if(!empty($productsToCategory)){
				echo '<option value=""></option>';
				foreach ($productsToCategory as $rlpro)
				{ ?>
				<option value="<?php echo $rlpro->id; ?>"><?php echo $rlpro->name; ?></option>
				<?php }
			}
		}
		
	}
