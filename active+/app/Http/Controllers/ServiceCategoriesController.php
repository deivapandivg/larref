<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class ServiceCategoriesController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function service_categories(Request $request){
        if ($request->ajax()) {
            $data = DB::table('service_categories as a')->where('a.deleted','No')->select(['a.service_category_id',
                'a.service_category_name',
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
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 29)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('service_categories.service_categories',compact('GetCustomFields','GetCustomFieldTypes'));
    }

    public function service_categories_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->service_category_id))
        {
            $CustomFieldValuesArr=array();
            
            $AttachmentsFolder="public/uploads/ServiceCategories/";
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
            $data = array('service_category_name' => $request->service_category_name, 'custom_fields' => $CustomFieldJsonValue, 'updated_by' => $updatedby, 'updated_at' => Now());
            $service_categorie_update=DB::table('service_categories')->where('service_category_id',$request->service_category_id)->update($data);
        }
        else
        {
            $CustomFieldValuesArr=array();
         $AttachmentsFolder="public/uploads/ServiceCategories/";
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
                 // dd($CustomFieldValuesArr);
              }
           }
         $CustomFieldJsonValue=json_encode($CustomFieldValuesArr);
            $data = array('service_category_id' => $request->service_category_id, 'service_category_name' => $request->service_category_name, 'custom_fields' => $CustomFieldJsonValue, 'created_by' => $createdby, 'created_at' => Now());

            $service_categorie_add=DB::table('service_categories')->insert($data);
        }
        return redirect('service_categories');
    }

    public function service_categories_edit(Request $request){
        $service_categories_details=DB::table('service_categories')->where('service_category_id', $request->service_category_id)->first();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 29)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $data='<div class="modal-body">
        <input type="hidden" name="service_category_id" value="'.$service_categories_details->service_category_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Service Category Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" id="" required name="service_category_name" class="name form-control" placeholder="Menu Name" value="'.$service_categories_details->service_category_name.'">
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
                          $product_category_value=$service_categories_details->custom_fields;
                          $CustomFieldsValueArr=json_decode($product_category_value, true);
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
                          $product_category_value=$service_categories_details->custom_fields;
                          $CustomFieldsValueArr=json_decode($product_category_value, true);
                          $custom_field_id=$GetCustomField->custom_field_id;
                          
                          foreach($GetCustomFieldTypes as $GetCustomFieldType)
                          {
                             if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                             {
                                if($CustomFieldsValueArr!='')
                                {
                                   if(array_key_exists($custom_field_id,$CustomFieldsValueArr)){
                                      $data.=''.$CustomFieldsValueArr[$custom_field_id].'&nbsp;&nbsp;<a href="public/uploads/ServiceCategories/'.$CustomFieldsValueArr[$custom_field_id].'" target="_blank"><i class="fa fa-eye"></i></a><input type="'.$GetCustomFieldType->field_type.'" class="name form-control" value="'.$CustomFieldsValueArr[$custom_field_id].'" name="Custom-'.$GetCustomField->custom_field_id.'">';
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
                          $product_category_value=$service_categories_details->custom_fields;
                          $CustomFieldsValueArr=json_decode($product_category_value, true);
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
                          $product_category_value=$service_categories_details->custom_fields;
                          $CustomFieldsValueArr=json_decode($product_category_value, true);
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
                             <option value="" disabled selected>Select '.$GetCustomField->field_name.'</option>';
                             
                             $field_value=$GetCustomField->field_value;
                             $OptionsArr=json_decode($field_value, true);
                             $product_category_value=$service_categories_details->custom_fields;
                             $CustomFieldsValueArr=json_decode($product_category_value, true);
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
                                      $data.='echo "<option>'.$Options['FieldValue'].'</option>";';
                                   }
                                }
                                else{
                                   $data.='echo "<option>'.$Options['FieldValue'].'</option>";';
                                }
                             }
                          $data.='</select>';
                       }
                       elseif($GetCustomField->field_type==11)
                       {   
                          $product_category_value=$service_categories_details->custom_fields;
                          $CustomFieldsValueArr=json_decode($product_category_value, true);
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
