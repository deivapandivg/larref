<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class TimeLinesController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function timelines(){
        $department_lists=DB::table('departments')->where('deleted', 'No')->get();
        $communication_medium_lists=DB::table('communication_mediums')->where('deleted', 'No')->get();
        $communication_type_lists=DB::table('communication_types')->where('deleted', 'No')->get();
        // $user_lists=DB::table('users')->where('deleted', 'No')->get();
        $auth_id = Auth::id();
        $get_auth_user = DB::table('users')->where('deleted', 'No')->where('id',$auth_id)->first();

        if($get_auth_user->designation_id==1){

            $user_lists = DB::table('users')->where('deleted', 'No')->get();

        }
        else{

            
            $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $auth_id)->where('deleted', 'No')->select('id', 'first_name');

            $user_lists = DB::table('users')->where('id', $auth_id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();
            
        }

        return view('timelines.timelines',compact('department_lists','user_lists','communication_medium_lists','communication_type_lists'));
    }
    public function timeline_submit(Request $request){
        // dd($request->all());
        $user_id = Auth::id();
        $files=[];
        if($request->Image!=""){
             foreach($request->file('Image') as $file)
            {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move(public_path('Image'),$name);
                $files[] = $name;
            }

        $attachments_arr=array();
        for ($i = 0; $i < count($files); $i++) {
                $attachments = $files[$i];
                $attachments_arr[] = array('attachment' => $attachments,);
               
            }

        $attachment=json_encode($attachments_arr);
        }
        else{
            $attachment="";

        }
        $data=array(
            'timeline_for_id' => $request->lead_id,
            'communication_medium_id' => $request->communication_mediumid,
            'communication_medium_type_id' => $request->communication_type_id,
            'lead_stage_id' => $request->lead_stages_id,
            'lead_sub_stage_id' => $request->lead_sub_stages_id,
            'description' => $request->lead_sub_stages_id,
            'attachments' => $attachment,
            'created_at' => Now(),
            'created_by' => $user_id,
        );
        $timeline_insert=DB::table('timelines')->insert($data);
        $radio_button=$request->radio_button;
        if($radio_button=='Enable'){
            $reminder_insert=array('reminder_for_id' => $request->lead_id,'communication_medium_id' => $request->communication_medium,'communication_type'=> $request->communication_type,'task_at'=>$request->contactdate,'comments'=>$request->comments1,'created_at' => Now(),
            'created_by' => $user_id,'reminder_for'=>'1','assigned_to'=>$user_id);
        $reminder=DB::table('reminders')->insert($reminder_insert);
        }
        $leads_update=array('lead_stage_id'=>$request->lead_stages_id,'lead_sub_stage_id'=>$request->lead_sub_stages_id,'communication_medium_id'=>$request->communication_mediumid,'communication_medium_type_id'=>$request->communication_type_id);

        if($timeline_insert){
        $lead=DB::table('leads')->where('lead_id',$request->lead_id)->update($leads_update);
        }


        return redirect('leads');
    }

    public function timeline_list(Request $request){

        if($request->ajax()){
            $data= DB::table('timelines as a')->where('a.deleted', 'No')->select([ 
                'a.timeline_id',
                'c.lead_name',
                'c.mobile_number',
                'd.communication_medium',
                'e.communication_type',
                'f.lead_stage_name',
                'g.lead_sub_stage',
                'a.description',
                'b.first_name',
                'a.created_at',
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('leads as c','c.lead_id', '=', 'a.timeline_for_id')->leftjoin('communication_mediums as d','d.communication_medium_id', '=', 'a.communication_medium_id')->leftjoin('communication_types as e','e.communication_type_id', '=', 'a.communication_medium_type_id')->leftjoin('lead_stages as f', 'f.lead_stage_id', '=', 'a.lead_stage_id')->leftjoin('lead_sub_stage as g', 'g.lead_sub_stage_id', '=', 'a.lead_sub_stage_id');
             if($request->all_date!="yes")
            {
                $data->whereBetween('a.created_at', [$request->from_date." 00:00:00", $request->to_date." 23:59:59"]);
            }
            if($request->communication_medium_id!="All") 
            {
                $data->where('a.communication_medium_id',("$request->communication_medium_id") );
            }
             if($request->communication_type_id!="All") 
            {
                $data->where('a.communication_medium_type_id',("$request->communication_type_id ") );
            }
            if($request->user_id!="All")
            {
                $data->where('a.created_by',("$request->user_id"));
            }
            else{
                $auth_id = Auth::id();
                if($auth_id==1){
                    $get_all_users= DB::table('users')->select('id')->where('deleted','No')->get();
                    foreach($get_all_users as $get_all_user)
                    {
                        $UserArray[] = $get_all_user->id;
                    }
                    $Users = implode(', ', $UserArray);
                    $data->where('a.created_by',$Users);
                }
                else{
                    $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $auth_id)->where('deleted', 'No')->select('id', 'first_name');

                    $users_lists = DB::table('users')->where('id', $auth_id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();
                    foreach($users_lists as $users_list)
                    {
                        $UserArray[] = $users_list->id;
                    }
                    $Users = implode(', ', $UserArray);
                    $data->where('a.created_by',$Users);

                }
            }
                
             return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
        }
        return view('timelines.timelines');  

    }
}
