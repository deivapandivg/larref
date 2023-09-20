<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;
use DB;
use Redirect, Response, Session;
use DataTables;

class ReportsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function employees_report(){
		$user_lists = DB::table('users')->where('deleted', 'No')->get();
		return view('reports.employees_report',compact('user_lists'));
	}

	public function report_chart_counts(Request $request)
	{
		$user_id = Auth::user()->id;
		$user = $request->user;
		$date_wise_count = [];
		$date_wise_count_arr = [];
		$report_dates_arr = [];
		$timeline_counts_arr = [];
		$lead_conversion_counts_arr = [];

		
		if($request->period_id==1){

			$nth_day = $request->nth_day;
			$today = date("Y-m-d");
			$last_nth_day = date("Y-m-d", strtotime('-' . ($nth_day - 1) . ' days'));

			$timeline_counts_result = DB::select("SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as report_date,count('timeline_id') as timeline_count FROM timelines WHERE created_at<='" . $today . "' AND created_at>='" . $last_nth_day . "' AND created_by=" . $user . " GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')");

			$lead_conversion_counts_result = DB::select("SELECT DATE_FORMAT(updated_at, '%Y-%m-%d') as report_date,count('lead_id') as lead_conversion_count FROM leads WHERE updated_at<='" . $today . "' AND updated_at>='" . $last_nth_day . "' AND updated_by=" . $user . " AND lead_stage_id = 4 GROUP BY DATE_FORMAT(updated_at, '%Y-%m-%d')");

			for ($i = 0; $i < $nth_day; $i++) {

				$report_date = date("Y-m-d", strtotime('-' . $i . ' days'));
				$timeline_counts = collect($timeline_counts_result)->where('report_date', $report_date)->first();
				$timeline_counts_final = $timeline_counts == null ? 0 : $timeline_counts->timeline_count;
				$lead_conversion_counts = collect($lead_conversion_counts_result)->where('report_date', $report_date)->first();
				$lead_conversion_counts_final = $lead_conversion_counts == null ? 0 : $lead_conversion_counts->lead_conversion_count;
				array_push($report_dates_arr, $report_date);
				array_push($timeline_counts_arr, $timeline_counts_final);
				array_push($lead_conversion_counts_arr, $lead_conversion_counts_final);

			}
			$response_arr = [];
			$response_arr['date_lables'] = $report_dates_arr;
			$response_arr['timeline_counts'] = $timeline_counts_arr;
			$response_arr['lead_conversion_counts'] = $lead_conversion_counts_arr;
			return $response_arr;
		}
		elseif($request->period_id==2){
			$today = date("Y-m-d");

			$nth_day = $request->nth_day;
			$per_week = 7;
			$last_nth_day_value = $nth_day * $per_week;
			$last_nth_day = date("Y-m-d", strtotime('-' . ($last_nth_day_value - 1) . ' days'));

			$timeline_counts_result = DB::select("SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as report_date,count('timeline_id') as timeline_count FROM timelines WHERE created_at<='" . $today . "' AND created_at>='" . $last_nth_day . "' AND created_by=" . $user . " GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')");

			$lead_conversion_counts_result = DB::select("SELECT DATE_FORMAT(updated_at, '%Y-%m-%d') as report_date,count('lead_id') as lead_conversion_count FROM leads WHERE updated_at<='" . $today . "' AND updated_at>='" . $last_nth_day . "' AND updated_by=" . $user . " AND lead_stage_id = 4 GROUP BY DATE_FORMAT(updated_at, '%Y-%m-%d')");
			for ($i = 0; $i < $last_nth_day_value; $i++) {

				$report_date = date("Y-m-d", strtotime('-' . $i . ' days'));
				$timeline_counts = collect($timeline_counts_result)->where('report_date', $report_date)->first();
				$timeline_counts_final = $timeline_counts == null ? 0 : $timeline_counts->timeline_count;
				$lead_conversion_counts = collect($lead_conversion_counts_result)->where('report_date', $report_date)->first();
				$lead_conversion_counts_final = $lead_conversion_counts == null ? 0 : $lead_conversion_counts->lead_conversion_count;
				array_push($report_dates_arr, $report_date);
				array_push($timeline_counts_arr, $timeline_counts_final);
				array_push($lead_conversion_counts_arr, $lead_conversion_counts_final);

			}
			$response_arr = [];
			$response_arr['date_lables'] = $report_dates_arr;
			$response_arr['timeline_counts'] = $timeline_counts_arr;
			$response_arr['lead_conversion_counts'] = $lead_conversion_counts_arr;
			return $response_arr;
		}
		elseif($request->period_id==3){
			$today = date("Y-m-d");

			$nth_day = $request->nth_day;
			$per_month = 30;
			$last_nth_day_value = $nth_day * $per_month;
			$last_nth_day = date("Y-m-d", strtotime('-' . ($last_nth_day_value - 1) . ' days'));

			$timeline_counts_result = DB::select("SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as report_date,count('timeline_id') as timeline_count FROM timelines WHERE created_at<='" . $today . "' AND created_at>='" . $last_nth_day . "' AND created_by=" . $user . " GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')");

			$lead_conversion_counts_result = DB::select("SELECT DATE_FORMAT(updated_at, '%Y-%m-%d') as report_date,count('lead_id') as lead_conversion_count FROM leads WHERE updated_at<='" . $today . "' AND updated_at>='" . $last_nth_day . "' AND updated_by=" . $user . " AND lead_stage_id = 4 GROUP BY DATE_FORMAT(updated_at, '%Y-%m-%d')");
			for ($i = 0; $i < $last_nth_day_value; $i++) {

				$report_date = date("Y-m-d", strtotime('-' . $i . ' days'));
				$timeline_counts = collect($timeline_counts_result)->where('report_date', $report_date)->first();
				$timeline_counts_final = $timeline_counts == null ? 0 : $timeline_counts->timeline_count;
				$lead_conversion_counts = collect($lead_conversion_counts_result)->where('report_date', $report_date)->first();
				$lead_conversion_counts_final = $lead_conversion_counts == null ? 0 : $lead_conversion_counts->lead_conversion_count;
	            // $date_wise_count[$report_date]=$report_count;
				array_push($report_dates_arr, $report_date);
				array_push($timeline_counts_arr, $timeline_counts_final);
				array_push($lead_conversion_counts_arr, $lead_conversion_counts_final);

			}
			$response_arr = [];
			$response_arr['date_lables'] = $report_dates_arr;
			$response_arr['timeline_counts'] = $timeline_counts_arr;
			$response_arr['lead_conversion_counts'] = $lead_conversion_counts_arr;
			return $response_arr;
		}

	}

	public function daily_report_send_mail(Request $request){

	$today_date = date('Y-m-d');

    
    $SendMailController=new SendMailController();

    $data = array('to_mail_id' =>'selvakcena786@gmail.com',
            'subject' => 'subject'
            );

    $send_mail=$SendMailController->send_mail("report",$data);


    }

    public function quotations_pdf(Request $request) {
        
        $today_date = date('Y-m-d');
        $path = public_path('accsource/mainlogo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $pic = 'data:image/' .$type . ';base64,' . base64_encode($data);
        $pdf = PDF::loadView('reports.reports_download',compact('pic','today_date'))->setOptions(['defaultFont' => 'sans-serif','isHtml5ParserEnabled' => true,'isRemoteEnabled' => true]);
        
        
        return $pdf->stream('daily_employee_report.pdf');
    }


}
