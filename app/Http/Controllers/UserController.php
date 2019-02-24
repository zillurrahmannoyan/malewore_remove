 <?php

namespace App\Http\Controllers;

use Validator, Redirect, Input, Auth, Hash;
use Illuminate\Http\Request;

use App\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    protected $user;

    public function __construct(User $user)
    {
        $this->user_id      = (Auth::check()) ? Auth::user()->id : '';
        $this->user         = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $role = Input::get('role');

        $search = array ('s' => Input::get('s'), 'role' => $role);

        $data['rows'] = $this->user->search($search)->orderBy('id', 'DESC')->paginate(15);

        $data['count'] = $this->user->search($search)->count();
        $data['all'] = $this->user->count();
        $data['admins'] = $this->user->where('role', 'admin')->count();
        $data['users'] = $this->user->where('role', 'user')->count();

        return view('backend.users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function add()
    {
        return view('backend.users.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

        $validator = Validator::make(Input::all(), User::$insertRules);

        if($validator->passes()) {
            $user = $this->user;

            $user->fill(Input::all());          
            $user->password = Hash::make( Input::get('password') );
            $user->status   = Input::get('status', 0);
            $user->username   = Input::get('username');
                
            if( $user->save() ) {

                if(Input::hasFile('upload')){

                    $fileUpload = Input::file('upload');
                    $imageFile = $fileUpload->getRealPath();
                    $ext = $fileUpload->getClientOriginalExtension();
                    $path = 'uploads/images/users/'.$user->id.'.'.$ext;

                    if(file_exists($path)) chmod($path, 0777);                        
                    \Image::make($imageFile)->save($path);
                    $user->photo = $path;
                    $user->save();
                } 

                return Redirect::route('backend.users.index')
                               ->with('success','New user has been added!');
            } 
        }

        return Redirect::route('backend.users.add')
                       ->withErrors($validator)
                       ->withInput();   

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data['info'] = $this->user->findOrFail($id);
        return view('backend.users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        if(Input::get('password')) {
            $updateRules = [
                'username'          => 'required|min:4|max:25|unique:users,username,'.$id.',id',
                'email'             => 'required|email|max:64|unique:users,email,'.$id.',id',
                'fname'             => 'required|max:25',
                'lname'             => 'required|max:25',
                'password'          => 'min:4|max:64',
                'role'          => 'required',
                ];          
        } else {
            $updateRules = [
                'username'   => 'required|min:4|max:25|unique:users,username,'.$id.',id',
                'email'      => 'required|email|max:64|unique:users,email,'.$id.',id',
                'fname'      => 'required|max:25',
                'lname'      => 'required|max:25',
                'role'   => 'required',
            ];          
        }

        $validator = Validator::make(Input::all(), $updateRules);
        
        if($validator->passes()) {
            
            $user = $this->user->findOrFail($id);
            $user->fill(Input::all());
            $user->status = Input::get('status', 0);
            $user->username = Input::get('username');

            //check if there's an uploaded image
                                    
            if(Input::get('password')) {
                $user->password = Hash::make( Input::get('password') );
            }


            if(Input::hasFile('upload')){

                $fileUpload = Input::file('upload');
                $imageFile = $fileUpload->getRealPath();
                $ext = $fileUpload->getClientOriginalExtension();
                $path = 'uploads/images/users/'.$id.'.'.$ext;
                
                if(file_exists($path)) chmod($path, 0777);                        
                \Image::make($imageFile)->save($path);
                $user->photo = $path;
            } 

            if( $user->save() ) {              
                return Redirect::route('backend.users.edit', $id)
                               ->with('success','User has been updated!');
            } 

        } 

        return Redirect::route('backend.users.edit', $id)
                       ->withErrors($validator)
                       ->withInput();           
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
       if($id) {
            $this->user->findOrFail($id)->delete();
            return Redirect::route('backend.users.index')
                           ->with('success','User has been deleted');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function profile()
    {
        if(Input::get('op')) {

            $messages = array();
            
            if(Input::get('change_password')) {

                Validator::extend('passcheck', function($attribute, $value, $parameters) {
                    return Hash::check($value, Auth::user()->password); // Works for any form!
                });

                $messages = array(
                    'passcheck' => 'Your old password was incorrect',
                );

                $updateRules = [
                    'username'                  => 'required|min:4|max:25|unique:users,username,'.$this->user_id.',id',
                    'email'                     => 'required|email|max:64|unique:users,email,'.$this->user_id.',id',
                    'fname'                     => 'required|max:25',
                    'lname'                     => 'required|max:25',
                    'old_password'              => 'required|min:4|max:64|passcheck',
                    'new_password'              => 'required|min:4|max:64|confirmed',
                    'new_password_confirmation' => 'required|min:4',
                    ];          
            } else {
                $updateRules = [
                    'username'   => 'required|min:4|max:25|unique:users,username,'.$this->user_id.',id',
                    'email'      => 'required|email|max:64|unique:users,email,'.$this->user_id.',id',
                    'fname'      => 'required|max:25',
                    'lname'      => 'required|max:25',
                ];          
            }

            $validator = Validator::make(Input::all(), $updateRules, $messages);
            
            if($validator->passes()) {
                
                $user = $this->user->findOrFail($this->user_id);
                $user->fill(Input::all());

                if(Input::get('change_password') && Input::get('new_password') ) {
                    $user->password = Hash::make( Input::get('new_password') );
                }

                if(Input::hasFile('upload')){

                    $fileUpload = Input::file('upload');
                    $imageFile = $fileUpload->getRealPath();
                    $ext = $fileUpload->getClientOriginalExtension();
                    $path = 'uploads/images/users/'.$this->user_id.'.'.$ext;                    
                    if(file_exists($path)) chmod($path, 0777);                        
                    \Image::make($imageFile)->save($path);
                    $user->photo = $path;
                } 

                if( $user->save() ) {
                    return Redirect::route('backend.users.profile')
                                   ->with('success','Profile has been updated!');
                } 

            } else {
                return Redirect::route('backend.users.profile')
                               ->withErrors($validator)
                               ->withInput();           
            }
        }

        $data['info'] = $this->user->findOrFail($this->user_id);

        return view('backend.users.profile', $data);
    }
}

