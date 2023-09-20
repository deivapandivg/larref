<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use Redirect, Response, Session;

class LeadsUploadsController extends Controller
{    
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function lead_create_todo($lead_id)
    {
        $lead=DB::table('leads')->where('lead_id',$lead_id)->first();
        $user_id=Auth::user()->id; 
        $data=array(
            'todo_for'=>'lead',
            'todo_for_id'=>$lead->lead_id,
            'user_id'=>$lead->lead_owner,
            'name'=>$lead->first_name,
            'mobile_number'=>$lead->mobile_number,
            'email'=>$lead->email_id,
            'language_id'=>$lead->langauge_id,
            'created_by'=>$user_id,
            'created_at'=>Now()
        );
        $todo_id=DB::table('to_do')->insertGetId($data);
        // dd($todo_id);
    }
   
    public function leads_import_file_fetch(Request $request)
    {
        if(isset($request->selectData))
        {
            $selectColumn=$request->selectData;
        }
        else
        {
            $selectColumn='';
        }
        
        $name = $_FILES['file']['name'];
        $filename = explode('.', $name);

        if($filename[1] == 'csv')
        {
            $fileopen = fopen($_FILES['file']['tmp_name'],'r');
            return $getdata = fgetcsv($fileopen);
            // foreach ($getdata as $key => $value) {
            //     if($selectColumn==trim($value)){$Selected='Selected';}else{$Selected="";}
            //     echo $options='<option value="'.$key.'" '.$Selected.'>'.$value.'</option>';
            // }
        }
        else
        {
            echo $toaster='<script type="text/javascript">
                swal("Invalid File Type");
            </script>';
        }
    }

    public function import_submit(Request $request){
        // $name = $_FILES['image']['name'];
        // $filename = explode('.', $name);
        $FirstNameIndex=$request->FirstName;
        // $LastNameIndex=$request->LastName;
        $MobileNumberIndex=$request->MobileNumber;
        $AlterMobileNumberIndex=$request->AlterMobileNumber;
        $EmailIdIndex=$request->EmailId;
        $AlterEmailIdIndex=$request->AlterEmailId;
        $AgeIndex=$request->Age;
        $ProductIndex=$request->Product;
        $CountryNameIndex=$request->CountryName;
        $StateNameIndex=$request->StateName;
        $CityNameIndex=$request->CityName;
        $AddressIndex=$request->Address;
        $DescriptionIndex=$request->Description;
        // $LanguageIndex=$request->Language;
        $CreateTodo=$request->create_todo;
        $UserIdArry=$request->UserIdArry;
        $MediumId=$request->MediumId;
        $SourceId=$request->SourceId;
        $SubSourceid=$request->SubSourceid;
        $CampaignId=$request->CampaignId;
        $LeadStage=$request->LeadStage;
        $LeadSubStage=$request->LeadSubStage;
        $UserId=Auth::id();
        if(explode(".",$_FILES['image']['name'])[1] == 'csv')
        {
            $user_index=0;
            $file = fopen($_FILES['image']['tmp_name'],'r');
            $data_counter=1;
            while($data_row=fgetcsv($file))
            {
                if($data_counter>1)
                {
                    // Lead Owner Round Robin Setup
                    $lead_owner=$UserIdArry[$user_index];
                    if($user_index+1==count($UserIdArry))
                    {
                        $user_index=0;
                    }
                    else
                    {
                        $user_index++;
                    }
                    // Lead Owner Round Robin Setup
                    $FirstName=$data_row[$FirstNameIndex];
                    // $LastName=$data_row[$LastNameIndex];
                    $AlterMobileNumber=$data_row[$AlterMobileNumberIndex];
                    $MobileNumber=$data_row[$MobileNumberIndex];
                    $EmailId=$data_row[$EmailIdIndex];
                    $AlterEmailId=$data_row[$AlterEmailIdIndex];
                    $Age=$data_row[$AgeIndex];
                    $Product=$data_row[$ProductIndex];
                    $CountryName=$data_row[$CountryNameIndex];
                    $StateName=$data_row[$StateNameIndex];
                    $CityName=$data_row[$CityNameIndex];
                    $Address=$data_row[$AddressIndex];
                    $Description=$data_row[$DescriptionIndex];
                    // $Language=$data_row[$LanguageIndex];
                    // City Id Country Id State Id Get From City Name
                    $city=DB::table('cities')->where('city_name',$CityName)->first();
                    if($city)
                    {
                        $CityId=$city->city_id;
                        $StateId=$city->state_id;
                        $CountryId=$city->country_id;
                    }
                    else
                    {
                        $CityId=$StateId=$CountryId=0;
                    }
                    // Get Language Id From Language Name
                    // $languages=DB::table('languages')->where('language_name',$Language)->first();
                    // if($languages)
                    // {
                    //     $LanguageId=$languages->language_id;
                    // }
                    // else
                    // {
                    //     $LanguageId=0;
                    // }


                    // Get Product Id From Product Name
                    $products=DB::table('products')->where('product_name',$Product)->first();
                    if($products)
                    {
                        $ProductId=$products->product_id;
                    }
                    else
                    {
                        $ProductId=0;
                    }
                    $data=array('lead_name'=>$FirstName,'mobile_number'=>$MobileNumber,'alter_mobile_number'=>$AlterMobileNumber,'email_id'=>$EmailId,'alter_email_id'=>$AlterEmailId,'age'=>$Age,'lead_stage_id'=>$LeadStage,'lead_sub_stage_id'=>$LeadSubStage,'medium_id'=>$MediumId,'source_id'=>$SubSourceid,'sub_source_id'=>$SubSourceid,'campaign_id'=>$CampaignId,'lead_owner'=>$lead_owner,'product_id'=>$ProductId,'state_id'=>$StateId,'city_id'=>$CityId,'address'=>$Address,'created_by'=>$UserId,'created_at'=>now());
                    $lead_upload_id=DB::table('leads')->insertGetId($data);
                    if($CreateTodo=="Yes" AND $lead_owner!=0)
                    {
                        // Create Todo
                        $this->lead_create_todo($lead_upload_id);
                    }
                }
                $data_counter++;
            }
            fclose($file);
        }
        else
        {
            echo $toaster='<script type="text/javascript">
            swal("Invalid File Type");
            </script>';
        }

        return redirect('leads');
    }
    
}
