<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class ClientsController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function clients(Request $request){
        if ($request->ajax()) {
            $data = DB::table('clients as a')->where('a.deleted','No')->select(['a.client_id',
                'a.client_name',
                'a.company_name',
                'a.mobile_number',
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

                 $btn ='<a class="vg-btn-ssp-success ClientViewModal text-center" data-toggle="tooltip" data-placement="right" title="View" data-original-title="View"><i class="fa fa-eye text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a href="'.route('clients_edit',base64_encode($row->client_id)).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        
        return view('clients.clients');
    }

    public function clients_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();

        if($request->client_id)
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
            $data = array('client_name' => $request->client_name, 'company_name' => $request->company_name, 'mobile_number' => $request->mobile_number, 'alter_mobile_number' => $request->alter_mobile_number, 'email_id' => $request->email_id , 'alter_email_id' => $request->alter_email_id, 'gst_number' => $request->gst_number, 'address' => $request->address, 'custom_fields' => $CustomFieldJsonValue, 'updated_by' => $updatedby, 'updated_at' => Now());
            $client_update=DB::table('clients')->where('client_id',$request->client_id)->update($data);
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
            $data = array('client_name' => $request->client_name, 'company_name' => $request->company_name, 'mobile_number' => $request->mobile_number, 'alter_mobile_number' => $request->alter_mobile_number, 'email_id' => $request->email_id , 'alter_email_id' => $request->alter_email_id, 'gst_number' => $request->gst_number, 'address' => $request->address, 'custom_fields' => $CustomFieldJsonValue, 'created_by' => $createdby, 'created_at' => Now());
            
            $client_add=DB::table('clients')->insert($data);
        }
        return redirect('clients');
    }

    public function client_add(){
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 30)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('clients.clients_add',compact('GetCustomFields','GetCustomFieldTypes'));
    }

    public function clients_edit(Request $request){
        $clients_details=DB::table('clients')->where('client_id', base64_decode($request->client_id))->first();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 30)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('clients.clients_edit',compact('clients_details','GetCustomFields','GetCustomFieldTypes'));
    }

    public function clients_view(Request $request){
        $clients_details=DB::table('clients')->where('client_id', base64_decode($request->client_id))->first();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 30)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('clients.clients_view',compact('clients_details','GetCustomFields','GetCustomFieldTypes'));
        
    }
    
    public function clients_modal_view(Request $request)
    {
        $clients_details=DB::table('clients')->where('client_id',$request->client_id)->first();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 30)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $model ='
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Client Name :</b>
                       <p>'.$clients_details->client_name.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Company Name :</b>
                       <p>'.$clients_details->company_name.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Mobile Number :</b>
                       <p>'.$clients_details->mobile_number.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Alter Mobile Number :</b>
                       <p>'.$clients_details->alter_mobile_number.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Email Id :</b>
                       <p>'.$clients_details->email_id.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Alter Email :</b>
                       <p>'.$clients_details->alter_email_id.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Gst Number :</b>
                       <p>'.$clients_details->gst_number.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b> Address :</b>
                       <p>'.$clients_details->address.'</p>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-12">
               <div class="form-group">
                  <center>
                  <a href="'.route('clients_view',base64_encode($clients_details->client_id)).'" target="_blank">
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
