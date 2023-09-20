<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;
use App\Http\Controllers\SendMailController;
use Illuminate\Support\Facades\Mail;


class EmployeesController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }
   
    public function employees(Request $request){
        if ($request->ajax()) {
            $data = DB::table('users as a')->where('a.deleted','No')->select(['a.id',
                'a.employee_name',
                'a.employee_code',
                'd.shift_name as shift_id',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftJoin('users as b','a.created_by', '=', 'b.id')->leftJoin('users as c','a.created_by', '=', 'c.id')->leftjoin('shifts as d', 'd.shift_id', '=', 'a.shift_id')->leftjoin('shift_timings as e', 'e.shift_timing_id', '=', 'a.shift_timing_id');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<a href="'.route('employees_view',base64_encode($row->id)).'" class="vg-btn-ssp-success view_model_btn text-center" data-toggle="tooltip" data-placement="right" title="view" data-original-title="view"><i class="fa fa-eye  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a href="'.route('employees_edit',base64_encode($row->id)).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('employees.employees');
    }

    public function employees_add(){
        $designation_lists=DB::table('designations')->select('*')->where('deleted', 'No')->get();
        $department_lists=DB::table('departments')->select('*')->where('deleted', 'No')->get();
        $team_lists=DB::table('teams')->select('*')->where('deleted', 'No')->get();
        $reporting_to_lists=DB::table('users')->select('*')->where('deleted', 'No')->get();
        $country_lists=DB::table('countries')->select('*')->where('deleted', '=', 'No')->get();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 2)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $shift_lists = DB::table('shifts')->select(DB::raw('*'))->where('deleted','No')->get();
        return view('employees.employees_add',compact('designation_lists','department_lists','team_lists','reporting_to_lists','country_lists','GetCustomFields','GetCustomFieldTypes','shift_lists'));
    }

    public function employees_submit(Request $request){
         $createdby = Auth::id();
         $updatedby = Auth::id();
         if($profile = $request->file('profile_upload')) {
            $destinationPath = 'public/profile_uploads/';
            $profile_image = date('YmdHis') . "." . $profile->getClientOriginalExtension();
            $profile->move($destinationPath, $profile_image);
         }
         elseif($request->profile_upload=='')
         {
            $profile_image = Auth::user()->profile_upload;
         }

         if ($address_uploads = $request->file('address_upload')) 
         {
            $destinationPath = 'public/address_uploads/';
            $address_upload = date('YmdHis') . "." . $address_uploads->getClientOriginalExtension();
            $address_uploads->move($destinationPath, $address_upload);
         }
         elseif($request->address_upload=='')
         {
            $address_upload = Auth::user()->address_upload;
         }

        if(isset($request->id))
        {

            $CustomFieldValuesArr=array();
            
            $AttachmentsFolder="public/uploads/Employees/";
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
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'employee_code' => $request->employee_code,
            'employee_name' => $request->employee_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'personal_mail_id' => $request->personal_mail_id,
            'personal_mobile_number' => $request->personal_mobile_number,
            'emergency_contact_number' => $request->emergency_contact_number,
            'blood_group' => $request->blood_group,
            'date_of_joining' => $request->date_of_joining,
            'designation_id' => $request->designation_id,
            'department_id' => $request->department_id,
            'team_id' => $request->team_id,
            'reporting_to_id' => $request->reporting_to_id,
            'shift_id' => $request->shift_id,
            'shift_timing_id' => $request->shift_timing_id,
            'profile_upload' => $profile_image,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'pin_code' => $request->pin_code,
            'address' => $request->address,
            'address_upload' => $address_upload,
            'email'=> $request->official_mail_id,
            'custom_fields'=> $CustomFieldJsonValue,
            'updated_by' => $updatedby, 
            'updated_at' => Now());
            $employee_update=DB::table('users')->where('id',$request->id)->update($data);
            
        }
        else
        {
            $CustomFieldValuesArr=array();
         $AttachmentsFolder="public/uploads/Employees/";
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
            if($profile = $request->file('profile_upload')) 
            {
            $destinationPath = 'public/profile_uploads/';
            $profile_image = date('YmdHis') . "." . $profile->getClientOriginalExtension();
            $profile->move($destinationPath, $profile_image);
            }
            $data = array('first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'employee_code' => $request->employee_code,
            'employee_name' => $request->employee_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'personal_mail_id' => $request->personal_mail_id,
            'personal_mobile_number' => $request->personal_mobile_number,
            'emergency_contact_number' => $request->emergency_contact_number,
            'blood_group' => $request->blood_group,
            'date_of_joining' => $request->date_of_joining,
            'designation_id' => $request->designation_id,
            'department_id' => $request->department_id,
            'team_id' => $request->team_id,
            'reporting_to_id' => $request->reporting_to_id,
            'shift_id' => $request->shift_id,
            'shift_timing_id' => $request->shift_timing_id,
            'profile_upload' => $profile_image,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'pin_code' => $request->pin_code,
            'address' => $request->address,
            'address_upload' => $address_upload, 
            'email'=> $request->official_mail_id,
            'custom_fields'=> $CustomFieldJsonValue,
            'created_by' => $createdby, 
            'created_at' => Now());

            $employee_add=DB::table('users')->insert($data);
            $SendMailController=new SendMailController();
            $data = array('first_name'=>$request->first_name,
            'to_mail_id' => $request->personal_mail_id,
            'subject' => 'Welcome Mail'
            );
            $send_mail=$SendMailController->send_mail("Welcome_mail",$data);
        }
        return redirect('employees');
    }

    public function employees_edit(Request $request){
        $user_details=DB::table('users')->where('id', base64_decode($request->id))->first();

        $country_lists=DB::table('countries')->select('*')->get();
        $state_lists=DB::table('states')->select('*')->where('country_id',$user_details->country_id)->get();
        $city_lists=DB::table('cities')->select('*')->where('state_id',$user_details->state_id)->get();


        $designation_lists=DB::table('designations')->select('*')->where('deleted', 'No')->get();
        $department_lists=DB::table('departments')->select('*')->where('deleted', 'No')->get();
        $team_lists=DB::table('teams')->select('*')->where('deleted', 'No')->get();
        $reporting_to_lists=DB::table('users')->select('*')->where('deleted', 'No')->get();
       

        // $city_lists=DB::table('cities')->select('*')->where('state_id', '=', '35')->get();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 2)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $employee_details=DB::table('users')->where('id',base64_decode($request->id))->first();
        $shift_lists = DB::table('shifts')->select(DB::raw('*'))->where('deleted','No')->get();
        $shift_timing_lists = DB::table('shift_timings')->select(DB::raw('*'))->where('deleted','No')->get();
        return view('employees.employees_edit',compact('user_details','designation_lists','department_lists','team_lists','reporting_to_lists','country_lists','state_lists','city_lists','GetCustomFields','GetCustomFieldTypes','employee_details','shift_lists','shift_timing_lists'));
    }

    public function employees_view(Request $request){
        $user_details=DB::table('users')->where('id', base64_decode($request->id))->first();

        $country_lists=DB::table('countries')->where('country_id',$user_details->country_id)->first();
        $state_lists=DB::table('states')->where('state_id',$user_details->state_id)->first();
        $city_lists=DB::table('cities')->where('city_id',$user_details->city_id)->first();


        $designation_lists=DB::table('designations')->where('designation_id', $user_details->designation_id)->first();
        $department_lists=DB::table('departments')->where('department_id', $user_details->department_id)->first();
        $team_lists=DB::table('teams')->where('team_id', $user_details->team_id)->first();
        $reporting_to_lists=DB::table('users')->where('id', $user_details->reporting_to_id)->first();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 2)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $employee_details=DB::table('users')->where('id',base64_decode($request->id))->first();

        return view('employees.employees_view',compact('user_details','designation_lists','department_lists','team_lists','reporting_to_lists','country_lists','state_lists','city_lists','GetCustomFields','GetCustomFieldTypes','employee_details'));
   }

   

}
