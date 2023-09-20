<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAudioRequest;
use App\Jobs\ConvertAudioForDownloading;
use App\Jobs\ConvertAudioForStreaming;
use App\Audio;

class AuthController extends Controller
{
    public function register(Request $request){
        $data=$request->validate([
            'first_name'=> 'required|string|max:191',
            'email'=> 'required|email|max:191|unique:users,email',
            'password'=> 'required|string',
        ]);
        $user=User::create([
            'first_name'=>$data['first_name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),

        ]);

        $token= $user->createToken('registertoken')->plainTextToken;

        $response=[
            'user'=>$user,
            'token'=>$token,
        ];
        return response($response, 201);
    }

    public function login(Request $request){
        $data=$request->validate([
            'email'=> 'required|email|max:191',
            'password'=> 'required|string',
        ]);

        $user= User::where('email',$data['email'])->first();

        if(!$user || !Hash::check($data['password'], $user->password))
        {
            return response(['message'=>'invalid credentials'],401);

        }
        else
        {
            
            $response= [
                'api_success'=>'true',
                'message'=>'Login Successfully!',
                'user' => $user,
            ];
            
        }
        
        return response($response,201);
    }

    // public function logout(){
    //     auth()->user()->token()->delete();
    //     return response('message'=>'logout successfully.');
    // }

    public function user_details(Request $request){
        $user_id = $request->id;
        // $users = User::find($user_id);
        $users = DB::table('users as a')->where('a.id',$user_id)->select(['a.first_name',
            'a.last_name',
            'a.email',
            'a.employee_code',
            'a.gender',
            'a.date_of_birth',
            'a.personal_mobile_number',
            'a.emergency_contact_number',
            'a.blood_group',
            'a.date_of_joining',
            'b.designation_name as designation_id',
            'a.profile_upload',
            'c.country_name as country_id',
            'd.state_name as state_id',
            'e.city_name as city_id',
            'a.pin_code',
            'a.address',
        ])->leftjoin('designations as b', 'b.designation_id', '=', 'a.designation_id')->leftjoin('countries as c', 'c.country_id', '=', 'a.country_id')->leftjoin('states as d', 'd.state_id', '=', 'a.state_id')->leftjoin('cities as e', 'e.city_id', '=', 'a.city_id')->first();

        $f_name = $users->first_name;
        $l_name = $users->last_name;
        $user_name = $f_name.' '.$l_name;
        if($users->gender==1){
            $gender = 'Male';
        }
        elseif($users->gender==2){
            $gender = 'Female';
        }
        else{
            $gender = 'Others';
        }
        $response = [
            'user_name' => $user_name,
            'email' => $users->email,
            'employee_code' => $users->employee_code,
            'gender' => $gender,
            'date_of_birth' => $users->date_of_birth,
            'personal_mobile_number' => $users->personal_mobile_number,
            'emergency_contact_number' => $users->emergency_contact_number,
            'blood_group' => $users->blood_group,
            'date_of_joining' => $users->date_of_joining,
            'designation_id' => $users->designation_id,
            'profile_upload' => $users->profile_upload,
            'country_id' => $users->country_id,
            'state_id' => $users->state_id,
            'city_id' => $users->city_id,
            'pin_code' => $users->pin_code,
            'address' => $users->address,
            'Api_success' => 'true',
            'Api_message' => 'Success',
        ];
        return response($response, 201);
    }

    public function user_details_update(Request $request){
        if($request->id){
        $user_id = $request->id;
        $users = User::find($user_id);
        $token = $users->createToken('active')->plainTextToken;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $user_name = $first_name.' '.$last_name;
        $data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'employee_name' => $user_name,
            'personal_mobile_number' => $request->personal_mobile_number,
            'emergency_contact_number' => $request->emergency_contact_number,
            'pin_code' => $request->pin_code,
            'address' => $request->address,
            'updated_by' => $user_id,
            'updated_at' => Now(),
        ];
        $user_update = DB::table('users')->where('id',$user_id)->update($data);

        $response = [
            'user'=>$data,
            'token'=>$token,
            'Api_success' => 'True',
            'Api_message' => 'User Updated Successfully!'
        ];
        }
        else{
            $response = [
            'Api_success' => 'false',
            'Api_message' => 'User Updated Failed!'
        ];
        }
        return response($response, 201);
    }

    public function user_details_profile_update(Request $request){

        if($request->id){

            $user_id = $request->id;
            $users = User::find($user_id);
            $token = $users->createToken('active')->plainTextToken;

            if($profile = $request->file('profile_upload')) {

                $destinationPath = 'public/profile_uploads/';
                $profile_image = date('YmdHis') . "." . $profile->getClientOriginalExtension();
                $profile->move($destinationPath, $profile_image);

                $data = [
                    'profile_upload' => $profile_image,
                    'updated_by' => $user_id,
                    'updated_at' => Now(),
                ];

                $user_update = DB::table('users')->where('id',$user_id)->update($data);

            }

            $response = [
                'user'=>$data,
                'token'=>$token,
                'Api_success' => 'True',
                'Api_message' => 'User Updated Successfully!'
            ];

        }
        else{

            $response = [
                'Api_success' => 'false',
                'Api_message' => 'User Updated Failed!'
            ];

        }
        return response($response, 201);

    }

    public function attendance_list(Request $request){

        $user_id = $request->user_id;
        
        $limit = $request->limit ? $request->limit : 10;
        $page = $request->page && $request->page > 0 ? $request->page : 1;
        $skip = ($page - 1) * $limit;

        $attendance_list = DB::table('attendance')->where('user_id', $user_id)->orderby('attendance_id', 'desc')->paginate($limit);
        foreach($attendance_list as $attendance){
            $attendance_arr[] = $attendance;   
        }
        if(isset($attendance_arr))
        {
            $response = [
                'Api_success' => 'true',
                'attendance_list' => $attendance_arr,
            ];
        }
        else
        {
            $attendance_list = array();
            $response = [
                'Api_success' => 'true',
                'attendance_list' => $attendance_list,
            ];
        }
        return response($response, 201);
    }

    public function get_states(Request $request){

        $country_id = $request->country_id;
        $states = DB::table('states')->where('country_id',$country_id)->select('state_id as Id', 'state_name as Name')->get();
        // $token = $users->createToken('active')->plainTextToken; 
        foreach($states as $state){
            $StatesArr[] = $state;   
        }
        $StatesArr[]=array("Id"=>"0","Name"=>"Unknown");
        if(isset($StatesArr))
        {
            $response = [
                'Api_success' => 'true',
                'state_lists' => $StatesArr,
            ];
        }
        else
        {
            $response = [
                'Api_success' => 'false',
                'state_lists' => 'states not found',
            ];
        }
        
        return response($response, 201);
    }

    public function get_cities(Request $request){

        $state_id = $request->state_id;
        $cities = DB::table('cities')->where('state_id',$state_id)->select('city_id as Id', 'city_name as Name')->get();
        // $token = $users->createToken('active')->plainTextToken; 
        foreach($cities as $city){
            $citiesArr[] = $city;   
        }
        $citiesArr[]=array("Id"=>"0","Name"=>"Unknown");
        if(isset($citiesArr))
        {
            $response = [
                'Api_success' => 'true',
                'cities_lists' => $citiesArr,
            ];
        }
        else
        {
            $response = [
                'Api_success' => 'false',
                'cities_lists' => 'cities not found',
            ];
        }
        
        return response($response, 201);
    }

    public function get_countries(Request $request){

        $country_id = $request->country_id;
        $countries = DB::table('countries')->where('country_id',$country_id)->select('country_id as Id', 'country_name as Name')->get();
        // $token = $users->createToken('active')->plainTextToken; 
        foreach($countries as $country){
            $countriesArr[] = $country;   
        }
        $countriesArr[]=array("Id"=>"0","Name"=>"Unknown");

        if(isset($countriesArr))
        {
            $response = [
                'Api_success' => 'true',
                'countries_lists' => $countriesArr,
            ];
        }
        else
        {
            $response = [
                'Api_success' => 'false',
                'countries_lists' => 'countries not found',
            ];
        }
        
        return response($response, 201);
    }
    public function get_designations(Request $request){
        $designation_id=$request->designation_id;
        $designations=DB::table('designations')->where('designation_id',$designation_id)->select('designation_id','designation_name')->where('deleted', 'No')->get();
        foreach($designations as $designation){
            $designationArr[] = $designation;   
        }
        if(isset($designationArr))
        {
            $response = [
                'Api_success' => 'true',
                'designation_list' => $designationArr,
            ];
        }
        else
        {
            $response = [
                'Api_success' => 'false',
                'designation_list' => 'Not Found',
            ];
        }
        return response($response, 201);
    }

        public function check_in(Request $request){
        if($request->id){
        $user_id = $request->id;
        $users = User::find($user_id);
        $token = $users->createToken('active')->plainTextToken;
        $data = [
            'user_id'=>$user_id,
            'description' => $request->description,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'checkin_from'=> 'Active App',
            'type'=>'checkin',
            'attendance_at'=>Now(),
            'created_by' => $user_id,
            'created_at' => Now(),
        ];
        $check_in = DB::table('attendance')->insert($data);

        $response = [
            'user'=>$data,
            'token'=>$token,
            'Api_success' => 'True',
            'Api_message' => 'Checkin Successfully!'
        ];
        }
        else{
            $response = [
            'Api_success' => 'false',
            'Api_message' => 'Checkin Failed!'
        ];
        }
        return response($response, 201);
    }

    public function check_out(Request $request){
        if($request->id){
        $user_id = $request->id;
        $users = User::find($user_id);
        $token = $users->createToken('active')->plainTextToken;
        $data = [
            'user_id'=>$user_id,
            'description' => $request->description,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'checkin_from'=> 'Active App',
            'type'=>'checkout',
            'attendance_at'=>Now(),
            'created_by' => $user_id,
            'created_at' => Now(),
        ];
        $check_in = DB::table('attendance')->insert($data);

        $response = [
            'user'=>$data,
            'token'=>$token,
            'Api_success' => 'True',
            'Api_message' => 'Checkout Successfully!'
        ];
        }
        else{
            $response = [
            'Api_success' => 'false',
            'Api_message' => 'Checkout Failed!'
        ];
        }
        return response($response, 201);
    }

    public function dashboard_counts(Request $request){
        
        $user_id = $request->id;
        if($user_id!=""){
            // if($user_id==1){
                $leads_count = DB::table('leads')->where('deleted', 'No')->count('lead_id');
                $pettycash_count = DB::table('pettycash')->where('deleted', 'No')->count('pettycash_id');
                $quotation_count = DB::table('quotations')->where('deleted', 'No')->count('quotation_id');
                $tasks_count = DB::table('tasks')->where('deleted', 'No')->count('task_id');
                $timelines_count = DB::table('timelines')->where('deleted', 'No')->count('timeline_id');
                $tickets_count = DB::table('tickets')->where('deleted', 'No')->count('ticket_id');
                $response = [
                    'api_success' => 'true',
                    'leads_count' => $leads_count,
                    'tasks_count' => $tasks_count,
                    'timelines_count' => $timelines_count,
                    'tickets_count' => $tickets_count,
                    'pettycash_count' => $pettycash_count,
                    'quotation_count' => $quotation_count,
                ];
            // }
            // else{
            //     $leads_count = DB::table('leads')->where('deleted', 'No')->where('lead_owner', $user_id)->count('lead_id');
            //     $campaigns_count = DB::table('campaigns')->where('deleted', 'No')->count('campaign_id');
            //     $clients_count = DB::table('clients')->where('deleted', 'No')->count('client_id');
            //     $tasks_count = DB::table('tasks')->where('deleted', 'No')->count('task_id');
            //     $timelines_count = DB::table('timelines')->where('deleted', 'No')->count('timeline_id');
            //     $tickets_count = DB::table('tickets')->where('deleted', 'No')->where('assign_to', $user_id)->count('ticket_id');
            //     $response = [
            //         'api_success' => 'true',
            //         'leads_count' => $leads_count,
            //         'tasks_count' => $tasks_count,
            //         'timelines_count' => $timelines_count,
            //         'tickets_count' => $tickets_count,
            //     ];
            // }
        }
        else
        {
            $response = [
                'api_success' => 'false',
                'api_message' => 'failed to load counts' 
            ];
        }
        return response($response, 201);
    }

    public function checkin_status(Request $request){
        $today_date=date('Y-m-d');
        $user_id=$request->user_id;
        if($user_id!="")
        {
            $attendance_details = DB::table('attendance')->where('user_id', $user_id)->get();
            foreach($attendance_details as $attendance_detail){
                $Checkin_date=$attendance_detail->attendance_at;
                $CheckinDate=date('Y-m-d',strtotime($Checkin_date));

                $Checkin_time=$attendance_detail->attendance_at;
                $CheckinTime=date('H:i',strtotime($Checkin_time));

                $Checkout_time=$attendance_detail->attendance_at;
                $CheckoutTime=date('H:i',strtotime($Checkout_time));

                $Status=$attendance_detail->type;
                $CheckinDescription=$attendance_detail->description;
                $CheckoutDescription=$attendance_detail->description;
            }
            if($CheckinDate!=$today_date)
            {
                $Status="NULL";
                $APIResponse['api_success']="true";
                $APIResponse['api_message']="Status Not Found";
                $APIResponse['Status']=$Status;
            }
            else if($Status=="checkin" AND $CheckinDate==$today_date)
            {

                $GetDayAttendanceId=DB::table('attendance')->where('user_id', $user_id)->select('attendance_id', 'attendance_at')->orderby('attendance_id', 'Desc', 1)->first();
                if ($GetDayAttendanceId!="")
                {
                    $LastAttendanceId=$GetDayAttendanceId->attendance_id;
                    $last_checkin_time=$GetDayAttendanceId->attendance_at;
                    $LastCheckinTime=date('H:i:s',strtotime($last_checkin_time));
                    $AttendanceId=$LastAttendanceId;
                    $CheckinTime=$LastCheckinTime;
                }

                $Status="checkin";
                $APIResponse['api_success']="true";
                $APIResponse['api_message']="Status Found";
                $APIResponse['attendance_id']=$LastAttendanceId;
                $APIResponse['CheckinDate']=$CheckinDate;
                $APIResponse['CheckinTime']=$CheckinTime;
                $APIResponse['CheckinDescription']=$CheckinDescription;
                $APIResponse['Status']=$Status;
            }
            elseif($Status=="checkout" AND $CheckinDate==$today_date)
            {
                $Status="checkout";

                $GetDayAttendanceId=DB::table('attendance')->where('user_id', $user_id)->orderby('attendance_id', 'Desc', 1)->first();
                if ($GetDayAttendanceId!="")
                {
                    $LastAttendanceId=$GetDayAttendanceId->attendance_id;
                    $last_checkin_time=$GetDayAttendanceId->attendance_at;
                    $LastCheckinTime=date('H:i:s',strtotime($last_checkin_time));
                    $Check_out_date=$GetDayAttendanceId->attendance_at;
                    $CheckOutDate=date('Y-m-d',strtotime($Check_out_date));
                    $CheckinDescription=$GetDayAttendanceId->description;
                    // $Latitude=$GetDayAttendanceId->latitude;
                    // $Longitude=$GetDayAttendanceId->longitude;
                    $CheckoutTime=$LastCheckinTime;
                    $CheckoutDescription=$GetDayAttendanceId->description;
                    $CheckOutLongitude=$GetDayAttendanceId->longitude;
                    $CheckOutLatitude=$GetDayAttendanceId->latitude;

                    $APIResponse['api_success']="true";
                    $APIResponse['api_message']="Status Found";
                    $APIResponse['attendance_id']=$LastAttendanceId;
                    // $APIResponse['CheckinDate']=$CheckinDate;
                    // $APIResponse['CheckinTime']=$CheckinTime;
                    // $APIResponse['CheckinDescription']=$CheckinDescription;
                    $APIResponse['CheckoutDate']=$CheckOutDate;
                    $APIResponse['CheckoutTime']=$CheckoutTime;
                    $APIResponse['CheckoutDescription']=$CheckoutDescription;
                    $APIResponse['Status']=$Status;
                }
            }
            
            
            
            $APIResponseArr=array_merge($APIResponse);
            // echo$Response=json_encode($APIResponseArr);
            return response($APIResponseArr, 201);

        }
        else
        {
            $APIResponse['api_success']="true";
            $APIResponse['api_message']="Attendance List Not Found";
            $APIResponseArr=array_merge($APIResponse);
            // echo json_encode($APIResponseArr);
            return response($APIResponseArr, 201);
        }

    }
 
    public function attendance_status(Request $request){
        
            $today_date = date('Y-m-d');
            $attendance_details = DB::table('attendance')->where(DB::raw("STR_TO_DATE(attendance_at,'%Y-%m-%d')"),$today_date)->where('user_id', $request->user_id)->where('deleted','No')->get();
            foreach($attendance_details as $attendance_detail){
                $attendance_detailArr[] = $attendance_detail;
            }
            if(isset($attendance_detailArr))
            {
                $response = [
                'Api_success' => 'True',
                'attendance_details' => $attendance_detailArr, 
                ];
            }
            else
            {
                $attendance_detailArr = array();
                $response = [
                'Api_success' => 'False',
                'Api_message' => $attendance_detailArr,
                ];
            }
    return response($response, 201);

    }

    public function task_update(Request $request){
        if(isset($request->task_id) && $request->task_id!="")
        {
            $task_id = $request->task_id;
            $task_details = DB::table('tasks as a')->where('a.task_id', $task_id)->where('a.deleted', 'No')->select(['a.task_id as task_id',
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
            ])->leftjoin('task_attachments as b', 'b.task_id', '=', 'a.task_id')->leftjoin('clients as c', 'c.client_id', '=', 'a.client_id')->leftjoin('projects as d', 'd.project_id', '=', 'a.project_id')->leftjoin('users as e', 'e.id', '=', 'a.assign_to')->leftjoin('users as f', 'f.id', '=', 'a.created_by')->leftjoin('users as g', 'g.id', '=', 'a.updated_by')->leftjoin('status as h', 'h.status_id', '=', 'a.task_status')->first();

            if($task_details!="")
            {
                $task_id = $task_details->task_id;
                $client_name = $task_details->client_name;
                $project_name = $task_details->project_name;
                $task_name = $task_details->task_name;
                $description = strip_tags(html_entity_decode($task_details->description));
                $assign_to = $task_details->assign_to;
                $status_description = strip_tags(html_entity_decode($task_details->status_description));
                $attachment = $task_details->attachment;
                $created_by = $task_details->created_by;
                $created_at = $task_details->created_at;
                $created_date = date('Y-m-d',strtotime($created_at));
                $created_time = date('H:i:s',strtotime($created_at));

                $updated_by = $task_details->updated_by;
                $updated_at = $task_details->updated_at;
                $updated_date = date('Y-m-d',strtotime($updated_at));
                $updated_time = date('H:i:s',strtotime($updated_at));

                $task_status = $task_details->task_status;
                $task_status_name = $task_details->status_name;

                $status_details = DB::table('status')->where('deleted', 'No')->select('status_id as id', 'status_name as name')->get();
                foreach($status_details as $status_detail) {
                    $status_arr[]=$status_detail;
                }
                $status_json=$status_arr;

                $APIResponse['api_success']="true";
                $APIResponse['api_message']="Task Found";
                $APIResponse['task_id']=$task_id;
                $APIResponse['client_name']=$client_name;
                $APIResponse['project_name']=$project_name;
                $APIResponse['description']=$description;
                $APIResponse['task_name']=$task_name;
                $APIResponse['assign_to']=$assign_to;
                $APIResponse['status_description']=$status_description;
                $APIResponse['attachment']=$attachment;
                $APIResponse['created_by']=$created_by;
                $APIResponse['created_date']=$created_date;
                $APIResponse['created_time']=$created_time;
                $APIResponse['updated_by']=$updated_by;
                $APIResponse['updated_date']=$updated_date;
                $APIResponse['updated_time']=$updated_time;
                $APIResponse['updated_at']=$updated_at;
                $APIResponse['task_status_name']=$task_status_name;
                $APIResponse['task_status']=strip_tags($task_status);
                $APIResponse['status_list']=$status_json;
                $APIResponseArr=array_merge($APIResponse);
                return response($APIResponseArr, 201);
            }
            else
            {
                $APIResponse['api_success']="true";
                $APIResponse['api_message']="No Task Found";
                $APIResponseArr=array_merge($APIResponse);
                return response($APIResponseArr, 201);
            }
        }
    }

    public function task_update_submit(Request $request){

        if(isset($request->task_id) AND $request->task_id!='' AND $request->task_status AND $request->task_status!='')
        {
            $task_id = $request->task_id;
            $task_status = $request->task_status;
            $status_description = $request->status_description;
            $user_id = $request->user_id;
            $request_person_id = $user_id;
            if($task_status == 5)
            {
                $get_users = DB::table('users')->where('id', $user_id)->where('deleted', 'No')->first();
                $reporting_to = $get_users->reporting_to_id;
                $add_data = array('task_id' => $task_id, 'approval_status' => 1, 'request_person_id' => $request_person_id, 'approval_description' => $status_description, 'created_by' => $user_id, 'created_at' => Now());
                $task_approval_insert_query = DB::table('task_approvals')->insertGetId($add_data);
                $notification_to = $reporting_to;
                $get_notifications_name = DB::table('users')->where('id', $notification_to)->first();
                $notification_to_name = $get_notifications_name->first_name;
                $url = "tasks";
                $descriptions="Dear $notification_to_name, You Have One Task Approval : Thank You.";
                $createdby=$user_id;
                $add_notification_data = array('title' => 'Task Approval', 'description' => $descriptions, 'url' => $url, 'notification_to' => $notification_to, 'created_by' => $createdby, 'created_at' => Now());
                $add_notification = DB::table('notifications')->insert($add_notification_data);              
            }
            else
            {
                $request_data = array('task_status' => $task_status, 'status_description' => $status_description, 'updated_by' => $user_id, 'updated_at' => Now());
                $task_update = DB::table('tasks')->where('task_id', $task_id)->update($request_data);
            }
            $APIResponse['api_success']="true";
            $APIResponse['api_message']="Task Updated";
            $APIResponseArr=array_merge($APIResponse);
            return response($APIResponseArr, 201);
        }
        else
        {
            $APIResponse['api_success']="false";
            $APIResponse['api_message']="Invalid Details";
            $APIResponseArr=array_merge($APIResponse);
            return response($APIResponseArr, 201);
        }
    }

    public function ticket_update_submit(Request $request){

        if(isset($request->ticket_id) AND $request->ticket_id!='' AND $request->ticket_status AND $request->ticket_status!='')
        {
            $ticket_id = $request->ticket_id;
            $ticket_status = $request->ticket_status;
            $status_description = $request->status_description;
            $user_id = $request->user_id;
            $request_person_id = $user_id;
            if($ticket_status == 5)
            {
                $get_users = DB::table('users')->where('id', $user_id)->where('deleted', 'No')->first();
                $reporting_to = $get_users->reporting_to_id;
                $add_data = array('ticket_id' => $ticket_id, 'status_id' => $ticket_status, 'description' => $status_description, 'created_by' => $user_id, 'created_at' => Now());
                $timeline_insert = DB::table('tickets_timeline')->insertGetId($add_data);
                $request_data = array('status_id' => $ticket_status, 'description' => $status_description, 'updated_by' => $user_id, 'updated_at' => Now());
                $ticket_update = DB::table('tickets')->where('ticket_id', $ticket_id)->update($request_data);
                $notification_to = $reporting_to;
                $get_notifications_name = DB::table('users')->where('id', $notification_to)->first();
                $notification_to_name = $get_notifications_name->first_name;
                $url = "tickets";
                $descriptions="Dear $notification_to_name, One Ticket Updated By $user_id : Thank You.";
                $createdby=$user_id;
                $add_notification_data = array('title' => 'Ticket Closed', 'description' => $descriptions, 'url' => $url, 'notification_to' => $notification_to, 'created_by' => $createdby, 'created_at' => Now());
                $add_notification = DB::table('notifications')->insert($add_notification_data);              
            }
            else
            {
                $add_data = array('ticket_id' => $ticket_id, 'status_id' => $ticket_status, 'description' => $status_description, 'created_by' => $user_id, 'created_at' => Now());
                $timeline_insert = DB::table('tickets_timeline')->insert($add_data);
                $request_data = array('status_id' => $ticket_status, 'description' => $status_description, 'updated_by' => $user_id, 'updated_at' => Now());
                $ticket_update = DB::table('tickets')->where('ticket_id', $ticket_id)->update($request_data);
            }
            $response = [
                'Api_success' => 'true',
                'timeline_insert' => 'Timeline Created Successfully',
                'ticket_update' => 'Ticket Updated Successfully',
            ];
        }
        else
        {
            $timeline_insert = array();
            $ticket_update = array();
            $response = [
                'Api_success' => 'false',
                'timeline_insert' => $timeline_insert,
                'ticket_update' => $ticket_update,
            ];
            
        }
        return response($response, 201);
    }


    public function forget_password(Request $request){

        $request->validate([
            'email' => 'required|email|max:191',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status == Password::RESET_LINK_SENT){
            return [
                'status' => __($status)
            ];
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
    public function password_update(Request $request)
    {
        if($request->lead_id)
        {
            $data = array('password' => Hash::make($request->password), 'updated_by' => $request->id, 'updated_at' => Now());
            $update=DB::table('users')->where('id',$request->id)->update($data);
            if($update)
            {
                 $response = [
                'Api_success' => 'True',
                'Api_message' => 'Updated Successfully!'
                ];
            }
            else
            {
                 $response = [
                'Api_success' => 'False',
                'Api_message' => 'Updated Failed!'
                ];
            }
        }
        else
        {
           $response = [
                'Api_success' => 'False',
                'Api_message' => 'UserId is Empty!'
                ];
        }
        return response($response, 201);
    }
    public function lead_details(Request $request)
    {
        if($request->id)
        {
            $leadId = $request->id;
           $getLeadDetails = DB::table('leads as a')->select(['a.lead_id','a.lead_name','a.mobile_number','a.email_id','a.alter_email_id','a.age','o.lead_stage_name','p.lead_sub_stage','d.medium_name','e.lead_source_name','f.lead_sub_source_name','g.campaign_name','q.first_name as lead_owner','i.ad_name','j.product_category_name','k.product_name','l.country_name','m.state_name','n.city_name','a.address','a.pincode','r.communication_medium','s.communication_type','b.first_name as created_by','a.created_at'])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('mediums as d','d.medium_id', '=', 'a.medium_id')->leftjoin('lead_sources as e','e.lead_source_id', '=', 'a.source_id')->leftjoin('lead_sub_sources as f','f.lead_sub_source_id', '=', 'a.sub_source_id')->leftjoin('campaigns as g','g.campaign_id', '=', 'a.campaign_id')->leftjoin('users as h','h.id', '=', 'a.lead_owner')->leftjoin('ad_names as i','i.ad_name_id', '=', 'a.ad_name_id')->leftjoin('product_categories as j','j.product_category_id', '=', 'a.product_category_id')->leftjoin('products as k','k.product_id', '=', 'a.product_id')->leftjoin('countries as l','l.country_id', '=', 'a.country_id')->leftjoin('states as m','m.state_id', '=', 'a.state_id')->leftjoin('cities as n','n.city_id', '=', 'a.city_id')->leftjoin('lead_stages as o','o.lead_stage_id', '=', 'a.lead_stage_id')->leftjoin('lead_sub_stage as p','p.lead_sub_stage_id', '=', 'a.lead_sub_stage_id')->leftjoin('users as q', 'q.id', '=', 'a.lead_owner',)->leftjoin('communication_mediums as r','r.communication_medium_id', '=', 'a.communication_medium_id')->leftjoin('communication_types as s', 's.communication_type_id', '=', 'a.communication_medium_type_id')->where('a.lead_id',$leadId)->where('a.deleted','No')->first();
            // $getLeadDetails = DB::table('leads')->where('lead_id',$request->id)->get();

                $get_timeline_history= DB::table('timelines as a')->select('a.timeline_for_id','a.timeline_id','b.lead_stage_name','c.lead_sub_stage','d.communication_medium','e.communication_type','a.description')->leftjoin('lead_stages as b', 'b.lead_stage_id','=', 'a.lead_stage_id')->leftjoin('lead_sub_stage as c', 'c.lead_sub_stage_id', '=', 'a.lead_sub_stage_id')->leftjoin('communication_mediums as d','d.communication_medium_id', '=', 'a.communication_medium_id')->leftjoin('communication_types as e', 'e.communication_type_id', '=', 'a.communication_medium_type_id')->where('timeline_for_id',$leadId)->get();
                // $lead_data1=array($get_timeline_history);
                // $lead_data2=array($getLeadDetails);
                // $getLead=array_merge($lead_data1,$lead_data2);

            if($getLeadDetails)
            {
                $response = [
                'Api_success' => 'True',
                'lead_details' => $getLeadDetails, 
                'timeline_details' => $get_timeline_history,
                ];
            }
            else
            {
                $response = [
                'Api_success' => 'False',
                'Api_message' => 'LeadId Not Found!'
                ];
            }
        }
        else
        {
            $response = [
                'Api_success' => 'False',
                'Api_message' => 'LeadId is Empty!'
                ];
        }

        return response($response, 201);
    }
    public function lead_list(Request $request)
    {
        $id = $request->id;
        

        if($request->status_id==""){
            $status_id = 1;
        }
        else{
            $status_id = $request->status_id;
        }
        $limit = $request->limit ? $request->limit : 10;
        $page = $request->page && $request->page > 0 ? $request->page : 1;
        $skip = ($page - 1) * $limit;

        $user_details = DB::table('users')->where('id',$id)->first();

        $status_details = DB::table('lead_stages')->where('deleted','No')->select('lead_stage_id','lead_stage_name')->get();

        


        if($id!=""){
            $where="a.deleted='No'";

            $get_auth_user = DB::table('users')->where('deleted', 'No')->where('id',$id)->first();

            if($get_auth_user->designation_id==1){

                $user_lists = DB::table('users')->where('deleted', 'No')->select('id', 'first_name')->get();

            }
            else{

                $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $id)->where('deleted', 'No')->select('id', 'first_name');

                $user_lists = DB::table('users')->where('id', $id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();
            
                foreach($user_lists as $user_list)
                {
                    $storedArray[] = $user_list->id;
                }
                $string = implode(', ', $storedArray);
                $where.="AND a.lead_owner IN ($string)";
            }

            if($request->from_date!="" && $request->to_date!=""){
                $FromDate=$request->from_date;
                $ToDate=$request->to_date;
                $where.="AND a.created_at >= '".$FromDate." 00:00:00' AND a.created_at <= '".$ToDate." 23:59:59'";
            }
            if($user_details->designation_id==1)
            {
                
                $lead_details = DB::table('leads as a')->select('a.lead_id','a.lead_name','a.mobile_number','a.email_id','a.alter_email_id','a.age','o.lead_stage_name','p.lead_sub_stage','d.medium_name','e.lead_source_name','f.lead_sub_source_name','g.campaign_name','q.first_name as lead_owner','i.ad_name','j.product_category_name','k.product_name','l.country_name','m.state_name','n.city_name','a.address','a.pincode','r.communication_medium','s.communication_type','b.first_name as created_by','a.created_at')->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('mediums as d','d.medium_id', '=', 'a.medium_id')->leftjoin('lead_sources as e','e.lead_source_id', '=', 'a.source_id')->leftjoin('lead_sub_sources as f','f.lead_sub_source_id', '=', 'a.sub_source_id')->leftjoin('campaigns as g','g.campaign_id', '=', 'a.campaign_id')->leftjoin('users as h','h.id', '=', 'a.lead_owner')->leftjoin('ad_names as i','i.ad_name_id', '=', 'a.ad_name_id')->leftjoin('product_categories as j','j.product_category_id', '=', 'a.product_category_id')->leftjoin('products as k','k.product_id', '=', 'a.product_id')->leftjoin('countries as l','l.country_id', '=', 'a.country_id')->leftjoin('states as m','m.state_id', '=', 'a.state_id')->leftjoin('cities as n','n.city_id', '=', 'a.city_id')->leftjoin('lead_stages as o','o.lead_stage_id', '=', 'a.lead_stage_id')->leftjoin('lead_sub_stage as p','p.lead_sub_stage_id', '=', 'a.lead_sub_stage_id')->leftjoin('users as q', 'q.id', '=', 'a.lead_owner',)->leftjoin('communication_mediums as r','r.communication_medium_id', '=', 'a.communication_medium_id')->leftjoin('communication_types as s', 's.communication_type_id', '=', 'a.communication_medium_type_id')->where('a.lead_stage_id',$status_id)->whereRaw($where)->orderby('lead_id', 'desc')->paginate($limit);
            }
            else{
                    $lead_details = DB::table('leads as a')->select('a.lead_id','a.lead_name','a.mobile_number','a.email_id','a.alter_email_id','a.age','o.lead_stage_name','p.lead_sub_stage','d.medium_name','e.lead_source_name','f.lead_sub_source_name','g.campaign_name','q.first_name as lead_owner','i.ad_name','j.product_category_name','k.product_name','l.country_name','m.state_name','n.city_name','a.address','a.pincode','r.communication_medium','s.communication_type','b.first_name as created_by','a.created_at')->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('mediums as d','d.medium_id', '=', 'a.medium_id')->leftjoin('lead_sources as e','e.lead_source_id', '=', 'a.source_id')->leftjoin('lead_sub_sources as f','f.lead_sub_source_id', '=', 'a.sub_source_id')->leftjoin('campaigns as g','g.campaign_id', '=', 'a.campaign_id')->leftjoin('users as h','h.id', '=', 'a.lead_owner')->leftjoin('ad_names as i','i.ad_name_id', '=', 'a.ad_name_id')->leftjoin('product_categories as j','j.product_category_id', '=', 'a.product_category_id')->leftjoin('products as k','k.product_id', '=', 'a.product_id')->leftjoin('countries as l','l.country_id', '=', 'a.country_id')->leftjoin('states as m','m.state_id', '=', 'a.state_id')->leftjoin('cities as n','n.city_id', '=', 'a.city_id')->leftjoin('lead_stages as o','o.lead_stage_id', '=', 'a.lead_stage_id')->leftjoin('lead_sub_stage as p','p.lead_sub_stage_id', '=', 'a.lead_sub_stage_id')->leftjoin('users as q', 'q.id', '=', 'a.lead_owner',)->leftjoin('communication_mediums as r','r.communication_medium_id', '=', 'a.communication_medium_id')->leftjoin('communication_types as s', 's.communication_type_id', '=', 'a.communication_medium_type_id')->where('a.deleted','No')->where('a.lead_stage_id',$status_id)->whereRaw($where)->orderby('lead_id', 'desc')->paginate($limit);

            }
            
        }
        else
        {
            $lead_details = DB::table('leads')->where('lead_id', '=',$request->selected_result)->orwhere('email_id', '=', $request->selected_result)->orwhere('mobile_number', '=', $request->selected_result)->select('*')->get();
        }


        foreach($lead_details as $lead_detail){
            $lead_arr[] = $lead_detail;
        }
        if(isset($lead_arr))
        {
            $response = [
                'Api_success' => 'true',
                'lead_details' => $lead_arr,
                'user_lists' => $user_lists,
                'status_details' => $status_details,
            ];
        }
        else
        {
            $lead_details = array();
            $response = [
                'Api_success' => 'true',
                'lead_details' => $lead_details,
            ];
        }
        return response($response, 201);
    }

    public function lead_filter(Request $request)
    {
        $id = $request->id;
        

        // if($request->status_id==""){
        //     $status_id = 1;
        // }
        // else{
        //     $status_id = $request->status_id;
        // }
        // $limit = $request->limit ? $request->limit : 10;
        // $page = $request->page && $request->page > 0 ? $request->page : 1;
        // $skip = ($page - 1) * $limit;

        $user_details = DB::table('users')->where('id',$id)->first();

        // $status_details = DB::table('lead_stages')->where('deleted','No')->select('lead_stage_id','lead_stage_name')->get();

        


        if($id!=""){
            $where="a.deleted='No'";

            $get_auth_user = DB::table('users')->where('deleted', 'No')->where('id',$id)->first();

            if($get_auth_user->designation_id==1){

                $user_lists = DB::table('users')->where('deleted', 'No')->select('id', 'first_name')->get();

            }
            else{

                $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $id)->where('deleted', 'No')->select('id', 'first_name');

                $user_lists = DB::table('users')->where('id', $id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();
            
                foreach($user_lists as $user_list)
                {
                    $storedArray[] = $user_list->id;
                }
                $string = implode(', ', $storedArray);
                $where.="AND a.lead_owner IN ($string)";
            }

            if($request->from_date!="" && $request->to_date!=""){
                $FromDate=$request->from_date;
                $ToDate=$request->to_date;
                $where.="AND a.created_at >= '".$FromDate." 00:00:00' AND a.created_at <= '".$ToDate." 23:59:59'";
            }
            if($user_details->designation_id==1)
            {
                
                $lead_details = DB::table('leads as a')->select('a.lead_id','a.lead_name','a.mobile_number','a.email_id','a.alter_email_id','a.age','o.lead_stage_name','p.lead_sub_stage','d.medium_name','e.lead_source_name','f.lead_sub_source_name','g.campaign_name','q.first_name as lead_owner','i.ad_name','j.product_category_name','k.product_name','l.country_name','m.state_name','n.city_name','a.address','a.pincode','r.communication_medium','s.communication_type','b.first_name as created_by','a.created_at')->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('mediums as d','d.medium_id', '=', 'a.medium_id')->leftjoin('lead_sources as e','e.lead_source_id', '=', 'a.source_id')->leftjoin('lead_sub_sources as f','f.lead_sub_source_id', '=', 'a.sub_source_id')->leftjoin('campaigns as g','g.campaign_id', '=', 'a.campaign_id')->leftjoin('users as h','h.id', '=', 'a.lead_owner')->leftjoin('ad_names as i','i.ad_name_id', '=', 'a.ad_name_id')->leftjoin('product_categories as j','j.product_category_id', '=', 'a.product_category_id')->leftjoin('products as k','k.product_id', '=', 'a.product_id')->leftjoin('countries as l','l.country_id', '=', 'a.country_id')->leftjoin('states as m','m.state_id', '=', 'a.state_id')->leftjoin('cities as n','n.city_id', '=', 'a.city_id')->leftjoin('lead_stages as o','o.lead_stage_id', '=', 'a.lead_stage_id')->leftjoin('lead_sub_stage as p','p.lead_sub_stage_id', '=', 'a.lead_sub_stage_id')->leftjoin('users as q', 'q.id', '=', 'a.lead_owner',)->leftjoin('communication_mediums as r','r.communication_medium_id', '=', 'a.communication_medium_id')->leftjoin('communication_types as s', 's.communication_type_id', '=', 'a.communication_medium_type_id')->whereRaw($where)->orderby('lead_id', 'desc')->get();
            }
            else{
                    $lead_details = DB::table('leads as a')->select('a.lead_id','a.lead_name','a.mobile_number','a.email_id','a.alter_email_id','a.age','o.lead_stage_name','p.lead_sub_stage','d.medium_name','e.lead_source_name','f.lead_sub_source_name','g.campaign_name','q.first_name as lead_owner','i.ad_name','j.product_category_name','k.product_name','l.country_name','m.state_name','n.city_name','a.address','a.pincode','r.communication_medium','s.communication_type','b.first_name as created_by','a.created_at')->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('mediums as d','d.medium_id', '=', 'a.medium_id')->leftjoin('lead_sources as e','e.lead_source_id', '=', 'a.source_id')->leftjoin('lead_sub_sources as f','f.lead_sub_source_id', '=', 'a.sub_source_id')->leftjoin('campaigns as g','g.campaign_id', '=', 'a.campaign_id')->leftjoin('users as h','h.id', '=', 'a.lead_owner')->leftjoin('ad_names as i','i.ad_name_id', '=', 'a.ad_name_id')->leftjoin('product_categories as j','j.product_category_id', '=', 'a.product_category_id')->leftjoin('products as k','k.product_id', '=', 'a.product_id')->leftjoin('countries as l','l.country_id', '=', 'a.country_id')->leftjoin('states as m','m.state_id', '=', 'a.state_id')->leftjoin('cities as n','n.city_id', '=', 'a.city_id')->leftjoin('lead_stages as o','o.lead_stage_id', '=', 'a.lead_stage_id')->leftjoin('lead_sub_stage as p','p.lead_sub_stage_id', '=', 'a.lead_sub_stage_id')->leftjoin('users as q', 'q.id', '=', 'a.lead_owner',)->leftjoin('communication_mediums as r','r.communication_medium_id', '=', 'a.communication_medium_id')->leftjoin('communication_types as s', 's.communication_type_id', '=', 'a.communication_medium_type_id')->where('a.deleted','No')->whereRaw($where)->orderby('lead_id', 'desc')->get();

            }
            
        }
        else
        {
            $lead_details = DB::table('leads')->where('lead_id', '=',$request->selected_result)->orwhere('email_id', '=', $request->selected_result)->orwhere('mobile_number', '=', $request->selected_result)->select('*')->get();
        }


        foreach($lead_details as $lead_detail){
            $lead_arr[] = $lead_detail;
        }

        if(isset($lead_arr))
        {
            $response = [
                'Api_success' => 'true',
                'lead_details' => $lead_arr,
                'user_lists' => $user_lists,
                // 'status_details' => $status_details,
            ];
        }
        else
        {
            $lead_details = array();
            $response = [
                'Api_success' => 'true',
                'lead_details' => $lead_details,
            ];
        }
        return response($response, 201);
    }

    public function lead_create(Request $request)
    {
        $user_id=$request->user_id;
        $data = [
            'user_id' => $user_id,
            'description' => $request->description,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'checkin_from'=> 'Active App',
            'type'=>'checkin',
            'attendance_at'=>Now(),
            'created_by' => $user_id,
            'created_at' => Now(),
        ];
        $leadAdd = DB::table('leads')->insert($data);
        if(isset($leadAdd))
        {
            $response = [
                'Api_success' => 'true', 
                'lead_details' => 'Added Successfully',
            ];
        }
        else
        {
            $response = [
                'Api_success' => 'false',
                'lead_details' => 'Insert Error',
            ];
        }
        return response($response, 201);
    }

    public function get_lead_stage(Request $request){
        $lead_stage_id=$request->lead_stage_id;
        $lead_stages=DB::table('lead_stages')->where('lead_stage_id',$lead_stage_id)->select('lead_stage_id','lead_stage_name')->where('deleted', 'No')->get();
        foreach($lead_stages as $lead_stage){
            $lead_stageArr[] = $lead_stage;   
        }
        if(isset($lead_stageArr))
        {
            $response = [
                'Api_success' => 'true',
                'lead_stages' => $lead_stageArr,
            ];
        }
        else
        {
            $response = [
                'Api_success' => 'false',
                'lead_stages' => 'Not Found',
            ];
        }
        return response($response, 201);
    }

   public function get_lead_sub_stage(Request $request){
        $lead_stage_id=$request->lead_stage_id;
        $lead_sub_stages=DB::table('lead_sub_stage')->where('lead_stage_id',$lead_stage_id)->where('deleted', 'No')->select('lead_sub_stage_id','lead_sub_stage')->get();
        foreach($lead_sub_stages as $lead_sub_stage){
            $lead_sub_stageArr[] = $lead_sub_stage;   
        }
        if(isset($lead_sub_stageArr))
        {
            $response = [
                'Api_success' => 'true',
                'lead_sub_stages' => $lead_sub_stageArr,
            ];
        }
        else
        {
            $response = [
                'Api_success' => 'false',
                'lead_sub_stages' => 'Not Found',
            ];
        }
        return response($response, 201);

   } 

   public function get_products(Request $request){
        $products= DB::table('products')->select('product_id','product_name')->where('product_category_id',$request->product_category_id)->where('deleted','No')->get();
        foreach($products as $product){
            $productArr[] = $product;   
        }
        if(isset($productArr))
        {
            $response = [
                'Api_success' => 'true',
                'product_list' => $productArr,
            ];
        }
        else
        {
            $response = [
                'Api_success' => 'false',
                'product_list' => 'Not Found',
            ];
        }
        return response($response, 201);

   } 

   public function lead_edit(Request $request){
    
        $lead_edit_details = DB::table('leads')->where('lead_id',$request->lead_id)->where('deleted','No')->first();
        $lead_stages= DB::table('lead_stages')->select('lead_stage_id','lead_stage_name')->where('deleted', 'No')->get();
        $lead_sub_stages= DB::table('lead_sub_stage')->select('lead_sub_stage_id','lead_sub_stage')->where('lead_stage_id',$lead_edit_details->lead_stage_id)->where('deleted', 'No')->get();
        $lead_sources= DB::table('lead_sources')->select('lead_source_id','lead_source_name')->where('deleted', 'No')->get();
        $lead_sub_sources= DB::table('lead_sub_sources')->select('lead_sub_source_id','lead_sub_source_name')->where('deleted','No')->where('lead_source_id',$lead_edit_details->source_id)->get();
        $campaigns_list= DB::table('campaigns')->select('campaign_id','campaign_name')->where('deleted','No')->get();
        $ad_name_list= DB::table('ad_names')->select('ad_name_id','ad_name')->where('deleted','No')->get();
        $medium_lists= DB::table('mediums')->select('medium_id','medium_name')->where('deleted','No')->get();
        $countries = DB::table('countries')->select('country_id', 'country_name')->get();
        $states = DB::table('states')->where('country_id',$lead_edit_details->country_id)->select('state_id','state_name')->get();
        $cities = DB::table('cities')->where('state_id',$lead_edit_details->state_id)->select('city_id', 'city_name')->get();
        $products_category_list = DB::table('product_categories')->select('product_category_id','product_category_name')->where('deleted', 'No')->get();
        $products_list= DB::table('products')->select('product_id','product_name')->where('product_category_id',$lead_edit_details->product_category_id)->where('deleted','No')->get();
        $communication_mediums= DB::table('communication_mediums')->select('communication_medium_id','communication_medium')->where('communication_medium_id',$lead_edit_details->communication_medium_id)->where('deleted','No')->get();
        $communication_types= DB::table('communication_types')->select('communication_type_id','communication_type')->where('communication_type_id',$lead_edit_details->communication_medium_type_id)->where('deleted','No')->get();


        if(isset($lead_edit_details))
        {
            $response = [
                'Api_success' => 'true',
                'lead_details' => $lead_edit_details,
                'lead_stages' => $lead_stages,
                'lead_sub_stages'=>$lead_sub_stages,
                'lead_sources' =>$lead_sources,
                'lead_sub_sources' =>$lead_sub_sources,
                'campaigns'=>$campaigns_list,
                'ad_name_list'=>$ad_name_list,
                'medium_lists'=>$medium_lists,
                'countries'=>$countries,
                'states'=>$states,
                'cities'=>$cities,
                'products_category_list'=>$products_category_list,
                'products_list'=>$products_list,
                'communication_mediums'=>$communication_mediums,
                'communication_types'=>$communication_types,
            ];
        }
        else
        {
            $response = [
                'Api_success' => 'false',
                'lead_details' => 'list not found',
            ];
        }
        return response($response, 201);

   }

   public function lead_update(Request $request){

    if(isset($request->lead_id)){
        $data = array( 
            'lead_name' => $request->lead_name,
            'mobile_number' => $request->mobile_number,
            'alter_mobile_number' => $request->alter_mobile_number,
            'email_id' => $request->email_id,
            'alter_email_id' => $request->alter_email_id,
            'age' => $request->age,
            'medium_id' => $request->medium_id,
            'source_id' => $request->source_id,
            'sub_source_id' => $request->sub_source_id,
            'campaign_id' => $request->campaign_id,
            // 'lead_owner' => $request->lead_owner,
            'ad_name_id' => $request->ad_name_id,
            'product_category_id' => $request->product_category_id,
            'product_id' => $request->product_id,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'pincode' => $request->pincode,
            'address' => $request->address,
            // 'custom_fields' => $CustomFieldJsonValue,
            'updated_by' =>$request->user_id,
            'updated_at' => Now(),
            'lead_stage_id'=>$request->lead_stage_id,
            'lead_sub_stage_id'=>$request->lead_sub_stage_id
         );
         $lead_update=DB::table('leads')->where('lead_id',$request->lead_id)->update($data);
        if($lead_update)
            {
                 $response = [
                'Api_success' => 'True',
                'Api_message' => 'Updated Successfully!'
                ];
            }
            else
            {
                 $response = [
                'Api_success' => 'False',
                'Api_message' => 'Updated Failed!'
                ];
            }
        }
        else
        {
           $response = [
                'Api_success' => 'False',
                'Api_message' => 'LeadId is Empty!'
                ];
        }
        return response($response, 201);
    }

    public function add_server_key(Request $request){
        if(isset($request->user_id) && $request->user_id!=""){
            $user_id = $request->user_id;
            $server_key = $request->server_key;
            $data = array('server_key' => $server_key, 'updated_by' => 0, 'updated_at' => Now());
            $update_user = DB::table('users')->where('id', $user_id)->update($data);
            if($update_user)
            {
                 $response = [
                'Api_success' => 'True',
                'Api_message'=> 'ServerKey Updated',
                'update_user' => $update_user,
                'server_key' => $server_key,
                ];
            }
            else
            {
                 $response = [
                'Api_success' => 'False',
                'Api_message' => 'Updated Failed!'
                ];
            }
        }
        else{
             $response = [
                'Api_success' => 'False',
                'Api_message' => 'Invalid Details Send!'
                ];
        }
        return response($response, 201);
    }

    public function timeline_add(Request $request){
        $user_id = $request->Id;
        
        
        if(isset($user_id))
        {

            
            if ($file_name = $request->file('Image')) 
            {
                $destinationPath = 'public/Image/'.$user_id.'/'.date("Y-m-d").'/';
                $attachment = date('YmdHis') . "." . $file_name->getClientOriginalExtension();
                $file_name->move($destinationPath, $attachment);
            }
            
            else{
                $attachment="";

            }
            $RecordingFile = $request->file('RecordingFile');
            $FileNameArr=explode(".",basename($_FILES["RecordingFile"]["name"]));
            $FileName=str_replace(" ","_",$FileNameArr[0]);
            $FileExtn="mp3";
            $FolderPath = 'public/Recordings/'.$user_id.'/'.date("Y-m-d");
            $SaveFileName=$FileName.".".$FileExtn;
            if($RecordingFile->move($FolderPath, $SaveFileName))
            {
                if($FileExtn!="mp3")
                {
                    $FileExtn="mp3";
                }
                $CallRecording= $FolderPath."/".$FileName.".".$FileExtn;
                
            }
            else
            {
                $CallRecording="";
               
            }
            
            // if ($RecordingFile = $request->file('RecordingFile')) 
            // {
            //     $destinationPath = 'public/Recordings/'.$user_id.'/'.date("Y-m-d").'/';
            //     $FileName = date('YmdHis') . "." . $RecordingFile->getClientOriginalExtension();
            //     $RecordingFile->move($destinationPath, $FileName);


            // }
            // else{
            //     $FileName="";
            // }

            if (!file_exists($destinationPath)) {
                $destinationPath=mkdir($destinationPath, 0777, true);
            }

            if(isset($request->CommunicationMedium)){$CommunicationMedium=$request->CommunicationMedium; }else{$CommunicationMedium="2";}

            if(isset($request->CommunicationType)){$CommunicationType=$request->CommunicationType; }else{$CommunicationType="4";}

            $CallUniqueId=$request->CallUniqueId;
            $MobileNumber=$request->MobileNumber;
            $CallDuration=$request->CallDuration;
            $RefDuration=$request->CallDuration;
            function time2sec($item) {
                $durations = array_reverse(explode(':', $item));
                $second = array_shift($durations);
                foreach ($durations as $duration) {
                    $second += (60 * (int)$duration);
                }
                return (int)$second+15;
            }
            $CallDuration=time2sec($RefDuration);
            $CallStartedAt=$request->CallStartedAt;
            $CallEndedAt=$request->CallEndedAt;
            $CallType=$request->CallType;
            $CallStatus=$request->CallStatus;
            if(isset($request->CallDescription)){
            $CallDescription=$request->CallDescription;
            }
            $lead_details = DB::table('leads')->where('mobile_number', $MobileNumber)->first();
            $LeadStatus=$lead_details->lead_stage_id;
            $LeadSubStatus=$lead_details->lead_sub_stage_id;
            $data=array(
                'timeline_for' => 'Lead',
                'timeline_for_id' => $lead_details->lead_id,
                'communication_medium_id' => $lead_details->communication_medium_id,
                'communication_medium_type_id' => $lead_details->communication_medium_type_id,
                'lead_stage_id' => $lead_details->lead_stage_id,
                'lead_sub_stage_id' => $lead_details->lead_sub_stage_id,
                'description' => $CallDescription,
                'call_duration' => $CallDuration,
                'ref_duration' => $RefDuration,
                'call_started_at' => $CallStartedAt,
                'call_ended_at' => $CallEndedAt,
                'call_type' => $CallType,
                'call_status' => $CallStatus,
                'attachments' => $attachment,
                'call_recording' => $CallRecording,
                'unique_id' => $CallUniqueId,
                'created_at' => Now(),
                'created_by' => $user_id,
            );

            $timeline_insert=DB::table('timelines')->insert($data);
            $response = [
                'Api_success' => 'True',
                'Api_message'=> 'Timeline Added Successfully',
                'timeline_insert' => $timeline_insert,
                ];
            
                $GetLead=DB::table('leads')->where('lead_id', $lead_details->lead_id)->first();
                $FirstActivityDateTime=$GetLead->first_activity_at;
                $LastActivity=$CommunicationType;
                $LeadOwnerId=$GetLead->lead_owner;


                if($CommunicationMedium==3 AND (is_null($GetLead['first_activity_at']) OR ($GetLead['first_activity_at']=="0000-00-00 00:00:00")))
                {
                    $FirstActivityBy=$UserId;
                    $FirstActivity=$CommunicationType;
                    $FirstActivityDateTime=date("Y-m-d H:i:s");
                }
                else
                {
                    $FirstActivityBy=$GetLead->last_activity_by;
                    $FirstActivity=$GetLead->first_activity;
                    $FirstActivityDateTime=$GetLead->first_activity_at;
                }

                if($LeadStatus==0)
                {
                    $LeadStatus=$GetLead->lead_stage_id;
                    $LeadSubStatus=$GetLead->lead_sub_stage_id;
                }


                $leads_update=array('lead_stage_id'=>$LeadStatus,'lead_sub_stage_id'=>$LeadSubStatus,'communication_medium_id'=>$request->communication_mediumid,'communication_medium_type_id'=>$request->communication_type_id, 'first_activity_by' => $FirstActivityBy, 'first_activity' => $FirstActivity, 'last_activity' => $LastActivity, 'last_activity_by' => $user_id, 'first_activity_at' => $CallStartedAt, 'last_activity_at' => Now(),'updated_by'=> $user_id, 'updated_at'=> Now(),'last_comments'=> $CallDescription,'lead_owner'=> $LeadOwnerId);

                

                $lead=DB::table('leads')->where('lead_id',$request->LeadId)->update($leads_update);
        }
        else{
         $response = [
            'Api_success' => 'False',
            'Api_message' => 'Something Went Wrong!'
            ];
        }
        
        return response($response, 201);
    }

    public function get_communication_medium(Request $request){
        
        $communication_mediums=DB::table('communication_mediums')->select('communication_medium_id','communication_medium')->where('deleted', 'No')->get();
        foreach($communication_mediums as $communication_medium){
            $communication_mediumArr[] = $communication_medium;   
        }
        if(isset($communication_mediumArr))
        {
            $response = [
                'Api_success' => 'true',
                'communication_mediums' => $communication_mediumArr,
            ];
        }
        else
        {
            $response = [
                'Api_success' => 'false',
                'communication_mediums' => 'Not Found',
            ];
        }
        return response($response, 201);
    }

    public function get_communication_type(Request $request){
        $communication_types=DB::table('communication_types')->select('communication_type_id','communication_type')->where('deleted', 'No')->get();
        foreach($communication_types as $communication_type){
            $communication_typeArr[] = $communication_type;   
        }
        if(isset($communication_typeArr))
        {
            $response = [
                'Api_success' => 'true',
                'communication_types' => $communication_typeArr,
            ];
        }
        else
        {
            $response = [
                'Api_success' => 'false',
                'lead_stages' => 'Not Found',
            ];
        }
        return response($response, 201);
    }

    public function get_task_select_values(Request $request){

        $auth_id = $request->user_id;

        $client_details = DB::table('clients')->where('deleted', 'No')->select('client_id', 'client_name')->get();

        $project_details = DB::table('projects')->where('deleted', 'No')->select('project_id', 'project_name')->get();

        $status_details = DB::table('status')->where('deleted', 'No')->select('status_id', 'status_name')->get();

        $task_priority_details = DB::table('task_priority')->where('deleted', 'No')->select('priority_id', 'priority_name')->get();
        
        if($auth_id==1){

            $user_details = DB::table('users')->where('deleted', 'No')->select('id', 'first_name')->get();

        }
        else{

            $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $auth_id)->where('deleted', 'No')->select('id', 'first_name');

            $user_details = DB::table('users')->where('id', $auth_id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();

        }

        $response = [
            'client_details' => $client_details,
            'project_details' => $project_details,
            'status_details' => $status_details,
            'task_priority_details' => $task_priority_details,
            'user_details' => $user_details,
        ];
        return response($response, 201);
    }

    public function task_add(Request $request){
        $createdby=$request->user_id;
        $updatedby=Auth::id();
        if(isset($request->task_id)){
            $files=[];
            if(!is_null($request->file('attachment'))){
                foreach($request->file('attachment') as $file)
                {
                    $name = time() . rand(1, 100) . '.' . $file->extension();
                    $file->move(public_path('task_uploads'),$name);
                    $files[] = $name;
                }
            }
            $data = array('client_id' => $request->client_id, 'task_name' => $request->task_name, 'project_id' => $request->project_id, 'assign_to' => $request->assign_to, 'task_status' => $request->status_id, 'priority_id' => $request->priority_id, 'description' => $request->description, 'updated_by' => $createdby, 'updated_at' => Now());

            $tasks_edit=DB::table('tasks')->where('task_id',$request->task_id)->update($data);
            if($tasks_edit){
                $response = [
                    'api_success' => 'True',
                    'api_message' => 'Task Updated Successfully',
                    'task_update' => $tasks_edit,
                ];
                return response($response, 201);
            }
            else{
                $response = [
                    'api_success' => 'False',
                    'api_message' => 'Something Went Wrong!',
                ];
                return response($response, 201);
            }   
             $data = array('task_id'=>$request->task_id,'client_id' => $request->client_id, 'task_name' => $request->task_name, 'project_id' => $request->project_id, 'assign_to' => $request->assign_to,  'task_status' => $request->status_id,  'priority_id' => $request->priority_id, 'status_description' => $request->description, 'updated_by' => $createdby, 'updated_at' => Now());
            $task_log_update = DB::table('tasks_log')->insert($data);

            for($i = 0; $i < count($files); $i++){
                $attachments = $files[$i];
                $attachment_add = array('task_id' => $request->task_id, 'attachment' => $attachments, 'created_by' => $createdby, 'created_at' => Now());

                $task_attachment_add = DB::table('task_attachments')->insert($attachment_add);
            }
        }
        else
        {
            
            if($request->client_id!="" && $request->task_name!="" && $request->project_id!="" && $request->assign_to!="" && $request->priority_id!="" && $createdby!=""){
                $files=[];
                if(!is_null($request->file('attachment'))){
                    foreach($request->file('attachment') as $file)
                    {
                        $name = time() . rand(1, 100) . '.' . $file->extension();
                        $file->move(public_path('task_uploads'),$name);
                        $files[] = $name;
                    }
                }
                if($request->status_id!=''){
                    $status_id= $request->status_id;  
                }
                else{
                    $status_id=1;
                }

                $data = array('client_id' => $request->client_id, 'task_name' => $request->task_name, 'project_id' => $request->project_id, 'assign_to' => $request->assign_to, 'task_status' => $status_id, 'priority_id' => $request->priority_id, 'description' => $request->description, 'created_by' => $createdby, 'created_at' => Now());
                $tasks_add=DB::table('tasks')->insertGetId($data);
                if($tasks_add){
                    
                $log_insert = array('task_id' => $tasks_add,'client_id' => $request->client_id, 'task_name' => $request->task_name, 'project_id' => $request->project_id, 'assign_to' => $request->assign_to,  'task_status' => $status_id,  'priority_id' => $request->priority_id, 'status_description' => $request->description, 'created_by' => $createdby, 'created_at' => Now());
                $task_log_insert = DB::table('tasks_log')->insert($log_insert);
                for($i = 0; $i < count($files); $i++){
                    $attachments = $files[$i];
                    $attachment_add = array('task_id' => $tasks_add, 'attachment' => $attachments, 'created_by' => $createdby, 'created_at' => Now());

                    $task_attachment_add = DB::table('task_attachments')->insert($attachment_add);
                }
                $notification_to = $request->assign_to;
                $get_user_name = DB::table('users')->where('id', $notification_to)->select('first_name')->first();
                $notification_to_name = $get_user_name->first_name;
                $from_user_name = DB::table('users')->where('id', $createdby)->select('first_name')->first();
                $notification_from_name = $from_user_name->first_name;
                $URL="tasks";
                $Descriptions="Dear $notification_to_name, You have received one Task From $notification_from_name : Thank You.";
                $notification_data = array('title' => 'Task Assigned For You', 'description' => $Descriptions, 'url' => $URL, 'notification_to' => $notification_to, 'created_by' => $createdby, 'created_at' => Now());
                $insert_notification = DB::table('notifications')->insert($notification_data);

                $response = [
                        'api_success' => 'True',
                        'api_message' => 'Task Created Successfully',
                        'task_id' => $tasks_add,
                    ];
                    return response($response, 201);
                }
                else{
                    $response = [
                        'api_success' => 'True',
                        'api_message' => 'Something Went Wrong!',
                    ];
                    return response($response, 201);
                }
            }
            else{
                $response = [
                        'api_success' => 'True',
                        'api_message' => 'Input Datas are Empty!',
                    ];
                    return response($response, 201);
            }
        }
    }

    public function get_ticket_select_values(Request $request){

        $auth_id = $request->user_id;

        $client_details = DB::table('clients')->where('deleted', 'No')->select('client_id', 'client_name')->get();

        $customers_contact_lists = DB::table('customer_contacts')->where('deleted','No')->select('customer_contact_id', 'first_name')->get();

        $ticket_type_details = DB::table('ticket_types')->where('deleted', 'No')->select('ticket_type_id', 'ticket_type_name')->get();

        $ticket_priority_details = DB::table('ticket_priority')->where('deleted', 'No')->select('priority_id', 'priority_name')->get();

        $ticket_source_details = DB::table('ticket_sources')->where('deleted', 'No')->select('ticket_source_id', 'ticket_source_name')->get();

        $ticket_status_details = DB::table('ticket_status')->where('deleted', 'No')->select('ticket_status_id', 'ticket_status_name')->get();

        $ticket_created_type_details = DB::table('ticket_created_type')->where('deleted', 'No')->select('ticket_created_type_id', 'ticket_created_type_name')->get();
        
        if($auth_id==1){

            $user_details = DB::table('users')->where('deleted', 'No')->select('id', 'first_name')->get();

        }
        else{

            $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $auth_id)->where('deleted', 'No')->select('id', 'first_name');

            $user_details = DB::table('users')->where('id', $auth_id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();

        }

        $response = [
            'client_details' => $client_details,
            'customers_contact_lists' => $customers_contact_lists,
            'ticket_type_details' => $ticket_type_details,
            'ticket_priority_details' => $ticket_priority_details,
            'ticket_source_details' => $ticket_source_details,
            'user_details' => $user_details,
            'ticket_status_details' => $ticket_status_details,
            'ticket_created_type_details' => $ticket_created_type_details,
        ];
        return response($response, 201);
    }

    public function ticket_add(Request $request){
        $createdby = $request->user_id;
        $updatedby = $request->user_id;
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


                $response = [
                    'api_success' => 'True',
                    'api_message' => 'Ticket Updated Successfully',
                    'ticket_update' => $ticket_update,
                ];
                return response($response, 201);
            }
            else{
                $response = [
                    'api_success' => 'False',
                    'api_message' => 'Something Went Wrong!',
                ];
                return response($response, 201);
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
            if($ticket_add){
                
            for($i = 0; $i < count($files); $i++){
                $attachments = $files[$i];
                $attachment_add = array('ticket_id' => $ticket_add, 'attachment' => $attachments, 'created_by' => $createdby, 'created_at' => Now());

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
            $response = [
                'api_success' => 'True',
                'api_message' => 'Ticket Created Successfully',
                'ticket_add' => $ticket_add,
            ];
            return response($response, 201);
            }
            else{
                $response = [
                    'api_success' => 'False',
                    'api_message' => 'Something Went Wrong!',
                ];
                return response($response, 201);
            }
        }
    }

    public function ticket_status_update(Request $request){
        $createdby = Auth::id();
        $timeline_lists = DB::table('tickets_timeline')->where('ticket_id',$request->ticket_id)->first();
        $timeline_status_lists = DB::table('ticket_status')->where('ticket_status_id',$timeline_lists->status_id)->first();
        $ticket_timeline_data = array('ticket_id' => $request->ticket_id, 'client_id' => $request->client_id, 'customer_contact_id' => $request->customer_contact_id, 'description' => $request->description, 'status_id' => $request->status_id, 'operation_comments' => $request->description, 'created_by' => $createdby, 'created_at' => Now(), 'created_date' => now());
        $ticket_timeline_add = DB::table('tickets_timeline')->insertGetId($ticket_timeline_data);
        if($ticket_timeline_add){
            $data = array('status_id' => $request->status_id, 'updated_by' => $createdby, 'updated_at' => Now());
            $tickets_update = DB::table('tickets')->where('ticket_id', $request->ticket_id)->update($data);
            $response = [
                'api_success' => 'True',
                'api_message' => 'Ticket Status Updated Successfully!',
                'ticket_update' => $tickets_update,
            ];
            return response($response,201);
        }
        else{
            $response = [
                'api_success' => 'True',
                'api_message' => 'Something Went Wrong!',
            ];
            return response($response,201);
        }
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
    }

    public function ticket_view(Request $request){
        if(isset($request->ticket_id)){

            $tickets_list = DB::table('tickets as a')->where('a.ticket_id',$request->ticket_id)->select(['a.ticket_id',
                'b.client_name as client_id',
                'c.first_name as customer_contact_id',
                'a.subject',
                'a.description',
                'd.ticket_type_name as ticket_type_id',
                'e.priority_name as priority_id',
                'f.ticket_source_name as source_id',
                'g.ticket_status_name as status_id',
                'h.first_name as assign_to',
                'i.ticket_created_type_name as ticket_created_type_id',
                'j.first_name as created_by',
                'a.created_at',
                'k.first_name as updated_by',
                'a.updated_at',
            ])->leftjoin('clients as b', 'b.client_id', '=', 'a.client_id')->leftjoin('customer_contacts as c', 'c.customer_contact_id', '=', 'a.customer_contact_id')->leftjoin('ticket_types as d', 'd.ticket_type_id', '=', 'a.ticket_type_id')->leftjoin('ticket_priority as e', 'e.priority_id', '=', 'a.priority_id')->leftjoin('ticket_sources as f', 'f.ticket_source_id', '=', 'a.source_id')->leftjoin('ticket_status as g', 'g.ticket_status_id', '=', 'a.status_id')->leftjoin('users as h', 'h.id', '=', 'a.assign_to')->leftjoin('ticket_created_type as i', 'i.ticket_created_type_id', '=', 'a.ticket_created_type_id')->leftjoin('users as j', 'j.id', '=', 'a.created_by')->leftjoin('users as k', 'k.id', '=', 'a.updated_by')->first();

            $ticket_attachments = DB::table('ticket_attachments')->where('ticket_id',$request->ticket_id)->select('attachment')->get();

            $ticket_status_details = DB::table('ticket_status')->where('deleted','No')->select('ticket_status_id','ticket_status_name')->get();

            $response = [
                'api_success' => 'true',
                'ticket_id' => $tickets_list->ticket_id,
                'client_id' => $tickets_list->client_id,
                'customer_contact_id' => $tickets_list->customer_contact_id,
                'subject' => $tickets_list->subject,
                'description' => $tickets_list->description,
                'ticket_type_id' => $tickets_list->ticket_type_id,
                'priority_id' => $tickets_list->priority_id,
                'source_id' => $tickets_list->source_id,
                'status_id' => $tickets_list->status_id,
                'assign_to' => $tickets_list->assign_to,
                'ticket_created_type_id' => $tickets_list->ticket_created_type_id,
                'created_by' => $tickets_list->created_by,
                'created_at' => $tickets_list->created_at,
                'updated_by' => $tickets_list->updated_by,
                'updated_at' => $tickets_list->updated_at,
                'ticket_attachments' => $ticket_attachments,
                'ticket_status_details' => $ticket_status_details,
            ];
        }
        else{
            $response = [
                'api_success' => 'true',
                'tickets_list' => $tickets_list,
            ];
        }

        return response($response,201);

    }

    // public function timeline_add(Request $request){
    //     $user_id = $request->id;
    //     $lead_id = $request->lead_id;
    //     if($user_id!="" AND $lead_id){
    //         $files=[];
    //         if($request->Image!=""){
    //              foreach($request->file('Image') as $file)
    //             {
    //                 $name = time() . rand(1, 100) . '.' . $file->extension();
    //                 $file->move(public_path('Image'),$name);
    //                 $files[] = $name;
    //             }

    //         $attachments_arr=array();
    //         for ($i = 0; $i < count($files); $i++) {
    //                 $attachments = $files[$i];
    //                 $attachments_arr[] = array('attachment' => $attachments,);
                   
    //             }

    //         $attachment=json_encode($attachments_arr);
    //         }
    //         else{
    //             $attachment="";

    //         }
    //         $data=array(
    //             'timeline_for_id' => $lead_id,
    //             'timeline_for' => 'lead',
    //             'communication_medium_id' => $request->communication_mediumid,
    //             'communication_medium_type_id' => $request->communication_type_id,
    //             'lead_stage_id' => $request->lead_stage_id,
    //             'lead_sub_stage_id' => $request->lead_sub_stage_id,
    //             'description' => $request->description,
    //             'attachments' => $attachment,
    //             'created_at' => Now(),
    //             'created_by' => $user_id,
    //         );
    //         $timeline_insert=DB::table('timelines')->insert($data);
    //         $leads_update=array('lead_stage_id'=>$request->lead_stage_id,'lead_sub_stage_id'=>$request->lead_sub_stage_id,'communication_medium_id'=>$request->communication_mediumid,'communication_medium_type_id'=>$request->communication_type_id,'updated_by' => $user_id,'updated_at' => Now());

    //         if($timeline_insert){
    //             $lead=DB::table('leads')->where('lead_id',$request->lead_id)->update($leads_update);
    //         }
    //         $response = [
    //             'api_success' => 'true',
    //             'lead_id' => $request->lead_id,
    //             'communication_medium_id' => $request->communication_mediumid,
    //             'communication_medium_type_id' => $request->communication_type_id,
    //             'lead_stage_id' => $request->lead_stage_id,
    //             'lead_sub_stage_id' => $request->lead_sub_stage_id,
    //             'description' => $request->description,
    //             'attachments' => $request->attachments,
    //             'created_at' => Now(),
    //             'created_by' => $user_id,
    //         ];
    //     }
    //     else{
    //         $response = [
    //             'api_success' => 'false',
    //             'tickets_list' => 'Something Went Wrong!',
    //         ];
    //     }

    //     return response($response,201);
        
    // }

    public function quotations_list(Request $request){

        $user_id = $request->user_id;

        $quotations_list = DB::table('quotations')->where('deleted', 'No')->where('created_by', $user_id)->get();
        foreach($quotations_list as $quotation_list){
            $quotation_arr[] = $quotation_list;   
        }
        if($quotations_list==True){
            $response = [
                'Api_success' => 'true',
                'Api_message' => 'Success',
                'quotations' => $quotation_arr,
            ];
        }
        else{
            $response = [
                'Api_success' => 'false',
                'Api_message' => 'Failed',
            ];
        }
        return response($response, 201);
    }

    public function audio_store(StoreAudioRequest $request)
    {
        $audio = Audio::create([
            'disk'          => 'audios_disk',
            'original_name' => $request->audio->getClientOriginalName(),
            'path'          => $request->audio->store('audios', 'audios_disk'),
            'title'         => $request->title,
        ]);

        $this->dispatch(new ConvertAudioForDownloading($audio));
        $this->dispatch(new ConvertAudioForStreaming($audio));

        return response()->json([
            'id' => $audio->id,
        ], 201);
    }

    public function website_lead_insert(Request $request)
    {
        if($request->Phone)
        {
            $data =['lead_name'=>$request->Name,'mobile_number'=>$request->Phone,'email_id'=>$request->Email,'lead_stage_id'=>1,'lead_sub_stage_id'=>1, 'source_id'=>1,'sub_source_id'=>1,'lead_owner'=>1,'created_at'=>now()];
            $insert=DB::table('leads')->insert($data);

            $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://app-server.wati.io/api/v1/sendTemplateMessage?whatsappNumber='."91".$request->Phone.'',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "template_name": "woocommerce_default_order_fulfilled_v1",
    "broadcast_name": "order_update",
    "parameters": [
        {
            "name": "name",
            "value": "'.$request->Name.'"
        },
        {
            "name": "shop_name",
            "value": "VGT Online"
        }
    ]
}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI1MTg5NWUwNy0zNWQwLTQwZjQtYmYyMi1iMzI1ZWJhMWQ1OTIiLCJ1bmlxdWVfbmFtZSI6ImRlaXZhdmluZ3JlZW5AZ21haWwuY29tIiwibmFtZWlkIjoiZGVpdmF2aW5ncmVlbkBnbWFpbC5jb20iLCJlbWFpbCI6ImRlaXZhdmluZ3JlZW5AZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDMvMjQvMjAyMyAxNDo1NzowNyIsImRiX25hbWUiOiJ3YXRpX2FwcF90cmlhbCIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6IlRSSUFMIiwiZXhwIjoxNjgwMzA3MjAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.JyLl9hKznXb5nQ9b0CqE8aL4fGvXmgdnYv58OrLdGNc',
    'Content-Type: application/json',
    'Cookie: affinity=1679672318.027.39611.73260|aab056cd4cc9b597f01aa146c61e0719'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
            if($insert)
            {
                 $response = [
                'Api_success' => 'True',
                'Api_message' => 'Insert Successfully!'
                ];
            }
            else
            {
                 $response = [
                'Api_success' => 'False',
                'Api_message' => 'Insert Failed!'
                ];
            }
        }
        else
        {
           $response = [
                'Api_success' => 'False',
                'Api_message' => 'MobileNumber is Empty!'
                ];
        }
        return response($response, 201);
    }


    public function facebook_leads(Request $request)
    {
        if($request->mobile_number!="")
        {
            $data =['lead_name'=>$request->name,'mobile_number'=>$request->mobile_number,'email_id'=>$request->email_id,'lead_stage_id'=>1,'lead_sub_stage_id'=>1, 'source_id'=>2,'sub_source_id'=>2,'lead_owner'=>1,'created_at'=>now()];
            $insert=DB::table('leads')->insert($data);


            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://app-server.wati.io/api/v1/sendTemplateMessage?whatsappNumber='."91".$request->mobile_number.'',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
                "template_name": "woocommerce_default_order_fulfilled_v1",
                "broadcast_name": "order_update",
                "parameters": [
                    {
                        "name": "name",
                        "value": "'.$request->name.'"
                    },
                    {
                        "name": "shop_name",
                        "value": "VGT Online"
                    }
                ]
            }',
              CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI1MTg5NWUwNy0zNWQwLTQwZjQtYmYyMi1iMzI1ZWJhMWQ1OTIiLCJ1bmlxdWVfbmFtZSI6ImRlaXZhdmluZ3JlZW5AZ21haWwuY29tIiwibmFtZWlkIjoiZGVpdmF2aW5ncmVlbkBnbWFpbC5jb20iLCJlbWFpbCI6ImRlaXZhdmluZ3JlZW5AZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDMvMjQvMjAyMyAxNDo1NzowNyIsImRiX25hbWUiOiJ3YXRpX2FwcF90cmlhbCIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6IlRSSUFMIiwiZXhwIjoxNjgwMzA3MjAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.JyLl9hKznXb5nQ9b0CqE8aL4fGvXmgdnYv58OrLdGNc',
                'Content-Type: application/json',
                'Cookie: affinity=1679672318.027.39611.73260|aab056cd4cc9b597f01aa146c61e0719'
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            echo $response;

            if($insert)
            {
                 $response = [
                'Api_success' => 'True',
                'Api_message' => 'Insert Successfully!'
                ];
            }
            else
            {
                 $response = [
                'Api_success' => 'False',
                'Api_message' => 'Insert Failed!'
                ];
            }
        }
        else
        {
            $response = [
                'Api_success' => 'False',
                'Api_message' => 'Apikey is Not valid!'
            ];
        }
        return response($response, 201);
    }



    public function fb_tool_leads (Request $request){

         if($request->mobile_number!="")
        {
            
            $data =['lead_name'=>$request->name,'mobile_number'=>$request->mobile_number,'email_id'=>$request->email_id,'lead_stage_id'=>1,'lead_sub_stage_id'=>1, 'source_id'=>2,'sub_source_id'=>2,'lead_owner'=>1,'created_at'=>now()];
            $insert=DB::table('leads')->insert($data);


    
            if($insert)
            {
                 $response = [
                'Api_success' => 'True',
                'Api_message' => 'Insert Successfully!'
                ];
            }
            else
            {
                 $response = [
                'Api_success' => 'False',
                'Api_message' => 'Insert Failed!'
                ];
            }
        }
        else
        {
            $response = [
                'Api_success' => 'False',
                'Api_message' => 'Apikey is Not valid!'
            ];
        }
        return response($response, 201);

}
}
