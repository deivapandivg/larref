<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use Redirect, Response, Session;

class CommunicationTypeController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }

   public function communication_types(Request $request){
         if ($request->ajax()) {
         $data = DB::table('communication_types')->where('communication_types.deleted','No')->select([
            'communication_types.communication_type_id',
            'd.communication_medium',
            'communication_types.communication_type',
            'communication_types.duration',
            'b.first_name as created_by',
            'communication_types.created_at', 
            'c.first_name as updated_by', 
            'communication_types.updated_at', 
         ])->leftJoin('users as b','b.id', '=', 'communication_types.created_by')->leftJoin('users as c','c.id', '=', 'communication_types.updated_by')->leftjoin('communication_mediums as d','d.communication_medium_id', '=', 'communication_types.communication_medium_id');
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
       $communication_medium_lists = DB::table('communication_mediums')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        return view('communication_types.communication_types',compact('communication_medium_lists'));
    }

    public function communication_type_submit(Request $request)
    {
        $user_id = Auth::id();
        if(isset($request->communication_type_id))
        {
            $data = array('communication_medium_id' => $request->communication_medium_id, 'communication_type' => $request->communication_type,'duration' => $request->duration,'updated_by' => $user_id, 'updated_at' => Now());

            $communication_type_update=DB::table('communication_types')->where('communication_type_id',$request->communication_type_id)->update($data);
        }
        else
        {
            $data = array('communication_medium_id' => $request->communication_medium_id,'communication_type' => $request->communication_type, 'duration' => $request->duration,'created_by' => $user_id, 'created_at' => Now());

            $communication_type_add=DB::table('communication_types')->insert($data);
        }

        return redirect('communication_types');
    }
    
    public function communication_type_edit(Request $request)
    {
        $communication_type_details=DB::table('communication_types')->where('communication_type_id', $request->communication_type_id)->first();

        $communication_medium_lists = DB::table('communication_mediums')->select(DB::raw('*'))
        ->where('deleted','No')->get();

        $data='<div class="modal-body">
                   <div class="row">
                    <input type="hidden" name="communication_type_id" value="'.$communication_type_details->communication_type_id.'">
                      <div class="col-lg-12">
                         <div class="form-group">
                            <fieldset class="form-group floating-label-form-group"><b>Communication Medium <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                               <select class="form-control border-primary select2" name="communication_medium_id" style="width:100%;">
                                  <option selected>Select</option>';
                                foreach($communication_medium_lists as $communication_medium_list)
                                {
                                    if($communication_medium_list->communication_medium_id==$communication_type_details->communication_medium_id){ $selected='selected'; }else{ $selected=''; }
                                    $data.='<option value="'.$communication_medium_list->communication_medium_id.'" '.$selected.'>'.$communication_medium_list->communication_medium.'</option>';
                                   
                                }
                                $data.='</select>
                            </fieldset>
                         </div>
                      </div>
                      <div class="col-lg-12">
                         <div class="form-group">
                            <fieldset class="form-group floating-label-form-group"><b>Communication Type <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                               <input type="text" required name="communication_type" class="name form-control" placeholder="Communication Type" value="'.$communication_type_details->communication_type.'">
                            </fieldset>
                         </div>
                      </div>
                       <div class="col-lg-12">
                         <div class="form-group">
                            <fieldset class="form-group floating-label-form-group"><b>Duration :</b>
                               <input type="text"  name="duration" class="name form-control" placeholder="Communication Type" value="'.$communication_type_details->duration.'">
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
