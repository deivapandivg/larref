<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class LeadSubSourceController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function lead_sub_sources(Request $request){
        if ($request->ajax()) {
            $data = DB::table('lead_sub_sources as a')->where('a.deleted','No')->select(['a.lead_sub_source_id',
                'lead_sources.lead_source_name',
                'a.lead_sub_source_name',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('lead_sources', 'lead_sources.lead_source_id', '=', 'a.lead_source_id');
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
        $lead_source_lists = DB::table('lead_sources')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        return view('lead_sub_sources.lead_sub_sources',compact('lead_source_lists'));
    }

    public function lead_sub_sources_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->lead_sub_source_id))
        {
            $data = array('lead_source_id' => $request->lead_source_id, 'lead_sub_source_id' => $request->lead_sub_source_id, 'lead_sub_source_name' => $request->lead_sub_source_name, 'updated_by' => $updatedby, 'updated_at' => Now());
            $lead_sub_source_update=DB::table('lead_sub_sources')->where('lead_sub_source_id',$request->lead_sub_source_id)->update($data);
        }
        else
        {
            $data = array('lead_source_id' => $request->lead_source_id, 'lead_sub_source_id' => $request->lead_sub_source_id, 'lead_sub_source_name' => $request->lead_sub_source_name, 'created_by' => $createdby, 'created_at' => Now());

            $lead_sub_source_add=DB::table('lead_sub_sources')->insert($data);
        }
        return redirect('lead_sub_sources');
    }

    public function lead_sub_sources_edit(Request $request){
        $lead_sub_sources_details=DB::table('lead_sub_sources')->where('lead_sub_source_id', $request->lead_sub_source_id)->first();
        $lead_source_details=DB::table('lead_sources')->select('*')->where('deleted', 'No')->get();
        $model='<div class="modal-body">
        <input type="hidden" name="lead_sub_source_id" value="'.$lead_sub_sources_details->lead_sub_source_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Lead Source Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <select class="form-control select2 form-select" name="lead_source_id" data-placeholder="Choose one" style="width:100%;">
        <option selected>Select</option>';
        foreach($lead_source_details as $lead_source_detail){
            if($lead_source_detail->lead_source_id==$lead_sub_sources_details->lead_source_id){ $selected='selected'; }else{ $selected=''; }
            $model.='<option value="'.$lead_source_detail->lead_source_id.'" '.$selected.'>'.$lead_source_detail->lead_source_name.'</option>';
        }
        $model.='</select>
        </fieldset>
        </div>
        </div>
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Lead Sub Source Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" id="" required name="lead_sub_source_name" class="name form-control" placeholder="Menu Name" value="'.$lead_sub_sources_details->lead_sub_source_name.'">
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
