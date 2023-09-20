<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class RecruitmentStagesController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function recruitment_stages(Request $request){
        if ($request->ajax()) {
            $data = DB::table('recruitment_stages as a')->where('a.deleted','No')->select(['a.recruitment_stage_id',
                'a.recruitment_stage',
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
        $recruitment_stage_lists = DB::table('recruitment_stages')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        return view('recruitment_stages.recruitment_stages',compact('recruitment_stage_lists'));
    }

    public function recruitment_stages_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->recruitment_stage_id))
        {
            $data = array('recruitment_stage' => $request->recruitment_stage, 'tab_list' => $request->taborder_list,'recruitment_stage_color' => $request->stage_color,'recruit_stage' => $request->stage_status,'updated_by' => $updatedby, 'updated_at' => Now());
            $recruitment_stage_update=DB::table('recruitment_stages')->where('recruitment_stage_id',$request->recruitment_stage_id)->update($data);
        }
        else
        {
            $data = array('recruitment_stage' => $request->recruitment_stage, 'tab_list' => $request->taborder_list,'recruitment_stage_color' => $request->stage_color,'recruit_stage' => $request->stage_status,'created_by' => $createdby, 'created_at' => Now());

            $recruitment_stage_add=DB::table('recruitment_stages')->insert($data);
        }
        return redirect('recruitment_stages');
    }

    public function recruitment_stages_edit(Request $request){
        $recruitment_stages_details=DB::table('recruitment_stages')->where('recruitment_stage_id', $request->recruitment_stage_id)->first();
        
        $positive=$recruitment_stages_details->recruitment_stage_color=="Positive" ? "Checked" : "";
        $negative=$recruitment_stages_details->recruitment_stage_color=="Negative" ? "Checked" : "";
        $model='<div class="modal-body">
        <input type="hidden" name="recruitment_stage_id" value="'.$recruitment_stages_details->recruitment_stage_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Recruitment Stage <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" id="" required name="recruitment_stage" class="name form-control" placeholder="Recruitment Stage" value="'.$recruitment_stages_details->recruitment_stage.'">
        </fieldset>
        </div>
        </div>
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>TabList Order :</b>
        <input type="number" id="" min="0" name="taborder_list" class="name form-control" placeholder="1" value="'.$recruitment_stages_details->tab_list.'">
        </fieldset>
        </div>
        </div>
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Stage Color <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="color" required name="stage_color" class="name form-control" value="'.$recruitment_stages_details->recruitment_stage_color.'">
        </fieldset>
        </div>
        </div>
        <div class="col-lg-12">
        <div class="form-group">
        <label class="label-control"><b>Lead Status :</b></label>
        <input required type="radio" checked name="stage_color" id="Positive" value="Positive" '.$positive.'>
        <label class="label-control" for="Positive">Positive</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="stage_color" id="Negative" value="Negative" '.$negative.'>
        <label class="label-control" for="Negative">Negative</label>
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
