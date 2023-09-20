<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class ProjectsController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function projects(Request $request){
        if ($request->ajax()) {
            $data = DB::table('projects as a')->where('a.deleted','No')->select(['a.project_id',
                'd.client_name as client_id',
                'a.project_name',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('clients as d', 'd.client_id', '=', 'a.client_id');
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
        $client_lists = DB::table('clients')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 41)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('projects.projects',compact('GetCustomFields','GetCustomFieldTypes','client_lists'));
    }

    public function project_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->project_id))
        {
            $CustomFieldValuesArr=array();
            
            $AttachmentsFolder="public/uploads/Projects/";
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
            $data = array('client_id' => $request->client_id, 'project_id' => $request->project_id, 'project_name' => $request->project_name, 'custom_fields' => $CustomFieldJsonValue,'updated_by' => $updatedby, 'updated_at' => Now());
            $project_update=DB::table('projects')->where('project_id',$request->project_id)->update($data);
        }
        else
        {
            $CustomFieldValuesArr=array();
         $AttachmentsFolder="public/uploads/Projects/";
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
            $data = array('client_id' => $request->client_id, 'project_name' => $request->project_name, 'custom_fields' => $CustomFieldJsonValue, 'created_by' => $createdby, 'created_at' => Now());

            $project_add=DB::table('projects')->insert($data);
        }
        return redirect('projects');
    }

    public function project_edit(Request $request){
        $projects_details=DB::table('projects')->where('project_id', $request->project_id)->first();
        $client_details=DB::table('clients')->select('*')->where('deleted', 'No')->get();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 41)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $data='<div class="modal-body">
        <input type="hidden" name="project_id" value="'.$projects_details->project_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Client Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <select class="form-control select2-show-search form-select" name="client_id" data-placeholder="Choose one" style="width: 100%;">
        <option selected disabled>Select</option>';
        foreach($client_details as $client_detail){
            if($client_detail->client_id==$projects_details->client_id){ $selected='selected'; }else{ $selected=''; }
            $data.='<option value="'.$client_detail->client_id.'" '.$selected.'>'.$client_detail->client_name.'</option>';
        }
        $data.='</select>
        </fieldset>
        </div>
        </div>
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Project Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" id="" required name="project_name" class="name form-control" placeholder="Menu Name" value="'.$projects_details->project_name.'">
        </fieldset>
        </div>
        </div>
        </div>
        <div class="row">';
            foreach($GetCustomFields as $GetCustomField){
                 
              $data.='<div class="col-lg-12">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><label>'.$GetCustomField->field_name.' ';if($GetCustomField->required_field=="Yes"){$data.='<sup style="color:red;font-size:12px;">*</sup>';}$data.=' :</label><br>';
                    
                       if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==5 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==10){
                          $projects_value=$projects_details->custom_fields;
                          $CustomFieldsValueArr=json_decode($projects_value, true);
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
                          $projects_value=$projects_details->custom_fields;
                          $CustomFieldsValueArr=json_decode($projects_value, true);
                          $custom_field_id=$GetCustomField->custom_field_id;
                          
                          foreach($GetCustomFieldTypes as $GetCustomFieldType)
                          {
                             if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                             {
                                if($CustomFieldsValueArr!='')
                                {
                                   if(array_key_exists($custom_field_id,$CustomFieldsValueArr)){
                                      $data.=''.$CustomFieldsValueArr[$custom_field_id].'&nbsp;&nbsp;<a href="public/uploads/projectss/'.$CustomFieldsValueArr[$custom_field_id].'" target="_blank"><i class="fa fa-eye"></i></a><input type="'.$GetCustomFieldType->field_type.'" class="name form-control" value="'.$CustomFieldsValueArr[$custom_field_id].'" name="Custom-'.$GetCustomField->custom_field_id.'">';
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
                          $projects_value=$projects_details->custom_fields;
                          $CustomFieldsValueArr=json_decode($projects_value, true);
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
                          $projects_value=$projects_details->custom_fields;
                          $CustomFieldsValueArr=json_decode($projects_value, true);
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
                             $projects_value=$projects_details->custom_fields;
                             $CustomFieldsValueArr=json_decode($projects_value, true);
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
                          $projects_value=$projects_details->custom_fields;
                          $CustomFieldsValueArr=json_decode($projects_value, true);
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
