<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use Redirect, Response, Session;

class LeadsNewController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }

   public function to_do(Request $request)
   { 
      if ($request->ajax()) {
         $data = DB::table('leads as a')->where('a.lead_stage_id', 1)->where('a.deleted', 'No')->select(['a.lead_id',
             'a.lead_name',
             'a.mobile_number',
             'a.alter_mobile_number',
             'a.email_id',
             'a.alter_email_id',
             'a.age',
             'd.medium_name',
             'e.lead_source_name',
             'f.lead_sub_source_name',
             'g.campaign_name',
             'h.first_name as lead_owner',
             'i.ad_name',
             'j.product_category_name',
             'k.product_name',
             'l.country_name',
             'm.state_name',
             'n.city_name',
             'a.pincode',
             'a.address',
             'b.first_name as created_by',
             'a.created_at', 
             'c.first_name as updated_by', 
             'a.updated_at', 
         ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('mediums as d','d.medium_id', '=', 'a.medium_id')->leftjoin('lead_sources as e','e.lead_source_id', '=', 'a.source_id')->leftjoin('lead_sub_sources as f','f.lead_sub_source_id', '=', 'a.sub_source_id')->leftjoin('campaigns as g','g.campaign_id', '=', 'a.campaign_id')->leftjoin('users as h','h.id', '=', 'a.lead_owner')->leftjoin('ad_names as i','i.ad_name_id', '=', 'a.ad_name_id')->leftjoin('product_categories as j','j.product_category_id', '=', 'a.product_category_id')->leftjoin('products as k','k.product_id', '=', 'a.product_id')->leftjoin('countries as l','l.country_id', '=', 'a.country_id')->leftjoin('states as m','m.state_id', '=', 'a.state_id')->leftjoin('cities as n','n.city_id', '=', 'a.city_id');
         return Datatables::of($data)
         ->addIndexColumn()
         ->addColumn('action', function($row){

             $btn ='<a  href="'.route('lead_view',base64_encode($row->lead_id)).'" class="vg-btn-ssp-success ViewCampaignModal text-center" data-toggle="tooltip" data-placement="right" title="View" data-original-title="View"><i class="fa fa-eye text-white text-center"></i></a>';

             $btn.='&nbsp;&nbsp;&nbsp;<a href="'.route('lead_edit',base64_encode($row->lead_id)).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';

             $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a>';

             $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-success TimelineModal text-center" data-toggle="tooltip" data-placement="right" title="TimeLine Add" data-original-title="TimeLine Add"><i class="fa fa-clock text-white text-center"></i></a>';
             return $btn;
         })
         ->rawColumns(['action'])
         ->make(true);
      }
        $users_list = DB::table('users')->where('deleted','No')->get();
        return view('leads.to_do',compact('users_list'));
   }

   public function lead_add(Request $request)
   { 
      $get_mediums= DB::table('mediums')->select('medium_id','medium_name')->where('deleted','No')->get();
      $get_sources= DB::table('lead_sources')->select('lead_source_id','lead_source_name')->where('deleted','No')->get();
      $get_campaigns= DB::table('campaigns')->select('campaign_id','campaign_name')->where('deleted','No')->get();
      $get_users= DB::table('users')->select('id','first_name')->where('deleted','No')->get();
      $get_ad_names= DB::table('ad_names')->select('ad_name_id','ad_name')->where('deleted','No')->get();
      $get_product_categories= DB::table('product_categories')->select('product_category_id','product_category_name')->where('deleted','No')->get();
      $get_countries= DB::table('countries')->select('country_id','country_name')->where('deleted','No')->get();
      $get_lead_stages= DB::table('lead_stages')->select('lead_stage_id','lead_stage_name')->where('deleted', 'No')->get();

      $GetCustomFields=DB::table('custom_fields')->where('field_page', 20)->where('deleted', 'No')->get();
      $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
      return view('leads.lead_add',compact('get_mediums','get_sources','get_campaigns','get_users','get_ad_names','get_product_categories','get_countries','GetCustomFields','GetCustomFieldTypes','get_lead_stages'));  
   }

   public function product_ajax(Request $request)
   {

      $get_products= DB::table('products')->select('product_id','product_name')->where('product_category_id',$request->product_category_id)->where('deleted','No')->get();

      foreach ($get_products as $get_product) {

         echo $data='<option value="'.$get_product->product_id.'">'.$get_product->product_name.'</option>';
      }

   }

   public function lead_stage_ajax(Request $request)
   {
      if($request->lead_stages_id!="All")
      {
   
          $get_lead_sub_stages= DB::table('lead_sub_stage')->select('lead_sub_stage_id','lead_sub_stage')->where('lead_stage_id',$request->lead_stages_id)->where('deleted','No')->get();

         foreach ($get_lead_sub_stages as $get_lead_sub_stage) {

         echo $data.='<option value="'.$get_lead_sub_stage->lead_sub_stage_id.'">'.$get_lead_sub_stage->lead_sub_stage.'</option>';
         }

      }

      if($request->lead_stages_id=="All")
      {
   
          $get_lead_sub_stages= DB::table('lead_sub_stage')->select('lead_sub_stage_id','lead_sub_stage')->where('deleted','No')->get();

         foreach ($get_lead_sub_stages as $get_lead_sub_stage) {

         echo $data.='<option value="'.$get_lead_sub_stage->lead_sub_stage_id.'">'.$get_lead_sub_stage->lead_sub_stage.'</option>';
         }

      }
     

   }

   public function state_ajax(Request $request)
   {

      $get_states= DB::table('states')->select('state_id','state_name')->where('country_id',$request->country_id)->where('deleted','No')->get();

      foreach ($get_states as $get_state) {

         echo $data='<option value="'.$get_state->state_id.'">'.$get_state->state_name.'</option>';
      }

   }

   public function city_ajax(Request $request)
   {

      $get_cities= DB::table('cities')->select('city_id','city_name')->where('state_id',$request->state_id)->where('deleted','No')->get();

      foreach ($get_cities as $get_city) {

         echo $data='<option value="'.$get_city->city_id.'">'.$get_city->city_name.'</option>';

      }

   }
    public function lead_substage_ajax(Request $request)
   {
      $get_lead_sub_status= DB::table('lead_sub_stage')->select('lead_sub_stage_id','lead_sub_stage')->where('lead_stage_id',$request->lead_stage_id)->where('deleted','No')->get();

      foreach ($get_lead_sub_status as $get_lead_sub_statuss) {

         echo $data='<option value="'.$get_lead_sub_statuss->lead_sub_stage_id.'">'.$get_lead_sub_statuss->lead_sub_stage.'</option>';

      }

   }

   public function source_ajax(Request $request)
   {
      $get_sub_sources= DB::table('lead_sub_sources')->select('lead_sub_source_id','lead_sub_source_name')->where('lead_source_id',$request->source_id)->where('deleted','No')->get();

      foreach ($get_sub_sources as $get_sub_source) {

         echo $data='<option value="'.$get_sub_source->lead_sub_source_id.'">'.$get_sub_source->lead_sub_source_name.'</option>';

      }

   }

   public function communication_type_ajax(Request $request)
   {
      $get_communication_types= DB::table('communication_types')->select('communication_type_id','communication_type')->where('communication_medium_id',$request->communication_id)->where('deleted','No')->get();

      foreach ($get_communication_types as $get_communication_type) {

         echo $data='<option value="'.$get_communication_type->communication_type_id.'">'.$get_communication_type->communication_type.'</option>';

      }

   }

    public function communication_type_ajax_task(Request $request)
   {
      $get_communication_types= DB::table('communication_types')->select('communication_type_id','communication_type')->where('communication_medium_id',$request->communication_medium)->where('deleted','No')->get();

      foreach ($get_communication_types as $get_communication_type) {

         echo $data='<option value="'.$get_communication_type->communication_type_id.'">'.$get_communication_type->communication_type.'</option>';

      }

   }



   public function leads_submit(Request $request)
   {
      $user_id = Auth::id();
      if(isset($request->lead_id))
      {
         $CustomFieldValuesArr=array();
            
            $AttachmentsFolder="public/uploads/Leads/";
           if (!file_exists($AttachmentsFolder))
           {
              mkdir($AttachmentsFolder, 0777, true);
           }
           foreach($_POST as $key => $value) 
           {
              if (strpos($key, 'Custom-') !== false)
              {
                 if(is_array($value))
                 {
                    $value=implode(",", $value);
                 }
                 $CustomFieldValuesArr+=array(str_replace("Custom-", "", $key)=>$value);
              }
           }
           foreach($_FILES as $key => $value) 
           {
              if (strpos($key, 'Custom-') !== false)
              {
                 $FileTempName = $_FILES[$key]['tmp_name'];
                 $FileName = $_FILES[$key]['name'];
                 $FilePath = $AttachmentsFolder.'/'.$FileName;
                 move_uploaded_file($FileTempName,$FilePath);
                 $CustomFieldValuesArr+=array(str_replace("Custom-", "", $key)=>$FileName);
              }
           }
         $CustomFieldJsonValue=json_encode($CustomFieldValuesArr);
         $data = array( 
            'lead_name' => $request->lead_name,
            'mobile_number' => $request->mobile_number,
            'alter_mobile_number' => $request->alter_mobile_number,
            'email_id' => $request->email_id,
            'alter_email_id' => $request->alter_email_id,
            'age' => $request->age,
            'medium_id' => $request->medium_id,
            'source_id' => $request->source_id,
            'sub_source_id' => $request->sub_source_id,
            'campaign_id' => $request->campaign_id,
            'lead_owner' => $request->lead_owner,
            'ad_name_id' => $request->ad_name_id,
            'product_category_id' => $request->product_category_id,
            'product_id' => $request->product_id,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'pincode' => $request->pincode,
            'address' => $request->address,
            'custom_fields' => $CustomFieldJsonValue,
            'updated_by' => $user_id, 
            'updated_at' => Now(),
            'lead_stage_id'=>$request->lead_stages_id,
            'lead_sub_stage_id'=>$request->lead_sub_stages_id
         );
         $lead_update=DB::table('leads')->where('lead_id',$request->lead_id)->update($data);

      }
      else
      {
         $CustomFieldValuesArr=array();
         $AttachmentsFolder="public/uploads/Leads/";
           if (!file_exists($AttachmentsFolder))
           {
              mkdir($AttachmentsFolder, 0777, true);
           }
            
         foreach($_POST as $key => $value) 
         {
            if (strpos($key, 'Custom-') !== false)
            {
               if(is_array($value))
               {
                  $value=implode(",", $value);
               }
               $CustomFieldValuesArr+=array(str_replace("Custom-", "", $key)=>$value);
            }
         }
         foreach($_FILES as $key => $value) 
           {
              if (strpos($key, 'Custom-') !== false)
              {
                 $FileTempName = $_FILES[$key]['tmp_name'];
                 $FileName = $_FILES[$key]['name'];
                 $FilePath = $AttachmentsFolder.'/'.$FileName;
                 move_uploaded_file($FileTempName,$FilePath);
                 $CustomFieldValuesArr+=array(str_replace("Custom-", "", $key)=>$FileName);
              }
           }
         $CustomFieldJsonValue=json_encode($CustomFieldValuesArr);
         $data = array( 
            'lead_name' => $request->lead_name,
            'mobile_number' => $request->mobile_number,
            'alter_mobile_number' => $request->alter_mobile_number,
            'email_id' => $request->email_id,
            'alter_email_id' => $request->alter_email_id,
            'age' => $request->age,
            'medium_id' => $request->medium_id,
            'source_id' => $request->source_id,
            'sub_source_id' => $request->sub_source_id,
            'campaign_id' => $request->campaign_id,
            'lead_owner' => $request->lead_owner,
            'ad_name_id' => $request->ad_name_id,
            'product_category_id' => $request->product_category_id,
            'product_id' => $request->product_id,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'pincode' => $request->pincode,
            'address' => $request->address,
            'custom_fields' => $CustomFieldJsonValue,
            'created_by' => $user_id, 
            'created_at' => Now(),
            'lead_stage_id'=>$request->lead_stages_id,
            'lead_sub_stage_id'=>$request->lead_sub_stages_id
         );

         $lead_add=DB::table('leads')->insert($data);
      }

      return redirect('leads');
   }

   public function lead_edit(Request $request)
   {
      $lead= DB::table('leads')->where('lead_id',base64_decode($request->lead_id))->first();
      $get_mediums= DB::table('mediums')->select('medium_id','medium_name')->where('deleted','No')->get();
      $get_sources= DB::table('lead_sources')->select('lead_source_id','lead_source_name')->where('deleted','No')->get();
      $get_sub_sources= DB::table('lead_sub_sources')->select('*')->where('deleted', 'No')->where('lead_source_id',$lead->source_id)->get();

      $get_campaigns= DB::table('campaigns')->select('campaign_id','campaign_name')->where('deleted','No')->get();
      $get_users= DB::table('users')->select('id','first_name')->where('deleted','No')->get();
      $get_ad_names= DB::table('ad_names')->select('ad_name_id','ad_name')->where('deleted','No')->get();
      $get_product_categories= DB::table('product_categories')->select('product_category_id','product_category_name')->where('deleted','No')->get();
      $get_products_details= DB::table('products')->select('*')->where('deleted', 'No')->where('product_id',$lead->product_category_id)->get();

        $get_countries=DB::table('countries')->select('*')->get();
        $get_states=DB::table('states')->select('*')->where('country_id',$lead->country_id)->get();
        $get_cities=DB::table('cities')->select('*')->where('state_id',$lead->state_id)->get();

        $get_lead_statuss=DB::table('lead_stages')->select('lead_stage_id','lead_stage_name')->where('deleted', 'No')->get();

        $get_lead_sub_statuss=DB::table('lead_sub_stage')->select('*')->where('lead_stage_id',$lead->lead_stage_id)->where('deleted', 'No')->get();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 20)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $lead_details=DB::table('leads')->where('lead_id',base64_decode($request->lead_id))->first();
      return view('leads.lead_edit',compact('lead','get_mediums','get_sources','get_campaigns','get_users','get_ad_names','get_product_categories','get_countries','get_states','get_cities','get_lead_statuss','get_lead_sub_statuss','get_products_details','get_sub_sources','GetCustomFields','GetCustomFieldTypes','lead_details'));  

   }

   public function lead_view(Request $request)
   {
      if($request->ajax()){
            $data= DB::table('timelines as a')->where('timeline_for_id',$request->lead_id)->select([ 
                'a.timeline_id',
                'c.lead_name',
                'c.mobile_number',
                'd.communication_medium',
                'e.communication_type',
                'f.lead_stage_name',
                'g.lead_sub_stage',
                'a.description',
                'b.first_name',
                'a.created_at',])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('leads as c','c.lead_id', '=', 'a.timeline_for_id')->leftjoin('communication_mediums as d','d.communication_medium_id', '=', 'a.communication_medium_id')->leftjoin('communication_types as e','e.communication_type_id', '=', 'a.communication_medium_type_id')->leftjoin('lead_stages as f', 'f.lead_stage_id', '=', 'a.lead_stage_id')->leftjoin('lead_sub_stage as g', 'g.lead_sub_stage_id', '=', 'a.lead_sub_stage_id');
             return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
        }
      $lead= DB::table('leads')->where('lead_id',base64_decode($request->lead_id))->first();
      $get_mediums= DB::table('mediums')->where('medium_id',$lead->medium_id)->first();
      $get_sources= DB::table('lead_sources')->where('lead_source_id',$lead->source_id)->first();
      $get_sub_sources= DB::table('lead_sub_sources')->where('lead_sub_source_id',$lead->sub_source_id)->first();
      $get_campaigns= DB::table('campaigns')->where('campaign_id',$lead->campaign_id)->first();
      $get_users= DB::table('users')->where('id',$lead->lead_owner)->first();
      $get_ad_names= DB::table('ad_names')->where('ad_name_id',$lead->ad_name_id)->first();
      $get_product_categories= DB::table('product_categories')->where('product_category_id',$lead->product_category_id)->first();
      $get_products= DB::table('products')->where('product_id',$lead->product_id)->first();
        $get_countries=DB::table('countries')->where('country_id',$lead->country_id)->first();
        $get_states=DB::table('states')->where('state_id',$lead->state_id)->first();
        $get_cities=DB::table('cities')->where('city_id',$lead->city_id)->first();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 20)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $lead_details=DB::table('leads')->where('lead_id',base64_decode($request->lead_id))->first();

      return view('leads.lead_view',compact('lead','get_mediums','get_sources','get_campaigns','get_users','get_ad_names','get_product_categories','get_countries','get_states','get_cities','get_products','get_sub_sources','GetCustomFields','GetCustomFieldTypes','lead_details'));  

   }


   public function timeline_add(Request $request)
   {
      $leads_details=DB::table('leads')->where('lead_id', $request->lead_id)->first();
      $communication_medium_lists=DB::table('communication_mediums')->select('*')->where('deleted','No')->get();
       $lead_stage_lists=DB::table('lead_stages')->select('*')->where('deleted','No')->get();
       $lead_sub_stage_lists=DB::table('lead_sub_stage')->select('*')->where('deleted','No')->get();
      $data='<div class="modal-body">
               <div class="row">
                  <div class="col-lg-6">
                     <div class="form-group">
                        <input type="hidden" name="lead_id" value="'.$request->lead_id.'">
                        <label class="form-group floating-label-form-group"><b>Communication Medium  <span class="text-danger">*</span> :</b></label>
                          <select class="form-control border-primary select2" name="communication_mediumid" id="communication_mediumid" style="width:100%;">
                                  <option selected>Select</option>';
                                foreach($communication_medium_lists as $communication_medium_list)
                                {
                                    $data.='<option value="'. $communication_medium_list->communication_medium_id .'" >'. $communication_medium_list->communication_medium .'
                                          </option>';
                                }
                              $data.='</select>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label class="form-group floating-label-form-group"><b>Communication Type<span class="text-danger">*</span> :</b></label>
                          <select class="form-control border-primary select2"  name="communication_type_id" id="communication_type_id" style="width: 100%">
                           <option selected="selected" value="">Select Lead Sub Source</option>';
                              $data.='</select>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label class="form-group floating-label-form-group"><b>Lead Status <span class="text-danger">*</span> :</b></label>
                          <select class="form-control border-primary select2" name="lead_stages_id" id="lead_stages_id" style="width:100%;">
                                  <option selected>Select</option>';
                              foreach ($lead_stage_lists as $lead_stage_list)
                              { 
                                 if($leads_details->lead_stage_id!=$lead_stage_list->lead_stage_id)
                                 {

                                    $data.='<option value="'.$lead_stage_list->lead_stage_id .'">'. $lead_stage_list->lead_stage_name .'</option>';
                                 }
                                 else
                                 {
                                   $data.= '<option value="'. $leads_details->lead_stage_id.'" selected>'. $lead_stage_list->lead_stage_name .'</option>'; 
                                 }
                              }
                              $data.='</select>

                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label class="form-group floating-label-form-group"><b>Lead Sub Status <span class="text-danger">*</span> :</b></label>
                          <select class="form-control border-primary select2" name="lead_sub_stage_id" id="lead_sub_stages_id" style="width:100%;">
                                  <option selected>Select</option>';
                                  foreach ($lead_sub_stage_lists as $lead_sub_stage_list)
                                  {
                                    if($leads_details->lead_sub_stage_id!=$lead_sub_stage_list->lead_sub_stage_id){
                                       $data.='<option value="'.$lead_sub_stage_list->lead_sub_stage_id .'"> '.$lead_sub_stage_list->lead_sub_stage .'</option>';
                                    }
                                    else{
                                       $data.='<option value="'. $leads_details->lead_sub_stage_id.'" selected>'. $lead_sub_stage_list->lead_sub_stage.'</option>'; 
                                    }
                                 }
                              $data.='</select>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><label><b>Timeline Attachment :</b></label>
                           <center>
                              <table id="AddImageTable" width="50%">
                                 <tbody id="ImageTBodyAdd">
                                    <tr class="add_row">
                                       <td width="100%"><input name="Image[]"  type="file" multiple ></td>
                                       <td width="20%"><button class="btn btn-success btn-sm" type="button" id="add" title="Add new file"><i class="fa fa-plus"></i></button></td>
                                    </tr>
                                 </tbody>
                              </table>
                           </center>
                        </fieldset>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     &nbsp;&nbsp;&nbsp;<label class="label-control" for="userinput3"><b>Comments :</b></label>
                     <div class="form-group">
                        <center><textarea class="form-control border-primary" placeholder="Comments" name="Comments"></textarea> </center>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <label class="label-control" for="userinput3"><b>Do You Want Create Task :</b></label>
                     <input type="radio" class="Checkbutton" name="Task" id="" value="Enable">&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;                               
                     <input type="radio" class="Checkbutton" name="Task" id="" value="Disable" checked>&nbsp;&nbsp;No
                  </div>
                  <div id="GetTaskAjaxYes" >
                  </div>
               </div>
            </div>
            <div class="modal-footer">
         <button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
            <i class="fa fa-times"></i> Close
         </button>
         <button type="submit" name="submit" class="btn btn-primary btn-md">
            <i class="fa fa-check"></i> Add
         </button>
      </div>';
      echo $data;
   }

      public function remainder_task_add(Request $request)
   {
      $leads_details=DB::table('leads')->where('lead_id', $request->lead_id)->first();
      $communication_medium_lists=DB::table('communication_mediums')->select('*')->where('deleted','No')->get();
       $lead_stage_lists=DB::table('lead_stages')->select('*')->where('deleted','No')->get();
       $lead_sub_stage_lists=DB::table('lead_sub_stage')->select('*')->where('deleted','No')->get();
       $radio_button=$request->RadioButton;
       if($radio_button=='Enable')
       {     
         $data=' <div class="modal-body">  
         <h6 class="form-section">
         <i class="fas fa-calendar-day"></i> Task Details</h6>
      <div class="row">
         <div class="col-lg-6">
            <div class="form-group">
               <input type="hidden" name="radio_button" value="'.$radio_button.'">
               <label class="form-group floating-label-form-group"><b>Communication Medium <span class="text-danger">*</span> :</b></label>
               <div class="col-md-6-">
                  <select class="form-control border-primary select2" name="communication_medium" id="communication_medium" style="width:100%;">
                     <option selected>Select</option>';
                    foreach($communication_medium_lists as $communication_medium_list)
                    {
                        $data.='<option value="'. $communication_medium_list->communication_medium_id .'" >'. $communication_medium_list->communication_medium .'
                              </option>';
                    }
                     $data.='</select>
               </div>
            </div>
         </div>
         <div class="col-lg-6">
            <div class="form-group">
               <label class="form-group floating-label-form-group"><b>Communication Type <span class="text-danger">*</span> :</b></label>
               <div class="col-md-6-">
                   <select class="form-control border-primary select2"  name="communication_type" id="communication_type" style="width: 100%">
                     <option selected="selected" value="">Select Lead Sub Source</option>';
                        $data.='</select>
               </div>
            </div>
         </div>
         <div class="col-lg-6">
            <div class="form-group">
               <label class="form-group floating-label-form-group"><b>Followup Date & Time <span class="text-danger">*</span> :</b></label>
               <div class="col-md-6-">
                  <input type="datetime-local" required id="Contactdate" class="form-control border-primary" min=" " name="contactdate">
               </div>
            </div>
         </div>
         <div class="col-lg-12">
            &nbsp;&nbsp;&nbsp;<label class="label-control" for="userinput3"><b>Comments :</b></label>
            <div class="form-group">
                  <textarea class="form-control border-primary" placeholder="Comments" name="comments1"></textarea>
            </div>
         </div>
      </div>
      </div>';
      echo $data;
      }
   }




   public function leads_import(Request $request)
   { 
      $get_mediums= DB::table('mediums')->select('medium_id','medium_name')->where('deleted','No')->get();
      $get_sources= DB::table('lead_sources')->select('lead_source_id','lead_source_name')->where('deleted','No')->get();
      $get_campaigns= DB::table('campaigns')->select('campaign_id','campaign_name')->where('deleted','No')->get();
      $get_lead_stages= DB::table('lead_stages')->select('lead_stage_id','lead_stage_name')->where('deleted', 'No')->get();
      $get_users= DB::table('users')->select('id','first_name')->where('deleted','No')->get();
      $get_ad_names= DB::table('ad_names')->select('ad_name_id','ad_name')->where('deleted','No')->get();
      $get_product_categories= DB::table('product_categories')->select('product_category_id','product_category_name')->where('deleted','No')->get();
      $get_countries= DB::table('countries')->select('country_id','country_name')->where('deleted','No')->get();

      return view('leads.leads_import',compact('get_mediums','get_sources','get_campaigns','get_users','get_ad_names','get_product_categories','get_countries','get_lead_stages'));  
   }

    //    public function lead_timeline_view(Request $request){
    //     if($request->ajax()){
    //         $data= DB::table(' timelines as a')->where('timeline_for_id',$request->$lead_id)->select([ 
    //             'a.timeline_id',
    //             'c.lead_name',
    //             'c.mobile_number',
    //             'd.communication_medium',
    //             'e.communication_type',
    //             'f.lead_stage_name',
    //             'g.lead_sub_stage',
    //             'a.description',
    //             'b.first_name',
    //             'a.created_at',
    //         ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('leads as c','c.lead_id', '=', 'a.timeline_for_id')->leftjoin('communication_mediums as d','d.communication_medium_id', '=', 'a.communication_medium_id')->leftjoin('communication_types as e','e.communication_type_id', '=', 'a.communication_medium_type_id')->leftjoin('lead_stages as f', 'f.lead_stage_id', '=', 'a.lead_stage_id')->leftjoin('lead_sub_stage as g', 'g.lead_sub_stage_id', '=', 'a.lead_sub_stage_id');
    //          return Datatables::of($data)
    //         ->addIndexColumn()
    //         ->make(true);
    //     }
    //   $lead= DB::table('leads')->where('lead_id',$request->lead_id)->first();
    //     return view('leads.lead_view',compact('lead'));
    // }

}
