 <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Validator, Redirect, Input, Auth, Hash, Config, Mail, Blade, PDF, DB;
use App\Setting;

class SettingController extends Controller
{

    protected $setting;
    
    /**
     * Constructor.
     *
     * @var interface
     */
    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

/*        $data['send_from_email']    = get_setting('mail_email');
        $data['send_from_name']     = get_setting();
        $data['send_to_email']      = 'michaelrafallo@gmail.com';
        $data['send_to_name']       = 'Mhike';
        $data['subject']            = 'Test Mail';

        Mail::send('emails.basic', $data, function($message) use ($data)
        {
            $message->from($data['send_from_email'], $data['send_from_name']);
            $message->to($data['send_to_email'], $data['send_to_name'])->subject($data['subject']);
        });*/

        $timezone = Setting::getInfo('timezone');
        date_default_timezone_set($timezone);

/*        $server = request()->server->get('SERVER_NAME');
        if( $server == 'localhost' ) {
            // Edit timezone in environement
            $realpath = str_replace('public', '', realpath('.')); 
            editFile($realpath.'.env', 'TIMEZONE', $timezone);
        } else {
            // Dev site
            $realpath = str_replace('dev', '', realpath('.')); 
            editFile($realpath.'/dev.smrcdb.com/.env', 'TIMEZONE', $timezone);
        }
*/
        $data = array();
                        
        // Submit form 
        $settings = [
            'site_name',
            'copy_right',
            'admin_email',
            'carbon_copy_email',
            'timezone',
            'analytics',
        ];

        foreach($settings as $setting) {
            $data[$setting] =  @Setting::where('key', $setting)->first()->value;
        }
            

        if (Input::get('op')) {
            
            foreach($settings as $setting) {
                $s = $this->setting->where('key', $setting)->first();
                echo  $s->value.'<br>';
                $s->value = Input::get($setting);
                $s->save();
            }

            if(Input::hasFile('logo_small')){

                $fileUpload = Input::file('logo_small');
                $imageFile = $fileUpload->getRealPath();
                $ext = $fileUpload->getClientOriginalExtension();
                $path = 'uploads/images/logo_small.png';
                
                \Image::make($imageFile)->save($path);
            } 

            if(Input::hasFile('tds_header')){

                $fileUpload = Input::file('tds_header');
                $imageFile = $fileUpload->getRealPath();
                $ext = $fileUpload->getClientOriginalExtension();
                $path = 'uploads/images/tds-header.png';
                
                \Image::make($imageFile)->save($path);
            } 

            if(Input::hasFile('sds_header')){

                $fileUpload = Input::file('sds_header');
                $imageFile = $fileUpload->getRealPath();
                $ext = $fileUpload->getClientOriginalExtension();
                $path = 'uploads/images/sds-header.png';
                
                \Image::make($imageFile)->save($path);
            } 

            return Redirect::route('backend.settings.index')
                           ->with('success','Changes saved.');
        }

        return view('backend.settings.general', $data);
    }

    function addNote() {
        $data = array(
                'user_id'  => Auth::User()->id,
                'type'     => Input::get('type'),
                'sheet_id' => Input::get('sheet_id'),
                'message'  => Input::get('notes'),
                'created_at'  => date('Y-m-d H:i:s')
                );
        DB::table('notes')->insert($data);    
        
        return Redirect::back()->with('success','Notes has been posted!');
    }

    function deleteNote($id ='') {
        DB::table('notes')->where('id', $id)->delete();    
        
        return Redirect::back()->with('success','Notes has been deleted!');
    }

}



