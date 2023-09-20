<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use Redirect, Response, Session;

class LeadsAjaxController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function leads_ajax(Request $request)
    {
        $LeadStatusId = $request->LeadStatusId;
        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $AllDate = $request->AllDate;
        $user_id = $request->user_id;
        $leadwhere = "";
        $where = "a.deleted='No'";
        $UserId = Session::get('user_id');
        Session::put('from_date', $FromDate);
        Session::put('to_date', $ToDate);
        Session::put('all_date', $AllDate);
        Session::put('lead_stage_id', $LeadStatusId);

        $options = DB::table('global_options')->first();

        if($AllDate!='All')
        {
            $where.="AND a.created_at>='".$FromDate." 00:00:00' AND a.created_at<='".$ToDate." 23:59:59'";
        }

        if($LeadStatusId=='All')
        {
            $get_all_lead_status= DB::table('lead_stages')->select('lead_stage_id')->where('deleted','No')->get();
            foreach($get_all_lead_status as $all_lead_status)
            {
                $storedArray[] = $all_lead_status->lead_stage_id;
            }
            $string = implode(', ', $storedArray);
            $leadwhere.="AND a.lead_stage_id IN ($string)";
        }
        else
        {
            $leadwhere.=" AND a.lead_stage_id = '$LeadStatusId'";
        }

        if($user_id=='All')
        {
            $auth_id = Auth::id();
            if($auth_id==1){
                $get_all_users= DB::table('users')->select('id')->where('deleted','No')->get();
                foreach($get_all_users as $get_all_user)
                {
                    $UserArray[] = $get_all_user->id;
                }
                $Users = implode(', ', $UserArray);
                $where.="AND a.lead_owner IN ($Users)";
            }
            else{
                $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $auth_id)->where('deleted', 'No')->select('id', 'first_name');

                $users_lists = DB::table('users')->where('id', $auth_id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();
                foreach($users_lists as $users_list)
                {
                    $UserArray[] = $users_list->id;
                }
                $Users = implode(', ', $UserArray);
                $where.="AND a.lead_owner IN ($Users)";
            }
        }
        else
        {
            $where.="AND a.lead_owner = '$user_id'";
        }

        // if($date_where!="")
        // {
        //     $where.=" AND ".$date_where;

        // }
        
        Session::put('LeadAjaxWhere',$where);
        Session::put('LeadstatusWhere',$leadwhere);

        // DB::enableQueryLog();
        $GetLeadStatusCounts= DB::table('leads as a')->select('a.lead_stage_id',DB::raw('count(a.lead_stage_id) as lead_status_count'))->leftjoin('lead_stages as b','b.lead_stage_id', '=', 'a.lead_stage_id')->leftjoin('lead_sources as c','c.lead_source_id', '=', 'a.source_id')->leftjoin('users as d','d.id', '=', 'a.lead_owner')->whereRaw($where)->groupBy('a.lead_stage_id')->get();
        // dd(DB::getQueryLog());
        
        
        $LeadStatusCountArray=array();

        foreach ($GetLeadStatusCounts as $GetLeadStatusCount) 
        {
            $LeadStatusCountArray+=array($GetLeadStatusCount->lead_stage_id=>$GetLeadStatusCount->lead_status_count);
        }

        // dd($LeadStatusCountArray);
        
        $data='';
        $data.='<ul id="LeadStatusTabs" class="nav nav-tabs nav-underline no-hover-bg" role="tablist">';
            $TotalLeadCount=0;
            $get_leads_stages= DB::table('lead_stages')->select('*')->where('deleted','No')->orderby('lead_stage_id')->get();
            foreach($get_leads_stages as $leads_stage)
            {

                if($LeadStatusId==$leads_stage->lead_stage_id){ $active_1='active'; }else{ $active_1=''; }

                if($leads_stage->lead_stage=='Positive'){ $color=$leads_stage->lead_stage_color; }
                    else{ $color=$leads_stage->lead_stage_color; }

                if(array_key_exists($leads_stage->lead_stage_id,$LeadStatusCountArray))
                {
                    $TempCount=$LeadStatusCountArray[$leads_stage->lead_stage_id]; $TotalLeadCount+=$TempCount;
                }
                else
                {
                    $TempCount='0';
                }

                $data.='
                    <li class="nav-item" status="'.$leads_stage->lead_stage_id.'">
                        <a href="'.$leads_stage->lead_stage_id.'" class="nav-link '.$active_1.'" data-toggle="tab">
                            <span class="badge badge badge-pill badge-sm mr-1" style="background-color:'.$color.';">
                               '.$TempCount.'
                            </span> '.$leads_stage->lead_stage_name.'
                        </a>
                    </li>';
            }

            if($LeadStatusId=='All'){ $active='active'; }else{ $active=''; }
            $data.='<li class="nav-item" status="All">
                <a href="All" class="nav-link '.$active.'" data-toggle="tab">
                    <span class="badge badge badge-pill badge-sm mr-1" style="background-color:blue;"> '.$TotalLeadCount.'</span>All
                </a>
            </li> ';
        $data.='</ul>
         <div class="card-content">
                  <div class="card-body">
                    <div class="table-responsive">
                       <table class="table table-striped table-bordered  LeadsTable" style="width:100%;">
                           <thead>
                               <tr>
                                   <th>Id</th>
                                   <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                   <th>Lead Name</th>
                                   <th>Mobile Number</th>
                                   <th>Alter MobileNumber</th>
                                   <th>Email</th>
                                   <th>Alter EmailId</th>
                                   <th>Age</th>
                                   <th>Medium Id</th>
                                   <th>Source Id</th>
                                   <th>Sub SourceId</th>
                                   <th>CampaignId</th>
                                   <th>Lead Owner</th>
                                   <th>Ad Name</th>
                                   <th>Course Categoty</th>
                                   <th>Course</th>
                                   <th>Country</th>
                                   <th>State</th>
                                   <th>City</th>
                                   <th>Pincode</th>
                                   <th>Address</th>
                                   <th>Created By</th>
                                   <th>Created At</th>
                                   <th>Updated By</th>
                                   <th>Updated At</th>
                               </tr>
                           </thead>
                           <tfoot>
                              <tr>
                                   <th>Id</th>
                                   <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                   <th>Lead Name</th>
                                   <th>Mobile Number</th>
                                   <th>Alter MobileNumber</th>
                                   <th>Email</th>
                                   <th>Alter EmailId</th>
                                   <th>Age</th>
                                   <th>Medium Id</th>
                                   <th>Source Id</th>
                                   <th>Sub SourceId</th>
                                   <th>CampaignId</th>
                                   <th>Lead Owner</th>
                                   <th>Ad Name</th>
                                   <th>Course Categoty</th>
                                   <th>Course</th>
                                   <th>Country</th>
                                   <th>State</th>
                                   <th>City</th>
                                   <th>Pincode</th>
                                   <th>Address</th>
                                   <th>Created By</th>
                                   <th>Created At</th>
                                   <th>Updated By</th>
                                   <th>Updated At</th>
                              </tr>
                           </tfoot>
                       </table>
                     </div>
                  </div>
               </div>';
            echo $data;

    }

     
}
