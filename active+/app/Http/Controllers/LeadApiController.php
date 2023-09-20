<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use Redirect, Response, Session;


class LeadApiController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }

    public function lead_api_list(Request $request){

        if ($request->ajax()) {
         $data = DB::table('lead_api as a')->where('a.deleted','No')->select(['a.lead_api_id',
             'a.api_name',
             'a.api_key',
             'a.active',
             'd.medium_name',
             'e.lead_source_name',
             'f.lead_sub_source_name',
             'g.campaign_name',
             'h.ad_name',
             'a.api_notes',
             'b.first_name as created_by',
             'a.created_at', 
             'c.first_name as updated_by', 
             'a.updated_at', 
         ])->leftJoin('users as b','b.id', '=', 'a.created_by')->leftJoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('mediums as d','d.medium_id', '=', 'a.medium_id')->leftjoin('lead_sources as e', 'e.lead_source_id', '=', 'a.source_id')->leftjoin('lead_sub_sources as f', 'f.lead_sub_source_id', '=', 'a.sub_source_id')->leftjoin('campaigns as g', 'g.campaign_id', '=', 'a.campaign_id')->leftjoin('ad_names as h', 'h.ad_name_id', '=', 'a.ad_name_id');
         return Datatables::of($data)
         ->addIndexColumn()
         ->addColumn('action', function($row){

             $btn ='<div class="text-center"><div class="text-center"><a class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
             // $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a>';
              $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-warning CopyToClipBorad text-center" data-toggle="tooltip" data-placement="right" title="Copy to Clipboard" data-original-title="Copy to Clipboard" value="'.$row->api_key.'"><i class="fa fa-copy text-white text-center"></i></a></div>';
             return $btn;
         })
         ->rawColumns(['action'])
         ->make(true);
      }


  $get_mediums= DB::table('mediums')->select('medium_id','medium_name')->where('deleted','No')->get();
  $get_sources= DB::table('lead_sources')->select('lead_source_id','lead_source_name')->where('deleted','No')->get();
  $get_campaigns= DB::table('campaigns')->select('campaign_id','campaign_name')->where('deleted','No')->get();
  $get_ad_names= DB::table('ad_names')->select('ad_name_id','ad_name')->where('deleted','No')->get();
        return view('lead_api.lead_api', compact('get_mediums','get_sources','get_campaigns','get_ad_names'));
    }

    public function lead_api_submit(Request $request){

        $user_id = Auth::id();
         if(isset($request->lead_api_id))
        {
            $data = array('medium_id' => $request->medium_id, 'source_id' => $request->source_id,'sub_source_id' => $request->sub_source_id,'ad_name_id' => $request->ad_name_id,'campaign_id' => $request->campaign_id,'active' => $request->ActiveType,'api_name' => $request->api_name,
            'api_key' => $request->api_key,'api_notes' => $request->ApiNotes,'updated_by' => $user_id, 'updated_at' => Now());

            $lead_api_update=DB::table('lead_api')->where('lead_api_id',$request->lead_api_id)->update($data);
        }
        else
        {
            $ApiKey=md5("LEADAPIS".date('Y-m-d H:i:s').rand(1,10000000));
            $data = array('medium_id' => $request->medium_id, 'source_id' => $request->source_id,'sub_source_id' => $request->sub_source_id,'ad_name_id' => $request->ad_name_id,'campaign_id' => $request->campaign_id,'active' => $request->ActiveType,'api_name' => $request->api_name,
            'api_key' => $ApiKey,'api_notes' => $request->ApiNotes,'created_by' => $user_id, 'created_at' => Now());
            $lead_api_add=DB::table('lead_api')->insert($data);
        }

        return redirect('lead_api_list');
    }

    public function lead_api_edit(Request $request){
        $lead_api_details=DB::table('lead_api')->where('lead_api_id',$request->lead_api_id)->first();
        $lead_source_details=DB::table('lead_sources')->select('*')->where('deleted', 'No')->get();
        $medium_details=DB::table('mediums')->select('*')->where('deleted', 'No')->get();
        $lead_sub_sources=DB::table('lead_sub_sources')->select('*')->where('deleted', 'No')->get();
        $ad_name_details=DB::table('ad_names')->select('*')->where('deleted', 'No')->get();
        $campaign_details=DB::table('campaigns')->select('*')->where('deleted', 'No')->get();
        $positive=$lead_api_details->active=="yes" ? "Checked" : "";
        $negative=$lead_api_details->active=="No" ? "Checked" : "";
         $data='<div class="modal-body">
                  <input type="hidden" name="lead_api_id" value="'.$lead_api_details->lead_api_id.'">
                  <input type="hidden" name="api_key" value="'.$lead_api_details->api_key.'">
                  <div class="row">
                     <div class="col-lg-6">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Lead Stage  Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                              <input type="text" required name="api_name" class="name form-control" placeholder="" value="'.$lead_api_details->api_name.'">
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Medium <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                             <select class="form-control select2-show-search form-select" name="medium_id" data-placeholder="Choose one">
                                    <option selected>Select</option>';
                                    foreach($medium_details as $medium_detail){
                                        if($medium_detail->medium_id==$lead_api_details->medium_id){ $selected='selected'; }else{ $selected=''; }
                                        $data.='<option value="'.$medium_detail->medium_id.'" '.$selected.'>'.$medium_detail->medium_name.'</option>';
                                    }
                                    $data.='</select>
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Lead Source<sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                              <select class="form-control select2-show-search form-select" name="source_id" id="source_id" data-placeholder="Choose one">
                                    <option selected>Select</option>';
                                    foreach($lead_source_details as $lead_source_detail){
                                        if($lead_source_detail->lead_source_id==$lead_api_details->source_id){ $selected='selected'; }else{ $selected=''; }
                                        $data.='<option value="'.$lead_source_detail->lead_source_id.'" '.$selected.'>'.$lead_source_detail->lead_source_name.'</option>';
                                    }
                                    $data.='</select>
                           </fieldset>
                        </div>
                     </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Lead SubSource<sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                              <select class="form-control select2-show-search form-select" name="sub_source_id" id="sub_source_id" data-placeholder="Choose one">
                                    <option selected>Select</option>';
                                    foreach ($lead_sub_sources as $lead_sub_source)
                                  {
                                    if($lead_api_details->sub_source_id!=$lead_sub_source->lead_sub_source_id){
                                       $data.='<option value="'.$lead_sub_source->lead_sub_source_id .'"> '.$lead_sub_source->lead_sub_source_name .'</option>';
                                    }
                                    else{
                                       $data.='<option value="'. $lead_api_details->sub_source_id.'" selected>'. $lead_sub_source->lead_sub_source_name.'</option>'; 
                                    }
                                 }
                                    $data.='</select>
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>AD Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                              <select class="form-control select2-show-search form-select" name="ad_name_id" id="ad_name_id" data-placeholder="Choose one">
                                    <option selected>Select</option>';
                                    foreach($ad_name_details as $ad_name_detail){
                                        if($ad_name_detail->ad_name_id==$lead_api_details->ad_name_id){ $selected='selected'; }else{ $selected=''; }
                                        $data.='<option value="'.$ad_name_detail->ad_name_id.'" '.$selected.'>'.$ad_name_detail->ad_name.'</option>';
                                    }
                                    $data.='</select>
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Campaigns <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                              <select class="form-control select2-show-search form-select" name="campaign_id" id="campaign_id" data-placeholder="Choose one">
                                    <option selected>Select</option>';
                                    foreach($campaign_details as $campaign_detail){
                                        if($campaign_detail->campaign_id==$lead_api_details->campaign_id){ $selected='selected'; }else{ $selected=''; }
                                        $data.='<option value="'.$campaign_detail->campaign_id.'" '.$selected.'>'.$campaign_detail->campaign_name.'</option>';
                                    }
                                    $data.='</select>
                           </fieldset>
                        </div>
                     </div>
                    <div class="col-lg-6">
                       <div class="form-group">
                          <fieldset class="form-group floating-label-form-group"><b>Active Status</b>
                             <div class="d-inline-block custom-control custom-radio">
                                <input type="radio" checked name="ActiveType" id="Active" value="Yes" '.$positive.'>
                                <label for="Active"> Active</label>
                             </div>
                             <div class="d-inline-block custom-control custom-radio">
                                <input type="radio" name="ActiveType" id="InActive" value="No" '.$negative.'>
                                <label  for="InActive">InActive</label>
                             </div>
                          </fieldset>
                       </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <fieldset class="form-group floating-label-form-group"><b>Description</b>
                            <textarea class="form-control border-primary" name="ApiNotes">'.$lead_api_details->api_notes.'</textarea>
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
                     <i class="fa fa-check"></i> Add
                  </button>
               </div>';
      echo $data;
    }
}
