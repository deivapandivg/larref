<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class TicketsController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function tickets(Request $request){
        $Auth_id = Auth::id();
        $TicketAjaxWhere = Session::get('TicketAjaxWhere');
             
        if ($request->ajax()) {
            $data = DB::table('tickets as a')->whereRaw($TicketAjaxWhere)->select(['a.ticket_id',
                'd.client_name as client_id',
                'e.first_name as customer_contact_id',
                'a.subject', 
                'a.description', 
                'f.ticket_type_name as ticket_type_id',
                'g.priority_name as priority_id',
                'h.ticket_source_name as source_id',
                'i.first_name as assign_to',
                'j.ticket_status_name as status_id',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('clients as d', 'd.client_id', '=', 'a.client_id')->leftjoin('customer_contacts as e','e.customer_contact_id', '=','a.customer_contact_id')->leftjoin('ticket_types as f','f.ticket_type_id', '=','a.ticket_type_id')->leftjoin('ticket_priority as g','g.priority_id', '=','a.priority_id')->leftjoin('ticket_sources as h','h.ticket_source_id', '=','a.source_id')->leftjoin('users as i','i.id', '=','a.assign_to')->leftjoin('ticket_status as j','j.ticket_status_id', '=','a.status_id');
            // if($request->all_date!="yes")
            // {
            //     $data->whereBetween('a.created_at', [$request->from_date." 00:00:00", $request->to_date." 23:59:59"]);
            // }
            // if($request->user_id!="All")
            // {
            //     $data->where('a.assign_to',("$request->user_id"));
            // }
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<a class="vg-btn-ssp-success TicketViewModal text-center" data-toggle="tooltip" data-placement="right" title="View" data-original-title="view"><i class="fa fa-eye text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a href="'.route('ticket_edit',base64_encode($row->ticket_id)).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        
        
        $client_lists = DB::table('clients')->where('deleted','No')->get();
        $get_auth_user = DB::table('users')->where('deleted', 'No')->where('id',$Auth_id)->first();

        if($get_auth_user->designation_id==1){

            $users_list = DB::table('users')->where('deleted', 'No')->get();

        }
        else{

            
            $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $Auth_id)->where('deleted', 'No')->select('id', 'first_name');

            $users_list = DB::table('users')->where('id', $Auth_id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();
            
        }
        return view('tickets.tickets',compact('client_lists','users_list'));
    }

    public function ticket_add(Request $request){
        $customers_lists = DB::table('clients')->where('deleted','No')->get();
        $customers_contact_list = DB::table('customer_contacts')->where('deleted','No')->get();
        $ticket_type_lists = DB::table('ticket_types')->where('deleted','No')->get();
        $ticket_priority_lists = DB::table('ticket_priority')->where('deleted','No')->get();
        $ticket_source_lists = DB::table('ticket_sources')->where('deleted','No')->get();
        $ticket_assign_lists = DB::table('users')->where('deleted','No')->get();
        return view('tickets.ticket_add',compact('customers_lists','customers_contact_list','ticket_type_lists','ticket_priority_lists','ticket_source_lists','ticket_assign_lists'));
    }

    public function ticket_edit(Request $request){
        $tickets_list = DB::table('tickets')->where('ticket_id',base64_decode($request->ticket_id))->first();
        $ticket_attachments = DB::table('ticket_attachments')->where('ticket_id',base64_decode($request->ticket_id))->get();
        $customers_lists = DB::table('clients')->where('deleted','No')->get();
        $customers_contact_list = DB::table('customer_contacts')->where('deleted','No')->get();
        $ticket_type_lists = DB::table('ticket_types')->where('deleted','No')->get();
        $ticket_priority_lists = DB::table('ticket_priority')->where('deleted','No')->get();
        $ticket_source_lists = DB::table('ticket_sources')->where('deleted','No')->get();
        $ticket_assign_lists = DB::table('users')->where('deleted','No')->get();
        return view('tickets.ticket_edit',compact('tickets_list','customers_lists','customers_contact_list','ticket_type_lists','ticket_priority_lists','ticket_source_lists','ticket_assign_lists','ticket_attachments'));
    }

    public function ticket_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->ticket_id))
        {
            $files=[];
            if(!is_null($request->file('attachment'))){
                foreach($request->file('attachment') as $file)
                {
                    $name = time() . rand(1, 100) . '.' . $file->extension();
                    $file->move(public_path('ticket_uploads'),$name);
                    $files[] = $name;
                }
            }
            if($request->ticket_source_id==2){
                $ticket_created_type_id=2;
            }
            else{
                $ticket_created_type_id=1;
            }
            if($request->ticket_assign_id!=0) 
            {
                $status_id=2;
            }
            else
            {
                $status_id=1;
            }
            $data = array('client_id' => $request->client_id, 'customer_contact_id' => $request->customer_contact_id, 'subject' => $request->subject, 'description' => $request->description, 'ticket_type_id' => $request->ticket_type_id, 'priority_id' => $request->ticket_priority_id, 'source_id' => $request->ticket_source_id, 'assign_to' => $request->ticket_assign_id, 'status_id' => $status_id, 'ticket_created_type_id' => $ticket_created_type_id, 'updated_by' => $updatedby, 'updated_at' => Now());
            $ticket_update=DB::table('tickets')->where('ticket_id',$request->ticket_id)->update($data);
            if(isset($ticket_update))
            {
                $ticketIdNew=DB::table('tickets')->select('*')->where('ticket_id',$request->ticket_id)->first();
                $ticket_item_id=$ticketIdNew->ticket_id;
                
                for($i = 0; $i < count($files); $i++){
                    $attachments = $files[$i];
                    $attachment_add = array('ticket_id' => $ticket_item_id, 'attachment' => $attachments, 'created_by' => $createdby, 'created_at' => Now());
                    $ticket_attachment_add = DB::table('ticket_attachments')->insert($attachment_add);
                }

                if($request->ticket_assign_id!=''){
                    $get_user=DB::table('users')->where('id', $request->ticket_assign_id)->first();
                    $user_name=$get_user->first_name;
                    $operation_comments="Assigned to $user_name";
                }
                else{
                    $operation_comments='New Ticket';
                }
                $ticket_timeline_data=array('ticket_id' => $ticket_item_id, 'client_id' => $request->client_id, 'customer_contact_id' => $request->customer_contact_id, 'status_id' => $status_id, 'description' => $request->description, 'operation_comments' => $operation_comments, 'assign_to' => $request->ticket_assign_id, 'created_by' => $createdby, 'created_at' => now(), 'created_date' => now());
                $ticket_timeline_add=DB::table('tickets_timeline')->insert($ticket_timeline_data);
                $reporting_to=$request->ticket_assign_id;
                $notification_to = $reporting_to;
                $get_user_name = DB::table('users')->where('id', $notification_to)->select('first_name')->first();
                $notification_to_name = $get_user_name->first_name;
                $URL="tickets";
                $notification_from = DB::table('users')->where('id', $createdby)->select('first_name')->first();
                $send_by = $notification_from->first_name;
                $Descriptions="Dear $notification_to_name, You Have received One Ticket from $send_by : Thank You.";
                $notification_data = array('title' => 'Ticket Updated and Assigned to You', 'description' => $Descriptions, 'url' => $URL, 'notification_to' => $notification_to, 'created_by' => $createdby, 'created_at' => Now());
                $insert_notification = DB::table('notifications')->insert($notification_data);
            }
        }
        else
        {
            $files=[];
            if(!is_null($request->file('attachment'))){
                foreach($request->file('attachment') as $file)
                {
                    $name = time() . rand(1, 100) . '.' . $file->extension();
                    $file->move(public_path('ticket_uploads'),$name);
                    $files[] = $name;
                }
            }
            if($request->ticket_source_id==2){
                $ticket_created_type_id=2;
            }
            else{
                $ticket_created_type_id=1;
            }
            if($request->ticket_assign_id!=0) 
            {
                $status_id=2;
            }
            else
            {
                $status_id=1;
            }
            $data = array('client_id' => $request->client_id, 'customer_contact_id' => $request->customer_contact_id, 'subject' => $request->subject, 'description' => $request->description, 'ticket_type_id' => $request->ticket_type_id, 'priority_id' => $request->ticket_priority_id, 'source_id' => $request->ticket_source_id, 'assign_to' => $request->ticket_assign_id, 'status_id' => $status_id, 'ticket_created_type_id' => $ticket_created_type_id, 'created_by' => $createdby, 'created_at' => Now());

            $ticket_add=DB::table('tickets')->insertGetId($data);
            for($i = 0; $i < count($files); $i++){
                $attachments = $files[$i];
                $attachment_add = array('ticket_id' => $ticket_add, 'attachment' => $attachments, 'created_by' => $createdby, 'created_at' => Now());

                $ticket_attachment_add = DB::table('ticket_attachments')->insert($attachment_add);
            }
            if(isset($ticket_add)){
                if($request->ticket_assign_id!=''){
                    $get_user=DB::table('users')->where('id', $request->ticket_assign_id)->first();
                    $user_name=$get_user->first_name;
                    $operation_comments="Assigned to $user_name";
                }
                else{
                    $operation_comments='New Ticket';
                }
                $ticket_timeline_data=array('ticket_id' => $ticket_add, 'client_id' => $request->client_id, 'customer_contact_id' => $request->customer_contact_id, 'status_id' => $status_id, 'description' => $request->description, 'operation_comments' => $operation_comments, 'assign_to' => $request->ticket_assign_id, 'created_by' => $createdby, 'created_at' => now(), 'created_date' => now());
                $ticket_timeline_add=DB::table('tickets_timeline')->insert($ticket_timeline_data);
                $reporting_to=$request->ticket_assign_id;
                $notification_to = $reporting_to;
                $get_user_name = DB::table('users')->where('id', $notification_to)->select('first_name')->first();
                $notification_to_name = $get_user_name->first_name;
                $URL="tickets";
                $notification_from = DB::table('users')->where('id', $createdby)->select('first_name')->first();
                $send_by = $notification_from->first_name;
                $Descriptions="Dear $notification_to_name, You Have received One Ticket from $send_by : Thank You.";
                $notification_data = array('title' => 'Ticket Assigned to You', 'description' => $Descriptions, 'url' => $URL, 'notification_to' => $notification_to, 'created_by' => $createdby, 'created_at' => Now());
                $insert_notification = DB::table('notifications')->insert($notification_data);
            }
        }
        return redirect('tickets');
    }

    public function client_change_ajax(Request $request){
        $client_id = $request->client_id;
        $customers_contact_select=DB::table('customer_contacts')->where('client_id', $client_id)->get();
        foreach($customers_contact_select as $customer_contact_select){
            echo $data='<option value="'.$customer_contact_select->customer_contact_id.'">'.$customer_contact_select->first_name.'</option>';
        }
    }

    public function ticket_view(Request $request){
        if($request->ajax()){
            $data= DB::table('tickets_timeline as a')->where('ticket_id',$request->ticket_id)->select([ 
                'a.ticket_timeline_id',
                'b.client_name as client_id',
                'c.first_name as customer_contact_id',
                'd.ticket_status_name as status_id',
                'a.operation_comments',
                'a.description',
                'e.first_name as assign_to',
                'f.first_name as created_by',
                'a.created_at',
            ])->leftjoin('clients as b', 'b.client_id', '=', 'a.client_id')->leftjoin('customer_contacts as c', 'c.customer_contact_id', '=', 'a.customer_contact_id')->leftjoin('ticket_status as d', 'd.ticket_status_id', '=', 'a.status_id')->leftjoin('users as e', 'e.id', '=', 'a.assign_to')->leftjoin('users as f', 'f.id', '=', 'a.created_by');
                
             return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
        }
        $tickets_list = DB::table('tickets')->where('ticket_id',base64_decode($request->ticket_id))->first();
        $ticket_attachments = DB::table('ticket_attachments')->where('ticket_id',base64_decode($request->ticket_id))->get();
        $customers_lists = DB::table('clients')->where('client_id',$tickets_list->client_id)->first();
        $customers_contact_list = DB::table('customer_contacts')->where('customer_contact_id',$tickets_list->customer_contact_id)->first();
        $ticket_type_lists = DB::table('ticket_types')->where('ticket_type_id',$tickets_list->ticket_type_id)->first();
        $ticket_priority_lists = DB::table('ticket_priority')->where('priority_id',$tickets_list->priority_id)->first();
        $ticket_source_lists = DB::table('ticket_sources')->where('ticket_source_id',$tickets_list->source_id)->first();
        $ticket_status_lists = DB::table('ticket_status')->where('ticket_status_id',$tickets_list->status_id)->first();
        $ticket_assign_lists = DB::table('users')->where('id',$tickets_list->assign_to)->first();
        $created_by_list = DB::table('users')->where('id',$tickets_list->created_by)->first();
        $updated_by_list = DB::table('users')->where('id',$tickets_list->updated_by)->first();
        $ticket_created_type_lists = DB::table('ticket_created_type')->where('ticket_created_type_id',$tickets_list->ticket_created_type_id)->first();

        
        $status_lists = DB::table('ticket_status')->where('deleted', 'No')->get();

        $timeline_lists = DB::table('tickets_timeline as a')->where('ticket_id',base64_decode($request->ticket_id))->first();
        $timeline_lists_get = DB::table('tickets_timeline as a')->where('ticket_id',base64_decode($request->ticket_id))->get();
        $user_lists = DB::table('users')->where('deleted', 'No')->get();

        $chat_messages = DB::table('ticket_chat_message as a')->where('ticket_id', base64_decode($request->ticket_id))->leftjoin('users as b', 'b.id', '=', 'a.created_by')->leftjoin('customer_contacts as c', 'c.customer_contact_id', '=', 'a.created_by')->get();

        return view('tickets.ticket_view',compact('tickets_list', 'customers_lists', 'customers_contact_list', 'ticket_type_lists', 'ticket_priority_lists', 'ticket_source_lists', 'ticket_assign_lists', 'ticket_attachments', 'ticket_status_lists', 'ticket_created_type_lists', 'created_by_list', 'updated_by_list', 'timeline_lists', 'status_lists', 'timeline_lists_get', 'user_lists', 'chat_messages'));
    }

    public function timelines_ticket_submit(Request $request){
        $createdby = Auth::id();
        $timeline_lists = DB::table('tickets_timeline')->where('ticket_id',$request->ticket_id)->first();
        $timeline_status_lists = DB::table('ticket_status')->where('ticket_status_id',$timeline_lists->status_id)->first();
        $ticket_timeline_data = array('ticket_id' => $request->ticket_id, 'client_id' => $request->client_id, 'customer_contact_id' => $request->customer_contact_id, 'description' => $request->description, 'status_id' => $request->status_id, 'operation_comments' => $request->description, 'created_by' => $createdby, 'created_at' => Now(), 'created_date' => now());
        $ticket_timeline_add = DB::table('tickets_timeline')->insertGetId($ticket_timeline_data);
        $files=[];
        if(!is_null($request->file('replacement_attachments'))){
            foreach($request->file('replacement_attachments') as $file)
            {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move(public_path('ticket_uploads'),$name);
                $files[] = $name;
            }
        }
        for($i = 0; $i < count($files); $i++){
            $attachments = $files[$i];
            $attachment_add = array('ticket_timeline_id' => $ticket_timeline_add, 'replacement_attachments' => $attachments, 'created_by' => $createdby, 'created_at' => Now());

            $ticket_attachment_add = DB::table('tickets_timeline_attachment')->insert($attachment_add);
        }
        return Redirect::back();
    }

    public function chat_submit(Request $request){
        $createdby = Auth::id();
        $data = array('ticket_id' => $request->TicketId, 'chat_message' => $request->Chat, 'ticket_created_type_id' => 1, 'created_by' => $createdby, 'created_at' => Now());

        $chat_add=DB::table('ticket_chat_message')->insertGetId($data);
        if(isset($chat_add)){
            $assign_to = DB::table('tickets')->where('ticket_id', $request->TicketId)->first();
            $created_by = $assign_to->created_by;
            $assign_to_id = $assign_to->assign_to;
            if($createdby==$created_by){
            $reporting_to=$assign_to->assign_to;
            $notification_to = $reporting_to;
            $get_customer_name = DB::table('users')->where('id', $notification_to)->select('first_name')->first();
            $notification_to_name = $get_customer_name->first_name;
            }
            elseif($createdby==$assign_to_id){
                $reporting_to=$assign_to->created_by;
                $notification_to = $reporting_to;
                $get_customer_name = DB::table('users')->where('id', $notification_to)->select('first_name')->first();
                $notification_to_name = $get_customer_name->first_name;
            }
            $URL="tickets";
            $notification_from = DB::table('users')->where('id', $createdby)->select('first_name')->first();
            $send_by = $notification_from->first_name;
            $Descriptions="Dear $notification_to_name, You Have received One Chat Message from $send_by : Thank You.";
            $notification_data = array('title' => 'Chat Message', 'description' => $Descriptions, 'url' => $URL, 'notification_to' => $notification_to, 'created_by' => $createdby, 'created_at' => Now());
            $insert_notification = DB::table('notifications')->insert($notification_data);
        }
        return back();
    }

    public function ticket_modal_view(Request $request){
        $tickets_list = DB::table('tickets')->where('ticket_id',$request->ticket_id)->first();
        $ticket_attachments = DB::table('ticket_attachments')->where('ticket_id',$request->ticket_id)->get();
        $customers_lists = DB::table('clients')->where('client_id',$tickets_list->client_id)->first();
        $customers_contact_list = DB::table('customer_contacts')->where('customer_contact_id',$tickets_list->customer_contact_id)->first();
        $ticket_type_lists = DB::table('ticket_types')->where('ticket_type_id',$tickets_list->ticket_type_id)->first();
        $ticket_priority_lists = DB::table('ticket_priority')->where('priority_id',$tickets_list->priority_id)->first();
        $ticket_source_lists = DB::table('ticket_sources')->where('ticket_source_id',$tickets_list->source_id)->first();
        $ticket_status_lists = DB::table('ticket_status')->where('ticket_status_id',$tickets_list->status_id)->first();
        $ticket_assign_lists = DB::table('users')->where('id',$tickets_list->assign_to)->first();
        $created_by_list = DB::table('users')->where('id',$tickets_list->created_by)->first();
        $updated_by_list = DB::table('users')->where('id',$tickets_list->updated_by)->first();
        $ticket_created_type_lists = DB::table('ticket_created_type')->where('ticket_created_type_id',$tickets_list->ticket_created_type_id)->first();

        
        $status_lists = DB::table('ticket_status')->where('deleted', 'No')->get();
        $model='
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Customer :</b>
                        <p>'.$customers_lists->client_name.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Customer Contact :</b>
                        <p>'.$customers_contact_list->first_name.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Subject :</b>
                        <p>'.$tickets_list->subject.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Description :</b>
                   <p>'.$tickets_list->description.'</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Ticket Type :</b>
                        <p>'.$ticket_type_lists->ticket_type_name.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Ticket Priority :</b>
                        <p>'.$ticket_priority_lists->priority_name.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Ticket Source :</b>
                        <p>'.$ticket_source_lists->ticket_source_name.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Ticket Status :</b>
                        <p>'.$ticket_status_lists->ticket_status_name.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Assign To :</b>
                        <p>'.$ticket_assign_lists->first_name.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Ticket Created Type :</b>
                        <p>'.$ticket_created_type_lists->ticket_created_type_name.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Created By :</b>
                        <p>'.$created_by_list->first_name.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Created At :</b>
                        <p>'.$tickets_list->created_at.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Updated By :</b>';
                   if($tickets_list->updated_by!=''){
                   $model.='<p>'.$updated_by_list->first_name.'</p>';
                   }
                $model.='</fieldset>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Updated At :</b>
                        <p>'.$tickets_list->updated_at.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Attachments :</b><br>';
                   foreach($ticket_attachments as $ticket_attachment){
                       $model.='<a href="'.url("").'/public/ticket_uploads/'.$ticket_attachment->attachment.'" target="_blank"><button type="button" class="btn btn-sm btn-primary"  title="View Attachment">
                       <i class="fa fa-eye"></i></button></a>'.$ticket_attachment->attachment.'<br><br>';
                    }
                   $model.='</fieldset>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <center>
                        <a href="'.route('ticket_view',base64_encode($tickets_list->ticket_id)).'" target="_blank">
                            <p><b><u>View More Details</u> </b></p>
                        </a>
                    </center>
                </div>
            </div>
        </div>
        ';
        echo $model;
    }

    
}
