<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class LeavesController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function leave_applied(Request $request){
        $user_id=Auth::id();
        $LeaveAppliedAjaxWhere = Session::get('LeaveAppliedAjaxWhere');
        if ($request->ajax()) {
            $data = DB::table('leave_approvals as a')->whereRaw($LeaveAppliedAjaxWhere)->where('a.deleted', 'No')->select(['a.leave_approval_id',
                'a.approval_status', 
                'a.created_at', 
                'd.leave_type_name as leave_type',
                'c.first_name as approval_person',
                'a.approval_comments', 
                'a.num_of_days', 
                'a.approved_at', 
            ])->leftjoin('users as c', 'c.id', '=', 'a.approval_person')->leftjoin('leave_types as d', 'd.leave_type_id', '=', 'a.leave_type');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a>';
                return $btn;
            })
            ->addIndexColumn()
            ->addColumn('approval', function($row){

                $btn2='&nbsp;&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-success view_model_btn text-center" data-toggle="tooltip" data-placement="right" title="View" data-original-title="View"><i class="fa fa-eye text-white text-center"></i></a>';
                return $btn2;
            })
            ->rawColumns(['action','approval'])
            ->make(true);
        }
        $get_auth_user = DB::table('users')->where('deleted', 'No')->where('id',$user_id)->first();

        if($get_auth_user->designation_id==1){

            $user_lists = DB::table('users')->where('deleted', 'No')->get();

        }
        else{

            
            $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $user_id)->where('deleted', 'No')->select('id', 'first_name');

            $user_lists = DB::table('users')->where('id', $user_id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();
            
        }
        $leave_types=DB::table('leave_types')->where('deleted', 'No')->get();
        return view('leaves.leave_applied',compact('user_lists','leave_types'));
    }

    public function leave_approvals(Request $request){
        $user_id=Auth::id();
        $LeaveApprovalAjaxWhere = Session::get('LeaveApprovalAjaxWhere');
        if ($request->ajax()) {
            $data = DB::table('leave_approvals as a')->whereRaw($LeaveApprovalAjaxWhere)->select(['a.leave_approval_id',
                'a.approval_status', 
                'b.first_name as user_id', 
                'c.leave_type_name as leave_type',
                'a.from_date',
                'a.to_date',
                'a.num_of_days', 
                'a.created_at', 
            ])->leftjoin('users as b', 'b.id', '=', 'a.user_id')->leftjoin('leave_types as c', 'c.leave_type_id', '=', 'a.leave_type');
            
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('approval', function($row){

                $btn2='&nbsp;&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-success view_model_btn text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-eye text-white text-center"></i></a>';
                return $btn2;
            })
            ->rawColumns(['approval'])
            ->make(true);
        }
        $get_auth_user = DB::table('users')->where('deleted', 'No')->where('id',$user_id)->first();

        if($get_auth_user->designation_id==1){

            $user_lists = DB::table('users')->where('deleted', 'No')->get();

        }
        else{

            
            $user_lists = DB::table('users')->where('reporting_to_id', $user_id)->where('deleted', 'No')->select('id', 'first_name')->get();
            
        }
        return view('leaves.leave_approvals',compact('user_lists'));
    }


    public function leave_applied_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->leave_approval_id))
        {
            if($attachment_file=$request->file('attachment'))
            {
                $destination_path = 'public/LeavesUpload/';
                $attachment_name = date('YmdHis').".".$attachment_file->getClientOriginalExtension();
                $attachment_file->move($destination_path,$attachment_name);
            }
            elseif($request->attachment=='')
            {
                $old_attachements_name = DB::table('leave_approvals')->where('leave_approval_id', $request->leave_approval_id)->select('attachment')->first();
                $attachment_name = $old_attachements_name->attachment;
            }
            $data = array('from_date' => $request->from_date, 'to_date' => $request->to_date, 'leave_type' => $request->leave_type, 'attachment' => $request->attachment, 'leave_description' => $request->description, 'updated_by' => $updatedby, 'updated_at' => Now());
            $leave_type_update=DB::table('leave_approvals')->where('leave_approval_id',$request->leave_approval_id)->update($data);
        }
        else
        {
            $num_of_days_sec = strtotime($request->from_date)-strtotime($request->to_date);
            $num_of_days = round($num_of_days_sec/(60*60*24))+1;
            if($attachment_file=$request->file('attachment'))
            {
                $destination_path = 'public/LeavesUpload/';
                $attachment_name = date('YmdHis').".".$attachment_file->getClientOriginalExtension();
                $attachment_file->move($destination_path,$attachment_name);
            }
            else{
                $attachment_name='';
            }
            $get_user = DB::table('users')->where('id', $createdby)->select('reporting_to_id')->first();
            $reporting_to = $get_user->reporting_to_id;
            $data = array('user_id' => $createdby, 'from_date' => $request->from_date, 'to_date' => $request->to_date, 'leave_type' => $request->leave_type, 'attachment' => $attachment_name, 'leave_description' => $request->description, 'num_of_days' => $num_of_days, 'approval_person' => $reporting_to, 'approval_status' => 'Pending', 'created_by' => $createdby, 'created_at' => Now());

            $leave_type_add=DB::table('leave_approvals')->insertGetId($data);
            $notification_to = $reporting_to;
            $get_user_name = DB::table('users')->where('id', $notification_to)->select('first_name')->first();
            $notification_to_name = $get_user_name->first_name;
            $URL="leave_approvals";
            $Descriptions="Dear $notification_to_name, You Have One Leave Approval : Thank You.";
            $notification_data = array('title' => 'Leave Approval', 'description' => $Descriptions, 'url' => $URL, 'notification_to' => $notification_to, 'created_by' => $createdby, 'created_at' => Now());
            $insert_notification = DB::table('notifications')->insert($notification_data);
        }
        return redirect('leave_applied');
    }

    public function leave_approval_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->leave_approval_id))
        {
            $leave_applied_details=DB::table('leave_approvals')->where('leave_approval_id', $request->leave_approval_id)->first();
            $approval_status=$request->submit;
            $data = array('approval_status' => $approval_status, 'approval_comments' => $request->approval_comments, 'attachment' => $request->attachment, 'leave_description' => $request->description, 'updated_by' => $updatedby, 'approved_by' => $createdby, 'updated_at' => Now(), 'approved_at' => Now());
            $leave_type_update=DB::table('leave_approvals')->where('leave_approval_id',$request->leave_approval_id)->update($data);

            $GetUserId=DB::table('users')->where('id',$leave_applied_details->user_id)->select('id')->first();
           $ReceivedNotifiTo=$GetUserId->id;

           $NotificationTo=$ReceivedNotifiTo;
           $GetNotificationsName=DB::table('users')->where('id', $NotificationTo)->select('first_name')->first();
           $NotifToName=$GetNotificationsName->first_name;
           $URL="leave_applied";
           if($request->submit=='Rejected')
           {
              $Approvalstatus='Your Leave Approval Rejected';

           }
           else
           { 
              $Approvalstatus='Your Leave Approval Accepted';
           }
           $Descriptions="Dear $NotifToName, $Approvalstatus : Thank You.";
           
           $notification_data = array('title' => $Approvalstatus, 'description' => $Descriptions, 'url' => $URL, 'notification_to' => $NotificationTo, 'created_by' => $createdby, 'created_at' => Now());
            $insert_notification = DB::table('notifications')->insert($notification_data);
        }
        return redirect('leave_approvals');
    }

    public function leave_applied_edit(Request $request){
        $leave_applied_details=DB::table('leave_approvals')->where('leave_approval_id', $request->leave_applied_id)->first();
        $leavetype_details=DB::table('leave_types')->select('*')->where('deleted', 'No')->get();
        $model='<div class="modal-body">
        <input type="hidden" name="leave_approval_id" value="'.$leave_applied_details->leave_approval_id.'">
        <div class="row">
        <div class="col-lg-6">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>From Date <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" id="" required name="from_date" class="name form-control" placeholder="Menu Name" value="'.$leave_applied_details->from_date.'">
        </fieldset>
        </div>
        </div>
        <div class="col-lg-6">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>To Date <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" id="" required name="to_date" class="name form-control" placeholder="Menu Name" value="'.$leave_applied_details->to_date.'">
        </fieldset>
        </div>
        </div>
        <div class="col-md-6">
           <div class="form-group">
              <fieldset class="form-group floating-label-form-group"><b>Leave Type <sup class="text-danger" style="font-size: 13px;">*</sup> : </b>
                 <select class="select2 form-control Customer" required name="leave_type"  style="width:100%;">
                    <option value="" disabled="disabled" selected>Select Leave Type</option>';
                    foreach($leavetype_details as $leavetype_detail){
                        if($leavetype_detail->leave_type_id==$leave_applied_details->leave_type){$selected='selected';}else{$selected='';}
                        $model.='<option  value="'.$leavetype_detail->leave_type_id.'" '.$selected.'>'.$leavetype_detail->leave_type_name.'</option>';
                    }
                 $model.='</select>
              </fieldset>
           </div>
        </div>
        <div class="col-md-6">
           <div class="form-group">
              <fieldset class="form-group floating-label-form-group">Attachments : ';
                if($leave_applied_details->attachment!=''){$model.='<a href="public/LeavesUpload/'.$leave_applied_details->attachment.'"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;'.$leave_applied_details->attachment.'';}else{$model.='';}
                 $model.='<input type="file" id="Attachment" name="attachment" class="form-control" value="'.$leave_applied_details->attachment.'">
              </fieldset>
           </div>
        </div>
        <div class="col-md 12">
           <div class="form-group">
              <fieldset class="form-group floating-label-form-group">Description :
                 <textarea class="form-control" name="description">'.$leave_applied_details->leave_description.'</textarea>
              </fieldset>
           </div>
        </div>
        </div>
        </div>
        <div class="modal-footer">
        <button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
        <i class="fa fa-times"></i> Close
        </button>
        <button type="submit" class="btn btn-primary btn-md">
        <i class="fa fa-check"></i> Update
        </button>
        </div>';
        echo $model;
    }

    public function leave_applied_status_view(Request $request){
        $leave_applied_details=DB::table('leave_approvals')->where('leave_approval_id', $request->leave_applied_id)->first();
        $leavetype_details=DB::table('leave_types')->select('*')->where('deleted', 'No')->get();
        $user_details=DB::table('users')->select('*')->where('deleted', 'No')->get();
        
        
        
        $model='<div class="modal-body">
        <input type="hidden" name="leave_approval_id" value="'.$leave_applied_details->leave_approval_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Leave ApprovalId: </b>'.$leave_applied_details->leave_approval_id.'
        </fieldset>
        </div>
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Request By : </b>';
        foreach($user_details as $user_detail){
            if($leave_applied_details->user_id==$user_detail->id){$request_person=$user_detail->first_name;}else{$request_person='';}
        $model.=''.$request_person.'';
        }
        $model.='</fieldset>
        </div>
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Requested At : </b>'.$leave_applied_details->created_at.'
        </fieldset>
        </div>
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Request Reason : </b>';foreach($leavetype_details as $leavetype_detail){
                if($leave_applied_details->leave_type==$leavetype_detail->leave_type_id){$leave_type_name=$leavetype_detail->leave_type_name;}else{$leave_type_name='';}
        $model.=''.$leave_type_name.'';
        }
        $model.='</fieldset>
        </div>
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Number Of Days : </b>'.$leave_applied_details->num_of_days.'
        </fieldset>
        </div>
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b> Approved By : </b>';
        foreach($user_details as $user_detail){
            if($leave_applied_details->approval_person==$user_detail->id){$approved_person=$user_detail->first_name;}else{$approved_person='';}
        $model.=''.$approved_person.'';
        }
        $model.='</fieldset>
        </div>
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b> Approved At : </b>'.$leave_applied_details->approved_at.'
        </fieldset>
        </div>
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b> Approved Reason : </b>'.$leave_applied_details->approval_comments.'
        </fieldset>
        </div>
        <div class="form-group">
        </div>
        </div>
        </div>
        </div>
        <div class="modal-footer">
        <button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
        <i class="fa fa-times"></i> Close
        </button>
        </div>';
        echo $model;
    }

    public function leave_approval_model(Request $request){
        $leave_applied_details=DB::table('leave_approvals')->where('leave_approval_id', $request->leave_approval_id)->first();
        $leavetype_details=DB::table('leave_types')->select('*')->where('deleted', 'No')->get();
        $user_details=DB::table('users')->select('*')->where('deleted', 'No')->get();
        
        
        
        $model='<div class="modal-body">
        <input type="hidden" name="leave_approval_id" value="'.$leave_applied_details->leave_approval_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Leave ApprovalId: </b>'.$leave_applied_details->leave_approval_id.'
        </fieldset>
        </div>
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Request By : </b>';
        foreach($user_details as $user_detail){
            if($leave_applied_details->user_id==$user_detail->id){$request_person=$user_detail->first_name;}else{$request_person='';}
        $model.=''.$request_person.'';
        }
        $model.='</fieldset>
        </div>
        <div class="form-group">
            <fieldset class="form-group floating-label-form-group"><b>From Date : </b>'.$leave_applied_details->from_date.'
            </fieldset>
        </div>
        <div class="form-group">
            <fieldset class="form-group floating-label-form-group"><b>To Date : </b>'.$leave_applied_details->to_date.'
            </fieldset>
        </div>
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Requested At : </b>'.$leave_applied_details->created_at.'
        </fieldset>
        </div>
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Request Reason : </b>';foreach($leavetype_details as $leavetype_detail){
                if($leave_applied_details->leave_type==$leavetype_detail->leave_type_id){$leave_type_name=$leavetype_detail->leave_type_name;}else{$leave_type_name='';}
        $model.=''.$leave_type_name.'';
        }
        $model.='</fieldset>
        </div>
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Number Of Days : </b>'.$leave_applied_details->num_of_days.'
        </fieldset>
        </div>
        <div class="form-group">
            <fieldset class="form-group floating-label-form-group"><b>Comments :</b>
                <textarea cols="50" type="text" id="Commend" required="" name="approval_comments" class="name form-control" placeholder="Description" commend></textarea>
            </fieldset>
        </div>
        </div>
        </div>
        </div>
        </div>
        <div class="modal-footer">
        <button type="submit" name="submit" value="Rejected" class="btn btn-danger btn-md">
        <i class="fa fa-times"></i> Reject
        </button>
        <button type="submit" name="submit" value="Approved" class="btn btn-success btn-md">
        <i class="fa fa-check"></i> Approve
        </button>
        </div>';
        echo $model;
    }

    public function leave_applied_ajax(Request $request){
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

        if($AllDate!='All')
        {
            $where.="AND a.created_at>='".$FromDate." 00:00:00' AND a.created_at<='".$ToDate." 23:59:59'";
        }

        if($user_id=='All')
        {
            $auth_id = Auth::id();
            if($auth_id==1){
                $get_all_users = DB::table('users')->select('id')->where('deleted','No')->get();
                foreach($get_all_users as $get_all_user)
                {
                    $UserArray[] = $get_all_user->id;
                }
                $Users = implode(', ', $UserArray);
                $where.="AND a.approval_person IN ($Users)";
            }
            else{
                $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $auth_id)->where('deleted', 'No')->select('id', 'first_name');

                $users_lists = DB::table('users')->where('id', $auth_id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();
                foreach($users_lists as $users_list)
                {
                    $UserArray[] = $users_list->id;
                }
                $Users = implode(', ', $UserArray);
                $where.="AND a.approval_person IN ($Users)";
            }
        }
        else
        {
            $where.="AND a.approval_person = '$user_id' ";
        }

        Session::put('LeaveAppliedAjaxWhere',$where);
        
        $data='';
        $data.='<div class="card-content">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped  table-bordered LeavesTable" style="width:100%;">
                       <thead>
                          <tr>
                             <th>Approval Id</th>
                             <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                             <th>Approvals</th>
                             <th>Approval Status</th>
                             <th>Request At</th>
                             <th>Request Reason</th>
                             <th>Approval Person</th>
                             <th>Comments</th>
                             <th>No Of Days</th>
                             <th>Approved At</th>
                          </tr>
                       </thead>
                       <tbody>
                       </tbody>
                       <tfoot>
                          <tr>
                             <th>Approval Id</th>
                             <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                             <th>Approvals</th>
                             <th>Approval Status</th>
                             <th>Request At</th>
                             <th>Request Reason</th>
                             <th>Approval Person</th>
                             <th>Comments</th>
                             <th>No Of Days</th>
                             <th>Approved At</th>
                          </tr>
                       </tfoot>
                    </table>
                </div>
            </div>
        </div>';
        echo $data;
    }

    public function leave_approval_ajax(Request $request){
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

        if($AllDate!='All')
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
                $where.="AND a.approval_person IN ($Users)";
            }
            else{
                $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $auth_id)->where('deleted', 'No')->select('id', 'first_name');

                $users_lists = DB::table('users')->where('id', $auth_id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();
                foreach($users_lists as $users_list)
                {
                    $UserArray[] = $users_list->id;
                }
                $Users = implode(', ', $UserArray);
                $where.="AND a.approval_person IN ($Users)";
            }
        }
        else
        {
            $where.="AND a.user_id = '$user_id' ";
        }

        Session::put('LeaveApprovalAjaxWhere',$where);
        
        $data='';
        $data.='<div class="card-content">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped  table-bordered LeavesTable" style="width:100%;">
                       <thead>
                          <tr>
                             <th>Approval Id</th>
                             <th>Approvals</th>
                             <th>Approval Status</th>
                             <th>Request By</th>
                             <th>Request Reason</th>
                             <th>&nbsp;&nbsp;From Date&nbsp;&nbsp;</th>
                             <th>&nbsp;&nbsp;&nbsp;To Date&nbsp;&nbsp;&nbsp;</th>
                             <th>Num of Days</th>
                             <th>&nbsp;&nbsp;Request At&nbsp;&nbsp;</th>
                          </tr>
                       </thead>
                       <tbody>
                       </tbody>
                       <tfoot>
                          <tr>
                             <th>Approval Id</th>
                             <th>Approvals</th>
                             <th>Approval Status</th>
                             <th>Request By</th>
                             <th>Request Reason</th>
                             <th>&nbsp;&nbsp;From Date&nbsp;&nbsp;</th>
                             <th>&nbsp;&nbsp;&nbsp;To Date&nbsp;&nbsp;&nbsp;</th>
                             <th>Num of Days</th>
                             <th>&nbsp;&nbsp;Request At&nbsp;&nbsp;</th>
                          </tr>
                       </tfoot>
                    </table>
                </div>
            </div>
        </div>';
        echo $data;
    }
}
