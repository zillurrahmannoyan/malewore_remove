 <?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{

	protected $primaryKey = 'id';
	
	public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lead';

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


    public function user() {
        return $this->belongsTo('App\User');
    }

    public function leadDetail() {
        return $this->hasMany('App\LeadDetail');
    }

    public function scopeSearch($query, $search = array()) {

        $query->leftJoin('lead_detail', 'lead_detail.lead_id', '=', 'lead.id');

    	if( isset($search['s']) ) {
            $query->where('lead_detail.field_number', '=', '1')
                  ->where('lead_detail.value', 'LIKE', '%'.$search['s'].'%');
		}
        if( isset($search['status']) ) {
            $query->where('lead.status', $search['status']);
        }
        
        $query->groupBy('lead.id');

        return $query;
	}

    public function scopeVisible($query) {

        if(\Auth::User()->role == 'chemist') {            
            $query->where('user_id', \Auth::User()->id);
        }

        if(\Auth::User()->role == 'user') {            
            $query->where('status', 'approve');
        }

        return $query;
    
    }

    public function scopeSearch2($search = '') {

        $lead = new LeadDetail;

        if( isset($search) ) {
             $lead->where('lead_detail.field_number', '=', '1')
                  ->where('lead_detail.field_number', '=', '1')
                  ->where('lead_detail.value', 'LIKE', '%'.$search.'%');
        }

        return $query;
    }
}
