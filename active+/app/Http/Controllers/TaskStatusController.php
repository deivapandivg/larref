<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;


class TaskStatusController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function task_status(Request $request){
        if ($request->ajax()) {
            $data = DB::table('status as a')->where('a.deleted','No')->select(['a.status_id',
                'a.status_name',
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
        return view('task_status.task_status');
    }

    public function status_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->status_id))
        {
            $data = array('status_id' => $request->status_id, 'status_name' => $request->status_name, 'updated_by' => $updatedby, 'updated_at' => Now());
            $status_update=DB::table('status')->where('status_id',$request->status_id)->update($data);
        }
        else
        {
            $data = array('status_id' => $request->status_id, 'status_name' => $request->status_name, 'created_by' => $createdby, 'created_at' => Now());

            $status_add=DB::table('status')->insert($data);
        }
        return redirect('task_status');
    }

    public function status_edit(Request $request){
        $status_details=DB::table('status')->where('status_id', $request->status_id)->first();
        $model='<div class="modal-body">
        <input type="hidden" name="status_id" value="'.$status_details->status_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Status Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" id="" required name="status_name" class="name form-control" placeholder="Menu Name" value="'.$status_details->status_name.'">
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
