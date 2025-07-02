<?php
	#app/Http/Controller/ShopFront.php
	namespace App\Http\Controllers;
	
	use App\Models\UserSubscription;
	use App\Models\ShopAttributeGroup;
	use App\Models\ShopBrand;
	use App\Models\ShopBanner;
	use App\Models\ShopCategory;
	use App\Models\ShopProduct;
	use App\Models\ProductFiles;
	use App\Models\Countdownloads;
	use App\Models\ShopVendor;
	use App\Models\ShopPage;
	use App\Models\Tags;
	use Illuminate\Http\Request;
	use App\Admin\Admin;
	use App\Admin\Models\AdminUser;
	use App\Models\Affiliatelink;
	use App\Models\Marketplace;
	use App\Models\ShopProductDescription;
	use URL;
	use Auth;
	
	class ShopFront extends GeneralController
	{
		public function __construct()
		{
			parent::__construct();
		}
		
		
		
		/**
			* [index description]
			* @return [type] [description]
		*/
		public function index(Request $request)
		{
			$menus = ShopCategory::where('top', '=', 0)->get();  
			//$allMenus = ShopCategory::pluck('title','id')->all();
			$catlist1 = (new ShopCategory)->getCategories($parent = 0);
			$page = ShopPage::where('alias', 'home')->where('status', 1)->first();
			
			return view($this->templatePath . '.shop_home',	
            array(
			'products_new' => (new ShopProduct)->getFeatured($type = null, $limit =12, $opt = 'paginate'),  
			'catlist1' => $catlist1,				
			'layout_page' => 'home',
			'pageData' => ShopPage::where('alias', 'home')->where('status', 1)->first(),
			'slider' => ShopBanner::where('status', 1)->orderBy('sort','asc')->get(),
			'menus' => $menus,
            )
			);
		}
		
		/**
			* [getCategories description]
			* @param  Request $request [description]
			* @return [type]           [description]
		*/
		public function getCategories(Request $request)
		{
			$page = ShopPage::where('alias', 'category')->where('status', 1)->first();
			$itemsList = ShopCategory::where('status',1)->where('top',1)->paginate(8);  
			
			return view($this->templatePath . '.shop_item_list',
			array(
            'title' => trans('front.categories'),
            'itemsList' => $itemsList,
            'keyword' => $page->keyword,
            'description' => $page->description,
            'layout_page' => 'item_list',
            'pageData' => $page,
			));
		}
		
		/**
			* [productToCategory description]
			* @param  [string] $alias [description]
			* @return [type]      [description]
		*/
		public function productToCategory($alias)
		{
			$sortBy = null;
			$sortOrder = 'asc';
			$filter_sort = request('filter_sort') ?? '';
			$filterArr = [
			'price_desc' => ['price', 'desc'],
			'price_asc' => ['price', 'asc'],
			'sort_desc' => ['sort', 'desc'],
			'sort_asc' => ['sort', 'asc'],
			'id_desc' => ['id', 'desc'],
			'id_asc' => ['id', 'asc'],
			];  
			if (array_key_exists($filter_sort, $filterArr)) {
				$sortBy = $filterArr[$filter_sort][0];
				$sortOrder = $filterArr[$filter_sort][1];
			}
			
			$category = (new ShopCategory)->getCategory($id = null, $alias);
			$cats = (new ShopCategory)->getIdCategories($category->id);
			if(!empty($cats)) $cid = $cats;
			else $cid = $category->id;
			
			$page = ShopPage::where('alias', 'product')->where('status', 1)->first();
			if ($category) {
				$products = (new ShopProduct)->getProductsToCategory($cid, $limit = sc_config('product_list'), $opt = 'paginate', $sortBy, $sortOrder);
				$itemsList = (new ShopCategory)->getCategories($parent = $category->id);
				return view($this->templatePath . '.shop_products_list',
				array(
				'title' => $category->name,
				'description' => $category->description,
				'keyword' =>  $page->keyword,
				'products' => $products,
				'itemsList' => $itemsList,
				'layout_page' => 'product_list',
				'og_image' => url($category->getImage()),
				'filter_sort' => $filter_sort,
				'pageData' => ShopPage::where('alias', 'product')->where('status', 1)->first(),
				)
				);
				} else {
				return $this->itemNotFound();
			}
			
		}
		
		
		/**
			* All products
			* @param  [type] $key [description]
			* @return [type]      [description]
		*/
		public function allProducts()
		{
			
			if(isset($_GET['refer']))
			{
				$crntUrl =  URL::full();
				$link = Affiliatelink::where('link',$crntUrl)->orderBy('id', 'desc')->first();
				$crdate = date("Y-m-d");
				if (!empty($link)) {
					$dateTimestamp1 = strtotime($link->expire_date);
					$dateTimestamp2 = strtotime($crdate);
					if ($dateTimestamp1 > $dateTimestamp2)
					{ 
						session(['affilUser' => $link->userid]);
						
					}
					else
					{
						return $this->itemNotFound();
					}            
				}               
			} 
			
			$sortBy = null;
			$sortOrder = 'asc';
			
			$filter_sort = request('filter_sort') ?? '';
			$filterArr = [
			'price_desc' => ['price', 'desc'],
			'price_asc' => ['price', 'asc'],
			'sort_desc' => ['sort', 'desc'],
			'sort_asc' => ['sort', 'asc'],
			'id_desc' => ['id', 'desc'],
			'id_asc' => ['id', 'asc'],
			];
			if (array_key_exists($filter_sort, $filterArr)) {
				$sortBy = $filterArr[$filter_sort][0];
				$sortOrder = $filterArr[$filter_sort][1];
			}
			
			$products = (new ShopProduct)->getProducts($type = null, $limit = 20, $opt = 'paginate', $sortBy, $sortOrder);
			$page = ShopPage::where('alias', 'product')->where('status', 1)->first();
			
			return view($this->templatePath . '.shop_products_list',
			array(
			'title' => trans('front.all_product'),
			'keyword' => $page->keyword,
			'description' => $page->description,
			'products' => $products,
			'layout_page' => 'product_list',
			'filter_sort' => $filter_sort,
			'pageData' => $page,
			));
		}
		
		public function tagProducts($alias)
		{
			$sortBy = null;
			$sortOrder = 'asc';
			$filter_sort = request('filter_sort') ?? '';
			$filterArr = [
			'price_desc' => ['price', 'desc'],
			'price_asc' => ['price', 'asc'],
			'sort_desc' => ['sort', 'desc'],
			'sort_asc' => ['sort', 'asc'],
			'id_desc' => ['id', 'desc'],
			'id_asc' => ['id', 'asc'],
			];
			if (array_key_exists($filter_sort, $filterArr)) {
				$sortBy = $filterArr[$filter_sort][0];
				$sortOrder = $filterArr[$filter_sort][1];
			}
			$tag = Tags::where('alias',$alias)->first();
			
			$products = (new ShopProduct)->getTagProducts($type = null, $limit = 20, $opt = 'paginate', $sortBy, $sortOrder, $tag->id);
			$page = ShopPage::where('alias', 'product')->where('status', 1)->first();
			
			return view($this->templatePath . '.tag_product_list',
			array(
			'title' => trans('front.all_product'),
			'keyword' => $page->keyword,
			'description' => $page->description,
			'products' => $products,
			'layout_page' => 'product_list',
			'filter_sort' => $filter_sort,
			'pageData' => $page,
			'tag' => $tag,
			));
		}
		
		/**
			* [productDetail description]
			* @param  [string] $alias
			* @param  [type] $id   [description]
			* @return [type]       [description]
		*/
		public function productDetail($alias)
		{
			$product = (new ShopProduct)->getProduct($id = null, $alias);
			$ShopProductDescription = ShopProductDescription::where('product_id',$product->id)->get();
			// $data = $ShopProductDescription;
			// dd($data[0]['meta_description']);
			
			if ($product) {
				
				if (Auth::user()) {
					$user = Auth::user();
					$crntdate = date('Y-m-d H:i:s');
					
					// $stripe = new Stripe\StripeClient(env('STRIPE_SECRET'));
					$getPlan = UserSubscription::where('user_id', $user->id)->where('status', 'active')->orderBy('id','DESC')->first();
					if(!empty($getPlan)){
						$checkSubs = $stripe->subscriptions->retrieve($getPlan->stripe_subscription_id);
						$newDate =  date("Y-m-d H:i:s", $checkSubs['current_period_end']);
						$getPlan->plan_period_end = $newDate;
						$getPlan->save();                
						$plan = UserSubscription::where('plan_period_end', '>=', $crntdate)->where('user_id', $user->id)->where('status', 'active')->where('download_limit', '>', 0)->orderBy('id','DESC')->first();
					}
				}
				$files = ProductFiles::where('pro_id',$product->id)->get();
				
				$affilDisc=0;
				if (!empty(session('affilUser'))) {
					$fprice= $product->getFinalPrice(); 
					$Afffees = Marketplace::Where('id',2)->first();
					$affilDisc = round($Afffees->fees * ($fprice / 100),2);
					session(['affproPrice' => $fprice]);
				}
				session(['affilDisc' => $affilDisc]); 
				
				//Update last view
				$product->view += 1;
				$product->date_lastview = date('Y-m-d H:i:s');
				$product->save();
				//End last viewed
				
				$rltdids  = explode(',', $product->related);
				$related = ShopProduct::relatedProduct($rltdids);
				//Product last view
				if (!empty(sc_config('LastViewProduct'))) {
					$arrlastView = empty(\Cookie::get('productsLastView')) ? array() : json_decode(\Cookie::get('productsLastView'), true);
					$arrlastView[$product->id] = date('Y-m-d H:i:s');
					arsort($arrlastView);
					\Cookie::queue('productsLastView', json_encode($arrlastView), (86400 * 30));
				}
				//End product last view
				
				$admin = AdminUser::find($product->adminid);
				
				
				$categories = $product->categories->keyBy('id')->toArray();
				$arrCategoriId = array_keys($categories);
				$productsToCategory = (new ShopProduct)->getProductsToCategory($arrCategoriId, $limit = sc_config('product_relation'), $opt = 'random');
				
				$ptag1  = explode(',', $product->tags);
				$tags = Tags::productTags($ptag1);
				
				
				//Check product available
				return view($this->templatePath . '.shop_product_detail',
				array(
				'title' => $product->name,
				'description' =>  ($ShopProductDescription[0]['meta_description'] != null) ? $ShopProductDescription[0]['meta_description'] : $product->description,
				'keyword' => ($ShopProductDescription[0]['meta_keyword'] != null) ? $ShopProductDescription[0]['meta_keyword'] : $product->keyword,		
				'product' => $product,
				'attributesGroup' => ShopAttributeGroup::all()->keyBy('id'),
				'productsToCategory' => $productsToCategory,
				'og_image' => url($product->getImage()),
				'layout_page' => 'product_detail',
				'tags'=>  $tags ?? [],
				'publisher'=>  $admin ?? [],
				'related' => $related ?? '',
				'plan' => $plan ?? [],
				'files' => $files ?? [],
				)
				);
				} else {
				return $this->itemNotFound();
			}
			
		}
		/**
			* Get product info
			* @param  [int] $id [description]
			* @return [json]     [description]
		*/
		public function productInfo()
		{
		$id = request('id') ?? 0;
		$product = (new ShopProduct)->getProduct($id);
		$product['showPrice'] = $product->showPrice();
		$product['brand_name'] = $product->brand->name;
		$showImages = '
		<div class="carousel-inner">
		<div class="view-product item active"  data-slide-number="0">
		<img src="' . asset($product->getImage()) . '" alt="">
		</div>';
		
		if ($product->images->count()) {
		foreach ($product->images as $key => $image) {
		$showImages .= '<div class="view-product item"  data-slide-number="' . ($key + 1) . '">
		<img src="' . asset($image->getImage()) . '" alt="">
		</div>';
		}
		}
		$showImages .= '</div>';
		if ($product->images->count()) {
		$showImages .= '<a class="left item-control" href="#similar-product" data-slide="prev">
		<i class="fa fa-angle-left"></i>
		</a>
		<a class="right item-control" href="#similar-product" data-slide="next">
		<i class="fa fa-angle-right"></i>
		</a>';
		}
		
		$availability = '';
		if (sc_config('show_date_available') && $product->date_available >= date('Y-m-d H:i:s')) {
		$availability .= $product->date_available;
		} elseif ($product->stock <= 0 && sc_config('product_buy_out_of_stock') == 0) {
		$availability .= trans('product.out_stock');
		} else {
		$availability .= trans('product.in_stock');
		}
		$product['availability'] = $availability;
		$product['showImages'] = $showImages;
		$product['url'] = $product->getUrl();
		return response()->json($product);
		
		}
		
		/**
		* [brands description]
		* @param  Request $request [description]
		* @return [type]           [description]
		*/
		public function getBrands(Request $request)
		{
		$sortBy = null;
		$sortOrder = 'asc';
		$filter_sort = request('filter_sort') ?? '';
		$filterArr = [
		'name_desc' => ['name', 'desc'],
		'name_asc' => ['name', 'asc'],
		'sort_desc' => ['sort', 'desc'],
		'sort_asc' => ['sort', 'asc'],
		'id_desc' => ['id', 'desc'],
		'id_asc' => ['id', 'asc'],
		];
		if (array_key_exists($filter_sort, $filterArr)) {
		$sortBy = $filterArr[$filter_sort][0];
		$sortOrder = $filterArr[$filter_sort][1];
		}
		
		$itemsList = (new ShopBrand)->getBrands($limit = sc_config('item_list'), $opt = 'paginate', $sortBy, $sortOrder);
		return view($this->templatePath . '.shop_item_list',
		array(
		'title' => trans('front.brands'),
		'itemsList' => $itemsList,
		'keyword' => '',
		'description' => '',
		'layout_page' => 'item_list',
		'filter_sort' => $filter_sort,
		));
		}
		
		/**
		* [productToBrand description]
		* @param  [string] $alias [description]
		* @return [type]       [description]
		*/
		public function productToBrand($alias)
		{
		$sortBy = null;
		$sortOrder = 'asc';
		$filter_sort = request('filter_sort') ?? '';
		$filterArr = [
		'price_desc' => ['price', 'desc'],
		'price_asc' => ['price', 'asc'],
		'sort_desc' => ['sort', 'desc'],
		'sort_asc' => ['sort', 'asc'],
		'id_desc' => ['id', 'desc'],
		'id_asc' => ['id', 'asc'],
		];
		if (array_key_exists($filter_sort, $filterArr)) {
		$sortBy = $filterArr[$filter_sort][0];
		$sortOrder = $filterArr[$filter_sort][1];
		}
		
		$brand = ShopBrand::where('alias', $alias)->first();
		if($brand) {
		return view($this->templatePath . '.shop_products_list',
		array(
		'title' => $brand->name,
		'description' => '',
		'keyword' => '',
		'layout_page' => 'product_list',
		'products' => $brand->getProductsToBrand($brand->id, $limit = sc_config('product_list'), $opt = 'paginate', $sortBy, $sortOrder),
		'filter_sort' => $filter_sort,
		)
		);
		} else {
		return $this->itemNotFound();
		}
		}
		
		/**
		* [vendors description]
		* @return [type]           [description]
		*/
		public function getVendors()
		{
		$sortBy = null;
		$sortOrder = 'asc';
		$filter_sort = request('filter_sort') ?? '';
		$filterArr = [
		'name_desc' => ['name', 'desc'],
		'name_asc' => ['name', 'asc'],
		'sort_desc' => ['sort', 'desc'],
		'sort_asc' => ['sort', 'asc'],
		'id_desc' => ['id', 'desc'],
		'id_asc' => ['id', 'asc'],
		];
		if (array_key_exists($filter_sort, $filterArr)) {
		$sortBy = $filterArr[$filter_sort][0];
		$sortOrder = $filterArr[$filter_sort][1];
		}
		
		$itemsList = (new ShopVendor)->getVendors($limit = sc_config('item_list'), $opt = 'paginate', $sortBy, $sortOrder);
		
		return view($this->templatePath . '.shop_item_list',
		array(
		'title' => trans('front.vendors'),
		'itemsList' => $itemsList,
		'keyword' => '',
		'description' => '',
		'layout_page' => 'item_list',
		'filter_sort' => $filter_sort,
		));
		}
		
		/**
		* [productToVendor description]
		* @param  [string] alias [description]
		* @param  [type] $id   [description]
		* @return [type]       [description]
		*/
		public function productToVendor($alias)
		{
		$sortBy = null;
		$sortOrder = 'asc';
		$filter_sort = request('filter_sort') ?? '';
		$filterArr = [
		'price_desc' => ['price', 'desc'],
		'price_asc' => ['price', 'asc'],
		'sort_desc' => ['sort', 'desc'],
		'sort_asc' => ['sort', 'asc'],
		'id_desc' => ['id', 'desc'],
		'id_asc' => ['id', 'asc'],
		];
		if (array_key_exists($filter_sort, $filterArr)) {
		$sortBy = $filterArr[$filter_sort][0];
		$sortOrder = $filterArr[$filter_sort][1];
		}
		
		$vendor = ShopVendor::where('alias', $alias)->first();
		if ($vendor) {
		return view($this->templatePath . '.shop_products_list',
		array(
		'title' => $vendor->name,
		'description' => '',
		'keyword' => '',
		'layout_page' => 'product_list',
		'products' => $vendor->getProductsToVendor($vendor->id, $limit = sc_config('product_list'), $opt = 'paginate', $sortBy, $sortOrder),
		'filter_sort' => $filter_sort,
		)
		);
		} else {
		return $this->itemNotFound();
		}
		
		
		}
		
		/**
		* [search description]
		* @param  Request $request [description]
		* @return [type]           [description]
		*/
		public function search(Request $request)
		{
		$sortBy = null;
		$sortOrder = 'asc';
		$filter_sort = request('filter_sort') ?? '';
		$filterArr = [
		'price_desc' => ['price', 'desc'],
		'price_asc' => ['price', 'asc'],
		'sort_desc' => ['sort', 'desc'],
		'sort_asc' => ['sort', 'asc'],
		'id_desc' => ['id', 'desc'],
		'id_asc' => ['id', 'asc'],
		];
		if (array_key_exists($filter_sort, $filterArr)) {
		$sortBy = $filterArr[$filter_sort][0];
		$sortOrder = $filterArr[$filter_sort][1];
		}
		$keyword = request('keyword') ?? '';
		return view($this->templatePath . '.search',
		array(
		'title' => trans('front.search') . ': ' . $keyword,
		'products' => (new ShopProduct)->getSearch($keyword, $limit = sc_config('product_list'), $sortBy, $sortOrder),
		'layout_page' => 'product_list',
		'filter_sort' => $filter_sort,
		'keyword' => $keyword,
		));
		}
		
		/**
		* Process click banner
		*
		* @param   [int]  $id  
		*
		*/
		public function clickBanner($id){
		$banner = ShopBanner::find($id);
		if($banner) {
		$banner->click +=1;
		$banner->save();
		return redirect(url($banner->url??'/'));
		}
		return redirect(url('/'));
		}
		
		
		public function DownloadCounter($id,$email){
		$url = route('home');
		$download = ProductFiles::find($id);
		$downloadData = array(
		'file_id' => $id,
		'email' => $email,
		'created_at' => date('Y-m-d H:i:s'),
		);            
		Countdownloads::create($downloadData);
		return redirect(url($url.$download->name));
		echo "<script>window.close();</script>";
		//return Response::download($url.$download->name, 'filename.pdf', $headers); 
		}
		
		public function landing()
		{	
		$catlist = (new ShopCategory)->getCategories($parent = 0);
		//$cats = (new ShopCategory)->getIdCategories($category->id);
		return view($this->templatePath . '.landing',
		array(
		'products_feat' => (new ShopProduct)->getFeatured($type = null, $limit =4),   
		'catlist' => $catlist,			
		'title' => 'Landing',
		'description' => '',
		'keyword' => '',
		)
		);
		}
		}
				