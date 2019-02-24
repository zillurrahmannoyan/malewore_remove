 <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Notification;
use App\User;
use App\Form;
use App\Lead;

use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail;

class DashboardController extends Controller
{
    protected $notification;
    protected $user;
    protected $form;
    protected $lead;

    public function __construct(Notification $notification, User $user, Form $form, Lead $lead)
    {
        $this->user_id = (Auth::check()) ? Auth::user()->id : '';
        $this->notification = $notification;
        $this->user = $user;
        $this->lead = $lead;
        $this->form = $form;

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $data['count_tds'] = $this->lead->visible()->where('type', 'tds')->count();
        $data['count_sds'] = $this->lead->visible()->where('type', 'sds')->count();
        $data['count_users'] = $this->user->where('role', 'user')->count();

        return view('backend.dashboard.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
