<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use Redirect, Response, Session;

class LeadSubStageController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }
    
    public function lead_sub_stages(Request $request){
         if ($request->ajax()) {
         $data = DB::table('lead_sub_stage')->where('lead_sub_stage.deleted','No')->select([
            'lead_sub_stage.lead_sub_stage_id',
            'd.lead_stage_name',
            'lead_sub_stage.lead_sub_stage',
            'b.first_name as created_by',
            'lead_sub_stage.created_at', 
            'c.first_name as updated_by', 
            'lead_sub_stage.updated_at', 
         ])->leftJoin('users as b','b.id', '=', 'lead_sub_stage.created_by')->leftJoin('users as c','c.id', '=', 'lead_sub_stage.updated_by')->leftjoin('lead_stages as d','d.lead_stage_id', '=', 'lead_sub_stage.lead_stage_id');
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
       $lead_stages_lists = DB::table('lead_stages')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        return view('lead_sub_stages.lead_sub_stages',compact('lead_stages_lists'));
    }

    public function lead_sub_stages_submit(Request $request)
    {
        $user_id = Auth::id();
        if(isset($request->lead_sub_stage_id))
        {
            $data = array('lead_stage_id' => $request->lead_stage_id, 'lead_sub_stage' => $request->lead_sub_stage, 'updated_by' => $user_id, 'updated_at' => Now());

            $lead_sub_status_update=DB::table('lead_sub_stage')->where('lead_sub_stage_id',$request->lead_sub_stage_id)->update($data);
        }
        else
        {
            $data = array('lead_stage_id' => $request->lead_stage_id, 'lead_sub_stage' => $request->lead_sub_stage,'created_by' => $user_id, 'created_at' => Now());

            $lead_sub_status_add=DB::table('lead_sub_stage')->insert($data);
        }

        return redirect('lead_sub_stages');
    }

    public function lead_sub_stage_edit(Request $request)
    {
        $lead_sub_status_details=DB::table('lead_sub_stage')->where('lead_sub_stage_id', $request->lead_sub_stage_id)->first();

        $lead_stages_lists = DB::table('lead_stages')->select(DB::raw('*'))
        ->where('deleted','No')->get();

        $data='<div class="modal-body">
                  <div class="row">
                     <input type="hidden" name="lead_sub_stage_id" value="'.$lead_sub_status_details->lead_sub_stage_id.'">
                     <div class="col-lg-12">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Lead Stage <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                              <select class="form-control border-primary select2" name="lead_stage_id" style="width:100%;">
                                 <option selected>Select</option>';
                                foreach($lead_stages_lists as $lead_stages_list)
                                {
                                    if($lead_stages_list->lead_stage_id==$lead_sub_status_details->lead_stage_id){ $selected='selected'; }else{ $selected=''; }
                                    $data.='<option value="'.$lead_stages_list->lead_stage_id.'" '.$selected.'>'.$lead_stages_list->lead_stage_name.'</option>';
                                   
                                }
                              $data.='</select>
                            </fieldset>
                         </div>
                      </div>
                      <div class="col-lg-12">
                         <div class="form-group">
                            <fieldset class="form-group floating-label-form-group"><b>Lead Sub Stage <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                               <input type="text" required name="lead_sub_stage" class="name form-control" placeholder="Lead Sub Status Name" value="'.$lead_sub_status_details->lead_sub_stage.'">
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
        echo $data;
    }
}
