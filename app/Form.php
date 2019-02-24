<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{

	protected $primaryKey = 'id';
	
	public $timestamps = true;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [];

	
	/**
	 * The rules applied when creating a item
	 */
	public static $insertRules = [];			

    public function scopeVisible($query) {

        if(\Auth::User()->role == 'chemist') {            
            $query->where('user_id', \Auth::User()->id);
        }
        return $query;
    
    }
    
    public function scopeSearch($query, $search = array()) {

    	if( isset($search['s']) ) {
			$query->where('content', 'LIKE', '%'.$search['s'].'%');
		}
        if( isset($search['status']) ) {         
            $query->where('status', $search['status']);
        } 

        return $query;
	}

}
