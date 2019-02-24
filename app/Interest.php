 <?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{

	protected $primaryKey = 'id';
	
	public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'interests';

    
}
