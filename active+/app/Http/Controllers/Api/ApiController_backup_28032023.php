<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DB;

class ApiController extends Controller
{
      public function task_list(Request $request){
        $id = $request->id;
        $user_details = DB::table('users')->where('deleted','No')->where('id',$request->id)->first();

        

        $limit = $request->limit ? $request->limit : 10;
        $page = $request->page && $request->page > 0 ? $request->page : 1;
        $skip = ($page - 1) * $limit;

        $ticket_status_details = DB::table('ticket_status')->where('deleted','No')->select('ticket_status_id','ticket_status_name')->get();


        if($id!=""){
            $where="a.deleted='No'";

            $get_auth_user = DB::table('users')->where('deleted', 'No')->where('id',$id)->first();

            if($get_auth_user->designation_id==1){

                $user_lists = DB::table('users')->where('deleted', 'No')->get();

            }
            else{

                $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $id)->where('deleted', 'No')->select('id', 'first_name');

                $user_lists = DB::table('users')->where('id', $id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();

                foreach($user_lists as $user_list)
                {
                    $storedArray[] = $user_list->id;
                }
                $string = implode(', ', $storedArray);
                $where.="AND a.assign_to IN ($string)";
                
            }
            $customer_id=$request->customer_id;
            if($customer_id=='')
            {
                $get_all_clients= DB::table('clients')->select('client_id')->where('deleted','No')->get();
                foreach($get_all_clients as $get_all_client)
                {
                    $storedArray[] = $get_all_client->client_id;
                }
                $string = implode(', ', $storedArray);
                $where.="AND a.client_id IN ($string)";
            }
            else
            {
                $where.=" AND a.client_id = '$customer_id'";
            }

            $priority_id=$request->priority_id;
            if($priority_id=='')
            {
                $get_all_priorities= DB::table('ticket_priority')->select('priority_id')->where('deleted','No')->get();
                foreach($get_all_priorities as $get_all_priority)
                {
                    $storedArray[] = $get_all_priority->priority_id;
                }
                $string = implode(', ', $storedArray);
                $where.="AND a.priority_id IN ($string)";
            }
            else
            {
                $where.=" AND a.priority_id = '$priority_id'";
            }

            $project_id=$request->project_id;
            if($project_id=='')
            {
                $get_all_projects= DB::table('projects')->select('project_id')->where('deleted','No')->get();
                foreach($get_all_projects as $get_all_project)
                {
                    $storedArray[] = $get_all_project->project_id;
                }
                $string = implode(', ', $storedArray);
                $where.="AND a.project_id IN ($string)";
            }
            else
            {
                $where.=" AND a.project_id = '$project_id'";
            }

            if($request->from_date!="" && $request->to_date!=""){
                $FromDate=$request->from_date;
                $ToDate=$request->to_date;
                $where.="AND a.created_at >= '".$FromDate." 00:00:00' AND a.created_at <= '".$ToDate." 23:59:59'";
            }

            if($request->status_id=="" OR $request->status_id==1){

                $status_id[] = $request->status_id;
                $wherein = $status_id;

            }
            elseif($request->status_id==3 OR $request->status_id==4 OR $request->status_id==2){
                $status_array = [2,3,4];
                $wherein = $status_array;
            }
            else{
                $status_id[] = $request->status_id;
                $wherein = $status_id;
            }

            if($user_details->designation_id==1)
            {

              $task_details = DB::table('tasks as a')->whereRaw($where)->whereIn('a.task_status',$wherein)->select(['a.task_id as task_id',
                    'a.task_name as task_name',
                    'a.description as description',
                    'a.status_description as status_description',
                    'a.task_status as task_status',
                    'b.attachment as attachment',
                    'a.created_at as created_at',
                    'a.updated_at as updated_at',
                    'c.client_name as client_name',
                    'd.project_name as project_name',
                    'e.first_name as assign_to',
                    'f.first_name as created_by',
                    'g.first_name as updated_by',
                    'h.status_name as status_name',
                ])->leftjoin('task_attachments as b', 'b.task_id', '=', 'a.task_id')->leftjoin('clients as c', 'c.client_id', '=', 'a.client_id')->leftjoin('projects as d', 'd.project_id', '=', 'a.project_id')->leftjoin('users as e', 'e.id', '=', 'a.assign_to')->leftjoin('users as f', 'f.id', '=', 'a.created_by')->leftjoin('users as g', 'g.id', '=', 'a.updated_by')->leftjoin('status as h', 'h.status_id', '=', 'a.task_status')->orderby('task_id', 'desc')->paginate($limit);
            }
            else{
                $task_details = DB::table('tasks as a')->whereRaw($where)->whereIn('a.task_status',$wherein)->select(['a.task_id as task_id',
                    'a.task_name as task_name',
                    'a.description as description',
                    'a.status_description as status_description',
                    'a.task_status as task_status',
                    'b.attachment as attachment',
                    'a.created_at as created_at',
                    'a.updated_at as updated_at',
                    'c.client_name as client_name',
                    'd.project_name as project_name',
                    'e.first_name as assign_to',
                    'f.first_name as created_by',
                    'g.first_name as updated_by',
                    'h.status_name as status_name',
                ])->leftjoin('task_attachments as b', 'b.task_id', '=', 'a.task_id')->leftjoin('clients as c', 'c.client_id', '=', 'a.client_id')->leftjoin('projects as d', 'd.project_id', '=', 'a.project_id')->leftjoin('users as e', 'e.id', '=', 'a.assign_to')->leftjoin('users as f', 'f.id', '=', 'a.created_by')->leftjoin('users as g', 'g.id', '=', 'a.updated_by')->leftjoin('status as h', 'h.status_id', '=', 'a.task_status')->orderby('task_id', 'desc')->paginate($limit);
            }
        }
        else{
            $task_details = DB::table('tasks')->where('task_id', '=',$request->selected_result)->orwhere('client_id', '=', $request->selected_result)->orwhere('assign_to', '=', $request->selected_result)->select('task_id', 'client_id','assign_to')->get();
        }

        $status_details = DB::table('status')->where('deleted', 'No')->select('status_id as id', 'status_name as name')->get();

        foreach($status_details as $status_detail) {
            $status_arr[]=$status_detail;
        }
        $status_json=$status_arr;

        foreach($task_details as $task_detail){
            $task_lists_arr[] = $task_detail;   
        }
        if(isset($task_lists_arr))
        {
            $response = [
                'Api_success' => 'true',
                'task_list' => $task_lists_arr,
                'status_list' => $status_json,
            ];
        }
        else
        {
            $task_details = array();
            $response = [
                'Api_success' => 'true',
                'task_list' => $task_details,

            ];
        }
            return response($response, 201);
        
    }

    public function task_filter(Request $request){
        $id = $request->id;
        $user_details = DB::table('users')->where('deleted','No')->where('id',$request->id)->first();

        

        $limit = $request->limit ? $request->limit : 10;
        $page = $request->page && $request->page > 0 ? $request->page : 1;
        $skip = ($page - 1) * $limit;

        // $ticket_status_details = DB::table('ticket_status')->where('deleted','No')->select('ticket_status_id','ticket_status_name')->get();


        if($id!=""){
            $where="a.deleted='No'";

            $get_auth_user = DB::table('users')->where('deleted', 'No')->where('id',$id)->first();

            if($get_auth_user->designation_id==1){

                $user_lists = DB::table('users')->where('deleted', 'No')->get();

            }
            else{

                $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $id)->where('deleted', 'No')->select('id', 'first_name');

                $user_lists = DB::table('users')->where('id', $id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();

                foreach($user_lists as $user_list)
                {
                    $storedArray[] = $user_list->id;
                }
                $string = implode(', ', $storedArray);
                $where.="AND a.assign_to IN ($string)";
                
            }
            $client_id=$request->client_id;
            if($client_id=='')
            {
                $get_all_clients= DB::table('clients')->select('client_id')->where('deleted','No')->get();
                foreach($get_all_clients as $get_all_client)
                {
                    $storedArray[] = $get_all_client->client_id;
                }
                $string = implode(', ', $storedArray);
                $wherein = array_map('intval', explode(',', $string));
            }
            else
            {
                $wherein = json_decode($request->client_id, true);
                
                // $wherein="'$client_id'";
            }

            $priority_id=$request->priority_id;
            if($priority_id=='')
            {
                $get_all_priorities= DB::table('task_priority')->select('priority_id')->where('deleted','No')->get();
                foreach($get_all_priorities as $get_all_priority)
                {
                    $storedArray[] = $get_all_priority->priority_id;
                }
                $string = implode(', ', $storedArray);
                $wherein2= array_map('intval', explode(',', $string));
            }
            else
            {
                $wherein2= json_decode($request->priority_id, true);
            }

            // $project_id=$request->project_id;
            // if($project_id=='')
            // {
            //     $get_all_projects= DB::table('projects')->select('project_id')->where('deleted','No')->get();
            //     foreach($get_all_projects as $get_all_project)
            //     {
            //         $storedArray[] = $get_all_project->project_id;
            //     }
            //     $string = implode(', ', $storedArray);
            //     $where.="AND a.project_id IN ($string)";
            // }
            // else
            // {
            //     $where.=" AND a.project_id = '$project_id'";
            // }

            if($request->from_date!="" && $request->to_date!=""){
                $FromDate=$request->from_date;
                $ToDate=$request->to_date;
                $where.="AND a.created_at >= '".$FromDate." 00:00:00' AND a.created_at <= '".$ToDate." 23:59:59'";
            }

            // if($request->status_id=="" OR $request->status_id==1){

            //     $status_id = $request->status_id;
            //     $where.=" AND a.task_status = '$status_id'";

            // }
            // elseif($request->status_id==3 OR $request->status_id==4 OR $request->status_id==2){
            //     $get_all_status=DB::table('tasks')->select('task_status')->whereIn('task_status',[2,3,4])->where('deleted', 'No')->get();
            //     foreach($get_all_status as $get_status)
            //     {
            //         $storedArray[] = $get_status->task_status;
            //     }
            //     $string = implode(', ', $storedArray);
            //     $where.="AND a.task_status IN ($string)";
            // }
            // else{
            //     $status_id = $request->status_id;
            //     $where.=" AND a.task_status = '$status_id'";
            // }

            if($user_details->designation_id==1)
            {

              $task_details = DB::table('tasks as a')->whereRaw($where)->whereIn('a.client_id', $wherein)->whereIn('a.priority_id', $wherein2)->select(['a.task_id as task_id',
                    'a.task_name as task_name',
                    'a.description as description',
                    'a.status_description as status_description',
                    'a.task_status as task_status',
                    'b.attachment as attachment',
                    'a.created_at as created_at',
                    'a.updated_at as updated_at',
                    'c.client_name as client_name',
                    'd.project_name as project_name',
                    'e.first_name as assign_to',
                    'f.first_name as created_by',
                    'g.first_name as updated_by',
                    'h.status_name as status_name',
                    'i.priority_name as priority_name',
                ])->leftjoin('task_attachments as b', 'b.task_id', '=', 'a.task_id')->leftjoin('clients as c', 'c.client_id', '=', 'a.client_id')->leftjoin('projects as d', 'd.project_id', '=', 'a.project_id')->leftjoin('users as e', 'e.id', '=', 'a.assign_to')->leftjoin('users as f', 'f.id', '=', 'a.created_by')->leftjoin('users as g', 'g.id', '=', 'a.updated_by')->leftjoin('status as h', 'h.status_id', '=', 'a.task_status')->leftjoin('task_priority as i', 'i.priority_id', '=', 'a.priority_id')->orderby('task_id', 'desc')->get();
            }
            else{
                $task_details = DB::table('tasks as a')->whereRaw($where)->whereIn('a.client_id', $wherein)->whereIn('a.priority_id', $wherein2)->select(['a.task_id as task_id',
                    'a.task_name as task_name',
                    'a.description as description',
                    'a.status_description as status_description',
                    'a.task_status as task_status',
                    'b.attachment as attachment',
                    'a.created_at as created_at',
                    'a.updated_at as updated_at',
                    'c.client_name as client_name',
                    'd.project_name as project_name',
                    'e.first_name as assign_to',
                    'f.first_name as created_by',
                    'g.first_name as updated_by',
                    'h.status_name as status_name',
                    'i.priority_name as priority_name',
                ])->leftjoin('task_attachments as b', 'b.task_id', '=', 'a.task_id')->leftjoin('clients as c', 'c.client_id', '=', 'a.client_id')->leftjoin('projects as d', 'd.project_id', '=', 'a.project_id')->leftjoin('users as e', 'e.id', '=', 'a.assign_to')->leftjoin('users as f', 'f.id', '=', 'a.created_by')->leftjoin('users as g', 'g.id', '=', 'a.updated_by')->leftjoin('status as h', 'h.status_id', '=', 'a.task_status')->leftjoin('task_priority as i', 'i.priority_id', '=', 'a.priority_id')->orderby('task_id', 'desc')->get();
            }
        }
        else
        {
            $task_details = DB::table('tasks')->where('task_id', '=',$request->selected_result)->orwhere('client_id', '=', $request->selected_result)->orwhere('assign_to', '=', $request->selected_result)->select('task_id', 'client_id','assign_to')->get();
        }

        // $status_details = DB::table('status')->where('deleted', 'No')->select('status_id as id', 'status_name as name')->get();

        // foreach($status_details as $status_detail) {
        //     $status_arr[]=$status_detail;
        // }
        // $status_json=$status_arr;

        foreach($task_details as $task_detail){
            $task_lists_arr[] = $task_detail;   
        }
        if(isset($task_lists_arr))
        {
            $response = [
                'Api_success' => 'true',
                'task_list' => $task_lists_arr,
                // 'status_list' => $status_json,
            ];
        }
        else
        {
            $task_details = array();
            $response = [
                'Api_success' => 'true',
                'task_list' => $task_details,

            ];
        }
            return response($response, 201);
        
    }

    public function leave_approval_add(Request $request){
        if($request->user_id){ 
        $user_id=$request->user_id;
        $users = User::find($user_id);
        $token = $users->createToken('active')->plainTextToken;
         if($attachment_file=$request->file('attachment'))
            {
                $destination_path = 'public/LeavesUpload/';
                $attachment_name = date('YmdHis').".".$attachment_file->getClientOriginalExtension();
                $attachment_file->move($destination_path,$attachment_name);
            }
            else{
                $attachment_name='';
            }
            $get_user = DB::table('users')->where('id', $user_id)->select('reporting_to_id')->first();
            $reporting_to = $get_user->reporting_to_id;
            $num_of_days_sec = strtotime($request->from_date)-strtotime($request->to_date);
            $num_of_days = round($num_of_days_sec/(60*60*24))+1;
            $data = [
                'user_id'=>$user_id,
                'approval_status'=>'Pending',
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'leave_type' => $request->leave_type,
                'attachment' => $attachment_name,
                'approval_person'=>$reporting_to,
                'num_of_days'=>$num_of_days,
                'created_by' =>$user_id,
                'created_at' => Now(),
            ];

            $leave_type_add=DB::table('leave_approvals')->insertGetId($data);
            $notification_to = $reporting_to;
            $get_user_name = DB::table('users')->where('id', $notification_to)->select('first_name')->first();
            $notification_to_name = $get_user_name->first_name;
            $URL="leave_approvals";
            $Descriptions="Dear $notification_to_name, You Have One Leave Approval : Thank You.";
            $notification_data = array('title' => 'Leave Approval', 'description' => $Descriptions, 'url' => $URL, 'notification_to' => $notification_to, 'created_by' => $user_id, 'created_at' => Now());
            $insert_notification = DB::table('notifications')->insert($notification_data);

            $response = [
            'Leave Approval'=>$data,
            'token'=>$token,
            'Api_success' => 'True',
            'Api_message' => 'Approval Create Successfully!'
        ];
        }
        else{
            $response = [
            'Api_success' => 'false',
            'Api_message' => 'Approval Create Failed!'
        ];
        }
        return response($response, 201);
    }

    public function leave_approval_list(Request $request)
    {
        $user_id=$request->user_id;
        $approval_list=DB::table('leave_approvals as a')->where('a.user_id',$user_id)->where('a.deleted', 'No')->select(['a.leave_approval_id as leave_approval_id',
            'a.user_id as user_id',
            'a.from_date as from_date',
            'a.to_date as to_date',
            'a.num_of_days as num_of_days',
            'a.approval_person as approval_person',
            'a.approval_status as approval_status',
            'a.leave_description as leave_description',
            'a.approval_comments as approval_comments',
            'c.status_name as leave_type',
            'd.first_name as created_by',
            'a.created_at as created_at',
            'e.first_name as updated_by',
            'a.approved_at as approved_at',
            ])->leftjoin('users as b', 'b.id', '=', 'a.approval_person')->leftjoin('status as c','c.status_id', '=', 'a.leave_type')->leftjoin('users as d', 'd.id', '=', 'a.created_by')->leftjoin('users as e','e.id', '=', 'a.updated_by')->get();
        
        foreach($approval_list as $approval){
            $approval_arr[] = $approval;   
        }
        if(isset($approval_arr))
        {
            $response = [
                'Api_success' => 'true',
                'Aprrovals' => $approval_arr,
            ];
        }
        else
        {
            $response = [
                'Api_success' => 'false',
                'Aprrovals' => 'list not found',
            ];
        }
        return response($response, 201);


    }

    public function ticket_lists(Request $request){
        $id = $request->id;
        $user_details=DB::table('users')->where('deleted','No')->where('id',$request->id)->first();

        $limit = $request->limit ? $request->limit : 10;
        $page = $request->page && $request->page > 0 ? $request->page : 1;
        $skip = ($page - 1) * $limit;

        $ticket_status_details = DB::table('ticket_status')->where('deleted','No')->select('ticket_status_id','ticket_status_name')->get();

        $get_auth_user = DB::table('users')->where('deleted', 'No')->where('id',$id)->first();


        if($id!=""){
            $where="a.deleted='No'";

            $get_auth_user = DB::table('users')->where('deleted', 'No')->where('id',$id)->first();

            if($get_auth_user->designation_id==1){

                $user_lists = DB::table('users')->where('deleted', 'No')->get();

            }
            else{

                $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $id)->where('deleted', 'No')->select('id', 'first_name');

                $user_lists = DB::table('users')->where('id', $id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();

                foreach($user_lists as $user_list)
                {
                    $storedArray[] = $user_list->id;
                }
                $string = implode(', ', $storedArray);
                $where.="AND a.assign_to IN ($string)";
                
            }

            $customer_id=$request->customer_id;
            if($customer_id=='')
            {
                $get_all_clients= DB::table('clients')->select('client_id')->where('deleted','No')->get();
                foreach($get_all_clients as $get_all_client)
                {
                    $storedArray[] = $get_all_client->client_id;
                }
                $string = implode(', ', $storedArray);
                $where.="AND a.client_id IN ($string)";
            }
            else
            {
                $where.=" AND a.client_id = '$customer_id'";
            }

            $priority_id=$request->priority_id;
            if($priority_id=='')
            {
                $get_all_priorities= DB::table('ticket_priority')->select('priority_id')->where('deleted','No')->get();
                foreach($get_all_priorities as $get_all_priority)
                {
                    $storedArray[] = $get_all_priority->priority_id;
                }
                $string = implode(', ', $storedArray);
                $where.="AND a.priority_id IN ($string)";
            }
            else
            {
                $where.=" AND a.priority_id = '$priority_id'";
            }

            if($request->from_date!="" && $request->to_date!=""){
                $FromDate=$request->from_date;
                $ToDate=$request->to_date;
                $where.="AND a.created_at >= '".$FromDate." 00:00:00' AND a.created_at <= '".$ToDate." 23:59:59'";
            }

            if($request->status_id=="" OR $request->status_id==1){

                $status_id[] = $request->status_id;
                $wherein = $status_id;

            }
            elseif($request->status_id==3 OR $request->status_id==4 OR $request->status_id==2){
                $status_array = [2,3,4];
                $wherein = $status_array;
            }
            else{
                $status_id[] = $request->status_id;
                $wherein = $status_id;
            }

            if($user_details->designation_id==1)
            {
                
                
                $ticket_lists=DB::table('tickets as a')->whereRaw($where)->whereIn('a.status_id', $wherein)->select(['a.ticket_id',
                'b.client_name as client_name',
                'c.first_name as first_name',
                'd.ticket_type_name as ticket_type_name',
                'e.priority_name as priority_name',
                'a.created_at',
                'f.first_name as assign_to',
                'a.subject',
                'a.description',
                'g.ticket_status_name as ticket_status_name',
                'a.ticket_update',
                'h.ticket_created_type_name as ticket_created_type_name',
                'i.first_name as created_by',
                'j.ticket_source_name as ticket_source_name',
                'a.updated_type_id',
                'k.first_name as updated_by',
                ])->leftjoin('clients as b','b.client_id', '=', 'a.client_id')->leftjoin('customer_contacts as c','c.customer_contact_id', '=', 'a.customer_contact_id')->leftjoin('ticket_types as d','d.ticket_type_id', '=', 'a.ticket_type_id')->leftjoin('ticket_priority as e', 'e.priority_id', '=', 'a.priority_id')->leftjoin('users as f', 'f.id', '=', 'a.assign_to')->leftjoin('ticket_status as g', 'g.ticket_status_id', '=', 'a.status_id')->leftjoin('ticket_created_type as h', 'h.ticket_created_type_id', '=', 'a.ticket_created_type_id')->leftjoin('users as i', 'i.id', '=', 'a.created_by')->leftjoin('ticket_sources as j', 'j.ticket_source_id', '=', 'a.source_id')->leftjoin('users as k', 'k.id', '=', 'a.updated_by')->orderby('ticket_id', 'desc')->paginate($limit);
            }
            else
            {

                $ticket_lists=DB::table('tickets as a')->whereRaw($where)->whereIn('a.status_id', $wherein)->select(['a.ticket_id',
                'b.client_name as client_name',
                'c.first_name as first_name',
                'd.ticket_type_name as ticket_type_name',
                'e.priority_name as priority_name',
                'a.created_at',
                'f.first_name as assign_to',
                'a.subject',
                'a.description',
                'g.ticket_status_name as ticket_status_name',
                'a.ticket_update',
                'h.ticket_created_type_name as ticket_created_type_name',
                'i.first_name as created_by',
                'j.ticket_source_name as ticket_source_name',
                'a.updated_type_id',
                'k.first_name as updated_by',
                ])->leftjoin('clients as b','b.client_id', '=', 'a.client_id')->leftjoin('customer_contacts as c','c.customer_contact_id', '=', 'a.customer_contact_id')->leftjoin('ticket_types as d','d.ticket_type_id', '=', 'a.ticket_type_id')->leftjoin('ticket_priority as e', 'e.priority_id', '=', 'a.priority_id')->leftjoin('users as f', 'f.id', '=', 'a.assign_to')->leftjoin('ticket_status as g', 'g.ticket_status_id', '=', 'a.status_id')->leftjoin('ticket_created_type as h', 'h.ticket_created_type_id', '=', 'a.ticket_created_type_id')->leftjoin('users as i', 'i.id', '=', 'a.created_by')->leftjoin('ticket_sources as j', 'j.ticket_source_id', '=', 'a.source_id')->leftjoin('users as k', 'k.id', '=', 'a.updated_by')->orderby('ticket_id', 'desc')->paginate($limit);
            }
        }
        else{
            $ticket_lists = DB::table('tickets')->where('ticket_id', '=',$request->selected_result)->orwhere('client_id', '=', $request->selected_result)->orwhere('assign_to', '=', $request->selected_result)->select('ticket_id', 'client_id','assign_to')->get();
        }        

        foreach($ticket_lists as $ticket_list){
            $tickets_arr[] = $ticket_list;
        }

        if(isset($tickets_arr)){

            $response =[
                'Api_success' => 'true',
                'tickets' => $tickets_arr,
                'user_lists' => $user_lists,
                'ticket_status_details' => $ticket_status_details,
            ];
        }
        else{
            $ticket_lists=array();
            $response = [
                'Api_false' => 'true',
                'tickets' => $ticket_lists,
            ];
        }
        return response($response, 201);
    }

    public function ticket_filter(Request $request){
        $id = $request->id;
        $user_details=DB::table('users')->where('deleted','No')->where('id',$request->id)->first();

        // $limit = $request->limit ? $request->limit : 10;
        // $page = $request->page && $request->page > 0 ? $request->page : 1;
        // $skip = ($page - 1) * $limit;

        // $ticket_status_details = DB::table('ticket_status')->where('deleted','No')->select('ticket_status_id','ticket_status_name')->get();

        $get_auth_user = DB::table('users')->where('deleted', 'No')->where('id',$id)->first();

        $client_id=$request->client_id;
            if($client_id=='')
            {
                $get_all_clients= DB::table('clients')->select('client_id')->where('deleted','No')->get();
                foreach($get_all_clients as $get_all_client)
                {
                    $storedArray[] = $get_all_client->client_id;
                }
                $string = implode(', ', $storedArray);
                $wherein = array_map('intval', explode(',', $string));
            }

        

        if($id!=""){
            $where="a.deleted='No'";

            $get_auth_user = DB::table('users')->where('deleted', 'No')->where('id',$id)->first();

            if($get_auth_user->designation_id==1){

                $user_lists = DB::table('users')->where('deleted', 'No')->get();

            }
            else{

                $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $id)->where('deleted', 'No')->select('id', 'first_name');

                $user_lists = DB::table('users')->where('id', $id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();

                foreach($user_lists as $user_list)
                {
                    $storedArray[] = $user_list->id;
                }
                $string = implode(', ', $storedArray);
                $where.="AND a.assign_to IN ($string)";
                
            }

            $client_id=$request->client_id;
            if($client_id=='')
            {
                $get_all_clients= DB::table('clients')->select('client_id')->where('deleted','No')->get();
                foreach($get_all_clients as $get_all_client)
                {
                    $storedArray[] = $get_all_client->client_id;
                }
                $string = implode(', ', $storedArray);
                $wherein = array_map('intval', explode(',', $string));
            }
            else
            {
                $wherein = json_decode($request->client_id, true);
                
                // $wherein="'$client_id'";
            }

            $priority_id=$request->priority_id;
            if($priority_id=='')
            {
                $get_all_priorities= DB::table('ticket_priority')->select('priority_id')->where('deleted','No')->get();
                foreach($get_all_priorities as $get_all_priority)
                {
                    $storedArray[] = $get_all_priority->priority_id;
                }
                $string = implode(', ', $storedArray);
                $wherein2= array_map('intval', explode(',', $string));
            }
            else
            {
                $wherein2= json_decode($request->priority_id, true);
            }

            if($request->from_date!="" && $request->to_date!=""){
                $FromDate=$request->from_date;
                $ToDate=$request->to_date;
                $where.="AND a.created_at >= '".$FromDate." 00:00:00' AND a.created_at <= '".$ToDate." 23:59:59'";
            }

            // if($request->status_id=="" OR $request->status_id==1){

            //     $status_id = $request->status_id;
            //     $where.="AND a.status_id = '$status_id'";

            // }
            // elseif($request->status_id==3 OR $request->status_id==4 OR $request->status_id==2){
            //     $get_all_status=DB::table('tickets')->select('status_id')->whereIn('status_id',[2,3,4])->where('deleted', 'No')->get();
            //     foreach($get_all_status as $get_status)
            //     {
            //         $storedArray[] = $get_status->status_id;
            //     }
            //     $string = implode(', ', $storedArray);
            //     $where.="AND a.status_id IN ($string)";
            // }
            // else{
            //     $status_id = $request->status_id;
            //     $where.="AND a.status_id = '$status_id'";
            // }

            if($user_details->designation_id==1)
            {
                
                
                $ticket_lists=DB::table('tickets as a')->whereRaw($where)->whereIn('a.client_id',$wherein)->whereIn('a.priority_id', $wherein2)->select(['a.ticket_id',
                'b.client_name as client_name',
                'c.first_name as first_name',
                'd.ticket_type_name as ticket_type_name',
                'e.priority_name as priority_name',
                'a.created_at',
                'f.first_name as assign_to',
                'a.subject',
                'a.description',
                'g.ticket_status_name as ticket_status_name',
                'a.ticket_update',
                'h.ticket_created_type_name as ticket_created_type_name',
                'i.first_name as created_by',
                'j.ticket_source_name as ticket_source_name',
                'a.updated_type_id',
                'k.first_name as updated_by',
                ])->leftjoin('clients as b','b.client_id', '=', 'a.client_id')->leftjoin('customer_contacts as c','c.customer_contact_id', '=', 'a.customer_contact_id')->leftjoin('ticket_types as d','d.ticket_type_id', '=', 'a.ticket_type_id')->leftjoin('ticket_priority as e', 'e.priority_id', '=', 'a.priority_id')->leftjoin('users as f', 'f.id', '=', 'a.assign_to')->leftjoin('ticket_status as g', 'g.ticket_status_id', '=', 'a.status_id')->leftjoin('ticket_created_type as h', 'h.ticket_created_type_id', '=', 'a.ticket_created_type_id')->leftjoin('users as i', 'i.id', '=', 'a.created_by')->leftjoin('ticket_sources as j', 'j.ticket_source_id', '=', 'a.source_id')->leftjoin('users as k', 'k.id', '=', 'a.updated_by')->orderby('ticket_id', 'desc')->get();
            }
            else
            {

                $ticket_lists=DB::table('tickets as a')->whereRaw($where)->whereIn('a.client_id',$wherein)->whereIn('a.priority_id', $wherein2)->select(['a.ticket_id',
                'b.client_name as client_name',
                'c.first_name as first_name',
                'd.ticket_type_name as ticket_type_name',
                'e.priority_name as priority_name',
                'a.created_at',
                'f.first_name as assign_to',
                'a.subject',
                'a.description',
                'g.ticket_status_name as ticket_status_name',
                'a.ticket_update',
                'h.ticket_created_type_name as ticket_created_type_name',
                'i.first_name as created_by',
                'j.ticket_source_name as ticket_source_name',
                'a.updated_type_id',
                'k.first_name as updated_by',
                ])->leftjoin('clients as b','b.client_id', '=', 'a.client_id')->leftjoin('customer_contacts as c','c.customer_contact_id', '=', 'a.customer_contact_id')->leftjoin('ticket_types as d','d.ticket_type_id', '=', 'a.ticket_type_id')->leftjoin('ticket_priority as e', 'e.priority_id', '=', 'a.priority_id')->leftjoin('users as f', 'f.id', '=', 'a.assign_to')->leftjoin('ticket_status as g', 'g.ticket_status_id', '=', 'a.status_id')->leftjoin('ticket_created_type as h', 'h.ticket_created_type_id', '=', 'a.ticket_created_type_id')->leftjoin('users as i', 'i.id', '=', 'a.created_by')->leftjoin('ticket_sources as j', 'j.ticket_source_id', '=', 'a.source_id')->leftjoin('users as k', 'k.id', '=', 'a.updated_by')->orderby('ticket_id', 'desc')->get();
            }
        }
        else{
            $ticket_lists = DB::table('tickets')->where('ticket_id', '=',$request->selected_result)->orwhere('client_id', '=', $request->selected_result)->orwhere('assign_to', '=', $request->selected_result)->select('ticket_id', 'client_id','assign_to')->get();
        }        

        foreach($ticket_lists as $ticket_list){
            $tickets_arr[] = $ticket_list;
        }

        if(isset($tickets_arr)){

            $response =[
                'Api_success' => 'true',
                'tickets' => $tickets_arr,
                // 'user_lists' => $user_lists,
                // 'ticket_status_details' => $ticket_status_details,
            ];
        }
        else{
            $ticket_lists=array();
            $response = [
                'Api_false' => 'true',
                'tickets' => $ticket_lists,
                'client_id' => $wherein,
            ];
        }
        return response($response, 201);
    }
}

    // public function ticket_view(Request $request){

    //    $ticket_view=DB::table('tickets as a')->where('a.deleted','No')->where('a.ticket_id',$request->id)->select(['a.ticket_id  as ticket_id',
    //         'b.client_name as client_name',
    //         'c.first_name as first_name',
    //         'd.ticket_type_name as ticket_type_name',
    //         'e.priority_name as priority_name',
    //         'a.created_at as created_at',
    //         'f.first_name as assign_to',
    //         'a.subject as subject',
    //         'a.description as description',
    //         'g.ticket_status_name as ticket_status_name',
    //         'a.ticket_update as ticket_update',
    //         'h.ticket_created_type_name as ticket_created_type_name',
    //         'i.first_name as created_by',
    //         'j.ticket_source_name as ticket_source_name',
    //         'a.updated_type_id as updated_type_id',
    //         'k.first_name as updated_by',
    //         'a.custom_fields as custom_fields',
    //         'a.deleted as deleted',
    //         'a.deleted_reason as deleted_reason',
    //         ])->leftjoin('clients as b','b.client_id', '=', 'a.client_id')->leftjoin('customer_contacts as c','c.customer_contact_id', '=', 'a.customer_contact_id')->leftjoin('ticket_types as d','d.ticket_type_id', '=', 'a.ticket_type_id')->leftjoin('ticket_priority as e', 'e.priority_id', '=', 'a.priority_id')->leftjoin('users as f', 'f.id', '=', 'a.assign_to')->leftjoin('ticket_status as g', 'g.ticket_status_id', '=', 'a.status_id')->leftjoin('ticket_created_type as h', 'h.ticket_created_type_id', '=', 'a.ticket_created_type_id')->leftjoin('users as i', 'i.id', '=', 'a.created_by')->leftjoin('ticket_sources as j', 'j.ticket_source_id', '=', 'a.source_id')->leftjoin('users as k', 'k.id', '=', 'a.updated_by')->first();

    //     return response($response, 201);

    // }



