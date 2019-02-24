 <?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadDetail extends Model
{

	protected $primaryKey = 'id';
	
	public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lead_detail';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
        'lead_id',
        'field_number',
        'value'
    ];

	/**
	 * The rules applied when creating a item
	 */
	public static $insertRules = [];		


    public function seo() {
        return $this->hasOne('App\Seo');
    }

    public function scopeSearch($query, $seach = array()) {

    	if( isset($seach['s']) ) {
			$query->where('title', 'LIKE', '%'.$seach['s'].'%');
		}
    	if( isset($seach['type']) ) {
    		if( $seach['type'] != 'all')
			$query->where('type', $seach['type']);
		}
    	if( isset($seach['status']) ) {
    		if( $seach['status'] != 'all')
			$query->where('status', $seach['status']);
		}

        return $query;
	}
}
