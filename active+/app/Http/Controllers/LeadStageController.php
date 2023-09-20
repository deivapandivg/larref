<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use Redirect, Response, Session;


class LeadStageController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }

   public function lead_stages(Request $request){
      if ($request->ajax()) {
         $data = DB::table('lead_stages')->where('lead_stages.deleted','No')->select(['lead_stages.lead_stage_id',
             'lead_stages.lead_stage_name',
             'lead_stages.lead_stage_name_color',
             'lead_stages.lead_stage_color',
             'lead_stages.lead_stage',
             'b.first_name as created_by',
             'lead_stages.created_at', 
             'c.first_name as updated_by', 
             'lead_stages.updated_at', 
         ])->leftJoin('users as b','b.id', '=', 'lead_stages.created_by')->leftJoin('users as c','c.id', '=', 'lead_stages.updated_by');
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
      return view('lead_stages.lead_stages');
   }


   public function lead_stages_submit(Request $request){
      $user_id = Auth::id();

      if(isset($request->lead_stage_id ))
      {
         $data = array('lead_stage_name' => $request->lead_stage_name, 'lead_stage_name_color' => $request->lead_stage_name_color, 'lead_stage_color' => $request->lead_stage_color, 'lead_stage' => $request->lead_stage, 'updated_by' => $user_id, 'updated_at' => Now());

         $update=DB::table('lead_stages')->where('lead_stage_id',$request->lead_stage_id)->update($data);
      }
      else
      {
         $data = array('lead_stage_name' => $request->lead_stage_name, 'lead_stage_name_color' => $request->lead_stage_name_color, 'lead_stage_color' => $request->lead_stage_color, 'lead_stage' => $request->lead_stage, 'created_by' => $user_id, 'created_at' => Now());

         $add=DB::table('lead_stages')->insert($data);
      }
      return Redirect('lead_stages');

   }

   public function lead_stage_edit(Request $request){
      $lead_stage_details=DB::table('lead_stages')->where('lead_stage_id',$request->lead_stage_id)->first();
      $positive=$lead_stage_details->lead_stage=="Positive" ? "Checked" : "";
      $negative=$lead_stage_details->lead_stage=="Negative" ? "Checked" : "";
         $data='<div class="modal-body">
                  <input type="hidden" name="lead_stage_id" value="'.$lead_stage_details->lead_stage_id.'">
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Lead Stage  Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                              <input type="text" required name="lead_stage_name" class="name form-control" placeholder="Lead Status Name" value="'.$lead_stage_details->lead_stage_name.'">
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-12">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Lead Stage  Name Color <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                              <input type="color"  required name="lead_stage_name_color" class="name form-control" value="'.$lead_stage_details->lead_stage_name_color.'">
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-12">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Lead Stage  Color <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                              <input type="color"  required name="lead_stage_color" class="name form-control" value="'.$lead_stage_details->lead_stage_color.'">
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-12">
                        <div class="form-group">
                           <label class="label-control"><b>Lead Status :</b></label>
                           <input required type="radio" checked name="lead_stage" id="Positive" value="Positive" '.$positive.'>
                           <label  for="Positive">Positive</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           <input type="radio" name="lead_stage"  id="Negative" value="Negative" '.$negative.'>
                           <label  for="Negative">Negative</label>
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
