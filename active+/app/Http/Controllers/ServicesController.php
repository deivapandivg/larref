<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class ServicesController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function services(Request $request){
        if ($request->ajax()) {
            $data = DB::table('services as a')->where('a.deleted','No')->select(['a.service_id',
                'd.service_category_name as service_category_id',
                'a.service_name',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('service_categories as d', 'd.service_category_id', '=', 'a.service_category_id');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<a class="vg-btn-ssp-success ServiceModalView text-center" data-toggle="tooltip" data-placement="right" title="view" data-original-title="view"><i class="fa fa-eye  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a href="'.route('services_edit',base64_encode($row->service_id)).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        
        return view('services.services');
    }

    public function services_add(){
        $service_category_lists = DB::table('service_categories')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 28)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('services.services_add',compact('service_category_lists','GetCustomFields','GetCustomFieldTypes'));
    }

    public function services_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();


        if(isset($request->service_id))
        {
            $CustomFieldValuesArr=array();
            
            $AttachmentsFolder="public/uploads/Services/";
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
            $data = array('service_code' => $request->service_code, 'service_category_id' => $request->service_category_id, 'service_id' => $request->service_id, 'service_name' => $request->service_name, 'service_amount' => $request->service_amount , 'gst' => $request->gst, 'service_description' => $request->service_description, 'custom_fields' => $CustomFieldJsonValue, 'updated_by' => $updatedby, 'updated_at' => Now());
            $service_update=DB::table('services')->where('service_id',$request->service_id)->update($data);
        }
        else
        {
            $CustomFieldValuesArr=array();
             $AttachmentsFolder="public/uploads/Services/";
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
            $data = array('service_code' => $request->service_code, 'service_category_id' => $request->service_category_id, 'service_id' => $request->service_id, 'service_name' => $request->service_name, 'service_amount' => $request->service_amount , 'gst' => $request->gst, 'service_description' => $request->service_description, 'custom_fields' => $CustomFieldJsonValue, 'created_by' => $createdby, 'created_at' => Now());
            
            $service_add=DB::table('services')->insert($data);
        }
        return redirect('services');
    }

    public function services_edit(Request $request){
        $services_details=DB::table('services')->where('service_id', base64_decode($request->service_id))->first();
        $service_category_details=DB::table('service_categories')->select('*')->where('deleted', 'No')->get();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 28)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('services.services_edit',compact('services_details','service_category_details','GetCustomFields','GetCustomFieldTypes'));        
    }

    public function services_view(Request $request){
        $services_details=DB::table('services')->where('service_id', base64_decode($request->service_id))->first();
        $service_category_details=DB::table('service_categories')->where('service_category_id', $services_details->service_category_id)->first();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 28)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('services.services_view',compact('services_details','service_category_details','GetCustomFields','GetCustomFieldTypes')); 
    }

    public function services_modal_view(Request $request){
        $services_details=DB::table('services')->where('service_id', $request->service_id)->first();
        $service_category_details=DB::table('service_categories')->where('service_category_id', $services_details->service_category_id)->first();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 28)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $model = '
        <div class="row">
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>Service Code :</b>
                    <p>'.$services_details->service_code.'</p>
                 </fieldset>
              </div>
           </div>
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>Service Category Name :</b>
                    <p>'.$service_category_details->service_category_name.'</p>
                 </fieldset>
              </div>
           </div>
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>Service Name :</b>
                    <p>'.$services_details->service_name.'</p>
                 </fieldset>
              </div>
           </div>
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>Service Amount :</b>
                    <p>'.$services_details->service_amount.'</p>
                 </fieldset>
              </div>
           </div>
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>GST :</b>
                    <p>'.$services_details->gst.'</p>
                 </fieldset>
              </div>
           </div>
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>Service Description :</b>
                     <p>'.$services_details->service_description.'</p>
                 </fieldset>
              </div>
           </div>
           <div class="col-md-12">
               <div class="form-group">
                  <center>
                  <a href="'.route('services_view',base64_encode($services_details->service_id)).'" target="_blank">
                     <p><b><u>View More Details</u> </b></p>
                  </a>
               </center>
               </div>
            </div>
        </div>
        ';
        echo $model; 
    }
}
