 <?php
namespace App\Http\Controllers;

use Validator, Redirect, Input, Auth, Hash, PDF, DB, URL;
use Illuminate\Http\Request;

use App\User;
use App\Form;
use App\Lead;
use App\LeadDetail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TdsController extends Controller
{
    protected $user;
    protected $form;
    protected $lead;
    protected $leadDetail;

    public function __construct(User $user, Form $form, Lead $lead, LeadDetail $leadDetail)
    {
        $this->user_id      = (Auth::check()) ? Auth::user()->id : '';
        $this->user         = $user;
        $this->form         = $form;
        $this->lead         = $lead;
        $this->leadDetail   = $leadDetail;

        $this->version = 2;

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

/*        foreach($this->form->get() as $form) {
            $lead = new Lead;

            $lead->user_id = $this->user_id;
            $lead->type    = 'tds';
            $lead->status  = 'pending';         
            $lead->token    = str_random('32');
            $lead->created_at = date('Y-m-d H:i:s');   

            $lead->save();

            $i=1;
            foreach( json_decode($form->content) as $content_k => $value) {

                $input_data = array(
                    'lead_id'      => $lead->id,
                    'field_number' => $i,
                    'value'        => $value,
                );

                $this->leadDetail->create($input_data);

                $i++;
            }


        }

        exit;*/

        if(Input::get('action') && Input::get('ids')) {
            $this->lead->whereIn('id', Input::get('ids'))->update(['status' => Input::get('action')]);
            return Redirect::route('backend.tds.index', query_vars('ids=0&action=0'))
                           ->with('success','Status has been updated!');
        }

        parse_str( query_vars(), $search );

        $data['rows'] = $this->lead->search($search)->visible()->where('type', 'tds')->orderBy('lead.id', 'DESC')->paginate(15);
        $data['count'] = count($this->lead->search($search)->visible()->where('type', 'tds')->get());

        $data['count_all'] = $this->lead->visible()->where('type', 'tds')->count();
        $data['count_approve'] = $this->lead->visible()->where('status', 'approve')->where('type', 'tds')->count();
        $data['count_pending'] = $this->lead->visible()->where('status', 'pending')->where('type', 'tds')->count();
        $data['count_trash'] = $this->lead->visible()->where('status', 'trash')->where('type', 'tds')->count();
        $data['count_draft'] = $this->lead->visible()->where('status', 'draft')->where('type', 'tds')->count();

        $data['version'] = $this->version;

        return view('backend.tds.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function add()
    {
        return view('backend.tds.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $forms = get_tds_forms();

        if( Input::get('v') == 2 ) {
            $forms = get_tds_v2_forms();
        }

        foreach($forms as $form) {
            if($form['rules']) {
                $insertRules['input_'.$form['name']] = $form['rules'];  
            }
        }

        $validator = Validator::make(Input::all(), $insertRules);

        if($validator->passes()) {

            $lead = $this->lead;

            $lead->user_id = $this->user_id;
            $lead->type    = 'tds';
            $lead->status  = 'pending';         
            $lead->token    = str_random('32');
            $lead->created_at = date('Y-m-d H:i:s');   

            if( $lead->save() ) {

	            $inputs = Input::except(['_token', 'v']);

		        $file = Input::file('file');

		        if( $file ) unset($inputs['input_31']);

	            foreach($inputs as $input_k => $input_v) {

                    if(is_array($input_v)) {
                        $value = serialize( array_chunk($input_v, 2) );
                    } else { 
                        $value = $input_v;
                    }

                    $input_data = array(
                        'lead_id'      => $lead->id,
                        'field_number' => str_replace(['input_', '_'], ['','.'], $input_k),
                        'value'        => $value,
                    );

                     if( $value ) $this->leadDetail->create($input_data);
                }

/*		        if( $file ) {
		        	$img_input = Input::get('input_31');
			        $string     = strtolower(str_random(16));
			        $imageFile  = $file->getRealPath();
			        $ext        = $file->getClientOriginalExtension();
			        $path       = 'uploads/images/tds/';  

		            $large_path   = $path.$string.'.'.$ext;
		            \Image::make($imageFile)->resize(600, null, function ($constraint) {
		                $constraint->aspectRatio();
		                $constraint->upsize();
		            })->save($large_path);

					$input_data = array(
						'lead_id'      => $id,
						'field_number' => 31,
						'value'        => $large_path,
					);

		            $this->leadDetail->create($input_data);
		        }*/

                return Redirect::route('backend.tds.index', query_vars())
                               ->with('success','New TDS has been added!');
            } 
        }

        return Redirect::route('backend.tds.add', query_vars())
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
        $data['note_count'] = DB::table('notes')->where('type', 'tds')->where('sheet_id', $id)->count(); 

        $data['info'] = $this->lead->findOrFail($id);
        return view('backend.tds.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $forms = get_tds_forms();

        if( Input::get('v') == 2 ) {
            $forms = get_tds_v2_forms();
        }

        foreach($forms as $form) {
            if($form['rules']) {
                $insertRules['input_'.$form['name']] = $form['rules'];  
            }
        }

        $validator = Validator::make(Input::all(), $insertRules);

        if($validator->passes()) {

            $lead = $this->lead->find($id);

            $lead->updated_at = date('Y-m-d H:i:s');   

            $this->leadDetail->where('lead_id', $id)->delete();

            $inputs = Input::except(['_token', 'v']);

	        $file = Input::file('file');

	        if( $file ) unset($inputs['input_31']);

            foreach($inputs as $input_k => $input_v) {

                if(is_array($input_v)) {
                    $value = serialize( array_chunk($input_v, 2) );
                } else { 
                    $value = $input_v;
                }

                $input_data = array(
                    'lead_id'      => $id,
                    'field_number' => str_replace(['input_', '_'], ['','.'], $input_k),
                    'value'        => $value,
                );
                
                if( $value ) $this->leadDetail->create($input_data);
            }

	        if( $file ) {
	        	$img_input = Input::get('input_31');
		        $string     = strtolower(str_random(16));
		        $imageFile  = $file->getRealPath();
		        $ext        = $file->getClientOriginalExtension();
		        $path       = 'uploads/images/tds/';  

		        if( file_exists( $img_input ) && $img_input  ) {
		        	unlink( $img_input );
		        }

	            $large_path   = $path.$string.'.'.$ext;
	            \Image::make($imageFile)->resize(600, null, function ($constraint) {
	                $constraint->aspectRatio();
	                $constraint->upsize();
	            })->save($large_path);

				$input_data = array(
					'lead_id'      => $id,
					'field_number' => 31,
					'value'        => $large_path,
				);

	            $this->leadDetail->create($input_data);
	        }

            if( $lead->save() ) {              
                $view = URL::route('backend.tds.pdf-view', [$id, $lead->token]);
                return Redirect::route('backend.tds.edit', [$id, query_vars()])
                               ->with('success','Changes have been saved, <a href="'.$view.'" target="_blank"><b>View TDS</b></a>');
            } 

        }   

        return Redirect::route('backend.tds.edit', [$id, query_vars()])
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

            return Redirect::route('backend.tds.index')
                           ->with('success','Tds has been deleted');
        }
    }

    public function emptyTrash()
    {
        $leads = $this->lead->where('lead.status', 'trash');
        
        foreach ($leads->get() as $lead) {
        	$this->leadDetail->where('lead_id', $lead->id)->delete();
        }

        $leads->delete();

        return Redirect::route('backend.tds.index')
                       ->with('success','Trash has been emptied');
    }

    public function pdfView($id, $token='')
    {

        $preview = 'tds.v'.Input::get('v', $this->version);

        $data['info'] = $info = $this->lead->where('token', $token)->find($id);

        $pdf = PDF::loadView('backend.reports.'.$preview, $data);


        $detail = $this->leadDetail->where('lead_id', $info->id)->where('field_number', 1)->first();
        $title = (@$detail->value) ? $detail->value : 'Technical Data Sheet';
        $pdf->SetTitle($title);
        
        // return view('backend.reports.'.$preview, $data);
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

            return Redirect::route('backend.tds.index')
                           ->with('success','TDS has been duplicated');
        }
    }

}

