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

      // if ($request->campaign_id=="All Campaigns")
      // {
      //    $campaign_name="All";
      // }
      // else
      // {
      //    $campaign_name=implode(",", $request->campaign_id);
      // }
      
      if ($request->lead_stage=="All Status")
      {
         
         // $GetLeadStatuss=DB::table('lead_stages')->select('lead_stage_id')->where('deleted', 'No')->get(); 

         // foreach($GetLeadStatuss as $GetLeadStatus){
         //    $LeadStausArr[]=$GetLeadStatus->lead_stage_id;
         // }

         // $lead_stage_id=implode(",", $LeadStausArr);
         $lead_stage_id = "All";
      }
      else
      {
         $lead_stage_id=$request->lead_stage_id;
      }
      
      
      if ($request->user_id=="All Employees")  
      {
         $user_id="All";
      }
      else
      {
         $user_id=$request->user_id;
      }

      $from_date=$request->from_date.' 00:00:00';
      $to_date=$request->to_date.' 23:59:59';
      $all_date=$request->AllDate;
      Session::put('user_id', $request->user_id);
      // Session::put('campaign_id', $request->campaign_id);
      Session::put('lead_stage_id', $request->lead_stage);
      Session::put('from_date', $request->from_date);
      Session::put('to_date', $request->to_date);
      Session::put('all_date', $request->AllDate);
      $where="";
      

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
             // dd($where);
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

      

      // if($campaign_name!="All")
      // {
      //    $where.=" AND a.campaign_id IN($campaign_name)";
      // }

      if($lead_stage_id!="All")
      {
         $where.=" AND a.lead_stage_id IN ($lead_stage_id)";
      }

      
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
                                       
                                       if($lead_stage_id=='All'){
                                          $LeadStatuss = DB::table('lead_stages')->where('deleted','No')->get();
                                       }
                                       else{
                                          $LeadStatuss = DB::table('lead_stages')->where('deleted','No')->where('lead_stage_id',$lead_stage_id)->get();
                                       }

                                       foreach($LeadStatuss as $LeadStatus){

                                          $data.='<th value="'.$LeadStatus->lead_stage_id.'">'. $LeadStatus->lead_stage_name.'
                                          </th>';

                                       }
                                 $data.='</tr>
                              </thead>
                              <tbody>';
                              
                              // $XAxisTotalArr=array();
                              $XAxisTotalT=0;
                              $XAxisTotalT1=0;
                              $XAxisTotalT2=0;
                              $XAxisTotalT3=0;
                              $XAxisTotalT4=0;
                              $XAxisTotalT5=0;

                              if($user_id=='All'){
                                 $Users=DB::table('users')->select('id','first_name')->where('deleted', 'No')->get();
                              }
                              else{
                                 $Users=DB::table('users')->select('id','first_name')->where('deleted', 'No')->where('id', $user_id)->get();
                              }

                              if($lead_stage_id=='All'){
                                 $lead_stages = DB::table('lead_stages')->where('deleted','No')->get();
                              }
                              else{
                                 $lead_stages = DB::table('lead_stages')->where('deleted','No')->where('lead_stage_id',$lead_stage_id)->get();
                              }

                              foreach($Users as $User){
                                 
                                    $UserId=$User->id;
                                    if($all_date=='All')
                                    {
                                       // $date ="created_at >= '$from_date' AND created_at <= '$to_date'";
                                       
                                       $GetLeadsCount = DB::table('leads')->select('lead_id')->where('lead_owner', $UserId)->where('deleted', 'No')->count();
                                    }
                                    else{

                                       $GetLeadsCount = DB::table('leads')->select('lead_id')->where('lead_owner', $UserId)->where('deleted', 'No')->where('created_at', '>=', $from_date)->where('created_at', '<=', $to_date)->count();

                                    }
                                    
                                    $data.='<tr>
                                       <td>'. $UserId .'</td>
                                       <td>'. $User->first_name .'</td>';
                                       $Count=$GetLeadsCount; 
                                          // if($Count!="")
                                          // { 
                                          //    $Count; 
                                          // }
                                          // else{
                                          //    $Count=0; 
                                          // } 
                                          $XAxisTotalT+=$Count; 
                                       $data.='
                                       <td>'.$Count.'</td>';
                                       
                                       foreach($lead_stages as $lead_stage){
                                          if($all_date=='All')
                                          {
                                            
                                             $GetLeadstageCount = DB::table('leads')->select('lead_stage_id')->where('lead_owner', $UserId)->where('lead_stage_id', $lead_stage->lead_stage_id)->where('deleted', 'No')->count();

                                          }
                                          else{
                                             $GetLeadstageCount = DB::table('leads')->select('lead_stage_id','lead_id')->where('lead_owner', $UserId)->where('lead_stage_id', $lead_stage->lead_stage_id)->where('deleted', 'No')->where('created_at', '>=', $from_date)->where('created_at', '<=', $to_date)->count();
                                          }

                                          
                                          $Count1=$GetLeadstageCount; 
                                          
                                          // if($Count2!="")
                                          // { 
                                          //    echo $Count2; 
                                          // }
                                          // else{
                                          //    echo $Count2=0; 
                                          // } 
                                          $XAxisTotalT1+=$GetLeadstageCount; 
                                          
                                          $data.='<td>'.$Count1.'</td>';
                                          
                                       }
                                       

                                       $GetLeadStatusCounts=DB::table('leads')->select('lead_stage_id')->where('deleted', 'No')->whereIn('lead_owner', [$UserId])->get();

                                       // $LeadSubStatusCountArray=array();
                                       // if(isset($GetLeadStatusCounts))
                                       // {
                                       //    foreach($GetLeadStatusCounts as $RowLeadStatusCount)
                                       //    {
                                       //       $LeadSubStatusCountArray+=array($RowLeadStatusCount->lead_stage_id => $RowLeadStatusCount->lead_stage_id);
                                       //    }
                                       // }
                                       // $XAxisTotalArr[]=$LeadSubStatusCountArray;
                                       // dd($XAxisTotalArr);

                                       // $GetLeadsStatus=DB::table('lead_stages')->select('lead_stage_id')->where('deleted','No')->whereIn('lead_stage_id', [$lead_stage_id])->get();
                                       
                                       // foreach($GetLeadsStatus as $RowLeadsStatus)
                                       // {
                                          

                                       //    $data.='<td UserId="'. $UserId .'" Stageid="'.$RowLeadsStatus->lead_stage_id .'">';
                                             
                                       //       if(array_key_exists($RowLeadsStatus->lead_stage_id,$LeadSubStatusCountArray))
                                       //          {  $LeadSubStatusCountArray[$RowLeadsStatus->lead_stage_id]; }
                                       //       else
                                       //          { echo "0";  } 
                                       //    $data.='</td>';
                                          
                                       
                                      
                                     $data.='</tr>';
                                 
                              }
                              
                               $data.='
                               </tbody>
                               <tfoot>
                                 <tr>';

                                 if($lead_stage_id=='All'){
                                    $data.='<th colspan="2"><center>Total</center></th>
                                    <th>'. $XAxisTotalT .'</th>
                                    <th>'. $XAxisTotalT1 .'</th>
                                    <th>'. $XAxisTotalT2 .'</th>
                                    <th>'. $XAxisTotalT3 .'</th>
                                    <th>'. $XAxisTotalT4 .'</th>';
                                 }else{
                                    $data.='<th colspan="2"><center>Total</center></th>
                                    <th>'. $XAxisTotalT .'</th>
                                    <th>'. $XAxisTotalT1 .'</th>';
                                 }
                                    
                                    
                                    
                                    // $GetLeadsStages=DB::table('lead_stages')->select('lead_stage_id')->where('deleted', 'No')->whereIn('lead_stage_id', [$lead_stage_id])->get();

                                    // foreach($GetLeadsStages as $RowLeadsStages)
                                    // {
                                       
                                    //     $data.='<th>'; 
                                    //     echo $test=array_sum(array_column($XAxisTotalArr,$RowLeadsStages->lead_stage_id)); $data.='</th>';
                                       
                                    // }
                                    // $GetLeadSubStatus=DB::table('lead_stages')->where('deleted', 'No')->get();
                                    // // while($RowLeadSubStatus=mysqli_fetch_assoc($GetLeadSubStatus))
                                    //    foreach($GetLeadSubStatus as $RowLeadSubStatus)
                                    // {
                                       
                                    //    $data.='<th>'.  array_sum(array_column($XAxisTotalArr,$RowLeadSubStatus->lead_stage_id)) .'</th>';
                                       
                                    // }
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

