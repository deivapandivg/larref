<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class VendorsController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function vendors(Request $request){
        if ($request->ajax()) {
            $data = DB::table('vendors as a')->where('a.deleted','No')->select(['a.vendor_id',
                'a.vendor_name',
                'a.contact_person_name',
                'a.official_mobile_number',
                'a.contact_person_number',
                'a.alter_mobile_number',
                'a.email_id',
                'a.alter_email_id',
                'a.gst_number',
                'a.address',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<a class="vg-btn-ssp-success VendorViewModal text-center" data-toggle="tooltip" data-placement="right" title="View" data-original-title="View"><i class="fa fa-eye text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a href="'.route('vendors_edit',base64_encode($row->vendor_id)).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        
        return view('vendors.vendors');
    }

    public function vendor_add(){
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 35)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('vendors.vendors_add',compact('GetCustomFields','GetCustomFieldTypes'));
    }

    public function vendors_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();


        if(isset($request->vendor_id))
        {
            $CustomFieldValuesArr=array();
            
            $AttachmentsFolder="public/uploads/Clients/";
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
            $data = array('vendor_name' => $request->vendor_name, 'contact_person_name' => $request->contact_person_name, 'official_mobile_number' => $request->official_mobile_number, 'contact_person_number' => $request->contact_person_number,'alter_mobile_number' => $request->alter_mobile_number, 'email_id' => $request->email_id , 'alter_email_id' => $request->alter_email_id, 'gst_number' => $request->gst_number, 'address' => $request->address, 'custom_fields' => $CustomFieldJsonValue, 'updated_by' => $updatedby, 'updated_at' => Now());
            $vendor_update=DB::table('vendors')->where('vendor_id',$request->vendor_id)->update($data);
        }
        else
        {
            $CustomFieldValuesArr=array();
             $AttachmentsFolder="public/uploads/Clients/";
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
            $data = array('vendor_name' => $request->vendor_name, 'contact_person_name' => $request->contact_person_name, 'official_mobile_number' => $request->official_mobile_number, 'contact_person_number' => $request->contact_person_number,'alter_mobile_number' => $request->alter_mobile_number, 'email_id' => $request->email_id , 'alter_email_id' => $request->alter_email_id, 'gst_number' => $request->gst_number, 'address' => $request->address, 'custom_fields' => $CustomFieldJsonValue, 'created_by' => $createdby, 'created_at' => Now());
            
            $vendor_add=DB::table('vendors')->insert($data);
        }
        return redirect('vendors');
    }

    public function vendors_edit(Request $request){
        $vendors_details=DB::table('vendors')->where('vendor_id', base64_decode($request->vendor_id))->first();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 35)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('vendors.vendors_edit',compact('vendors_details','GetCustomFields','GetCustomFieldTypes'));
    }

    public function vendors_view(Request $request){
        $vendors_details=DB::table('vendors')->where('vendor_id', base64_decode($request->vendor_id))->first();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 35)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('vendors.vendors_view',compact('vendors_details','GetCustomFields','GetCustomFieldTypes'));
    }

    public function vendors_modal_view(Request $request)
    {
        $vendors_details=DB::table('vendors')->where('vendor_id',$request->vendor_id)->first();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 35)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $model ='
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Vendor Name :</b>
                       <p>'.$vendors_details->vendor_name.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Contact Person Name :</b>
                       <p>'.$vendors_details->contact_person_name.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Official Mobile Number :</b>
                       <p>'.$vendors_details->official_mobile_number.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Contact Person Number :</b>
                       <p>'.$vendors_details->contact_person_number.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Alter Mobile Number :</b>
                       <p>'.$vendors_details->alter_mobile_number.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Email Id :</b>
                       <p>'.$vendors_details->email_id.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Alter Email :</b>
                       <p>'.$vendors_details->alter_email_id.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Gst Number :</b>
                       <p>'.$vendors_details->gst_number.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b> Address :</b>
                       <p>'.$vendors_details->address.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-12">
               <div class="form-group">
                  <center>
                  <a href="'.route('vendors_view',base64_encode($vendors_details->vendor_id)).'" target="_blank">
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
