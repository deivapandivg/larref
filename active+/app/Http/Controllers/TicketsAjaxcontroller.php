<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use Redirect, Response, Session;

class TicketsAjaxController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function tickets_ajax(Request $request)
    {
        $FromDate=$request->FromDate;
        $ToDate=$request->ToDate;
        $AllDate=$request->AllDate;
        $user_id=$request->user_id;
        $date_where="";
        $where="a.deleted='No'";
        $UserId = Session::get('user_id');
        Session::put('from_date', $FromDate);
        Session::put('to_date', $ToDate);
        Session::put('all_date', $AllDate);
        $session_all_date = Session::get('all_date');
        if($AllDate!='All')
        {
           $where.="AND a.created_at>='".$FromDate." 00:00:00' AND a.created_at<='".$ToDate." 23:59:59'";
        }

        if($session_all_date!='All')
        {
           $where.="AND a.created_at>='".$FromDate." 00:00:00' AND a.created_at<='".$ToDate." 23:59:59'";
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
                $where.="AND a.assign_to IN ($Users)";
            }
            else{
                $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $auth_id)->where('deleted', 'No')->select('id', 'first_name');

                $users_lists = DB::table('users')->where('id', $auth_id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();
                foreach($users_lists as $users_list)
                {
                    $UserArray[] = $users_list->id;
                }
                $Users = implode(', ', $UserArray);
                $where.="AND a.assign_to IN ($Users)";
            }
        }
        else
        {
            $where.="AND a.assign_to = '$user_id' ";
        }

        Session::put('TicketAjaxWhere',$where);
        
        $data='';
        $data.='
         <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered tickets" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Ticket Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Customer</th>
                                 <th>Customer Contact</th>
                                 <th>Subject</th>
                                 <th>Description</th>
                                 <th>Ticket Type</th>
                                 <th>Priority</th>
                                 <th>Source</th>
                                 <th>Assign to</th>
                                 <th>Status</th>
                                 <th>Created By</th>
                                 <th>Created At</th>
                                 <th>Updated By</th>
                                 <th>Update At</th>
                              </tr>
                           </thead>
                           <tbody>

                           </tbody>
                           <tfoot>
                              <tr>
                                 <th>Ticket Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Customer</th>
                                 <th>Customer Contact</th>
                                 <th>Subject</th>
                                 <th>Description</th>
                                 <th>Ticket Type</th>
                                 <th>Priority</th>
                                 <th>Source</th>
                                 <th>Assign to</th>
                                 <th>Status</th>
                                 <th>Created By</th>
                                 <th>Created At</th>
                                 <th>Updated By</th>
                                 <th>Update At</th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>
               </div>';
            echo $data;

    }

     
}
