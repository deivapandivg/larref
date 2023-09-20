<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentsController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function departments(Request $request){
        if ($request->ajax()) {
            $data = DB::table('departments as a')->where('a.deleted','No')->select(['a.department_id',
                'a.department_name',
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
        $department_lists = DB::table('departments')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        return view('departments.departments',compact('department_lists'));
    }

    public function departments_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->department_id))
        {
            $data = array('department_id' => $request->department_id, 'department_id' => $request->department_id, 'department_name' => $request->department_name, 'updated_by' => $updatedby, 'updated_at' => Now());
            $department_update=DB::table('departments')->where('department_id',$request->department_id)->update($data);
        }
        else
        {
            $data = array('department_id' => $request->department_id, 'department_id' => $request->department_id, 'department_name' => $request->department_name, 'created_by' => $createdby, 'created_at' => Now());

            $department_add=DB::table('departments')->insert($data);
            if($department_add!=""){
                Alert::success('Added','Data Added Successfully!');
            
            }
            else
            {
                alert::error('Error Occurs!');
            }
        }
        return redirect('departments')->withInput();
    }

    public function departments_edit(Request $request){
        $departments_details=DB::table('departments')->where('department_id', $request->department_id)->first();
        $department_details=DB::table('departments')->select('*')->where('deleted', 'No')->get();
        $model='<div class="modal-body">
        <input type="hidden" name="department_id" value="'.$departments_details->department_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>department Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" id="" required name="department_name" class="name form-control" placeholder="Menu Name" value="'.$departments_details->department_name.'">
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
