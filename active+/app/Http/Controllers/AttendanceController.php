<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;
use DB;
use Redirect, Response, Session;
use DataTables;

class AttendanceController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }

    public function attendance_log(Request $request){
        if ($request->ajax()) {
            $data = DB::table('attendance')->where('attendance.deleted', 'No')->select(['attendance.attendance_id','users.first_name','attendance.type','attendance.work_hours','attendance.earlier_checkin','attendance.earlier_checkout','attendance.over_time','attendance.attendance_at','attendance.ip_address','attendance.country_name','attendance.region_name','attendance.city_name'])->join('users', 'users.id', '=', 'attendance.created_by');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn = '<div class="text-center"><a href="'.route('attendance_view',base64_encode($row->attendance_id)).'" class="vg-btn-ssp-primary ViewDataModel" data-toggle="tooltip" data-placement="right" title="view" data-original-title="view"><i class="fa fa-eye text-white text-center"></i></a></div>';
                return $btn;
            })
           ->rawColumns(['action'])
           ->make(true);
        }
        return view('attendance_log.attendance_log');
    }

    public function dashboard(Request $request){
        $user_id = Auth::id();
        $today_date=date('Y-m-d');
        $createdby = Auth::id();

        $attendance_details = DB::table('attendance')->select('*')->where('user_id', $user_id)->latest()->first();
        $attendance_details_count = DB::table('attendance')->select('*')->where('user_id', $user_id)->Count();
        $teams_count = DB::table('teams')->select('team_id')->where('deleted', 'No')->count();
        $employees_count = DB::table('users')->select('id')->where('deleted', 'No')->count();
        $timelines_count = DB::table('timelines')->select('timeline_id')->where('deleted', 'No')->count();
        $branches_count = DB::table('office_locations')->select('office_location_id')->where('deleted', 'No')->count();
        $tasks_count = DB::table('tasks')->select('task_id')->where('deleted', 'No')->count();
        $departments_count = DB::table('departments')->select('department_id')->where('deleted', 'No')->count();
        $leads_count = DB::table('leads')->select('lead_id')->where('lead_stage_id', 1)->where('deleted', 'No')->count();
        if(Auth::user()->id == 1){
            $pending_tasks_count = DB::table('tasks')->select('task_id')->where('task_status', 4)->where('deleted', 'No')->count();
            $latest_tickets_count = DB::table('tickets')->select('ticket_id')->where('status_id', 2)->where('deleted', 'No')->count();
            
        }
        else
        {
            $pending_tasks_count = DB::table('tasks')->select('task_id')->where('task_status', 4)->where('deleted', 'No')->where('assign_to', $user_id)->count();
            $latest_tickets_count = DB::table('tickets')->select('ticket_id')->where('status_id', 2)->where('deleted', 'No')->where('assign_to', $user_id)->count();
        }
        $campaigns_count = DB::table('campaigns')->select('campaign_id')->where('deleted', 'No')->count();
        $clients_count = DB::table('clients')->select('client_id')->where('deleted', 'No')->count();
        $designations_count = DB::table('designations')->select('designation_id')->where('deleted', 'No')->count();
        return view('dashboard',compact('attendance_details','attendance_details_count','teams_count','employees_count','branches_count','departments_count','leads_count','campaigns_count','clients_count','designations_count','timelines_count','tasks_count','pending_tasks_count','latest_tickets_count'));
    }

    public function attendance(Request $request){

        $createdby = Auth::id();
        $user_details = DB::table('users')->where('id',$createdby)->first();
        $attendance_details = DB::table('attendance')->whereDay('created_at', '=', date('d'))->where('user_id',$createdby)->first();
        // dd($attendance_details);
        if($attendance_details!=''){
        $CheckinTime=$attendance_details->created_at;
        }
        $description = $request->input('description');
        $type = $request->input('type');
        $status = $request->input('status');
        $ip = '27.5.52.131';
        // $ip = request()->ip();   
        $currentUserInfo = Location::get($ip);
        $ip_address = $currentUserInfo->ip;
        $countryName = $currentUserInfo->countryName;
        $regionName = $currentUserInfo->regionName;
        $cityName = $currentUserInfo->cityName;
        $zipCode = $currentUserInfo->zipCode;
        $latitude = $currentUserInfo->latitude;
        $longitude = $currentUserInfo->longitude;
        $timezone = $currentUserInfo->timezone;
        $date = date('Y.m.d');
        $attendance_at = now();
        if($attachment_file=$request->file('attachment')){
            $destination_path = "public/attendance_upload/";
            $attachment_name = date('YmdHis'). '.' .$attachment_file->getClientOriginalExtension();
            $attachment_file->move($destination_path, $attachment_name);
        }
        else{
            $attachment_name = '';
        }

        
        if($attendance_details!=''){
            if($user_details->shift_id==1){
                $defaultcheckintime = date('Y-m-d 06:00:00');
                $defaultcheckouttime = date('Y-m-d 14:00:00');
            }
            elseif($user_details->shift_id==2){
                $defaultcheckintime = date('Y-m-d 10:00:00');
                $defaultcheckouttime = date('Y-m-d 18:00:00');
            }
            elseif($user_details->shift_id==3){
                $defaultcheckintime = date('Y-m-d 14:00:00');
                $defaultcheckouttime = date('Y-m-d 22:00:00');
            }
            elseif($user_details->shift_id==4){
                $defaultcheckintime = date('Y-m-d 22:00:00');
                $defaultcheckouttime = date('Y-m-d 06:00:00');
            }
            if($CheckinTime!=''){   
            // $defaulttime = date('Y-m-d H:i:s');
            $checkindateDiff = intval((strtotime($defaultcheckintime)-strtotime($CheckinTime))/60);
            $checkinhours = intval($checkindateDiff/60);
            $checkinminutes = $checkindateDiff%60;
            $earliercheckin=$checkinhours."H:"."$checkinminutes"."M";
            if($defaultcheckouttime>$attendance_at){
                $checkoutdateDiff = intval((strtotime($attendance_at)-strtotime($defaultcheckouttime))/60);
                $checkouthours = intval($checkoutdateDiff/60);
                $checkoutminutes = $checkoutdateDiff%60;
                $earliercheckout=$checkouthours."H:"."$checkoutminutes"."M";
            }
            else{
                $earliercheckout='';
            }

            $time = date('Y-m-d H:i:s');
            // dd($time);
            // dd($CheckinTime);
            $dateDiff = intval((strtotime($time)-strtotime($CheckinTime))/60);
            $hours = intval($dateDiff/60);
            $minutes = $dateDiff%60;
            $how=$hours."H:"."$minutes"."M";
            $work_hours=$hours.":"."$minutes"."";
            // dd($how);
            $eight_hours = date('08:00:00');
            if($work_hours > 08.00){
                $Over_time_diff = intval((strtotime($work_hours)-strtotime($eight_hours))/60);
                $othours = intval($Over_time_diff/60);
                $otminutes = $Over_time_diff%60;
                $Over_time = $othours."H:"."$otminutes"."M";
            }
            else
            {
                $Over_time = "";
            }
            $data1 = array('description' => $description, 'attachments' => $attachment_name, 'user_id' => $createdby, 'attendance_at' => $attendance_at, 'type' => $type, 'work_hours' => $how, 'created_by' => $createdby, 'created_at' => NOW(), 'earlier_checkin' => $earliercheckin, 'earlier_checkout' => $earliercheckout, 'over_time' => $Over_time, 'ip_address' => $ip_address, 'country_name' => $countryName, 'region_name' => $regionName, 'city_name' => $cityName, 'zip_code' => $zipCode, 'latitude' => $latitude, 'longitude' => $longitude, 'time_zone' => $timezone);
            // dd($data1);
            }
        }
        
        else
        {
            $data1 = array('description' => $description, 'attachments' => $attachment_name, 'user_id' => $createdby, 'attendance_at' => $attendance_at, 'type' => $type, 'created_by' => $createdby, 'created_at' => NOW(), 'ip_address' => $ip_address, 'country_name' => $countryName, 'region_name' => $regionName, 'city_name' => $cityName, 'zip_code' => $zipCode, 'latitude' => $latitude, 'longitude' => $longitude, 'time_zone' => $timezone);
            // dd($data1);
        }

        $checkin_add = DB::table('attendance')->insert($data1);
        if($type=='checkin'){
            $value = ['type' => 'success', 'message' => 'Checkin Done!', 'title' => 'Success!'];
        }
        elseif($type=='checkout'){
            $value = ['type' => 'success', 'message' => 'Checkout Done!', 'title' => 'Success!'];
        }
        else{
            $value = ['type' => 'error', 'message' => 'Something Went Wrong!', 'title' => 'Error!'];
        }
        return redirect('dashboard')->with($value);
    }

    public function attendance_view(Request $request){
        $attendance_id=base64_decode($request->attendance_id);
        $attendance_details=DB::table('attendance')->where('attendance_id', $attendance_id)->get();
        $user_details=DB::table('users')->where('deleted','No')->select('first_name','id')->get();
        return view('attendance_log.attendance_view',compact('attendance_details','user_details'));
    }

    public function search_model(Request $request)
    {

        $selected_result = $request->selected_result;
        // dd($selected_result);

        $lead_details = DB::table('leads')->where('lead_id', '=',$selected_result)->orwhere('email_id', '=', $selected_result)->orwhere('mobile_number', '=', $selected_result)->select('lead_id', 'lead_name','mobile_number')->get();


        $model = '<div class="modal-body modal-body-search">
        ';
        
        if (count($lead_details)>0) {
            $model .= '<div class="row">
            <div class="col-lg-12">
            <div class="form-group">
            <h4><strong><p class="search-lead-heading">Lead Details <p></strong><h4>
            </div>
            </div>
            </div>';
            foreach ($lead_details as $lead_detail) {
                $model .= '<div class="row" align="left">
                <div class="col-lg-3">
                <div class="form-group">
                <strong><p>ID : ' . $lead_detail->lead_id . '</p></strong>
                </div>
                </div>
                <div class="col-lg-3">
                <div class="form-group">
                <strong><p>' . $lead_detail->lead_name . '</p></strong>
                </div>
                </div>
                <div class="col-lg-3">
                <div class="form-group">
                <strong><p>' . $lead_detail->mobile_number . '</p></strong>
                </div>
                </div>
                <div class="col-lg-3" align="right">
                 <a href="' . route('lead_view', base64_encode($lead_detail->lead_id)) . '" target="_blank">
                   <button type="button" class="btn text-center btn-sm text-white search-lead-view-button">
                      <i class="fa fa-eye"> <strong>View</strong></i> 
                   </button>
                 </a>
                </div>
                </div>
                <hr>';
            }
        }

        else
        {
            $model.= '<div class="row">
            <div class="col-lg-12">
            <div class="form-group">
            <h5><p class="text-center text-danger"><strong>Records Not Found !</strong></p></h5>
            </div>
            </div>
            <div class="col-lg-12">
            <div class="form-group">
            <h6><p class="text-center text-primary"><strong>Please enter valid ID, Mobile Number or Email Id to get best search results...</strong></p></h6>
            </div>
            </div>
            </div>
            ';
        }

        
        echo $model;
    }

    public function dashboard_chart_counts(Request $request)
    {
        $today = date("Y-m-d");
        $user_id = Auth::user()->id;
        $nth_day = 7;
        $last_nth_day = date("Y-m-d", strtotime('-' . ($nth_day - 1) . ' days'));
        $date_wise_count = [];
        $date_wise_count_arr = [];
        $report_dates_arr = [];
        $lead_conversion_counts_arr = [];
        $timeline_counts_arr = [];
        
        if($user_id==1){
            $timeline_counts_result = DB::select("SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as report_date,count('timeline_id') as timeline_count FROM timelines WHERE created_at<='" . $today . "' AND created_at>='" . $last_nth_day . "' GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')");

            $lead_conversion_counts_result = DB::select("SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as report_date,count('lead_conversion_id') as lead_conversion_count FROM leads WHERE created_at<='" . $today . "' AND created_at>='" . $last_nth_day . "' AND lead_stage_id=4 GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')");
        }
        else
        {
            $timeline_counts_result = DB::select("SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as report_date,count('timeline_id') as timeline_count FROM timelines WHERE created_at<='" . $today . "' AND created_at>='" . $last_nth_day . "' AND created_by=" . $user_id . " GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')");

            $lead_conversion_counts_result = DB::select("SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as report_date,count('lead_conversion_id') as lead_conversion_count FROM leads WHERE created_at<='" . $today . "' AND created_at>='" . $last_nth_day . "' AND lead_stage_id=4 AND lead_owner=" . $user_id . " GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')");
        }

        for ($i = 0; $i < $nth_day; $i++) {
            $report_date = date("Y-m-d", strtotime('-' . $i . ' days'));
            $lead_conversion_counts = collect($lead_conversion_counts_result)->where('report_date', $report_date)->first();
            $lead_conversion_counts_final = $lead_conversion_counts == null ? 0 : $lead_conversion_counts->lead_conversion_count;
            $timeline_counts = collect($timeline_counts_result)->where('report_date', $report_date)->first();
            $timeline_counts_final = $timeline_counts == null ? 0 : $timeline_counts->timeline_count;
            // $date_wise_count[$report_date]=$report_count;
            array_push($report_dates_arr, $report_date);
            array_push($lead_conversion_counts_arr, $lead_conversion_counts_final);
            array_push($timeline_counts_arr, $timeline_counts_final);
        }
        $response_arr = [];
        $response_arr['date_lables'] = $report_dates_arr;
        $response_arr['lead_conversion_counts'] = $lead_conversion_counts_arr;
        $response_arr['timeline_counts'] = $timeline_counts_arr;
        return $response_arr;
    }
}
