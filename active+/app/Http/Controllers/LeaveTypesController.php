<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class LeaveTypesController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function leave_types(Request $request){
        if ($request->ajax()) {
            $data = DB::table('leave_types as a')->where('a.deleted','No')->select(['a.leave_type_id',
                'a.leave_type_name',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<div class="text-center"><div class="text-center"><a class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('leave_types.leave_types');
    }

    public function leave_types_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->leave_type_id))
        {
            $data = array('leave_type_id' => $request->leave_type_id, 'leave_type_name' => $request->leave_type_name, 'updated_by' => $updatedby, 'updated_at' => Now());
            $leave_type_update=DB::table('leave_types')->where('leave_type_id',$request->leave_type_id)->update($data);
        }
        else
        {
            $data = array('leave_type_id' => $request->leave_type_id, 'leave_type_name' => $request->leave_type_name, 'created_by' => $createdby, 'created_at' => Now());

            $leave_type_add=DB::table('leave_types')->insert($data);
        }
        return redirect('leave_types');
    }

    public function leave_types_edit(Request $request){
        $leave_types_details=DB::table('leave_types')->where('leave_type_id', $request->leave_type_id)->first();
        $model='<div class="modal-body">
        <input type="hidden" name="leave_type_id" value="'.$leave_types_details->leave_type_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Leave Type Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" id="" required name="leave_type_name" class="name form-control" placeholder="Menu Name" value="'.$leave_types_details->leave_type_name.'">
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
}
