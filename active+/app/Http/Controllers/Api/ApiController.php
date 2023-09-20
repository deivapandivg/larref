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

    public function pettycash_expense_list(Request $request)
    {
        
        $user_id = $request->id;

        $users_list = DB::table('users')->where('deleted', 'No')->select('id', 'first_name')->get();

        $pettycash_amounts = DB::table('pettycash')->where('deleted', 'No')->Sum('amount');
        $pettycash_topup_amounts = DB::table('pettycash_topup')->where('deleted', 'No')->Sum('amount');
        function RemoveSpecialChar($str)
        {
        $res = preg_replace('/[-\@\.\;\" "]+/', '', $str);
        return $res;
        }
        $balances = $pettycash_amounts-$pettycash_topup_amounts;
        $balance1 = RemoveSpecialChar($balances);

        $expense_list = DB::table('pettycash as a')->where('a.created_by',$user_id)->where('a.deleted', 'No')->select(['a.pettycash_id',
            'a.pettycash_date',
            'a.particulars',
            'a.voucher_number',
            'a.bill_receipt_number',
            'b.first_name as user_id',
            'a.amount',
            'a.balance_history',
            'c.first_name as created_by',
            'a.created_at',
            'd.first_name as updated_by',
            'a.updated_at',
            ])->leftjoin('users as b', 'b.id', '=', 'a.user_id')->leftjoin('users as c','c.id', '=', 'a.created_by')->leftjoin('users as d', 'd.id', '=', 'a.updated_by')->get();
        
        foreach($expense_list as $expense){

            $expense_arr[] = $expense;   

        }

        if(isset($expense_arr))
        {
            $response = [
                'Api_success' => 'true',
                'Expense List' => $expense_arr,
                'Users List' => $users_list,
                'Available_balance' => $balance1,
            ];
        }
        else
        {
            $list_not_found = array();
            $response = [
                'Api_success' => 'false',
                'Expense List' => $list_not_found,
            ];
        }
        return response($response, 201);


    }

    public function expense_add(Request $request){

        $createdby = $request->id;
        if($createdby!=""){

            $pettycash_amounts = DB::table('pettycash')->where('deleted', 'No')->Sum('amount');
            $pettycash_topup_amounts = DB::table('pettycash_topup')->where('deleted', 'No')->Sum('amount');
            function RemoveSpecialChar($str)
            {
            $res = preg_replace('/[-\@\.\;\" "]+/', '', $str);
            return $res;
            }
            $balances = $pettycash_topup_amounts-$pettycash_amounts-$request->amount;
            $balance1 = RemoveSpecialChar($balances);

            $data = array('pettycash_date' => $request->pettycash_date,
            'particulars' => $request->particulars,
            'voucher_number' => $request->voucher_number,
            'bill_receipt_number' => $request->bill_receipt_number,
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'balance_history' => $balance1,
            'created_by' => $createdby, 
            'created_at' => Now());

            $pettycash_add=DB::table('pettycash')->insertGetId($data);

            if($request->hasFile('bill_upload')) {

                $files = $request->bill_upload; 
                foreach($files as $file)
                {
                    
                    $name = time() . rand(1, 100) . '.' . $file->extension();
                    $file->move(public_path('bill_uploads'),$name);
                    
                    $attachment_add = array('pettycash_id' => $pettycash_add, 'attachment' => $name, 'created_by' => $createdby, 'created_at' => Now());
                    $pettycash_attachment_add = DB::table('pettycash_attachments')->insert($attachment_add);

                }

            }

            $response = [
                'Api_success' => 'true',
                'Api_message' => 'Expense Added Successfully.',
                'Attachment' => $pettycash_attachment_add,
            ];
            
        }
        else{

            $response = [
                'Api_success' => 'false',
                'Api_message' => 'Something Went Wrong!',
            ];

        }

        return response($response,201);

    }

    public function expense_edit(Request $request){

        $createdby = $request->id;
        $pettycash_id = $request->pettycash_id;
        if($pettycash_id!=""){

            $pettycash_amounts = DB::table('pettycash')->where('deleted', 'No')->Sum('amount');
            $pettycash_edit_amounts = DB::table('pettycash')->where('pettycash_id', $pettycash_id)->where('deleted', 'No')->Sum('amount');
            $pettycash_topup_amounts = DB::table('pettycash_topup')->where('deleted', 'No')->Sum('amount');
            function RemoveSpecialChar($str)
            {
            $res = preg_replace('/[-\@\.\;\" "]+/', '', $str);
            return $res;
            }
            $balances = $pettycash_edit_amounts+$pettycash_topup_amounts-$pettycash_amounts-$request->amount;
            $balance1 = RemoveSpecialChar($balances);

            $data = array('pettycash_date' => $request->pettycash_date,
            'particulars' => $request->particulars,
            'voucher_number' => $request->voucher_number,
            'bill_receipt_number' => $request->bill_receipt_number,
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'balance_history' => $balance1,
            'created_by' => $createdby, 
            'created_at' => Now());

            $pettycash_update=DB::table('pettycash')->where('pettycash_id', $pettycash_id)->update($data);

            if(isset($pettycash_update))
            {
                
                $pettycashIdNew=DB::table('pettycash')->select('*')->where('pettycash_id',$request->pettycash_id)->first();
                $pettycash_attachment_id=$pettycashIdNew->pettycash_id;

                $DeleteFilesItems = DB::table('pettycash_attachments')->where('pettycash_id', $request->pettycash_id)->delete();
                
                if ($request->existing_bill_upload!="") {

                   $old_files=[];

                   foreach ($request->existing_bill_upload as $oldfile) {
                      $name = $oldfile;
                      
                      $old_files[] = $name;
                   }
                   

                   for ($i = 0; $i < count($old_files); $i++) {

                      $OldFilesArr = $old_files[$i];
                      
                      $old_attachment_add = array('pettycash_id' => $pettycash_attachment_id, 'attachment' => $OldFilesArr, 'created_by' => $createdby, 'created_at' => Now());

                      $old_pettycash_attachment_add = DB::table('pettycash_attachments')->insert($old_attachment_add);

                   }
                }

                if($request->bill_upload!=""){

                    $files = [];
                    foreach ($request->file('bill_upload') as $file) {
                        $name = time() . rand(1, 100) . '.' . $file->extension();
                        $file->move(public_path('bill_uploads'), $name);
                        $files[] = $name;
                    }

                    for ($i = 0; $i < count($files); $i++) {

                        $attachments = $files[$i];
                        $attachment_add = array('pettycash_id' => $pettycash_attachment_id, 'attachment' => $attachments, 'created_by' => $createdby, 'created_at' => Now());
                        $pettycash_attachment_add = DB::table('pettycash_attachments')->insert($attachment_add);
                     
                    }
                }
            }

            $response = [
                'Api_success' => 'true',
                'Api_message' => 'Expense Updated Successfully.',
                'update' => $pettycash_update,
            ];

        }
        else{

            $response = [
                'Api_success' => 'false',
                'Api_message' => 'Something Went Wrong!',
            ];

        }

        return response($response,201);

    }

    public function expense_delete(Request $request){

        $pettycash_id = $request->pettycash_id;
        $user_id = $request->user_id;
        $deleted_reason = $request->deleted_reason;
        if($pettycash_id!=""){

            $data = ['updated_by' => $user_id, 'updated_at' => Now(), 'deleted' => 'Yes', 'deleted_reason' => $deleted_reason];

            $delete = DB::table('pettycash')->where('pettycash_id', $pettycash_id)->update($data);

            $response = [
                'Api_success' => 'True',
                'Api_message' => 'Pettycash Deleted Successfully!.',
            ];
        }
        else{

            $response = [
                'Api_success' => 'false',
                'Api_message' => 'Something Went Wrong!.',
            ];

        }

        return response($response,201);

    }

    public function quote_add(Request $request){

        if(isset($request->GSTType)){  

            if($request->GSTType==1){

                $product_result = $request->quotationItems;
                $products = json_encode($product_result);

                $service_result = $request->quotationServiceItems;
                $services = json_encode($service_result);

                // $products = $request->quotationItems;
                // $services = $request->quotationServiceItems;


                $data = array( 
                    'client_id' => $request->client_id,
                    'date_issue' => $request->qualification_date,
                    'date_due' => $request->valid_date,
                    'igst_type' => $request->GSTType,
                    'total_amount' => $request->TotalAmount,
                    'total_igst' => $request->TotalIGST,
                    'grand_total' => $request->GrandTotal,
                    'quotation_items' => $products,
                    'quotation_service_items' => $services,
                    'created_by' => $request->user_id, 
                    'created_at' => Now()
                );

                $quotation_product_service_add=DB::table('quotations')->insertGetId($data);
                
                if(isset($quotation_product_service_add))
                { 

                    if($request->quotationItems!=""){

                        $items = json_encode($request->quotationItems);
                        $itemss = json_decode($items);
                        // $itemss=json_decode($request->quotationItems);

                        foreach($itemss as $quotationItem){
                            $Product=$quotationItem->Product;
                            $Cost=$quotationItem->Cost;
                            $Quantity=$quotationItem->Quantity;
                            $GST=$quotationItem->GST;
                            $IGST=$quotationItem->IGST;
                            $Amount=$quotationItem->Amount;
                            $ProductDescription=$quotationItem->ProductDescription;
                            $quotationItems=array("quotationId" => $quotation_product_service_add,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $request->user_id, 'created_at' => Now());
                            $quotation_items_add=DB::table('quotation_items')->insert($quotationItems);
                        }
                    }

                    if($request->quotationServiceItems!="")
                    {
                        $service_items = json_encode($request->quotationServiceItems);
                        $service_itemss = json_decode($service_items);
                        // $service_itemss = json_decode($request->quotationServiceItems);

                        foreach($service_itemss as $quotationServiceItem){
                            $Service=$quotationServiceItem->Service;
                            $Cost=$quotationServiceItem->Cost;
                            $Quantity=$quotationServiceItem->Quantity;
                            $GST=$quotationServiceItem->GST;
                            $IGST=$quotationServiceItem->IGST;
                            $Amount=$quotationServiceItem->Amount;
                            $ServiceDescription=$quotationServiceItem->ServiceDescription;
                            $quotationServiceItemss=array("quotationId" => $quotation_product_service_add,"Service"=>$Service, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "Amount"=>$Amount, "ServiceDescription"=>$ServiceDescription,'created_by' => $request->user_id, 'created_at' => Now());
                            $quotation_service_items_add=DB::table('quotation_items')->insert($quotationServiceItemss);
                        }
                    }
                
                    $response = [
                        'Api_success' => 'true', 
                        'Api_message' => $quotation_items_add, 
                    ];
                }
            }
            else{


                $product_result = $request->quotationItems;
                $products = json_encode($product_result);

                $service_result = $request->quotationServiceItems;
                $services = json_encode($service_result);

                // $products = $request->quotationItems;
                // $services = $request->quotationServiceItems;


                $data = array( 
                    'client_id' => $request->client_id,
                    'date_issue' => $request->qualification_date,
                    'date_due' => $request->valid_date,
                    'igst_type' => $request->GSTType,
                    'total_amount' => $request->TotalAmount,
                    'total_cgst' => $request->TotalCGST,
                    'total_sgst' => $request->TotalSGST,
                    'grand_total' => $request->GrandTotal,
                    'quotation_items' => $products,
                    'quotation_service_items' => $services,
                    'created_by' => $request->user_id, 
                    'created_at' => Now()
                );

                $quotation_product_service_add=DB::table('quotations')->insertGetId($data);
                
                if(isset($quotation_product_service_add))
                {
                    
                    if($request->quotationItems!=""){

                        $items = json_encode($request->quotationItems);
                        $itemss = json_decode($items);
                        // $itemss=json_decode($request->quotationItems);

                        foreach($itemss as $quotationItem){
                            $Product=$quotationItem->Product;
                            $Cost=$quotationItem->Cost;
                            $Quantity=$quotationItem->Quantity;
                            $GST=$quotationItem->GST;
                            $CGST=$quotationItem->CGST;
                            $SGST=$quotationItem->SGST;
                            $Amount=$quotationItem->Amount;
                            $ProductDescription=$quotationItem->ProductDescription;
                            $quotationItems=array("quotationId" => $quotation_product_service_add,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $request->user_id, 'created_at' => Now());

                            $quotation_items_add=DB::table('quotation_items')->insert($quotationItems);
                        }
                    }

                    if($request->quotationServiceItems!=""){

                        $service_items = json_encode($request->quotationServiceItems);
                        $service_itemss = json_decode($service_items);
                        // $service_itemss = json_decode($request->quotationServiceItems);

                        foreach($service_itemss as $quotationServiceItem){
                            $Service=$quotationServiceItem->Service;
                            $Cost=$quotationServiceItem->Cost;
                            $Quantity=$quotationServiceItem->Quantity;
                            $GST=$quotationServiceItem->GST;
                            $CGST=$quotationServiceItem->CGST;
                            $SGST=$quotationServiceItem->SGST;
                            $Amount=$quotationServiceItem->Amount;
                            $ServiceDescription=$quotationServiceItem->ServiceDescription;
                            $quotationItems=array("quotationId" => $quotation_product_service_add,"Service"=>$Service, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ServiceDescription"=>$ServiceDescription,'created_by' => $request->user_id, 'created_at' => Now());

                            $quotation_items_add=DB::table('quotation_items')->insert($quotationItems);
                        }
                    }
                }
                    
                $response = [
                    'Api_success' => 'true', 
                    'Api_message' => $quotation_items_add, 
                ];
            }
        }
        else{
            $response = [
                'Api_success' => 'false', 
                'Api_success' => 'Something Went Wrong!.', 
            ];
        }

        return response($response,201);
    }

    public function get_quote(Request $request){

        $user_id = $request->user_id;
        $quote_id = $request->quotation_id;
        if(isset($quote_id)){

            $get_quote = DB::table('quotations as a')->where('a.quotation_id', $quote_id)->where('a.deleted', 'No')->where('a.created_by', $user_id)->select(['a.quotation_id',
                'd.client_name',
                'a.date_issue',
                'a.date_due',
                'a.igst_type',
                'a.total_amount',
                'a.total_igst',
                'a.total_cgst',
                'a.total_sgst',
                'a.grand_total',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('clients as d', 'd.client_id', '=', 'a.client_id')->first();

            $get_attachments = DB::table('quotation_items')->where('QuotationId', $quote_id)->where('deleted','No')->select('Product','Service','Quantity','Cost','GST','IGST','CGST','SGST','Amount','ProductDescription','ServiceDescription')->get();

            foreach($get_attachments as $get_attachment){

                $attachments[] = $get_attachment;

            }

            $response = [
                'Api_success' => 'true', 
                'get_quote' => $get_quote, 
                'get_attachments' => $attachments, 
            ];

        }
        else{

            $get_quote = array();
            $attachments = array();

            $response = [
                'Api_success' => 'false', 
                'get_quote' => $get_quote, 
                'get_attachments' => $attachments,  
            ];

        }

        return response($response,201);

    }

    public function quote_edit(Request $request){

        if(isset($request->quotation_id))
        {

            if($request->GSTType == 1){

                $product_result = $request->quotationItems;
                $products = json_encode($product_result);

                // $service_result = $request->quotationServiceItems;
                // $services = json_encode($service_result);

                // $products = $request->quotationItems;
                // $services = $request->quotationServiceItems;


                $data = array( 
                    'client_id' => $request->client_id,
                    'date_issue' => $request->qualification_date,
                    'date_due' => $request->valid_date,
                    'igst_type' => $request->GSTType,
                    'total_amount' => $request->TotalAmount,
                    'total_igst' => $request->TotalIGST,
                    'grand_total' => $request->GrandTotal,
                    'quotation_items' => $products,
                    // 'quotation_service_items' => $services,
                    'updated_by' => $request->user_id, 
                    'updated_at' => Now()
                );

                $quotation_product_service_edit = DB::table('quotations')->where('quotation_id', $request->quotation_id)->update($data);
                
                if(isset($quotation_product_service_edit))
                { 

                    $quotation_product_items=DB::table('quotation_items')->where('QuotationId',$request->quotation_id)->get();

                    foreach($quotation_product_items as $quotation_product_item){

                        if($quotation_product_item->Product!=''){

                            $quotation_product_items_delete=DB::table('quotation_items')->where('QuotationId',$request->quotation_id)->delete();

                        }
                        // elseif($quotation_product_item->Service!=''){

                        //     $quotation_service_items_delete=DB::table('quotation_items')->where('QuotationId',$request->quotation_id)->delete();

                        // }

                    }

                    $quotationIdNew=DB::table('quotations')->select('*')->where('quotation_id',$request->quotation_id)->first();
                    $quotation_item_id=$quotationIdNew->quotation_id;

                    if($request->quotationItems!=""){

                        $items = json_encode($request->quotationItems);
                        $itemss = json_decode($items);
                        // $itemss=json_decode($request->quotationItems);

                        foreach($itemss as $quotationItem){
                            $Product=$quotationItem->Product;
                            $Cost=$quotationItem->Cost;
                            $Quantity=$quotationItem->Quantity;
                            $GST=$quotationItem->GST;
                            $IGST=$quotationItem->IGST;
                            $Amount=$quotationItem->Amount;
                            $ProductDescription=$quotationItem->ProductDescription;
                            $quotationItems=array("quotationId" => $quotation_item_id,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $request->user_id, 'created_at' => Now());
                            $quotation_service_items_add=DB::table('quotation_items')->insert($quotationItems);
                        }
                    }

                    // if($request->quotationServiceItems!="")
                    // {
                    //     $service_items = json_encode($request->quotationServiceItems);
                    //     $service_itemss = json_decode($service_items);
                    //     // $service_itemss = json_decode($request->quotationServiceItems);

                    //     foreach($service_itemss as $quotationServiceItem){
                    //         $Service=$quotationServiceItem->Service;
                    //         $Cost=$quotationServiceItem->Cost;
                    //         $Quantity=$quotationServiceItem->Quantity;
                    //         $GST=$quotationServiceItem->GST;
                    //         $IGST=$quotationServiceItem->IGST;
                    //         $Amount=$quotationServiceItem->Amount;
                    //         $ServiceDescription=$quotationServiceItem->ServiceDescription;
                    //         $quotationServiceItemss=array("quotationId" => $quotation_item_id,"Service"=>$Service, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "Amount"=>$Amount, "ServiceDescription"=>$ServiceDescription,'created_by' => $request->user_id, 'created_at' => Now());
                    //         $quotation_service_items_add=DB::table('quotation_items')->insert($quotationServiceItemss);
                    //     }
                    // }
                
                    
                }

                $response = [
                        'Api_success' => 'true', 
                        'Api_message' => $quotation_service_items_add, 
                    ];


            }
            else{


                $product_result = $request->quotationItems;
                $products = json_encode($product_result);

                // $service_result = $request->quotationServiceItems;
                // $services = json_encode($service_result);

                // $products = $request->quotationItems;
                // $services = $request->quotationServiceItems;


                $data = array( 
                    'client_id' => $request->client_id,
                    'date_issue' => $request->qualification_date,
                    'date_due' => $request->valid_date,
                    'igst_type' => $request->GSTType,
                    'total_amount' => $request->TotalAmount,
                    'total_cgst' => $request->TotalCGST,
                    'total_sgst' => $request->TotalSGST,
                    'grand_total' => $request->GrandTotal,
                    'quotation_items' => $products,
                    // 'quotation_service_items' => $services,
                    'updated_by' => $request->user_id, 
                    'updated_at' => Now()
                );

                $quotation_product_service_edit=DB::table('quotations')->where('quotation_id', $request->quotation_id)->update($data);
                
                if(isset($quotation_product_service_edit))
                {
                    
                    $quotation_product_items=DB::table('quotation_items')->where('QuotationId',$request->quotation_id)->get();

                    foreach($quotation_product_items as $quotation_product_item){

                        if($quotation_product_item->Product!=''){

                            $quotation_service_items_delete=DB::table('quotation_items')->where('QuotationId',$request->quotation_id)->delete();
                            
                        }
                        // elseif($quotation_product_item->Service!=''){

                        //     $quotation_service_items_delete=DB::table('quotation_items')->where('QuotationId',$request->quotation_id)->delete();

                        // }

                    }

                    $quotationIdNew=DB::table('quotations')->select('*')->where('quotation_id',$request->quotation_id)->first();
                    $quotation_item_id=$quotationIdNew->quotation_id;

                    if($request->quotationItems!=""){

                        $items = json_encode($request->quotationItems);
                        $itemss = json_decode($items);
                        // $itemss=json_decode($request->quotationItems);

                        foreach($itemss as $quotationItem){
                            $Product=$quotationItem->Product;
                            $Cost=$quotationItem->Cost;
                            $Quantity=$quotationItem->Quantity;
                            $GST=$quotationItem->GST;
                            $CGST=$quotationItem->CGST;
                            $SGST=$quotationItem->SGST;
                            $Amount=$quotationItem->Amount;
                            $ProductDescription=$quotationItem->ProductDescription;
                            $quotationItems=array("quotationId" => $quotation_item_id,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $request->user_id, 'created_at' => Now());

                            $quotation_items_add=DB::table('quotation_items')->insert($quotationItems);
                        }
                    }

                    // if($request->quotationServiceItems!=""){

                    //     $service_items = json_encode($request->quotationServiceItems);
                    //     $service_itemss = json_decode($service_items);
                    //     // $service_itemss = json_decode($request->quotationServiceItems);

                    //     foreach($service_itemss as $quotationServiceItem){
                    //         $Service=$quotationServiceItem->Service;
                    //         $Cost=$quotationServiceItem->Cost;
                    //         $Quantity=$quotationServiceItem->Quantity;
                    //         $GST=$quotationServiceItem->GST;
                    //         $CGST=$quotationServiceItem->CGST;
                    //         $SGST=$quotationServiceItem->SGST;
                    //         $Amount=$quotationServiceItem->Amount;
                    //         $ServiceDescription=$quotationServiceItem->ServiceDescription;
                    //         $quotationItems=array("quotationId" => $quotation_item_id,"Service"=>$Service, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ServiceDescription"=>$ServiceDescription,'created_by' => $request->user_id, 'created_at' => Now());

                    //         $quotation_items_add=DB::table('quotation_items')->insert($quotationItems);
                    //     }
                    // }
                    
                }
                    
                
            }

            $response = [
                'Api_success' => 'true', 
                'Api_message' => $quotation_product_service_edit, 
            ];

        }
        else{

            $response = [
                'Api_success' => 'false', 
                'Api_success' => 'Something Went Wrong!.', 
            ];

        }
        
        return response($response,201);
        

    }

    public function quotation_list(Request $request)
    {
        
        $user_id = $request->id;

        $datas = DB::table('quotations as a')->where('a.deleted','No')->where('a.created_by', $user_id)->select(['a.quotation_id',
                'd.client_name',
                'a.date_issue',
                'a.date_due',
                'a.igst_type',
                'a.total_amount',
                'a.total_igst',
                'a.total_cgst',
                'a.total_sgst',
                'a.grand_total',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('clients as d', 'd.client_id', '=', 'a.client_id')->get();
        
        foreach($datas as $data){

            $quotation_arr[] = $data;   

        }

        $clients = DB::table('clients')->select('client_id', 'client_name')->where('deleted', 'No')->get();
        $products = DB::table('products')->select('product_id', 'product_name', 'gst', 'selling_price')->where('deleted', 'No')->get();
        $services = DB::table('services')->select('service_id', 'service_name')->where('deleted', 'No')->get();

        if(isset($quotation_arr))
        {
            $response = [
                'Api_success' => 'true',
                'Quotation List' => $quotation_arr,
                'Clients List' => $clients,
                'Products List' => $products,
                'Services List' => $services,
            ];
        }
        else
        {
            $list_not_found = array();
            $response = [
                'Api_success' => 'false',
                'Expense List' => $list_not_found,
                'Clients List' => $clients,
                'Products List' => $products,
                'Services List' => $services,
            ];
        }
        return response($response, 201);

    }

    public function invoice_add(Request $request){

        if(isset($request->GSTType)){  

            if($request->GSTType==1){

                $product_result = $request->invoiceItems;
                $products = json_encode($product_result);

                $service_result = $request->invoiceServiceItems;
                $services = json_encode($service_result);

                // $products = $request->invoiceItems;
                // $services = $request->invoiceServiceItems;


                $data = array( 
                    'client_id' => $request->client_id,
                    'date_issue' => $request->qualification_date,
                    'date_due' => $request->valid_date,
                    'igst_type' => $request->GSTType,
                    'total_amount' => $request->TotalAmount,
                    'total_igst' => $request->TotalIGST,
                    'grand_total' => $request->GrandTotal,
                    'invoice_items' => $products,
                    'invoice_service_items' => $services,
                    'created_by' => $request->user_id, 
                    'created_at' => Now()
                );

                $invoice_product_service_add=DB::table('invoices')->insertGetId($data);
                
                if(isset($invoice_product_service_add))
                { 

                    if($request->invoiceItems!=""){

                        $items = json_encode($request->invoiceItems);
                        $itemss = json_decode($items);
                        // $itemss=json_decode($request->invoiceItems);

                        foreach($itemss as $invoiceItem){
                            $Product=$invoiceItem->Product;
                            $Cost=$invoiceItem->Cost;
                            $Quantity=$invoiceItem->Quantity;
                            $GST=$invoiceItem->GST;
                            $IGST=$invoiceItem->IGST;
                            $Amount=$invoiceItem->Amount;
                            $ProductDescription=$invoiceItem->ProductDescription;
                            $invoiceItems=array("invoiceId" => $invoice_product_service_add,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $request->user_id, 'created_at' => Now());
                            $invoice_items_add=DB::table('invoice_items')->insert($invoiceItems);
                        }
                    }

                    if($request->invoiceServiceItems!="")
                    {
                        $service_items = json_encode($request->invoiceServiceItems);
                        $service_itemss = json_decode($service_items);
                        // $service_itemss = json_decode($request->invoiceServiceItems);

                        foreach($service_itemss as $invoiceServiceItem){
                            $Service=$invoiceServiceItem->Service;
                            $Cost=$invoiceServiceItem->Cost;
                            $Quantity=$invoiceServiceItem->Quantity;
                            $GST=$invoiceServiceItem->GST;
                            $IGST=$invoiceServiceItem->IGST;
                            $Amount=$invoiceServiceItem->Amount;
                            $ServiceDescription=$invoiceServiceItem->ServiceDescription;
                            $invoiceServiceItemss=array("invoiceId" => $invoice_product_service_add,"Service"=>$Service, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "Amount"=>$Amount, "ServiceDescription"=>$ServiceDescription,'created_by' => $request->user_id, 'created_at' => Now());
                            $invoice_service_items_add=DB::table('invoice_items')->insert($invoiceServiceItemss);
                        }
                    }
                
                    $response = [
                        'Api_success' => 'true', 
                        'Api_message' => $invoice_items_add, 
                    ];
                }
            }
            else{


                $product_result = $request->invoiceItems;
                $products = json_encode($product_result);

                $service_result = $request->invoiceServiceItems;
                $services = json_encode($service_result);

                // $products = $request->invoiceItems;
                // $services = $request->invoiceServiceItems;


                $data = array( 
                    'client_id' => $request->client_id,
                    'date_issue' => $request->qualification_date,
                    'date_due' => $request->valid_date,
                    'igst_type' => $request->GSTType,
                    'total_amount' => $request->TotalAmount,
                    'total_cgst' => $request->TotalCGST,
                    'total_sgst' => $request->TotalSGST,
                    'grand_total' => $request->GrandTotal,
                    'invoice_items' => $products,
                    'invoice_service_items' => $services,
                    'created_by' => $request->user_id, 
                    'created_at' => Now()
                );

                $invoice_product_service_add=DB::table('invoices')->insertGetId($data);
                
                if(isset($invoice_product_service_add))
                {
                    
                    if($request->invoiceItems!=""){

                        $items = json_encode($request->invoiceItems);
                        $itemss = json_decode($items);
                        // $itemss=json_decode($request->invoiceItems);

                        foreach($itemss as $invoiceItem){
                            $Product=$invoiceItem->Product;
                            $Cost=$invoiceItem->Cost;
                            $Quantity=$invoiceItem->Quantity;
                            $GST=$invoiceItem->GST;
                            $CGST=$invoiceItem->CGST;
                            $SGST=$invoiceItem->SGST;
                            $Amount=$invoiceItem->Amount;
                            $ProductDescription=$invoiceItem->ProductDescription;
                            $invoiceItems=array("invoiceId" => $invoice_product_service_add,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $request->user_id, 'created_at' => Now());

                            $invoice_items_add=DB::table('invoice_items')->insert($invoiceItems);
                        }
                    }

                    if($request->invoiceServiceItems!=""){

                        $service_items = json_encode($request->invoiceServiceItems);
                        $service_itemss = json_decode($service_items);
                        // $service_itemss = json_decode($request->invoiceServiceItems);

                        foreach($service_itemss as $invoiceServiceItem){
                            $Service=$invoiceServiceItem->Service;
                            $Cost=$invoiceServiceItem->Cost;
                            $Quantity=$invoiceServiceItem->Quantity;
                            $GST=$invoiceServiceItem->GST;
                            $CGST=$invoiceServiceItem->CGST;
                            $SGST=$invoiceServiceItem->SGST;
                            $Amount=$invoiceServiceItem->Amount;
                            $ServiceDescription=$invoiceServiceItem->ServiceDescription;
                            $invoiceItems=array("invoiceId" => $invoice_product_service_add,"Service"=>$Service, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ServiceDescription"=>$ServiceDescription,'created_by' => $request->user_id, 'created_at' => Now());

                            $invoice_items_add=DB::table('invoice_items')->insert($invoiceItems);
                        }
                    }
                }
                    
                $response = [
                    'Api_success' => 'true', 
                    'Api_message' => $invoice_items_add, 
                ];
            }
        }
        else{

            $response = [
                'Api_success' => 'false', 
                'Api_success' => 'Something Went Wrong!.', 
            ];

        }

        return response($response,201);
    }

    public function get_invoice(Request $request){

        $user_id = $request->user_id;
        $invoice_id = $request->invoice_id;
        if(isset($invoice_id)){

            $get_invoice = DB::table('invoices as a')->where('a.invoice_id', $invoice_id)->where('a.deleted', 'No')->where('a.created_by', $user_id)->select(['a.invoice_id',
                'd.client_name',
                'a.date_issue',
                'a.date_due',
                'a.igst_type',
                'a.total_amount',
                'a.total_igst',
                'a.total_cgst',
                'a.total_sgst',
                'a.grand_total',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('clients as d', 'd.client_id', '=', 'a.client_id')->first();

            $get_attachments = DB::table('invoice_items')->where('invoiceId', $invoice_id)->where('deleted','No')->select('Product','Service','Quantity','Cost','GST','IGST','CGST','SGST','Amount','ProductDescription','ServiceDescription')->get();

            foreach($get_attachments as $get_attachment){

                $attachments[] = $get_attachment;

            }

            $response = [
                'Api_success' => 'true', 
                'get_invoice' => $get_invoice, 
                'get_attachments' => $attachments, 
            ];

        }
        else{

            $get_invoice = array();
            $attachments = array();

            $response = [
                'Api_success' => 'false', 
                'get_invoice' => $get_invoice, 
                'get_attachments' => $attachments,  
            ];

        }

        return response($response,201);

    }

    public function invoice_edit(Request $request){

        if(isset($request->invoice_id))
        {
            if(isset($request->GSTType)){  

                if($request->GSTType==1){

                    $product_result = $request->invoiceItems;
                    $products = json_encode($product_result);

                    $service_result = $request->invoiceServiceItems;
                    $services = json_encode($service_result);

                    // $products = $request->invoiceItems;
                    // $services = $request->invoiceServiceItems;


                    $data = array( 
                        'client_id' => $request->client_id,
                        'date_issue' => $request->qualification_date,
                        'date_due' => $request->valid_date,
                        'igst_type' => $request->GSTType,
                        'total_amount' => $request->TotalAmount,
                        'total_igst' => $request->TotalIGST,
                        'grand_total' => $request->GrandTotal,
                        'invoice_items' => $products,
                        'invoice_service_items' => $services,
                        'updated_by' => $request->user_id, 
                        'updated_at' => Now()
                    );

                    $invoice_product_service_edit = DB::table('invoices')->where('invoice_id', $request->invoice_id)->update($data);
                    
                    if(isset($invoice_product_service_edit))
                    { 

                        $invoice_product_items=DB::table('invoice_items')->where('invoiceId',$request->invoice_id)->get();

                        foreach($invoice_product_items as $invoice_product_item){

                            if($invoice_product_item->Product!=''){

                                $invoice_product_items_delete=DB::table('invoice_items')->where('invoiceId',$request->invoice_id)->delete();

                            }
                            elseif($invoice_product_item->Service!=''){

                                $invoice_service_items_delete=DB::table('invoice_items')->where('invoiceId',$request->invoice_id)->delete();

                            }

                        }

                        $invoiceIdNew=DB::table('invoices')->select('*')->where('invoice_id',$request->invoice_id)->first();
                        $invoice_item_id=$invoiceIdNew->invoice_id;

                        if($request->invoiceItems!=""){

                            $items = json_encode($request->invoiceItems);
                            $itemss = json_decode($items);
                            // $itemss=json_decode($request->invoiceItems);

                            foreach($itemss as $invoiceItem){
                                $Product=$invoiceItem->Product;
                                $Cost=$invoiceItem->Cost;
                                $Quantity=$invoiceItem->Quantity;
                                $GST=$invoiceItem->GST;
                                $IGST=$invoiceItem->IGST;
                                $Amount=$invoiceItem->Amount;
                                $ProductDescription=$invoiceItem->ProductDescription;
                                $invoiceItems=array("invoiceId" => $invoice_item_id,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $request->user_id, 'created_at' => Now());
                                $invoice_service_items_add=DB::table('invoice_items')->insert($invoiceItems);
                            }
                        }

                        if($request->invoiceServiceItems!="")
                        {
                            $service_items = json_encode($request->invoiceServiceItems);
                            $service_itemss = json_decode($service_items);
                            // $service_itemss = json_decode($request->invoiceServiceItems);

                            foreach($service_itemss as $invoiceServiceItem){
                                $Service=$invoiceServiceItem->Service;
                                $Cost=$invoiceServiceItem->Cost;
                                $Quantity=$invoiceServiceItem->Quantity;
                                $GST=$invoiceServiceItem->GST;
                                $IGST=$invoiceServiceItem->IGST;
                                $Amount=$invoiceServiceItem->Amount;
                                $ServiceDescription=$invoiceServiceItem->ServiceDescription;
                                $invoiceServiceItemss=array("invoiceId" => $invoice_item_id,"Service"=>$Service, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "Amount"=>$Amount, "ServiceDescription"=>$ServiceDescription,'created_by' => $request->user_id, 'created_at' => Now());
                                $invoice_service_items_add=DB::table('invoice_items')->insert($invoiceServiceItemss);
                            }
                        }
                    
                        $response = [
                            'Api_success' => 'true', 
                            'Api_message' => $invoice_service_items_add, 
                        ];
                    }
                }
                else{


                    $product_result = $request->invoiceItems;
                    $products = json_encode($product_result);

                    $service_result = $request->invoiceServiceItems;
                    $services = json_encode($service_result);

                    // $products = $request->invoiceItems;
                    // $services = $request->invoiceServiceItems;


                    $data = array( 
                        'client_id' => $request->client_id,
                        'date_issue' => $request->qualification_date,
                        'date_due' => $request->valid_date,
                        'igst_type' => $request->GSTType,
                        'total_amount' => $request->TotalAmount,
                        'total_cgst' => $request->TotalCGST,
                        'total_sgst' => $request->TotalSGST,
                        'grand_total' => $request->GrandTotal,
                        'invoice_items' => $products,
                        'invoice_service_items' => $services,
                        'updated_by' => $request->user_id, 
                        'updated_at' => Now()
                    );

                    $invoice_product_service_edit=DB::table('invoices')->where('invoice_id', $request->invoice_id)->update($data);
                    
                    if(isset($invoice_product_service_edit))
                    {
                        
                        $invoice_product_items=DB::table('invoice_items')->where('invoiceId',$request->invoice_id)->get();

                        foreach($invoice_product_items as $invoice_product_item){

                            if($invoice_product_item->Product!=''){

                                $invoice_service_items_delete=DB::table('invoice_items')->where('invoiceId',$request->invoice_id)->delete();
                                
                            }
                            elseif($invoice_product_item->Service!=''){

                                $invoice_service_items_delete=DB::table('invoice_items')->where('invoiceId',$request->invoice_id)->delete();

                            }

                        }

                        $invoiceIdNew=DB::table('invoices')->select('*')->where('invoice_id',$request->invoice_id)->first();
                        $invoice_item_id=$invoiceIdNew->invoice_id;

                        if($request->invoiceItems!=""){

                            $items = json_encode($request->invoiceItems);
                            $itemss = json_decode($items);
                            // $itemss=json_decode($request->invoiceItems);

                            foreach($itemss as $invoiceItem){
                                $Product=$invoiceItem->Product;
                                $Cost=$invoiceItem->Cost;
                                $Quantity=$invoiceItem->Quantity;
                                $GST=$invoiceItem->GST;
                                $CGST=$invoiceItem->CGST;
                                $SGST=$invoiceItem->SGST;
                                $Amount=$invoiceItem->Amount;
                                $ProductDescription=$invoiceItem->ProductDescription;
                                $invoiceItems=array("invoiceId" => $invoice_item_id,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $request->user_id, 'created_at' => Now());

                                $invoice_items_add=DB::table('invoice_items')->insert($invoiceItems);
                            }
                        }

                        if($request->invoiceServiceItems!=""){

                            $service_items = json_encode($request->invoiceServiceItems);
                            $service_itemss = json_decode($service_items);
                            // $service_itemss = json_decode($request->invoiceServiceItems);

                            foreach($service_itemss as $invoiceServiceItem){
                                $Service=$invoiceServiceItem->Service;
                                $Cost=$invoiceServiceItem->Cost;
                                $Quantity=$invoiceServiceItem->Quantity;
                                $GST=$invoiceServiceItem->GST;
                                $CGST=$invoiceServiceItem->CGST;
                                $SGST=$invoiceServiceItem->SGST;
                                $Amount=$invoiceServiceItem->Amount;
                                $ServiceDescription=$invoiceServiceItem->ServiceDescription;
                                $invoiceItems=array("invoiceId" => $invoice_item_id,"Service"=>$Service, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ServiceDescription"=>$ServiceDescription,'created_by' => $request->user_id, 'created_at' => Now());

                                $invoice_items_add=DB::table('invoice_items')->insert($invoiceItems);
                            }
                        }
                    }
                        
                    $response = [
                        'Api_success' => 'true', 
                        'Api_message' => $invoice_items_add, 
                    ];
                }
            }
            else{

                $response = [
                    'Api_success' => 'false', 
                    'Api_success' => 'Something Went Wrong!.', 
                ];

            }
        }
        
        return response($response,201);

    }

    public function invoice_list(Request $request)
    {
        
        $user_id = $request->id;

        $datas = DB::table('invoices as a')->where('a.deleted', 'No')->where('a.created_by', $user_id)->select([
                'a.invoice_id',
                'd.client_name',
                'a.date_issue',
                'a.date_due',
                'a.igst_type',
                'a.total_amount',
                'a.total_igst',
                'a.total_cgst',
                'a.total_sgst',
                'a.grand_total',
                'b.first_name as created_by',
                'a.created_at',
                'c.first_name as updated_by',
                'a.updated_at',
            ])->leftjoin('users as b', 'b.id', '=', 'a.created_by')->leftjoin('users as c', 'c.id', '=', 'a.updated_by')->leftjoin('clients as d', 'd.client_id', '=', 'a.client_id')->get();
        
        foreach($datas as $data){

            $invoice_arr[] = $data;   

        }

        $clients = DB::table('clients')->select('client_id', 'client_name')->where('deleted', 'No')->get();
        $products = DB::table('products')->select('product_id', 'product_name', 'gst', 'selling_price')->where('deleted', 'No')->get();
        $services = DB::table('services')->select('service_id', 'service_name')->where('deleted', 'No')->get();

        if(isset($invoice_arr))
        {
            $response = [
                'Api_success' => 'true',
                'Invoice List' => $invoice_arr,
                'Clients List' => $clients,
                'Products List' => $products,
                'Services List' => $services,
            ];
        }
        else
        {
            $list_not_found = array();
            $response = [
                'Api_success' => 'false',
                'Expense List' => $list_not_found,
                'Clients List' => $clients,
                'Products List' => $products,
                'Services List' => $services,
            ];
        }
        return response($response, 201);

    }

    public function proforma_invoice_add(Request $request){

        if(isset($request->GSTType)){  

            if($request->GSTType==1){

                $product_result = $request->proformainvoiceItems;
                $products = json_encode($product_result);

                $service_result = $request->proforma_invoiceServiceItems;
                $services = json_encode($service_result);

                // $products = $request->proformainvoiceItems;
                // $services = $request->proforma_invoiceServiceItems;


                $data = array( 
                    'client_id' => $request->client_id,
                    'date_issue' => $request->qualification_date,
                    'date_due' => $request->valid_date,
                    'igst_type' => $request->GSTType,
                    'total_amount' => $request->TotalAmount,
                    'total_igst' => $request->TotalIGST,
                    'grand_total' => $request->GrandTotal,
                    'proforma_invoice_items' => $products,
                    'proforma_invoice_service_items' => $services,
                    'created_by' => $request->user_id, 
                    'created_at' => Now()
                );

                $proforma_invoice_product_service_add=DB::table('proforma_invoices')->insertGetId($data);
                
                if(isset($proforma_invoice_product_service_add))
                { 

                    if($request->proformainvoiceItems!=""){

                        $items = json_encode($request->proformainvoiceItems);
                        $itemss = json_decode($items);
                        // $itemss=json_decode($request->proformainvoiceItems);

                        foreach($itemss as $proforma_invoiceItem){
                            $Product=$proforma_invoiceItem->Product;
                            $Cost=$proforma_invoiceItem->Cost;
                            $Quantity=$proforma_invoiceItem->Quantity;
                            $GST=$proforma_invoiceItem->GST;
                            $IGST=$proforma_invoiceItem->IGST;
                            $Amount=$proforma_invoiceItem->Amount;
                            $ProductDescription=$proforma_invoiceItem->ProductDescription;
                            $proformainvoiceItems=array("proforma_invoiceId" => $proforma_invoice_product_service_add,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $request->user_id, 'created_at' => Now());
                            $proforma_invoice_items_add=DB::table('proforma_invoice_items')->insert($proformainvoiceItems);
                        }
                    }

                    if($request->proforma_invoiceServiceItems!="")
                    {
                        $service_items = json_encode($request->proforma_invoiceServiceItems);
                        $service_itemss = json_decode($service_items);
                        // $service_itemss = json_decode($request->proforma_invoiceServiceItems);

                        foreach($service_itemss as $proforma_invoiceServiceItem){
                            $Service=$proforma_invoiceServiceItem->Service;
                            $Cost=$proforma_invoiceServiceItem->Cost;
                            $Quantity=$proforma_invoiceServiceItem->Quantity;
                            $GST=$proforma_invoiceServiceItem->GST;
                            $IGST=$proforma_invoiceServiceItem->IGST;
                            $Amount=$proforma_invoiceServiceItem->Amount;
                            $ServiceDescription=$proforma_invoiceServiceItem->ServiceDescription;
                            $proforma_invoiceServiceItemss=array("proforma_invoiceId" => $proforma_invoice_product_service_add,"Service"=>$Service, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "Amount"=>$Amount, "ServiceDescription"=>$ServiceDescription,'created_by' => $request->user_id, 'created_at' => Now());
                            $proforma_invoice_service_items_add=DB::table('proforma_invoice_items')->insert($proforma_invoiceServiceItemss);
                        }
                    }
                
                    $response = [
                        'Api_success' => 'true', 
                        'Api_message' => $proforma_invoice_product_service_add, 
                    ];
                }
            }
            else{


                $product_result = $request->proformainvoiceItems;
                $products = json_encode($product_result);

                $service_result = $request->proforma_invoiceServiceItems;
                $services = json_encode($service_result);

                // $products = $request->proformainvoiceItems;
                // $services = $request->proforma_invoiceServiceItems;


                $data = array( 
                    'client_id' => $request->client_id,
                    'date_issue' => $request->qualification_date,
                    'date_due' => $request->valid_date,
                    'igst_type' => $request->GSTType,
                    'total_amount' => $request->TotalAmount,
                    'total_cgst' => $request->TotalCGST,
                    'total_sgst' => $request->TotalSGST,
                    'grand_total' => $request->GrandTotal,
                    'proforma_invoice_items' => $products,
                    'proforma_invoice_service_items' => $services,
                    'created_by' => $request->user_id, 
                    'created_at' => Now()
                );

                $proforma_invoice_product_service_add=DB::table('proforma_invoices')->insertGetId($data);
                
                if(isset($proforma_invoice_product_service_add))
                {
                    
                    if($request->proformainvoiceItems!=""){

                        $items = json_encode($request->proformainvoiceItems);
                        $itemss = json_decode($items);
                        // $itemss=json_decode($request->proformainvoiceItems);

                        foreach($itemss as $proforma_invoiceItem){
                            $Product=$proforma_invoiceItem->Product;
                            $Cost=$proforma_invoiceItem->Cost;
                            $Quantity=$proforma_invoiceItem->Quantity;
                            $GST=$proforma_invoiceItem->GST;
                            $CGST=$proforma_invoiceItem->CGST;
                            $SGST=$proforma_invoiceItem->SGST;
                            $Amount=$proforma_invoiceItem->Amount;
                            $ProductDescription=$proforma_invoiceItem->ProductDescription;
                            $proformainvoiceItems=array("proforma_invoiceId" => $proforma_invoice_product_service_add,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $request->user_id, 'created_at' => Now());

                            $proforma_invoice_items_add=DB::table('proforma_invoice_items')->insert($proformainvoiceItems);
                        }
                    }

                    if($request->proforma_invoiceServiceItems!=""){

                        $service_items = json_encode($request->proforma_invoiceServiceItems);
                        $service_itemss = json_decode($service_items);
                        // $service_itemss = json_decode($request->proforma_invoiceServiceItems);

                        foreach($service_itemss as $proforma_invoiceServiceItem){
                            $Service=$proforma_invoiceServiceItem->Service;
                            $Cost=$proforma_invoiceServiceItem->Cost;
                            $Quantity=$proforma_invoiceServiceItem->Quantity;
                            $GST=$proforma_invoiceServiceItem->GST;
                            $CGST=$proforma_invoiceServiceItem->CGST;
                            $SGST=$proforma_invoiceServiceItem->SGST;
                            $Amount=$proforma_invoiceServiceItem->Amount;
                            $ServiceDescription=$proforma_invoiceServiceItem->ServiceDescription;
                            $proformainvoiceItems=array("proforma_invoiceId" => $proforma_invoice_product_service_add,"Service"=>$Service, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ServiceDescription"=>$ServiceDescription,'created_by' => $request->user_id, 'created_at' => Now());

                            $proforma_invoice_items_add=DB::table('proforma_invoice_items')->insert($proformainvoiceItems);
                        }

                        

                    }

                }
                    
                $response = [
                    'Api_success' => 'true', 
                    'Api_message' => $proforma_invoice_product_service_add, 
                ];
            }

        }
        else{

            $response = [
                'Api_success' => 'false', 
                'Api_success' => 'Something Went Wrong!.', 
            ];

        }

        return response($response,201);
    }

    public function get_proforma_invoice(Request $request){

        $user_id = $request->user_id;
        $proforma_invoice_id = $request->proforma_invoice_id;
        if(isset($proforma_invoice_id)){

            $get_proforma_invoice = DB::table('proforma_invoices as a')->where('a.proforma_invoice_id', $proforma_invoice_id)->where('a.deleted', 'No')->where('a.created_by', $user_id)->select(['a.proforma_invoice_id',
                'd.client_name',
                'a.date_issue',
                'a.date_due',
                'a.igst_type',
                'a.total_amount',
                'a.total_igst',
                'a.total_cgst',
                'a.total_sgst',
                'a.grand_total',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('clients as d', 'd.client_id', '=', 'a.client_id')->first();

            $get_attachments = DB::table('proforma_invoice_items')->where('proforma_invoiceId', $proforma_invoice_id)->where('deleted','No')->select('Product','Service','Quantity','Cost','GST','IGST','CGST','SGST','Amount','ProductDescription','ServiceDescription')->get();

            foreach($get_attachments as $get_attachment){

                $attachments[] = $get_attachment;

            }

            $response = [
                'Api_success' => 'true', 
                'get_proforma_invoice' => $get_proforma_invoice, 
                'get_attachments' => $attachments, 
            ];

        }
        else{

            $get_proforma_invoice = array();
            $attachments = array();

            $response = [
                'Api_success' => 'false', 
                'get_proforma_invoice' => $get_proforma_invoice, 
                'get_attachments' => $attachments,  
            ];

        }

        return response($response,201);

    }

    public function proforma_invoice_edit(Request $request){

        if(isset($request->proforma_invoice_id))
        {
            if(isset($request->GSTType)){  

                if($request->GSTType==1){

                    $product_result = $request->proformainvoiceItems;
                    $products = json_encode($product_result);

                    $service_result = $request->proforma_invoiceServiceItems;
                    $services = json_encode($service_result);

                    // $products = $request->proformainvoiceItems;
                    // $services = $request->proforma_invoiceServiceItems;


                    $data = array( 
                        'client_id' => $request->client_id,
                        'date_issue' => $request->qualification_date,
                        'date_due' => $request->valid_date,
                        'igst_type' => $request->GSTType,
                        'total_amount' => $request->TotalAmount,
                        'total_igst' => $request->TotalIGST,
                        'grand_total' => $request->GrandTotal,
                        'proforma_invoice_items' => $products,
                        'proforma_invoice_service_items' => $services,
                        'updated_by' => $request->user_id, 
                        'updated_at' => Now()
                    );

                    $proforma_invoice_product_service_edit = DB::table('proforma_invoices')->where('proforma_invoice_id', $request->proforma_invoice_id)->update($data);
                    
                    if(isset($proforma_invoice_product_service_edit))
                    { 

                        $proforma_invoice_product_items=DB::table('proforma_invoice_items')->where('proforma_invoiceId',$request->proforma_invoice_id)->get();

                        foreach($proforma_invoice_product_items as $proforma_invoice_product_item){

                            if($proforma_invoice_product_item->Product!=''){

                                $proforma_invoice_product_items_delete=DB::table('proforma_invoice_items')->where('proforma_invoiceId',$request->proforma_invoice_id)->delete();

                            }
                            elseif($proforma_invoice_product_item->Service!=''){

                                $proforma_invoice_service_items_delete=DB::table('proforma_invoice_items')->where('proforma_invoiceId',$request->proforma_invoice_id)->delete();

                            }

                        }

                        $proforma_invoiceIdNew=DB::table('proforma_invoices')->select('*')->where('proforma_invoice_id',$request->proforma_invoice_id)->first();
                        $proforma_invoice_item_id=$proforma_invoiceIdNew->proforma_invoice_id;

                        if($request->proformainvoiceItems!=""){

                            $items = json_encode($request->proformainvoiceItems);
                            $itemss = json_decode($items);
                            // $itemss=json_decode($request->proformainvoiceItems);

                            foreach($itemss as $proforma_invoiceItem){
                                $Product=$proforma_invoiceItem->Product;
                                $Cost=$proforma_invoiceItem->Cost;
                                $Quantity=$proforma_invoiceItem->Quantity;
                                $GST=$proforma_invoiceItem->GST;
                                $IGST=$proforma_invoiceItem->IGST;
                                $Amount=$proforma_invoiceItem->Amount;
                                $ProductDescription=$proforma_invoiceItem->ProductDescription;
                                $proformainvoiceItems=array("proforma_invoiceId" => $proforma_invoice_item_id,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $request->user_id, 'created_at' => Now());
                                $proforma_invoice_service_items_add=DB::table('proforma_invoice_items')->insert($proformainvoiceItems);
                            }
                        }

                        if($request->proforma_invoiceServiceItems!="")
                        {
                            $service_items = json_encode($request->proforma_invoiceServiceItems);
                            $service_itemss = json_decode($service_items);
                            // $service_itemss = json_decode($request->proforma_invoiceServiceItems);

                            foreach($service_itemss as $proforma_invoiceServiceItem){
                                $Service=$proforma_invoiceServiceItem->Service;
                                $Cost=$proforma_invoiceServiceItem->Cost;
                                $Quantity=$proforma_invoiceServiceItem->Quantity;
                                $GST=$proforma_invoiceServiceItem->GST;
                                $IGST=$proforma_invoiceServiceItem->IGST;
                                $Amount=$proforma_invoiceServiceItem->Amount;
                                $ServiceDescription=$proforma_invoiceServiceItem->ServiceDescription;
                                $proforma_invoiceServiceItemss=array("proforma_invoiceId" => $proforma_invoice_item_id,"Service"=>$Service, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "Amount"=>$Amount, "ServiceDescription"=>$ServiceDescription,'created_by' => $request->user_id, 'created_at' => Now());
                                $proforma_invoice_service_items_add=DB::table('proforma_invoice_items')->insert($proforma_invoiceServiceItemss);
                            }
                        }
                    
                        $response = [
                            'Api_success' => 'true', 
                            'Api_message' => $proforma_invoice_product_service_edit, 
                        ];
                    }
                }
                else{


                    $product_result = $request->proformainvoiceItems;
                    $products = json_encode($product_result);

                    $service_result = $request->proforma_invoiceServiceItems;
                    $services = json_encode($service_result);

                    // $products = $request->proformainvoiceItems;
                    // $services = $request->proforma_invoiceServiceItems;


                    $data = array( 
                        'client_id' => $request->client_id,
                        'date_issue' => $request->qualification_date,
                        'date_due' => $request->valid_date,
                        'igst_type' => $request->GSTType,
                        'total_amount' => $request->TotalAmount,
                        'total_cgst' => $request->TotalCGST,
                        'total_sgst' => $request->TotalSGST,
                        'grand_total' => $request->GrandTotal,
                        'proforma_invoice_items' => $products,
                        'proforma_invoice_service_items' => $services,
                        'updated_by' => $request->user_id, 
                        'updated_at' => Now()
                    );

                    $proforma_invoice_product_service_edit=DB::table('proforma_invoices')->where('proforma_invoice_id', $request->proforma_invoice_id)->update($data);
                    
                    if(isset($proforma_invoice_product_service_edit))
                    {
                        
                        $proforma_invoice_product_items=DB::table('proforma_invoice_items')->where('proforma_invoiceId',$request->proforma_invoice_id)->get();

                        foreach($proforma_invoice_product_items as $proforma_invoice_product_item){

                            if($proforma_invoice_product_item->Product!=''){

                                $proforma_invoice_service_items_delete=DB::table('proforma_invoice_items')->where('proforma_invoiceId',$request->proforma_invoice_id)->delete();
                                
                            }
                            elseif($proforma_invoice_product_item->Service!=''){

                                $proforma_invoice_service_items_delete=DB::table('proforma_invoice_items')->where('proforma_invoiceId',$request->proforma_invoice_id)->delete();

                            }

                        }

                        $proforma_invoiceIdNew=DB::table('proforma_invoices')->select('*')->where('proforma_invoice_id',$request->proforma_invoice_id)->first();
                        $proforma_invoice_item_id=$proforma_invoiceIdNew->proforma_invoice_id;

                        if($request->proformainvoiceItems!=""){

                            $items = json_encode($request->proformainvoiceItems);
                            $itemss = json_decode($items);
                            // $itemss=json_decode($request->proformainvoiceItems);

                            foreach($itemss as $proforma_invoiceItem){
                                $Product=$proforma_invoiceItem->Product;
                                $Cost=$proforma_invoiceItem->Cost;
                                $Quantity=$proforma_invoiceItem->Quantity;
                                $GST=$proforma_invoiceItem->GST;
                                $CGST=$proforma_invoiceItem->CGST;
                                $SGST=$proforma_invoiceItem->SGST;
                                $Amount=$proforma_invoiceItem->Amount;
                                $ProductDescription=$proforma_invoiceItem->ProductDescription;
                                $proformainvoiceItems=array("proforma_invoiceId" => $proforma_invoice_item_id,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $request->user_id, 'created_at' => Now());

                                $proforma_invoice_items_add=DB::table('proforma_invoice_items')->insert($proformainvoiceItems);
                            }
                        }

                        if($request->proforma_invoiceServiceItems!=""){

                            $service_items = json_encode($request->proforma_invoiceServiceItems);
                            $service_itemss = json_decode($service_items);
                            // $service_itemss = json_decode($request->proforma_invoiceServiceItems);

                            foreach($service_itemss as $proforma_invoiceServiceItem){
                                $Service=$proforma_invoiceServiceItem->Service;
                                $Cost=$proforma_invoiceServiceItem->Cost;
                                $Quantity=$proforma_invoiceServiceItem->Quantity;
                                $GST=$proforma_invoiceServiceItem->GST;
                                $CGST=$proforma_invoiceServiceItem->CGST;
                                $SGST=$proforma_invoiceServiceItem->SGST;
                                $Amount=$proforma_invoiceServiceItem->Amount;
                                $ServiceDescription=$proforma_invoiceServiceItem->ServiceDescription;
                                $proformainvoiceItems=array("proforma_invoiceId" => $proforma_invoice_item_id,"Service"=>$Service, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ServiceDescription"=>$ServiceDescription,'created_by' => $request->user_id, 'created_at' => Now());

                                $proforma_invoice_items_add=DB::table('proforma_invoice_items')->insert($proformainvoiceItems);
                            }
                        }
                    }
                        
                    $response = [
                        'Api_success' => 'true', 
                        'Api_message' => $proforma_invoice_product_service_edit, 
                    ];
                }
            }
            else{

                $response = [
                    'Api_success' => 'false', 
                    'Api_success' => 'Something Went Wrong!.', 
                ];

            }
        }
        
        return response($response,201);

    }

    public function proforma_invoice_list(Request $request)
    {
        
        $user_id = $request->id;

        $datas = DB::table('proforma_invoices as a')->where('a.created_by', $user_id)->where('a.deleted','No')->select(['a.proforma_invoice_id',
                'd.client_name',
                'a.date_issue',
                'a.date_due',
                'a.igst_type',
                'a.total_amount',
                'a.total_igst',
                'a.total_cgst',
                'a.total_sgst',
                'a.grand_total',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('clients as d', 'd.client_id', '=', 'a.client_id')->get();
        
        foreach($datas as $data){

            $proforma_invoice_arr[] = $data;   

        }

        $clients = DB::table('clients')->select('client_id', 'client_name')->where('deleted', 'No')->get();
        $products = DB::table('products')->select('product_id', 'product_name', 'gst', 'selling_price')->where('deleted', 'No')->get();
        $services = DB::table('services')->select('service_id', 'service_name')->where('deleted', 'No')->get();

        if(isset($proforma_invoice_arr))
        {
            $response = [
                'Api_success' => 'true',
                'Proforma Invoice List' => $proforma_invoice_arr,
                'Clients List' => $clients,
                'Products List' => $products,
                'Services List' => $services,
            ];
        }
        else
        {
            $list_not_found = array();
            $response = [
                'Api_success' => 'false',
                'Expense List' => $list_not_found,
                'Clients List' => $clients,
                'Products List' => $products,
                'Services List' => $services,
            ];
        }
        return response($response, 201);

    }

    public function domain_details(Request $request){

        $apiKey = 'e4hUiM5rUcgo_XuinTqedNcAjMKp1hph9kT';
        $apiSecret = 'vXpTGSbs4kwYuZNaWRyAz';
        $url = 'https://api.godaddy.com/v1/subscriptions';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: sso-key '.$apiKey.':'.$apiSecret,
            'Content-Type: application/json'
        ));

          $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: '.curl_error($ch);
        } else {
             $subscriptions = json_decode($result, true);
            // Use DB Facades to store $subscriptions data in database

             // foreach($subscriptions as $subscription){
             //    $data= array('subscriptionId'=>$subscription['subscriptions']['subscriptionId'],'status'=>$subscription->status,'createdAt'=>$subscription->createdAt,'label'=>$subscription->label,'expiresAt'=>$subscription->expiresAt);

             //    $subscription_insert=DB::table('domain_details')->insert($data);
             // }

             foreach($subscriptions['subscriptions'] as $subscription) {
                        DB::table('domain_details')->updateOrInsert(
                            ['subscriptionId' => $subscription['subscriptionId'],'status'=>$subscription['status'],'createdAt'=>$subscription['createdAt'],'label'=>$subscription['label'],'expiresAt'=>$subscription['expiresAt'],'pfid'=>$subscription['product']['pfid'],'productLabel'=>$subscription['product']['label'],'renewalPfid'=>$subscription['product']['renewalPfid'],'renewalPeriod'=>$subscription['product']['renewalPeriod'],'renewalPeriodUnit'=>$subscription['product']['renewalPeriodUnit']]
                            
                        );

                    }

        }

        curl_close($ch);
        return response($subscriptions, 201);
    }

}

    



