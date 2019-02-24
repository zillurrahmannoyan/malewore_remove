 <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Setting;



class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $user;
    protected $setting;


    public function __construct(User $user, Setting $setting)
    {
        $this->user = $user;
        $this->setting = $setting;
    }

    //--------------------------------------------------------------------------

    public function login()
    {
        if( Input::get('request') ) {

            $rules = array(
                'name' => 'required',
                'email' => 'required',
                'message' => 'required',
            );

            $validator = Validator::make(Input::all(), $rules);

            if($validator->passes()) {

                $data['name']  = Input::get('name');
                $data['email'] = Input::get('email');
                $data['msg']   = Input::get('message');
                $data['to']    = $this->setting->getInfo('admin_email');
                $data['carbon_copy'] = explode(',', str_replace(' ', '', $this->setting->getInfo('carbon_copy_email')));                

                $data['email_title']   = 'Request an SDS';
                $data['email_subject'] = 'Request an SDS';

                Mail::send('emails.request', $data, function($message) use ($data)
                {
                    $message->from($data['email'], $data['email_title']);
                    $message->to($data['carbon_copy'], $data['name'])->subject($data['email_subject']);
                });            

                return Redirect::route('backend.auth.login')
                               ->with('req-success','Your request has been successfully sent!');
            } else {
                    
                return Redirect::route('backend.auth.login')
                               ->withErrors($validator)
                               ->with('req-error', 'Please check the form below for errors')
                               ->withInput();
            }

        }

        if( Auth::check() ) {
            if(Auth::User()->role == 'admin') {                      
                return Redirect::Route('backend.dashboard.index');
            }

            return Redirect::Route('backend.sds.index');        
        }
    
        if(Input::get('op')) {

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

                if($u->role == 'admin') {                      
                    return Redirect::Route('backend.dashboard.index');
                }
    
                return Redirect::Route('backend.sds.index');
            } 

            return Redirect::route('backend.auth.login')
                           ->with('error','Invalid username or password')
                           ->withInput();
        }
        return view('auth.login');
    }

    //--------------------------------------------------------------------------

    public function unlock()
    {
        $email = Input::get('email');

        if( Auth::check() )
            return Redirect::Route('backend.dashboard.index');

        $data['user'] = $user = $this->user->where('email', $email)->first();

        if( !$user )
            return Redirect::Route('backend.auth.login');

        if(Input::get('op')) {

            $credentials = [
                'username'  => $user->username,
                'password'  => Input::get('password'),
            ];

            if(Auth::attempt($credentials, true)) {               
                return Redirect::Route('backend.dashboard.index');

            } 

            return Redirect::route('backend.auth.unlock', 'email='.$email)
                           ->with('error','Invalid password')
                           ->withInput();
        }

        return view('auth.unlock', $data);
    }

    //--------------------------------------------------------------------------

    public function lock()
    {
        $email = Auth::user()->email;
        Auth::logout();
        Session::flash('success','You are now logged out!');
        return Redirect::route('backend.auth.unlock', 'email='.$email);
    }

    //--------------------------------------------------------------------------

    public function logout()
    {
        Auth::logout();
        Session::flash('success','You are now logged out!');
        return Redirect::route('backend.auth.login');
    }

    //--------------------------------------------------------------------------
    
    public function forgotPassword($token ='')
    {

        $data['setting'] = $this->setting;
        $data['token'] = $token;
        
        $u = $this->user->where('forgot_password_token', $token)->first();

        if(!$u) return Redirect::route('backend.auth.login');

        if($token) {

            if(Input::get('op') ) {

                $validator = Validator::make(Input::all(), User::$newPassword);
    
                if($validator->passes()) {

                    $u->password = Hash::make(Input::get('new_password'));
                    $u->forgot_password_token = NULL;

                    if( $u->save() ) {              
                        
                        Auth::loginUsingId($u->id);

                        return Redirect::route('backend.dashboard.index')
                                       ->with('success','You have successfully changed your password.');

                    } 
                } else {
                        
                    return Redirect::route('backend.auth.forgot-password')
                                   ->withErrors($validator)
                                   ->withInput();
                }
            }

            return view('auth.forgot-password', $data);

        } else {

            if(Input::get('op') ) {

                $validator = Validator::make(Input::all(), User::$forgotPassword);
    
                if($validator->passes()) {

                    $token = str_random(64);
                    $email = Input::get('email');

                    $u = $this->user->where('email', $email)->first();
                    $u->forgot_password_token = $token;

                    if( $u->save() ) {              
                        $data['name']      = ucwords( $u->fname );
                        $data['email']     = $u->email;
                        $data['token_url'] = URL::route('backend.auth.forgot-password', $u->forgot_password_token);
                        $data['base_url']  = url();
                        $data['site_name'] = $site_name = ucwords($this->setting->getInfo('site_name'));

                        $data['email_support'] = 'michaelrafallo@gmail.com';
                        $data['email_title']   = $site_name.' Support';
                        $data['email_subject'] = $site_name.' Forgotten Password!';

                        Mail::send('emails.forgot-password', $data, function($message) use ($data)
                        {
                            $message->from($data['email_support'], $data['email_title']);
                            $message->to($data['email'], $data['name'])->subject($data['email_subject']);
                        });

                        return Redirect::route('backend.auth.forgot-password')
                                       ->with('success','Forgot password link has been sent to your email address. Please check your inbox or spam folder.');

                    } 
                } else {
                        
                    return Redirect::route('backend.auth.forgot-password')
                                   ->withErrors($validator)
                                   ->withInput();
                }
            }

            return view('auth.forgot-password', $data);

        }


    }

    //--------------------------------------------------------------------------


}
