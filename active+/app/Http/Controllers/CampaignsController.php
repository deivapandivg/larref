<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use Redirect, Response, Session;


class CampaignsController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }
    
   public function campaigns(Request $request){
       if ($request->ajax()) {
            $data = DB::table('campaigns')->where('campaigns.deleted','No')->select(['campaigns.campaign_id',
                'campaigns.campaign_name',
                'campaigns.campaign_budget',
                'campaigns.campaign_date',
                'campaigns.lead_expecting',
                'campaigns.description',
                'b.first_name as created_by',
                'campaigns.created_at', 
                'c.first_name as updated_by', 
                'campaigns.updated_at', 
            ])->leftJoin('users as b','b.id', '=', 'campaigns.created_by')->leftJoin('users as c','c.id', '=', 'campaigns.updated_by');
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
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 14)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
      return view('campaigns.campaigns',compact('GetCustomFields','GetCustomFieldTypes'));
   }

   public function campaign_submit(Request $request){

      $user_id = Auth::id();

      if(isset($request->campaign_id))
      {
         $user_id=Auth::id();
         $CustomFieldValuesArr=array();
            
            $AttachmentsFolder="public/uploads/Campaigns/";
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
         $data = array('campaign_name' => $request->campaign_name, 'campaign_budget' => $request->campaign_budget, 'campaign_date' => $request->campaign_date, 'lead_expecting' => $request->lead_expecting, 'description' => $request->description, 'custom_fields' => $CustomFieldJsonValue, 'updated_by' => $user_id, 'updated_at' => Now());

         $update=DB::table('campaigns')->where('campaign_id',$request->campaign_id)->update($data);
      }
      else
      {
         $user_id=Auth::id();
         $CustomFieldValuesArr=array();
         $AttachmentsFolder="public/uploads/Campaigns/";
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
         $data = array('campaign_name' => $request->campaign_name, 'campaign_budget' => $request->campaign_budget, 'campaign_date' => $request->campaign_date, 'lead_expecting' => $request->lead_expecting, 'description' => $request->description, 'custom_fields' => $CustomFieldJsonValue, 'created_by' => $user_id, 'created_at' => Now());

         $add=DB::table('campaigns')->insert($data);
      }
      return Redirect('campaigns');

   }

   public function campaign_edit(Request $request){
      $campaign_details=DB::table('campaigns')->where('campaign_id',$request->campaign_id)->first();
      $GetCustomFields=DB::table('custom_fields')->where('field_page', 14)->where('deleted', 'No')->get();
      $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
      $data='<div class="modal-body">
              <div class="row">
              <input type="hidden" name="campaign_id" value="'.$campaign_details->campaign_id.'">
                  <div class="col-lg-6">
                     <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><b>Campaign Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                           <input type="text" id="" required name="campaign_name" class="name form-control" placeholder="campaign Name" value="'.$campaign_details->campaign_name.'">
                        </fieldset>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><b>Campaign Budget <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                           <input type="number" id="" required name="campaign_budget" class="name form-control" placeholder="Campaign Budget" value="'.$campaign_details->campaign_budget.'">
                        </fieldset>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><b>Campaign Date :</b>
                           <input type="date"  id="" name="campaign_date" class="name form-control" value='.$campaign_details->campaign_date.'>
                        </fieldset>
                     </div>
                  </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><b>Lead Expacting :</b>
                           <input type="number" id=""  name="lead_expecting" class="name form-control" placeholder="Lead expacting" value="'.$campaign_details->lead_expecting.'">
                        </fieldset>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-lg-12">
                     <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><b>Description :</b>
                           <textarea name="description" class="name form-control">'.$campaign_details->description.'</textarea>
                        </fieldset>
                     </div>
                  </div>
               </div>
               <div class="row">';
                  foreach($GetCustomFields as $GetCustomField){
                     
                  $data.='<div class="col-lg-12"><div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><label>'.$GetCustomField->field_name.' ';if($GetCustomField->required_field=="Yes"){$data.='<sup style="color:red;font-size:12px;">*</sup>';}$data.=' :</label><br>';
                        
                           if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==5 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==10){
                              $campaign_value=$campaign_details->custom_fields;
                              $CustomFieldsValueArr=json_decode($campaign_value, true);
                              $custom_field_id=$GetCustomField->custom_field_id;
                              foreach ($GetCustomFieldTypes as $GetCustomFieldType){
                                 if($GetCustomFieldType->field_type_id==$GetCustomField->field_type){
                                    if($CustomFieldsValueArr!=''){
                                       if(array_key_exists($custom_field_id,$CustomFieldsValueArr)){
                                          
                                          $data.='<input type="'.$GetCustomFieldType->field_type.'" class="name form-control" value="'.$CustomFieldsValueArr[$custom_field_id].'" placeholder="'.$GetCustomField->field_name.'" name="Custom-'.$GetCustomField->custom_field_id.'">';
                                       }
                                       else{
                                          $data.='<input type="'.$GetCustomFieldType->field_type.'" class="name form-control" value="" placeholder="'.$GetCustomField->field_name.'" name="Custom-'.$GetCustomField->custom_field_id.'">';
                                       }
                                    }
                                       else{
                                       $data.='<input type="'.$GetCustomFieldType->field_type.'" class="name form-control" value="" placeholder="'.$GetCustomField->field_name.'" name="Custom-'.$GetCustomField->custom_field_id.'">';
                                    }
                                 }
                              }
                           }
                           elseif($GetCustomField->field_type==6)
                           {
                              $campaign_value=$campaign_details->custom_fields;
                              $CustomFieldsValueArr=json_decode($campaign_value, true);
                              $custom_field_id=$GetCustomField->custom_field_id;
                              
                              foreach($GetCustomFieldTypes as $GetCustomFieldType)
                              {
                                 if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                 {
                                    if($CustomFieldsValueArr!='')
                                    {
                                       if(array_key_exists($custom_field_id,$CustomFieldsValueArr)){
                                          $data.=''.$CustomFieldsValueArr[$custom_field_id].'<a href="public/uploads/campaigns/'.$CustomFieldsValueArr[$custom_field_id].'" target="_blank"><i class="fa fa-eye"></i></a><input type="'.$GetCustomFieldType->field_type.'" class="name form-control" value="'.$CustomFieldsValueArr[$custom_field_id].'" name="Custom-'.$GetCustomField->custom_field_id.'">';
                                       }
                                       else
                                       {
                                          $data.='<input type="'.$GetCustomFieldType->field_type.'" class="name form-control" value="" placeholder="'.$GetCustomField->field_name.'" name="Custom-'.$GetCustomField->custom_field_id.'" >';
                                       }
                                    }
                                    else
                                    {
                                       $data.='<input type="'.$GetCustomFieldType->field_type.'" class="name form-control" value="" placeholder="'.$GetCustomField->field_name.'" name="Custom-'.$GetCustomField->custom_field_id.'" >';
                                    }
                                 }  
                              }
                           }
                           elseif($GetCustomField->field_type==7)
                           {
                              $data.='<br>';
                              $campaign_value=$campaign_details->custom_fields;
                              $CustomFieldsValueArr=json_decode($campaign_value, true);
                              $field_value=$GetCustomField->field_value;
                              $RadioArr=json_decode($field_value,true);
                              $custom_field_id=$GetCustomField->custom_field_id;
                              foreach($RadioArr as $Radios){
                                 foreach ($GetCustomFieldTypes as $GetCustomFieldType){
                                    if($GetCustomFieldType->field_type_id==$GetCustomField->field_type){
                                       $data.='&nbsp;&nbsp;<input type="'.$GetCustomFieldType->field_type.'"';
                                       if($CustomFieldsValueArr!=''){
                                          if(array_key_exists($custom_field_id,$CustomFieldsValueArr)){
                                             if($CustomFieldsValueArr[$custom_field_id]==$Radios['FieldValue'])
                                             {
                                                $checked='checked'; 
                                             }
                                             else
                                             {
                                                $checked='';
                                             } 
                                           
                                             $data.='class="check" name="Custom-'.$GetCustomField->custom_field_id.'" value="'.$Radios['FieldValue'].'" '.$checked.'>&nbsp;&nbsp;'.$Radios['FieldValue'].'&nbsp;&nbsp;';
                                          }
                                          else{
                                             $data.='&nbsp;&nbsp;<input checked type="'.$GetCustomFieldType->field_type.'" placeholder="'.$GetCustomField->field_name.'" name="Custom-'.$GetCustomField->custom_field_id.'" value="'.$Radios['FieldValue'].'" style="width:20px;height:20px;">&nbsp;&nbsp;'.$Radios['FieldValue'].'&nbsp;&nbsp;';
                                          }
                                       }
                                       else{
                                          $data.='&nbsp;&nbsp;<input checked type="'.$GetCustomFieldType->field_type.'" placeholder="'.$GetCustomField->field_name.'" name="Custom-'.$GetCustomField->custom_field_id.'" value="'.$Radios['FieldValue'].'" style="width:20px;height:20px;">&nbsp;&nbsp;'.$Radios['FieldValue'].'&nbsp;&nbsp;';
                                       }
                                    }
                                 }
                              }
                           }
                           elseif($GetCustomField->field_type==9)
                           {
                              $data.='<br>';
                              $field_value=$GetCustomField->field_value;
                              $CheckboxArr=json_decode($field_value, true);
                              $campaign_value=$campaign_details->custom_fields;
                              $CustomFieldsValueArr=json_decode($campaign_value, true);
                              $custom_field_id=$GetCustomField->custom_field_id;
                              foreach($CheckboxArr as $Checkbox)
                              {     
                                 foreach($GetCustomFieldTypes as $GetCustomFieldType)
                                 {
                                    if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                    {
                                       $data.='&nbsp;&nbsp;<input type="'.$GetCustomFieldType->field_type.'"';
                                       if($CustomFieldsValueArr!=''){
                                          if(array_key_exists($custom_field_id,$CustomFieldsValueArr)){ 
                                             if(in_array($Checkbox['FieldValue'], explode(",", $CustomFieldsValueArr[$custom_field_id])))
                                             {
                                                $checked='checked'; 
                                             }
                                             else
                                             {
                                                $checked='';
                                             } 
                                             $data.='name="Custom-'.$GetCustomField->custom_field_id.'[]" value="'.$Checkbox['FieldValue'].'" '.$checked.'>'.$Checkbox['FieldValue'].'';
                                          }
                                          else{
                                             $data.='name="Custom-'.$GetCustomField->custom_field_id.'[]" value="'.$Checkbox['FieldValue'].'">'.$Checkbox['FieldValue'].'';
                                          }
                                       }
                                       else
                                       {
                                          $data.='name="Custom-'.$GetCustomField->custom_field_id.'[]" value="'.$Checkbox['FieldValue'].'">'.$Checkbox['FieldValue'].''; 
                                       }
                                    }
                                 }  
                              }
                           }   
                           elseif($GetCustomField->field_type==3){
                              $data.='<select class="select2 form-control" style="width: 100%;" name="Custom-'.$GetCustomField->custom_field_id.'">
                                 <option value="">Select '.$GetCustomField->field_name.'</option>';
                                 
                                 $field_value=$GetCustomField->field_value;
                                 $OptionsArr=json_decode($field_value, true);
                                 $campaign_value=$campaign_details->custom_fields;
                                 $CustomFieldsValueArr=json_decode($campaign_value, true);
                                 $custom_field_id=$GetCustomField->custom_field_id;
                                 foreach($OptionsArr as $Options)
                                 {
                                    if($CustomFieldsValueArr!=''){
                                       if(array_key_exists($custom_field_id,$CustomFieldsValueArr)){
                                          if($CustomFieldsValueArr[$custom_field_id]==$Options['FieldValue'])
                                          {                                   
                                           $selected='selected'; 
                                          }
                                          else
                                          {
                                           $selected=''; 
                                          }
                                       
                                          $data.='<option value="'.$Options['FieldValue'].'" '.$selected.'>'.$Options['FieldValue'].'</option>';
                                       }
                                       else{
                                          $data.='echo "<option>"'.$Options['FieldValue'].'"</option>";';
                                       }
                                    }
                                    else{
                                       $data.='echo "<option>"'.$Options['FieldValue'].'"</option>";';
                                    }
                                 }
                              $data.='</select>';
                           }
                           elseif($GetCustomField->field_type==11)
                           {   
                              $campaign_value=$campaign_details->custom_fields;
                              $CustomFieldsValueArr=json_decode($campaign_value, true);
                              $custom_field_id=$GetCustomField->custom_field_id;
                              if($CustomFieldsValueArr!=''){                                 if(array_key_exists($custom_field_id,$CustomFieldsValueArr)){
                                    $data.='<textarea name="Custom-'.$GetCustomField->custom_field_id.'" class="form-control" placeholder="'.$GetCustomField->field_name.'">'.$CustomFieldsValueArr[$custom_field_id].'</textarea>';
                                 }
                                 else
                                 {
                                    $data.='<textarea name="Custom-'.$GetCustomField->custom_field_id.'" class="form-control" placeholder="'.$GetCustomField->field_name.'"></textarea>';
                                 }
                              }
                              else
                              {
                                 $data.='<textarea name="Custom-'.$GetCustomField->custom_field_id.'" class="form-control" placeholder="'.$GetCustomField->field_name.'"></textarea>';
                              }
                           }
                     $data.='</div>';
                  }$data.='</div>
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