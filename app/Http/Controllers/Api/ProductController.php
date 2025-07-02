<?php
namespace App\Http\Controllers\Api;

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
use App\Models\ShopVendor;
use App\Models\Tags;
use App\Models\ProductFiles;
use App\Models\License;
use Illuminate\Http\Request;
use Validator;
use App\Admin\Admin;
use Stripe;

class ProductController extends Controller
{

	public function postCreate()
    {

    	$user = Admin::user();
        $data = request()->all();        
        $langFirst = array_key_first(sc_language_all()->toArray()); //get first code language active
        $data['alias'] = !empty($data['name'])?$data['name']:'';
        $data['alias'] = sc_word_format_url($data['alias']);
         $data['alias'] = sc_word_limit($data['alias'], 100);

         $validator = Validator::make($data, [	       
            'tags' => 'required|array',
            'image' => 'required',
            'extension' => 'required',
            'license' => 'required|array',
            'sort' => 'numeric|min:0',
            'name' => 'required|string|max:100',
            'content' => 'required|string',
            'category' => 'required',
            'sku' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,sku',
            'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|unique:shop_product,alias|string|max:100',
            'files' => 'required|array',
      	 ], [
            'name.required' => trans('validation.required', ['attribute' => trans('product.name')]),
            'content.required' => trans('validation.required', ['attribute' => trans('product.content')]),
            'category.required' => trans('validation.required', ['attribute' => trans('product.category')]),
            'sku.regex' => trans('product.sku_validate'),
            'alias.regex' => trans('product.alias_validate'),
        ]);

      	 if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      }

        $tags = implode(',', $data['tags']);
        $category = $data['category'] ?? [];
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
            'sku' => $data['sku'],
            'cost' => $data['cost']??0,
            'stock' => $data['stock']??0,
            'type' => 0,
            'kind' => 0,
            'alias' => $data['alias'],
            'virtual' => 0,
            'date_available' => !empty($data['date_available']) ? $data['date_available'] : null,
            'image' => $data['image']??'',
            'status' => (!empty($data['status']) ? 1 : 0),
            'featured' => (!empty($data['featured']) ? 1 : 0),
            'sort' => (int) $data['sort'],
            'license' => $data['license'],
            'tags' => $tags,
        ];
		
		
		var_dump($dataInsert);
		
		die;
		
		
        //insert product
        $product = ShopProduct::create($dataInsert);

        //Insert category
        if ($category && in_array($data['kind'], [SC_PRODUCT_SINGLE, SC_PRODUCT_BUILD])) {
            $product->categories()->attach($category);
        }

         $dataDes = [
            'product_id' => $product->id,
            'lang' => 'en',
            'name' => $data['name'],
            'keyword' => $data['keyword'],
            'description' => $data['description'],
            'content' => $data['content'],
        ];

        ShopProductDescription::insert($dataDes);


    }
    public function edit($id)
    { 
        $product = ShopProduct::find($id);
        $files = ProductFiles::where('pro_id',$id)->get();

        if ($product === null) {
             return response()->json(['message'=>'No product'], 404);
        }
         return response()->json(['product'=>$product, 'files' => $files], 200);
    }

   
}
?>