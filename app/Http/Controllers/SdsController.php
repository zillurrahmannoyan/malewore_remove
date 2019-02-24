 <?php
namespace App\Http\Controllers;

use Validator, Redirect, Input, Auth, Hash, PDF, DB, URL, Mail;
use Illuminate\Http\Request;

use App\User;
use App\Form;
use App\Lead;
use App\LeadDetail;
use App\Setting;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SdsController extends Controller
{
    protected $user;
    protected $form;
    protected $lead;
    protected $leadDetail;
    protected $setting;

    public function __construct(User $user, Form $form, Lead $lead, LeadDetail $leadDetail, Setting $setting)
    {
        $this->user_id      = (Auth::check()) ? Auth::user()->id : '';
        $this->user         = $user;
        $this->form         = $form;
        $this->lead         = $lead;
        $this->leadDetail   = $leadDetail;
        $this->setting      = $setting;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        if(Input::get('action') && Input::get('ids')) {
            $this->lead->whereIn('id', Input::get('ids'))->update(['status' => Input::get('action')]);
            return Redirect::route('backend.sds.index', query_vars('ids=0&action=0'))
                           ->with('success','Status has been updated!');
        }


        parse_str( query_vars(), $search );

        $data['rows'] = $this->lead->search($search)->visible()->where('type', 'sds')->orderBy('lead.id', 'DESC')->paginate(15);
        $data['count'] = count($this->lead->search($search)->visible()->where('type', 'sds')->get());

        $data['count_all'] = $this->lead->visible()->where('type', 'sds')->count();
        $data['count_approve'] = $this->lead->visible()->where('status', 'approve')->where('type', 'sds')->count();
        $data['count_pending'] = $this->lead->visible()->where('status', 'pending')->where('type', 'sds')->count();
        $data['count_trash'] = $this->lead->visible()->where('status', 'trash')->where('type', 'sds')->count();
        $data['count_draft'] = $this->lead->visible()->where('status', 'draft')->where('type', 'sds')->count();


        return view('backend.sds.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function add()
    {
        return view('backend.sds.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        foreach(get_sds_forms() as $form) {
            if($form['rules']) {
                $insertRules['input_'.$form['name']] = $form['rules'];  
            }
        }

        $validator = Validator::make(Input::all(), $insertRules);

        if($validator->passes()) {

            $lead = $this->lead;

            $lead->user_id = $this->user_id;
            $lead->type    = 'sds';
            $lead->status  = 'pending';         
            $lead->token    = str_random('32');
            $lead->created_at = date('Y-m-d H:i:s');   

            if( $lead->save() ) {

                foreach(Input::except('_token') as $input_k => $input_v) {

                    if(is_array($input_v)) {
                        $value = serialize( array_chunk($input_v, 5) );
                    } else { 
                        $value = $input_v;
                    }

                    $input_data = array(
                        'lead_id'      => $lead->id,
                        'field_number' => str_replace(['input_', '_'], ['','.'], $input_k),
                        'value'        => $value,
                    );

                    //if( $value ) { 
                        $this->leadDetail->create($input_data);
                    //}    
                }

                return Redirect::route('backend.sds.index')
                               ->with('success','New sds has been added!');
            } 
        }

        return Redirect::route('backend.sds.add')
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
        $data['note_count'] = DB::table('notes')->where('type', 'sds')->where('sheet_id', $id)->count(); 

        $data['info'] = $this->lead->findOrFail($id);
        return view('backend.sds.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {

        foreach(get_sds_forms() as $form) {
            if($form['rules']) {
                $insertRules['input_'.$form['name']] = $form['rules'];  
            }
        }

        $validator = Validator::make(Input::all(), $insertRules);

        if($validator->passes()) {
            
            $lead = $this->lead->find($id);

            $lead->updated_at = date('Y-m-d H:i:s');   

            $this->leadDetail->where('lead_id', $id)->delete();

            foreach(Input::except('_token') as $input_k => $input_v) {

                if(is_array($input_v)) {
                    $value = serialize( array_chunk($input_v, 5) );
                } else { 
                    $value = $input_v;
                }

                $input_data = array(
                    'lead_id'      => $id,
                    'field_number' => str_replace(['input_', '_'], ['','.'], $input_k),
                    'value'        => $value,
                );

                //if( $value ) { 
                    $this->leadDetail->create($input_data);
                //}    
            }


            if( $lead->save() ) {     
                $view = URL::route('backend.sds.pdf-view', [$id, $lead->token]);
                return Redirect::route('backend.sds.edit', $id)
                               ->with('success','Changes have been saved, <a href="'.$view.'" target="_blank"><b>View SDS</b></a>');
            } 

        } 

        return Redirect::route('backend.sds.edit', $id)
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
        
	        $lead = $this->lead->findOrFail($id);
	        $this->leadDetail->where('lead_id', $lead->id)->delete();
	        $lead->delete();

            return Redirect::route('backend.sds.index')
                           ->with('success','sds has been deleted');
        }
    }

    public function emptyTrash()
    {
        $leads = $this->lead->where('lead.status', 'trash');
        
        foreach ($leads->get() as $lead) {
        	$this->leadDetail->where('lead_id', $lead->id)->delete();
        }

        $leads->delete();

        return Redirect::route('backend.sds.index')
                       ->with('success','Trash has been emptied');
    }

    public function request()
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

                return Redirect::route('backend.sds.request')
                               ->with('success','Your request has been successfully sent!');
            } else {
                    
                return Redirect::route('backend.sds.request')
                               ->withErrors($validator)
                               ->withInput();
            }

        }

        return view('backend.sds.request');
    }

    public function pdfView($id, $token='')
    {
        $preview = 'sds';

        $data['info'] = $info = $this->lead->where('token', $token)->find($id);

        $pdf = PDF::loadView('backend.reports.'.$preview, $data);

        $detail = $this->leadDetail->where('lead_id', $info->id)->where('field_number', 1)->first();
        $title = (@$detail->value) ? $detail->value : 'Safety Data Sheet';
        $pdf->SetTitle($title);
        
       //  return view('backend.reports.sds', $data);
        return $pdf->stream($title.'.pdf');
    }

    public function duplicate($id)
    {
       if($id) {
    
            $form = Lead::find($id);
            $newform = $form->replicate();
            $newform->token = str_random('32');
            $newform->save();

            foreach (LeadDetail::where('lead_id', $id)->get() as $leadDetail) {
                $newLeadDetail = new LeadDetail;
                $newLeadDetail->lead_id      = $newform->id;
                $newLeadDetail->field_number = $leadDetail->field_number;
                
                $copy = ($leadDetail->field_number == 1) ? 'Copy of ' : '';

                $newLeadDetail->value        = $copy.$leadDetail->value;
                $newLeadDetail->save();
            }

            return Redirect::route('backend.sds.index')
                           ->with('success','SDS has been duplicated');
        }
    }

}

