<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{

	protected $primaryKey = 'id';
	
	public $timestamps = true;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'user_id',
		'push_type',
		'push_id',
		'group',
		'to',
		'seen',
		'created_at',
	];

	/**
	 * The rules applied when creating a item
	 */
	public static $insertRules = [
			'user_id' => 'required',
	];			


}
