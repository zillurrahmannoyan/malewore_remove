 <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail;
use App\User;
use App\Ad;
use App\Comment;
use App\Feedback;
use App\Notification;

class AccountController extends Controller
{
    protected $user;
    protected $ad;
    protected $comment;
    protected $feedback;
    protected $notification;

    public function __construct(User $user, Ad $ad, Comment $comment, Feedback $feedback, Notification $notification)
    {
        $this->user_id      = (Auth::check()) ? Auth::user()->id : '';

        $this->user = $user;
        $this->ad = $ad;
        $this->comment = $comment;
        $this->feedback = $feedback;
        $this->notification = $notification;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data['info'] = Auth::User();

        $search = array(
            'sort' => Input::get('sort'),
        );

        $data['ads'] = $this->ad->search($search)->where('user_id', $this->user_id)->paginate(5);

        $data['feedbacks'] = $this->feedback->where('account_id', $this->user_id)->orderBy('id', 'DESC')->take(10)->get();

        $data['notifications'] = $this->notification->where('to', $this->user_id)->orderBy('id', 'DESC')->take(5)->get();

        $data['new_notifications'] = $this->notification->where('to', $this->user_id)->where('seen', '')->count();

        return view('frontend.account.index', $data);
    }

    public function user($username ='')
    {

        $data['info'] = $user = $this->user->where('username', $username)->first();
        $data['user_id'] = $this->user_id;
 
        if( Input::get('op') )
        {
            $validator = Validator::make(Input::all(), Feedback::$insertRules);

            if($validator->passes()) {

                $feedback = $this->feedback;

                $feedback->fill(Input::all());    

                if($this->user_id) {                    
                    $feedback->name = Auth::user()->username;
                } else {                    
                    $feedback->name = Input::get('name') ? Input::get('name') : 'Anonymous';     
                }

                $feedback->user_id    = $this->user_id;
                $feedback->account_id = $user->id;

                $feedback->created_at = date('Y-m-d H:i:s');
                                    
                if( $feedback->save() ) {

                    $notif = [
                        'user_id' => $this->user_id,
                        'push_type' => 'user_comment',
                        'push_id' => $feedback->id,
                        'group' => 'user',
                        'to' => $user->id
                    ];
                    \App\Notification::firstOrCreate($notif);

                    return Redirect::route('account.user', $username)
                                   ->with('success','Your feedback to the seller has been posted!');
                } 
            }

            return Redirect::route('account.user', $username)
                           ->withErrors($validator)
                           ->withInput(); 
        }


        $search = array(
            'sort' => Input::get('sort'),
            'category' => ''
        );

        $data['ads'] = $this->ad->search($search)->where('user_id', $user->id)->paginate(5);

        $data['feedbacks'] = $this->feedback->where('account_id', $user->id)->orderBy('id', 'DESC')->take(10)->get();

        return view('frontend.account.user', $data);
    }

    public function signin()
    {

        if( Auth::check() )
            return Redirect::Route('account.index');

        if(Input::get('op')) {

            $insertRules = [
                'username' => 'required',
                'password' => 'required',
            ];

            $validator = Validator::make(Input::all(), $insertRules);

            if($validator->passes()) {

                $field = filter_var(Input::get('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
                $remember = (Input::has('remember')) ? true : false;
                
                $credentials = [
                    $field      => Input::get('username'),
                    'password'  => Input::get('password'),                
                ];

                if(Auth::attempt($credentials, $remember)) {               
                    $user_id = Auth::user()->id;
                    Session::put('user_id', $user_id);

                    $u = $this->user->find($user_id);
                    $u->last_login = date('Y-m-d H:i:s');
                    $u->save();

                    if( Input::get('redirect_to') ) {
                        return Redirect::to( Input::get('redirect_to') );
                    }
                    
                    return Redirect::Route('account.index');

                } 

                return Redirect::route('account.signin', query_vars())
                               ->with('error','Invalid username or password')
                               ->withInput();
            }

            return Redirect::route('account.signin', query_vars())
                           ->withErrors($validator)
                           ->withInput(); 
        }

        return view('frontend.account.login');
    }

    //--------------------------------------------------------------------------

    public function signout()
    {
        Auth::logout();
        Session::flash('success','You are now logged out!');
        return Redirect::route('account.signin');
    }

    //--------------------------------------------------------------------------

    public function editProfile()
    {
        $id = $this->user_id;

        if( Input::get('op') )
        {

            if(Input::get('password')) {
                $updateRules = [
                    'email'         => 'required|email|max:64|unique:users,email,'.$id.',id',
                    'fname'         => 'max:25',
                    'lname'         => 'max:25',
                    'mobile_number' => 'required|numeric',
                    'province'      => 'required',
                    'city'          => 'required',
                    'password'      => 'min:4|max:64',
                    ];          
            } else {
                $updateRules = [
                    'email'         => 'required|email|max:64|unique:users,email,'.$id.',id',
                    'fname'         => 'max:25',
                    'lname'         => 'max:25',
                    'mobile_number' => 'required|numeric',
                    'province'      => 'required',
                    'city'          => 'required',
                ];          
            }

            $validator = Validator::make(Input::all(), $updateRules);

            if($validator->passes()) {

                $user = $this->user->findOrFail($id);

                $user->fill(Input::all());          

                if( Input::get('password') ) {
                    $user->password = Hash::make( Input::get('password') );
                }
                                    
                if( $user->save() ) {

                    return Redirect::route('account.index')
                                   ->with('success','Your account has been updated!');
                } 
            }

            return Redirect::route('account.edit-profile')
                           ->withErrors($validator)
                           ->withInput(); 
        }

        $data['info'] = $this->user->findOrFail($id);

        return view('frontend.account.edit-profile', $data);

    }

    //--------------------------------------------------------------------------

    public function uploadCover()
    {
        $type = Input::get('type');

        $id = $this->user_id;

        $updateRules = [
            'upload' => 'required|mimes:jpeg,jpg,png|max:10000',
        ];          

        $validator = Validator::make(Input::all(), $updateRules);

        if($validator->passes()) {

            if(Input::hasFile('upload')){
                
                $user = $this->user->findOrFail($id);

                $fileUpload = Input::file('upload');
                $imageFile = $fileUpload->getRealPath();
                $ext = $fileUpload->getClientOriginalExtension();

                $path = 'uploads/images/users/'.$id.'-'.$type.'.png';  

                if($type == 'cover_photo') {
                    $w = 680;
                    $h = 302;
                } else {                    
                    $w = 275;
                    $h = 275;
                }

                if(file_exists($path)) unlink($path);     
                $img = \Image::make($imageFile)->resize($w, $h, function ($constraint) {
                    // $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($path);
                compress($path, $path, 50);

                $user->$type = $path;
            } 
            
            if( $user->save() ) {             
                $type = str_replace('_', ' ', $type);
                return Redirect::route('account.index')
                               ->with('success', "Your $type has been updated!");
            }
        }

        return Redirect::route('account.index')
                       ->withErrors($validator)
                       ->withInput(); 

    }

    //--------------------------------------------------------------------------

    public function register()
    {

        if( Auth::check() ) {
            return Redirect::route('account.index');            
        }

        if( Input::get('op') )
        {

            $insertRules = [
                'fname'    => 'required|max:32',
                'lname'    => 'required|max:32',
                'email'    => 'required|email|unique:users,email',
                'username' => 'required|unique:users,username|min:4|max:32',
                'password' => 'required|min:4|max:32',
            ];

            $validator = Validator::make(Input::all(), $insertRules);

            if($validator->passes()) {
                $user = $this->user;

                $user->fill(Input::all());          
                $user->password = Hash::make( Input::get('password') );
                $user->status   = Input::get('status', 1);
                $user->username = Input::get('username');
                $user->role     = 'user';
                $user->last_login = date('Y-m-d H:i:s');
                    
                if( $user->save() ) {

                    Auth::loginUsingId($user->id);

                    $notif = [
                        'user_id' => $user->id,
                        'push_type' => 'account_register',
                        'push_id' => $user->id,
                        'group' => 'admin',
                    ];
                    \App\Notification::firstOrCreate($notif);


                    if( Input::get('redirect_to') ) {
                        return Redirect::to( Input::get('redirect_to') );
                    }

                    return Redirect::to( url('account/register/thank-you') )
                                   ->with('success','Your account has been registered!');
                } 
            }

            return Redirect::route('account.register', query_vars())
                           ->withErrors($validator)
                           ->withInput(); 

        }
  
        return view('frontend.account.register');
    }

    public function seen()
    {
        $id[] = (string)$this->user_id;
        $this->notification->where('seen', '')
                             ->where('to', $this->user_id)
                             ->update(['seen' => json_encode($id)]);
    }
}
