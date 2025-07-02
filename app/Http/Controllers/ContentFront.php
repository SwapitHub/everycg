<?php
#app/Http/Controller/ContentFront.php
namespace App\Http\Controllers;

use App\Models\ShopEmailTemplate;
use App\Models\ShopNews;
use App\Models\ShopPage;
use App\Models\Faq;
use App\Models\ShopSubscribe;
use Illuminate\Http\Request;
use App\Models\Contactus;
use Validator;
use App\Admin\Admin;
use App\Admin\Models\AdminPermission;
use App\Admin\Models\AdminRole;
use App\Admin\Models\AdminUser;
use Illuminate\Support\Facades\Auth;

class ContentFront extends GeneralController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [getContact description]
     * @return [type] [description]
     */
    public function getContact()
    {
        
        return view(
            $this->templatePath . '.shop_contact',
            array(
                'title' => trans('front.contact'),
                'description' => '',
                'keyword' => '',
                'og_image' => '',
                'pageData' => ShopPage::where('alias', 'contact')->where('status', 1)->first(),
            )
        );
    }

    /**
     * [postContact description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postContact(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'title' => 'required',
            'content' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ], [
            'name.required' => trans('validation.required'),
            'content.required' => trans('validation.required'),
            'title.required' => trans('validation.required'),
            'email.required' => trans('validation.required'),
            'email.email' => trans('validation.email'),
            'phone.required' => trans('validation.required')
        ]);
        //Send email
        $data = $request->all();
        $data['content'] = str_replace("\n", "<br>", $data['content']);

        $dataContact = array(
                'name' => $data['name'],
                'title' => $data['title'],
                'content' => $data['content'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'created_at' => date('Y-m-d H:i:s'),
            );
        
        Contactus::create($dataContact);

        if (sc_config('contact_to_admin')) {
            $checkContent = (new ShopEmailTemplate)
                ->where('group', 'contact_to_admin')
                ->where('status', 1)
                ->first();
            if ($checkContent) {
                $content = $checkContent->text;
                $dataFind = [
                    '/\{\{\$title\}\}/',
                    '/\{\{\$name\}\}/',
                    '/\{\{\$email\}\}/',
                    '/\{\{\$phone\}\}/',
                    '/\{\{\$content\}\}/',
                ];
                $dataReplace = [
                    $data['title'],
                    $data['name'],
                    $data['email'],
                    $data['phone'],
                    $data['content'],
                ];
                $content = preg_replace($dataFind, $dataReplace, $content);
                $data_email = [
                    'content' => $content,
                ];

                $config = [
                    'to' => sc_store('admin_email'),
                    'replyTo' => $data['email'],
                    'subject' => $data['title'],
                ];
                sc_send_mail('mail.contact_to_admin', $data_email, $config, []);
            }
        }

        return redirect()
            ->route('contact')
            ->with('success', trans('front.thank_contact'));
    }

    /**
     * Render page
     * @param  [string] $alias
     */
    public function pages($alias = null)
    {
        $page = $this->getPage($alias);
        if ($page) {
            return view(
                $this->templatePath . '.shop_page',
                array(
                    'title' => $page->title,
                    'description' => $page->description,
                    'keyword' => $page->keyword,
                    'page' => $page,
                )
            );
        } else {
            return $this->pageNotFound();
        }
    }

    /**
     * Get page info
     * @param  [string] $alias [description]
     * @return [type]      [description]
     */
    public function getPage($alias = null)
    {
        return ShopPage::where('alias', $alias)
            ->where('status', 1)
            ->first();
    }

    /**
     * Render news
     * @return [type] [description]
     */
    public function news()
    {
        $news = (new ShopNews)
            ->getItemsNews($limit = 3, $opt = 'paginate');
        return view(
            $this->templatePath . '.shop_news',
            array(
                'title' => trans('front.blog'),
                'description' => sc_store('description'),
                'keyword' => sc_store('keyword'),
                'news' => $news, 
                'pageData' => ShopPage::where('alias', 'news')->where('status', 1)->first(),
            )
        );
    }

    /**
     * News detail
     *
     * @param   [string]  $alias 
     * @param   [type]  $id
     *
     * @return  view  
     */
    public function newsDetail($alias)
    {
        $news_currently = ShopNews::where('alias', $alias)->first();
        if ($news_currently) {
            $rltdids  = explode(',', $news_currently->related);
            $related = ShopNews::relatedNews($rltdids);            
            $title = ($news_currently) ? $news_currently->title : trans('front.not_found');
            return view(
                $this->templatePath . '.shop_news_detail',
                array(
                    'title' => $title,
                    'news_currently' => $news_currently,
                    'description' => sc_store('description'),
                    'keyword' => sc_store('keyword'),
                    'blogs' => (new ShopNews)->getItemsNews($limit = 4),
                    'og_image' => $news_currently->getImage(),
                    'related' => $related ?? '',
                )
            );
        } else {
            return view(
                $this->templatePath . '.notfound',
                array(
                    'title' => trans('front.not_found'),
                    'description' => '',
                    'keyword' => sc_store('keyword'),
                    'msg' => trans('front.item_not_found'),
                )
            );
        }
    }

    /**
     * email subscribe
     * @param  Request $request
     * @return json
     */
	 
	 ## subscribe
	 function emailsubs(Request $request)
	 {
		$data   = $request->all();
        $validator = Validator::make($data, [
        'subscribe_email' => 'required|email:rfc,dns|unique:shop_subscribe,email',
            ], [
            'email.required' => trans('validation.required'),
            'email.email'    => trans('validation.email'),
        ]);  
		
		if ($validator->fails()) {
		$output['res'] ='error';
		$output['msg'] = 'already subscribed, try another email';
        } 
		else
		{
	      ShopSubscribe::insert(['email' => $data['subscribe_email']]);
		  $output['res'] ='success';
		  $output['msg'] ='successfully Subscribed';
		}
		
		
		echo json_encode($output);
		
		
	 }
	 
    public function emailSubscribe(Request $request)
    {
        $data   = $request->all();
        $validator = Validator::make($data, [
        'subscribe_email' => 'required|email:rfc,dns|unique:shop_subscribe,email',
            ], [
            'email.required' => trans('validation.required'),
            'email.email'    => trans('validation.email'),
        ]); 

        if ($validator->fails()) {
             return redirect(url()->previous() .'#newsletter')
                ->withErrors($validator)
                ->withInput($data);
        }         
       
        $checkEmail = ShopSubscribe::where('email', $data['subscribe_email'])
            ->first();
        if (!$checkEmail) {
            ShopSubscribe::insert(['email' => $data['subscribe_email']]);
        }
        return redirect()->back()
            ->with(['success' => trans('subscribe.subscribe_success')]);
    }

    public function getFaq()
    {
        $faqs = Faq::where('status',1)->orderBy('sort', 'ASC')->get();
        if ($faqs) {
            $page = ShopPage::where('alias', 'faq')->where('status', 1)->first();
            return view(
                $this->templatePath . '.faq',
                array(
                    'title' => 'FAQ',
                    'faqs' => $faqs,
                     'pageData' => $page,
                    'description' => $page->description,
                    'keyword' => $page->keyword
                )
            );
        }

    }

    public function vendorRegister()
    {
        return view(
                $this->templatePath . '.vendor_reg',
                array(
                    'title' => 'Vendor Register',
                    'description' => '',
                    'keyword' => ''
                )
            );

    }

    public function createVendor()
    {    
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'name' => 'required|string|max:100',
            'username' => 'required|regex:/(^([0-9A-Za-z@\._]+)$)/|unique:admin_user,username|string|max:100|min:3',
            'password' => 'required|string|max:60|min:6|confirmed',
        ], [
            'username.regex' => trans('user.username_validate'),
        ]);

        if ($validator->fails()) {  
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $dataInsert = [
            'name' => $data['name'],
            'username' => strtolower($data['username']),
            'password' => bcrypt($data['password']),
        ];

        $user = AdminUser::createUser($dataInsert);

        $roles = array(7);
        $permission = array(2,19,5,23);
        //Insert roles
        if ($roles) {
            $user->roles()->attach($roles);
        }
        //Insert permission
        if ($permission) {
            $user->permissions()->attach($permission);
        }      

        return redirect('sc_admin')
            ->with(['success' => 'You are successfully Register as vendor. Please login to your account!']);    
    }
     public function loginVendor(Request $request)
    {    
        $user = Auth::user();
        //$credentials = $request->only([$this->username(), 'password']);
         //$credentials =$request->only([$user->username, $user->userpwd]);
         if ($this->guard()->attempt(['username' => $user->username, 'password' => $user->userpwd])) {  
             return $this->sendLoginResponse($request);
         }
         else
         {
            echo "not";
         }
         /*$remember = $request->get('remember', false);  
       if ($this->guard()->attempt($credentials, $remember)) {
            return $this->sendLoginResponse($request);
        }*/
    }

    protected function sendLoginResponse(Request $request)
    {

        $request->session()->regenerate();
        return redirect()->intended($this->redirectPath())->with(['success' => trans('admin.login_successful')]);
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function verifyMail($id)
    {
         $email = base64_decode(strtr($id, '-_', '+/'));

         $vendorUser = AdminUser::where('email',$email)->first();
         if(!empty($vendorUser))
         {
             $vendorUpdate = [
                'emailverfy' => 1
                
            ];
            $vendorUser->update($vendorUpdate);
            return redirect()
            ->route('home')
            ->with('success', 'Email successfully verified!');
         }  
         else
         {
           return redirect()
            ->route('home')
            ->with('error', 'Email not exist in the database!');
         }

           


    }
}
