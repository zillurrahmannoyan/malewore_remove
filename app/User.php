 <?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
 protected $fillable = [
        'fname',
        'lname',
        'email',
        'mobile_number',
        'province',
        'city',
        'bio',
        'role',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The rules applied when creating a item
     */
    public static $insertRules = [
        'fname'    => 'required|max:32',
        'lname'    => 'required|max:32',
        'email'    => 'required|email|unique:users,email',
        'username' => 'required|unique:users,username|min:4|max:32',
        'password' => 'required|min:4|max:32',
        'role' => 'required',
    ];

    /**
     * The rules applied when updating a item
     */
    public static $forgotPassword = [
        'email' => 'required|email|max:64|exists:users,email',
    ];

    public static $newPassword = [
        'new_password'              => 'required|min:4|max:64|confirmed',
        'new_password_confirmation' => 'required|min:4',
    ];

    public function scopeSearch($query, $seach = array()) {

        if( isset($seach['s']) ) {
            $query->where('username', 'LIKE', '%'.$seach['s'].'%')
                  ->orWhere('email', 'LIKE', '%'.$seach['s'].'%');
        }
        if( isset($seach['role']) ) {
            $query->where('role', $seach['role']);
        }

        return $query;
    }
}
