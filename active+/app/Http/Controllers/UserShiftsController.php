<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class UserShiftsController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function users_shifts(Request $request){
        $auth_id=Auth::id();
        if($auth_id==1){
            if ($request->ajax()) {
                $data = DB::table('users_shifts as a')->where('a.deleted','No')->select(['a.users_shift_id',
                    'd.first_name as user_id',
                    'e.shift_name as shift_id',
                    'a.shift_description',
                    'a.from_date',
                    'a.to_date',
                    'b.first_name as created_by',
                    'a.created_at', 
                    'c.first_name as updated_by', 
                    'a.updated_at', 
                ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('users as d', 'd.id', '=', 'a.user_id')->leftjoin('shifts as e', 'e.shift_id', '=', 'a.shift_id');
                return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn ='<a class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                    $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a>';
                    $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-success view_model_btn text-center" data-toggle="tooltip" data-placement="right" title="View" data-original-title="View"><i class="fa fa-eye text-white text-center"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            }
        }
        else
        {
            if ($request->ajax()) {
                $data = DB::table('users_shifts as a')->where('a.deleted','No')->select(['a.users_shift_id',
                    'd.first_name as user_id',
                    'e.shift_name as shift_id',
                    'a.shift_description',
                    'a.from_date',
                    'a.to_date',
                    'b.first_name as created_by',
                    'a.created_at', 
                    'c.first_name as updated_by', 
                    'a.updated_at', 
                ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('users as d', 'd.id', '=', 'a.user_id')->leftjoin('shifts as e', 'e.shift_id', '=', 'a.shift_id');
                return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn ='<div class="text-center"><div class="text-center"><a class="vg-btn-ssp-success view_model_btn text-center" data-toggle="tooltip" data-placement="right" title="View" data-original-title="View"><i class="fa fa-eye text-white text-center"></i></a>';
                    
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            }
        }
        $users_lists = DB::table('users')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        $shift_lists = DB::table('shifts')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        return view('shifts.shifts',compact('users_lists','shift_lists'));
    }

    public function shift_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        $shift_lists = DB::table('shifts')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        if($request->users_shift_id)
        {
            $data = array('user_id' => $request->user_id, 'shift_id' => $request->shift_id,'shift_description' => $request->shift_description, 'from_date' => $request->from_date, 'to_date' => $request->to_date, 'updated_by' => $updatedby, 'updated_at' => Now());

            $shift_add=DB::table('users_shifts')->where('users_shift_id', $request->users_shift_id)->update($data);
            
            $user_data = array('shift' => $request->shift_id, 'shift_description' => $request->shift_description);

            $users=DB::table('users')->where('id', $request->user_id)->update($user_data);
        }
        else
        {
            $data = array('user_id' => $request->user_id, 'shift_id' => $request->shift_id,'shift_description' => $request->shift_description, 'from_date' => $request->from_date, 'to_date' => $request->to_date, 'created_by' => $createdby, 'created_at' => Now());

            $shift_add=DB::table('users_shifts')->insert($data);
            
            $user_data = array('shift' => $request->shift_id, 'shift_description' => $request->shift_description);

            $users=DB::table('users')->where('id', $request->user_id)->update($user_data);
            
        }
        return redirect('users_shifts');
    }

    public function shift_edit(Request $request){
        $users_shifts_details=DB::table('users_shifts')->where('users_shift_id', $request->users_shift_id)->first();
        $shift_details=DB::table('shifts')->select('*')->where('deleted', 'No')->get();
        $user_details=DB::table('users')->select('*')->where('deleted', 'No')->get();
        $model='<div class="modal-body">
        <input type="hidden" name="users_shift_id" value="'.$users_shifts_details->users_shift_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Employee Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <select class="form-control select2-show-search form-select" name="user_id" data-placeholder="Choose one" style="width:100%;">
        <option selected>Select</option>';
        foreach($user_details as $user_detail){
            if($user_detail->id==$users_shifts_details->user_id){ $selected='selected'; }else{ $selected=''; }
            $model.='<option value="'.$user_detail->id.'" '.$selected.'>'.$user_detail->first_name.'</option>';
        }
        $model.='</select>
        </fieldset>
        </div>
        </div>
          <div class="col-lg-12">
             <div class="form-group">
                <fieldset class="form-group floating-label-form-group"><b>Shift Timing <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                   <select class="form-control select2-show-search form-select" name="shift_id" data-placeholder="Choose one" style="width:100%;" required>
                    <option selected disabled>Select Shift</option>';
                    foreach($shift_details as $shift_detail){
                        if($shift_detail->shift_id==$users_shifts_details->shift_id){ $selected='selected'; }else{ $selected=''; }
                        $model.='<option value="'.$shift_detail->shift_id.'" '.$selected.'>'.$shift_detail->shift_name.'</option>';
                    }
        $model.='</select>
                </fieldset>
             </div>
          </div>
          <div class="col-lg-12">
             <div class="form-group">
                <fieldset class="form-group floating-label-form-group"><b>From Date :</b>
                   <input type="date" id="" name="from_date" class="name form-control" value="'.$users_shifts_details->from_date.'">
                </fieldset>
             </div>
          </div>
          <div class="col-lg-12">
             <div class="form-group">
                <fieldset class="form-group floating-label-form-group"><b>To Date  :</b>
                   <input type="date" id="" name="to_date" class="name form-control" value="'.$users_shifts_details->to_date.'">
                </fieldset>
             </div>
          </div>
          <div class="col-lg-12">
             <div class="form-group">
                <fieldset class="form-group floating-label-form-group"><b>Shift Description :</b>
                   <textarea class="form-control" rows="2" name="shift_description" placeholder="Shift Description">'.$users_shifts_details->shift_description.'</textarea>
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

    public function shift_view(Request $request){
        $users_shifts_details=DB::table('users_shifts')->where('users_shift_id', $request->users_shift_id)->first();
        $shift_details=DB::table('shifts')->select('*')->where('deleted', 'No')->get();
        $user_details=DB::table('users')->select('*')->where('deleted', 'No')->get();
        $model='<div class="modal-body">
        <input type="hidden" name="users_shift_id" value="'.$users_shifts_details->users_shift_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Employee Name :</b>
        ';
        foreach($user_details as $user_detail){
            if($user_detail->id==$users_shifts_details->user_id){ 
                $model.=''.$user_detail->first_name.'';
            }
        }
        $model.='
        </fieldset>
        </div>
        </div>
          <div class="col-lg-12">
             <div class="form-group">
                <fieldset class="form-group floating-label-form-group"><b>Employee Name :</b>
                ';
                foreach($shift_details as $shift_detail){
                    if($shift_detail->shift_id==$users_shifts_details->shift_id){ 
                        $model.=''.$shift_detail->shift_name.'';
                    }
                }
        $model.='
                </fieldset>
             </div>
          </div>
          <div class="col-lg-12">
             <div class="form-group">
                <fieldset class="form-group floating-label-form-group"><b>From Date :</b>
                   '.$users_shifts_details->from_date.'
                </fieldset>
             </div>
          </div>
          <div class="col-lg-12">
             <div class="form-group">
                <fieldset class="form-group floating-label-form-group"><b>To Date  :</b>
                   '.$users_shifts_details->to_date.'
                </fieldset>
             </div>
          </div>
          <div class="col-lg-12">
             <div class="form-group">
                <fieldset class="form-group floating-label-form-group"><b>Shift Description :</b>
                   '.$users_shifts_details->shift_description.'
                </fieldset>
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
}
