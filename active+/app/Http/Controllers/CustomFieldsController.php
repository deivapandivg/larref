<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class CustomFieldsController extends Controller
{
	public function __construct()
   {
      $this->middleware('auth');
   }

	public function custom_fields(Request $request){
		if ($request->ajax()) {
            $data = DB::table('custom_fields as a')->where('a.deleted','No')->select(['a.custom_field_id',
                'e.menu_name as field_page',
                'a.field_name',
                'd.field_type as field_type',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('custom_fieldtype as d', 'd.field_type_id', '=', 'a.field_type')->leftjoin('menus as e', 'e.menu_id', '=', 'a.field_page');
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
        $field_types = DB::table('custom_fieldtype')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        $menus = DB::table('menus')->select(DB::raw('*'))
        ->where('deleted','No')->get();
		return view('custom_fields.custom_fields',compact('menus','field_types'));
	}

	public function custom_fields_add(Request $request){
		$field_types = DB::table('custom_fieldtype')->select(DB::raw('*'))
        ->where('deleted','No')->get();
       $menus = DB::table('menus')->select(DB::raw('*'))
        ->where('deleted','No')->get();
		return view('custom_fields.custom_fields_add',compact('menus','field_types'));
	}

	public function custom_fields_edit(Request $request){
	    $customfield_details=DB::table('custom_fields')->where('custom_field_id',$request->custom_field_id)->first();
	    $field_types = DB::table('custom_fieldtype')->select(DB::raw('*'))
        ->where('deleted','No')->get();
	    $menu_details=DB::table('menus')->select('*')->where('deleted', 'No')->get();
	    $positive=$customfield_details->required_field=="Yes" ? "Checked" : "";
      	$negative=$customfield_details->required_field=="No" ? "Checked" : "";
      	$FieldValue=$customfield_details->field_value;
      	$FieldValueArr=json_decode($FieldValue,true);
	    $model='<div class="modal-body">
              	<div class="row">
              		<input type="hidden" name="custom_field_id" value="'.$customfield_details->custom_field_id.'">
                  	<div class="col-lg-12">
				        <div class="form-group">
					        <fieldset class="form-group floating-label-form-group"><b>Module Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
						        <select class="form-control select2-show-search form-select" name="menu_id" data-placeholder="Choose one">
						        <option selected>Select</option>';
						        foreach($menu_details as $menu_detail){
						            if($menu_detail->menu_id==$customfield_details->field_page){ $selected='selected'; }else{ $selected=''; }
						            $model.='<option value="'.$menu_detail->menu_id.'" '.$selected.'>'.$menu_detail->menu_name.'</option>';
						        }
						        $model.='</select>
					        </fieldset>
				        </div>
				    </div>
                  	<div class="col-lg-12">
                     	<div class="form-group">
	                        <fieldset class="form-group floating-label-form-group"><b>Field Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
	                           <input type="text" id="" required name="field_name" class="name form-control" placeholder="Campaign Budget" value="'.$customfield_details->field_name.'">
	                        </fieldset>
                    	</div>
                  	</div>
                  	<div class="col-lg-12">
				        <div class="form-group">
					        <fieldset class="form-group floating-label-form-group"><b>Module Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
						        <select class="form-control border-primary select2 form-select" id="edit_field_type_id" name="field_type_id" data-placeholder="Choose one">
						        <option selected>Select</option>';
						        foreach($field_types as $field_type){
						            if($field_type->field_type_id==$customfield_details->field_type){ $selected='selected'; }else{ $selected=''; }
						            $model.='<option value="'.$field_type->field_type_id.'" '.$selected.'>'.$field_type->field_type.'</option>';
						        }
						        $model.='</select>
					        </fieldset>
				        </div>
				    </div>
                 	<div class="col-lg-12">
                        <div class="form-group">
                            <fieldset class="form-group floating-label-form-group"><b>Required :</b>
                     			&nbsp;&nbsp;<input type="radio" name="RequiredField" value="Yes" '.$positive.'> Yes&nbsp;&nbsp;
             					<input type="radio" name="RequiredField" value="No" '.$negative.'> No
                  			</fieldset>
                        </div>
                    </div>
                    <div id="EditValuesGet">';
                    	if($FieldValue!=""){ 
                  		$model.='<div class="col-md-12">
                     		<table id="Dynamic_Field_Option">
                        	<tr>
                           		<td></td>
                           		<td><span class="btn btn-success" id="AddMoreOptions"><i class="fa fa-plus"></i></span></td>
                        	</tr>';
	                        $i=1;
	                        foreach ($FieldValueArr as $Values ) 
	                        {
	                           $model.='<tr id="rows '.$i.'">
	                              <td> <fieldset class="form-group floating-label-form-group"><b>Option :</b><input type="text" class="form-control border-primary" value="'.$Values['FieldValue'].'" name="OptionValue[]"></fieldset></td>
	                              <td><button type="button" name="remove" id="'.$i.'" class="btn  btn-danger btn_removec">X</button></td>
	                           </tr>';
	                           $i++; }     
	                        $model.='</table>
                     	</div>';
                  		}
                  	$model.='</div>
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

	public function custom_field_submit(Request $request){
		$user_id = Auth::id();
		if($request->custom_field_id)
		{
			$OptionValue = $request->OptionValue;
			if ($OptionValue!="")
	   		{
		      	$ValuesItemsArr=array();
		      	for($i=0; $i < count($request->OptionValue); $i++) 
		      	{
		        	$FieldValue=$request->OptionValue[$i];
		        	$ValuesItemsArr[]=array("FieldValue"=>$FieldValue);
		      	}
		  		$field_values_items=json_encode($ValuesItemsArr);
		   	}
	   		else
	   		{
	     	 	$field_values_items='';
	   		}
	        $data = array( 
	            'field_page' => $request->menu_id,
	            'field_name' => $request->field_name,
	            'field_type' => $request->field_type_id,
	            'field_value' => $field_values_items,
	            'required_field' => $request->RequiredField,
	            'updated_by' => $user_id, 
	            'updated_at' => Now()
	        );

	     	$custom_field_update=DB::table('custom_fields')->where('custom_field_id',$request->custom_field_id)->update($data);
	     	$custom_field_id=$request->custom_field_id;
	     	if($custom_field_update){
	     		
	     		$data = array( 
	            'custom_field_id' => $custom_field_id,
	            'for_id' => $request->menu_id,
	            'field_name' => $request->field_name,
	            'required' => $request->RequiredField,
	        );
	     	$field_update=DB::table('fields')->where('custom_field_id',$request->custom_field_id)->update($data);
	     	}
		}
		else
		{
			$OptionValue = $request->OptionValue;
			if ($OptionValue!="")
	   		{
		      	$ValuesItemsArr=array();
		      	for($i=0; $i < count($request->OptionValue); $i++) 
		      	{
		        	$FieldValue=$request->OptionValue[$i];
		        	$ValuesItemsArr[]=array("FieldValue"=>$FieldValue);
		      	}
		  		$field_values_items=json_encode($ValuesItemsArr);
		   	}
	   		else
	   		{
	     	 	$field_values_items='';
	   		}
	        $data = array( 
	            'field_page' => $request->menu_id,
	            'field_name' => $request->field_name,
	            'field_type' => $request->field_type_id,
	            'field_value' => $field_values_items,
	            'required_field' => $request->RequiredField,
	            'created_by' => $user_id, 
	            'created_at' => Now()
	        );

	     	$custom_field_add=DB::table('custom_fields')->insertGetId($data);
	     	if($custom_field_add){
	     		$data = array( 
	            'custom_field_id' => $custom_field_add,
	            'for_id' => $request->menu_id,
	            'field_name' => $request->field_name,
	            'required' => $request->RequiredField,
	        );
	     	$field_add=DB::table('fields')->insert($data);
	     	}
	    }
     	return redirect('custom_fields');
	}

	public function custom_fields_dropdown(Request $request){
		$model='
		<div class="col-md-12">
	      	<table id="Dynamic_Field_Option">
				<tr>
					<td> <fieldset class="form-group floating-label-form-group"><b>Option :</b><input type="text" class="form-control border-primary" placeholder="Option Value" required="" name="OptionValue[]"></fieldset></td>
					<td><button type="button" name="remove" class="btn  btn-danger btn_remove">X</button></td>
					<td><span class="btn  btn-success" id="AddMoreOption"><i class="fa fa-plus"></i></span></td>
				</tr>
			</table>
   		</div>
		';
		echo $model;
	}

	public function custom_fields_radio(Request $request){
		$model='
		<div class="col-md-12">
	      	<table id="Dynamic_Field_Radio">
				<tr>
					<td> <fieldset class="form-group floating-label-form-group"><b>Radio Value :</b><input type="text" class="form-control border-primary" required="" placeholder="Radio Value" maxlength="20" name="OptionValue[]"></fieldset></td>
					<td><button type="button" name="remove" class="btn  btn-danger btn_remove1">X</button></td>
					<td><span class="btn  btn-success" id="AddMoreRadio"><i class="fa fa-plus"></i></span></td>
				</tr>
			</table>
		</div>';
		echo $model;
	}

	public function custom_fields_checkbox(Request $request){
		$model='
		<div class="col-md-12">
	      	<table id="Dynamic_Field_Checkbox">
				<tr>
					<td> <fieldset class="form-group floating-label-form-group"><b>Checkbox Value :</b><input type="text" class="form-control border-primary" required=""  placeholder="Checkbox Value" name="OptionValue[]"></fieldset></td>
					<td><button type="button" name="remove" class="btn  btn-danger btn_remove2">X</button></td>
					<td><span class="btn  btn-success" id="AddMoreCheckbox"><i class="fa fa-plus"></i></span></td>
				</tr>
			</table>
   		</div>';
   		echo $model;
	}

	public function custom_new_field(Request $request){
        $field_types = DB::table('custom_fieldtype')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        $menus = DB::table('menus')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        $data ='<tr>
            <td width="30%">
                  <fieldset class="form-group floating-label-form-group"><b>Field Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                     <input type="text" id="field_name" required name="field_name" class="name form-control" placeholder="Field Name">
                  </fieldset>
            </td>
            <td width="30%">
               <fieldset class="form-group floating-label-form-group"><b>Custom Field Type <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                  <select class="form-control border-primary select2 form-select" name="field_type_id" id="field_types_id" data-placeholder="Choose one" style="width:100%;">
                     <option selected>Select</option>';
                     foreach($field_types as $field_type){
                     $data.='<option value="'.$field_type->field_type_id.'">'.$field_type->field_type.'</option>';
                     }
                  $data.='</select>
               </fieldset>
            </td>
        		<td width="30%">
        			<span type="button" class="btn btn-danger btn-sm RemovequotationItem text-white">
        				<i class="fa fa-trash"></i> 
        			</span>
        		</td>
        		<div id="ValueGet"></div>
        	</tr>
        	<script type="text/javascript">
        		$("#field_types_id").change(function(){
        			
            var field_type_id=$(this).val();
            
            alert(field_type_id);
            if (field_type_id==3) {
	            $.ajax({
	      			url:  "custom_fields_dropdown",
				      type: "POST",
				      data: {
				      		"field_type_id":field_type_id
				      		},
				      success: function (data) {
				    		$("#ValueGet").html(data);
				   	}
	    			});
	    		}
            else if(field_type_id==7)
            {
            $.ajax({
	      			url:  "custom_fields_radio",
				      type: "POST",
				      data: {
				      		
				      		"field_type_id":field_type_id
				      		},
				      success: function (data) {
				    		$("#ValueGet").html(data);
				   	}
	    			});
            }
             else if(field_type_id==9)
            {
            $.ajax({
	      			url:  "custom_fields_checkbox",
				      type: "POST",
				      data: {
				      		
				      		"field_type_id":field_type_id
				      		},
				      success: function (data) {
				    		$("#ValueGet").html(data);
				   	}
	    			});
            }
            else
            {
               $("#ValueGet").html("");
            }
         });
        	</script>
        	';
      echo $data;
    }
}
