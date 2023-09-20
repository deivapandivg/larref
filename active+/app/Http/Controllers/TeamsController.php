<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class TeamsController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function teams(Request $request){
        if ($request->ajax()) {
            $data = DB::table('teams as a')->where('a.deleted','No')->select(['a.team_id',
                'departments.department_name',
                'a.team_name',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('departments', 'departments.department_id', '=', 'a.department_id');
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
        return view('teams.teams',compact('department_lists'));
    }

    public function teams_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->team_id))
        {
            $data = array('department_id' => $request->department_id, 'team_id' => $request->team_id, 'team_name' => $request->team_name, 'updated_by' => $updatedby, 'updated_at' => Now());
            $team_update=DB::table('teams')->where('team_id',$request->team_id)->update($data);
        }
        else
        {
            $data = array('department_id' => $request->department_id, 'team_id' => $request->team_id, 'team_name' => $request->team_name, 'created_by' => $createdby, 'created_at' => Now());

            $team_add=DB::table('teams')->insert($data);
        }
        return redirect('teams');
    }

    public function teams_edit(Request $request){
        $teams_details=DB::table('teams')->where('team_id', $request->team_id)->first();
        $department_details=DB::table('departments')->select('*')->where('deleted', 'No')->get();
        $model='<div class="modal-body">
        <input type="hidden" name="team_id" value="'.$teams_details->team_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Department Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <select class="form-control select2-show-search form-select" name="department_id" data-placeholder="Choose one">
        <option selected>Select</option>';
        foreach($department_details as $department_detail){
            if($department_detail->department_id==$teams_details->department_id){ $selected='selected'; }else{ $selected=''; }
            $model.='<option value="'.$department_detail->department_id.'" '.$selected.'>'.$department_detail->department_name.'</option>';
        }
        $model.='</select>
        </fieldset>
        </div>
        </div>
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Team Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" id="" required name="team_name" class="name form-control" placeholder="Menu Name" value="'.$teams_details->team_name.'">
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
