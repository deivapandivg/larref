<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DataTables;
use DB;
use Redirect, Response, Session; 

class LeadsReportController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }

   public function leads_report(Request $request){

      $user_lists = DB::table('users')->where('deleted', 'No')->get();
      $campaigns = DB::table('campaigns')->where('deleted', 'No')->get();
      $lead_stages = DB::table('lead_stages')->where('deleted', 'No')->get();
      return view('reports.leads_report',compact('user_lists','campaigns','lead_stages'));

   }

   public function leads_report_ajax(Request $request){

      if ($request->campaign_id=="All Campaigns")
      {
         $campaign_name="All";
      }
      else
      {
         $campaign_name=implode(",", $request->campaign_id);
      }
      
      // if ($request->lead_stage=="All Status")
      // {
         
      //    $GetLeadStatuss=DB::table('lead_stages')->select('lead_stage_id')->where('deleted', 'No')->get(); 

      //    foreach($GetLeadStatuss as $GetLeadStatus){
      //       $LeadStausArr[]=$GetLeadStatus->lead_stage_id;
      //    }

      //    $lead_stage_id=implode(",", $LeadStausArr);
      // }
      // else
      // {
      //    $lead_stage_id=$request->lead_stage_id;
      // }
      
      
      if ($request->user_id=="All Employees")  
      {
         $user_id="All";
      }
      else
      {
         $user_id=implode(",", $request->user_id);
      }
      
      $to_date=$request->to_date.' 23:59:59';
      $from_date=$request->from_date.' 00:00:00';
      $all_date=$request->all_date;
      Session::put('user_id', $request->user_id);
      Session::put('campaign_id', $request->campaign_id);
      Session::put('lead_stage_id', $request->lead_stage_id);
      Session::put('from_date', $request->from_date);
      Session::put('to_date', $request->to_date);
      Session::put('all_date', $request->all_date);
      $where="a.Deleted='No'";
      $where1="Deleted='No'";

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

      if($all_date!='All')
      {
         $where.=" AND a.created_at>='$from_date' AND a.created_at<='$to_date'";
      }

      if($campaign_name!="All")
      {
         $where.=" AND a.campaign_id IN($campaign_name)";
      }

      // if($lead_stage_id!="All")
      // {
      //    $where.=" AND a.lead_stage_id IN ($lead_stage_id)";
      // }

      
      $data='<section id="basic-tabs-components">
         <div class="row match-height">
            <div class="col-xl-12 col-lg-12">
               <div class="card">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="tab-content px-1 pt-1">
                           <div style="overflow-x:auto;">
                              <table class="table table-striped table-bordered UsersTable" style="width: 100%;">
                                 <thead style="color: #6b6f80;">
                                    <tr>
                                       <th>Id</th>
                                       <th>User Name</th>
                                       <th>Lead Count</th>';
                                       
                                       $LeadStatuss=DB::table('lead_sub_stage')->select('lead_sub_stage_id','lead_sub_stage')->where('deleted', 'No')->whereIn('lead_stage_id', $lead_stage_id)->get();

                                       foreach($LeadStatuss as $LeadStatus){

                                          $data.='<th value="'.$LeadStatus['lead_sub_stage_id'].'">'. $LeadStatus['lead_sub_stage'].'
                                          </th>';

                                       }
                                 $data.='</tr>
                              </thead>';
                              
                              $XAxisTotalArr=array();
                              $XAxisTotalT=0;

                              $Users=DB::table('users')->select('id','first_name')->where($where1)->get();

                              foreach($Users as $User){
                              
                                 $UserId=$User['id'];
                                 
                                 $GetLeadsCount = DB::table('leads')->select('lead_id')->where('lead_owner', $UserId)->where($where)->count('lead_id');
                                 
                                 $data.='<tr>
                                    <td>'. $UserId .'</td>
                                    <td>'. $RowUsers['FirstName'] .'</td>
                                    <th UserId="'. $UserId .'" Stageid="All">';
                                       $Count=$GetLeadsCount; 
                                       if($Count!="")
                                       { 
                                          echo $Count; 
                                       }
                                       else{
                                          echo $Count=0; 
                                       } 
                                       $XAxisTotalT+=$Count; 
                                    $data.='</th>';
                                    
                                    $GetLeadSubStatusCounts=DB::table('leads')->select('lead_sub_stage_id')->where($where)->whereIn('lead_owner', $UserId)->count('lead_sub_stage_id');

                                    $LeadSubStatusCountArray=array();
                                    if($GetLeadSubStatusCounts>0)
                                    {
                                       foreach($GetLeadSubStatusCounts as $RowLeadSubStatusCount)
                                       {
                                          $LeadSubStatusCountArray+=array($RowLeadSubStatusCount['LeadSubStatusId']=>$RowLeadSubStatusCount['LeadSubStatusCount']);
                                       }
                                    }
                                    $XAxisTotalArr[]=$LeadSubStatusCountArray;

                                    $GetLeadsStatus=DB::table('lead_sub_stage')->select('lead_sub_stage_id')->where('deleted','No')->whereIn('lead_stage_id', $lead_stage_id)->get();
                                    
                                    foreach($GetLeadsStatus as $RowLeadsStatus)
                                    {
                                       

                                       $data.='<td UserId="'. $UserId .'" Stageid="'.$RowLeadsStatus['LeadSubStatusId'] .'">';
                                          
                                          if(array_key_exists($RowLeadsStatus['LeadSubStatusId'],$LeadSubStatusCountArray))
                                             { echo $LeadSubStatusCountArray[$RowLeadsStatus['LeadSubStatusId']]; }
                                          else
                                             { echo "0";  } 
                                       $data.='</td>';
                                       
                                    }
                                   
                                  $data.='</tr>';
                                 
                              }
                              
                               $data.='<tfoot>
                                 <tr>
                                    <th colspan="2"><center>Total</center></th>
                                    <th>'. $XAxisTotalT .'</th>';
                                    
                                    $GetLeadsStages=DB::table('lead_sub_stage')->select('lead_sub_stage_id')->where('deleted', 'No')->whereIn('lead_stage_id', $lead_stage_id)->get();

                                    foreach($GetLeadsStages as $RowLeadsStages)
                                    {
                                       
                                        $data.='<th> 
                                        '. array_sum(array_column($XAxisTotalArr,$RowLeadsStages['LeadSubStatusId'])) .'</th>';
                                       
                                    }
                                    
                                 $data.='</tr>
                              </tfoot>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </section>
      <script type="text/javascript">
         $(".UsersTable").DataTable({
            "destroy" : true,
            "order": [[ 0, "desc" ]],
            "pageLength" : 25,
            "deferRender": true,
            "stateSave" : true,
            "scrollX":true,
            "fixedColumns":{"leftColumns":2},
            "dom": "Bfrtip",
            "buttons":[{extend: "excelHtml5", footer: true,text:"Download" }],
         });
      </script>
      <style type="text/css">
      .btn-group,
      .btn-group-vertical {
         position: relative;
         display: -webkit-inline-box;
         display: -webkit-inline-flex;
         display: -moz-inline-box;
         display: -ms-inline-flexbox;
         display: inline-flex;
         vertical-align: middle;
         margin-bottom: -91px;}
         .btn-secondary {
            color: #fff;
            border-color: #ffffff;
            background-color: #4078b4;
         }
         .btn-secondary:hover {
            color: #fff;
            border-color: #2b43b3;
            background-color: #2b43b3;
         }
         .Download
         {
            margin-left: 17px;
            margin-top: 23px;
         }
         @media (min-width: 768px)
         {
            .col-md-6 {
               max-width: 45%;
            }
         }
      </style>';
      echo $data;
   }

}

