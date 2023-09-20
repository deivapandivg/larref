<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use PDF;
use Redirect, Response, Session;
use DataTables;

class QuotationsController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function quotations(Request $request){
        if ($request->ajax()) {
            $data = DB::table('quotations as a')->where('a.deleted','No')->select(['a.quotation_id',
                'd.client_name',
                'a.grand_total',
                'a.date_issue',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('clients as d', 'd.client_id', '=', 'a.client_id');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<a href="'.route('quotations_view',base64_encode($row->quotation_id)).'" class="vg-btn-ssp-success view_model_btn text-center" data-toggle="tooltip" data-placement="right" title="View" data-original-title="View"><i class="fa fa-eye text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a href="'.route('quotations_edit',base64_encode($row->quotation_id)).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a target="_blank" href="'.route('quotations_pdf',base64_encode($row->quotation_id)).'" class="vg-btn-ssp-warning text-center" data-toggle="tooltip" data-placement="right" title="PDF" data-original-title="PDF"><i class="fa fa-solid fa-file-pdf"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="Delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('quotations.quotations');
    }

    public function quotations_add(Request $request){

        $clients = DB::table('clients')->where('deleted', 'No')->get();
        $products = DB::table('products')->where('deleted', 'No')->get();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 31)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('quotations.quotations_add',compact('clients','products','GetCustomFields','GetCustomFieldTypes'));
    }

    public function quotations_edit(Request $request){
        
        $quotation_edits=DB::table('quotation_items')->where('QuotationId', base64_decode($request->quotation_id))->select('*')->get();
        $quotations=DB::table('quotations')->where('quotation_id', base64_decode($request->quotation_id))->select('*')->get();
        $clients = DB::table('clients')->where('deleted', 'No')->get();
        $products = DB::table('products')->where('deleted', 'No')->get();
        $services = DB::table('services')->where('deleted', 'No')->get();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 31)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $quotation_details=DB::table('quotations')->where('quotation_id', base64_decode($request->quotation_id))->first();
        return view('quotations.quotations_edit',compact('quotation_edits','quotations','clients','products', 'services','GetCustomFields','GetCustomFieldTypes','quotation_details'));
    }

    public function quotations_view(Request $request){
        $quotation_view=DB::table('quotation_items')->where('QuotationId', base64_decode($request->quotation_id))->select('*')->first();
        $quotation_items=DB::table('quotation_items')->where('QuotationId',base64_decode($request->quotation_id))->get();
        $quotations=DB::table('quotations')->where('quotation_id', base64_decode($request->quotation_id))->select('*')->first();
        $clients = DB::table('clients')->where('client_id', $quotations->client_id)->get();
        $products = DB::table('products')->where('deleted', 'No')->get();
        $services = DB::table('services')->where('deleted', 'No')->get();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 31)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $quotation_details=DB::table('quotations')->where('quotation_id', base64_decode($request->quotation_id))->first();
        return view('quotations.quotations_view',compact('quotations','clients','products','quotation_view','quotation_items', 'services','GetCustomFields','GetCustomFieldTypes','quotation_details'));
    }

    public function quotations_submit(Request $request){

        $user_id = Auth::id();

        if(isset($request->quotation_id))
        {
            if(isset($request->Product)){
                $quotationItemsArr=array();
                for($i=0; $i < count($request->Product); $i++) {
                    $Product = $request->Product[$i];
                    $Cost = $request->Cost[$i];
                    $Quantity = $request->Quantity[$i];
                    $GST = $request->GST[$i];
                    $IGST = $request->IGST[$i];
                    $CGST = $request->CGST[$i];
                    $SGST = $request->SGST[$i];
                    $Amount = $request->Amount[$i];
                    $ProductDescription = $request->ProductDescription[$i];
                    $quotationItemsArr[] = array("Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription);

                }
                $quotationItems=json_encode($quotationItemsArr);
                $CustomFieldValuesArr=array();

                $AttachmentsFolder="public/uploads/Quotations/";
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
                    'client_id' => $request->client_id,
                    'date_issue' => $request->qualification_date,
                    'date_due' => $request->valid_date,
                    'igst_type' => $request->GSTType,
                    'total_amount' => $request->TotalAmount,
                    'total_igst' => $request->TotalIGST,
                    'total_cgst' => $request->TotalCGST,
                    'total_sgst' => $request->TotalSGST,
                    'grand_total' => $request->GrandTotal,
                    'quotation_items' => $quotationItems,
                    'custom_fields'=> $CustomFieldJsonValue,
                    'updated_by' => $user_id, 
                    'updated_at' => Now()
                );

                $quotation_update=DB::table('quotations')->where('quotation_id',$request->quotation_id)->update($data);
                // dd($quotation_update);
                

                if(isset($quotation_update))
                {
                    $quotation_product_items=DB::table('quotation_items')->where('quotationId',$request->quotation_id)->get();
                    foreach($quotation_product_items as $quotation_product_item){
                        if($quotation_product_item->Product!=''){
                            $quotation_service_items_delete=DB::table('quotation_items')->where('quotationId',$request->quotation_id)->delete();
                        }
                    }
                    $quotationIdNew=DB::table('quotations')->select('*')->where('quotation_id',$request->quotation_id)->first();
                    $quotation_item_id=$quotationIdNew->quotation_id;
                    for($i=0; $i < count($request->Product); $i++) {
                        if($request->Quantity[$i]>0){
                        $Product=$request->Product[$i];
                        $Cost=$request->Cost[$i];
                        $Quantity=$request->Quantity[$i];
                        $GST=$request->GST[$i];
                        $IGST=$request->IGST[$i];
                        $CGST=$request->CGST[$i];
                        $SGST=$request->SGST[$i];
                        $Amount=$request->Amount[$i];
                        $ProductDescription=$request->ProductDescription[$i];
                        $quotationItems=array("quotationId" => $quotation_item_id,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $user_id, 'created_at' => Now());
                        $quotation_items_update=DB::table('quotation_items')->insert($quotationItems);
                        }
                    }
                }
            }
            if($request->Service!=''){
                $quotationItemsServiceArr=array();
                for($i=0; $i < count($request->Service); $i++) {
                    $Service=$request->Service[$i];
                    $ServiceCost=$request->ServiceCost[$i];
                    $ServiceQuantity=$request->ServiceQuantity[$i];
                    $ServiceGST=$request->ServiceGST[$i];
                    $ServiceIGST=$request->ServiceIGST[$i];
                    $ServiceCGST=$request->ServiceCGST[$i];
                    $ServiceSGST=$request->ServiceSGST[$i];
                    $ServiceAmount=$request->ServiceAmount[$i];
                    $ServiceDescription=$request->ServiceDescription[$i];
                    $quotationItemsServiceArr[]=array("Service"=>$Service, "Cost"=>$ServiceCost, "Quantity"=>$ServiceQuantity, "GST"=>$ServiceGST, "IGST"=>$ServiceIGST, "CGST"=>$ServiceCGST, "SGST"=>$ServiceSGST, "Amount"=>$ServiceAmount, "ServiceDescription"=>$ServiceDescription);

                }
                $quotationServiceItems=json_encode($quotationItemsServiceArr);
                $data = array( 
                    'client_id' => $request->client_id,
                    'date_issue' => $request->qualification_date,
                    'date_due' => $request->valid_date,
                    'igst_type' => $request->GSTType,
                    'total_amount' => $request->TotalAmount,
                    'total_igst' => $request->TotalIGST,
                    'total_cgst' => $request->TotalCGST,
                    'total_sgst' => $request->TotalSGST,
                    'grand_total' => $request->GrandTotal,
                    'quotation_service_items' => $quotationServiceItems,
                    'updated_by' => $user_id, 
                    'updated_at' => Now()
                );

                $quotation_service_update=DB::table('quotations')->where('quotation_id',$request->quotation_id)->update($data);
                if(isset($quotation_service_update))
                {
                    $quotation_service_items=DB::table('quotation_items')->where('quotationId',$request->quotation_id)->get();
                    foreach($quotation_service_items as $quotation_service_item){
                        if($quotation_service_item->Service!=''){
                            $quotation_service_items_delete=DB::table('quotation_items')->where('quotationId',$request->quotation_id)->delete();
                        }
                    }
                    $quotationIdNew=DB::table('quotations')->select('*')->where('quotation_id',$request->quotation_id)->first();
                    $quotation_item_service_id=$quotationIdNew->quotation_id;
                    for($i=0; $i < count($request->Service); $i++) {
                        $Service=$request->Service[$i];
                        $ServiceCost=$request->ServiceCost[$i];
                        $ServiceQuantity=$request->ServiceQuantity[$i];
                        $ServiceGST=$request->ServiceGST[$i];
                        $ServiceIGST=$request->ServiceIGST[$i];
                        $ServiceCGST=$request->ServiceCGST[$i];
                        $ServiceSGST=$request->ServiceSGST[$i];
                        $ServiceAmount=$request->ServiceAmount[$i];
                        $ServiceDescription=$request->ServiceDescription[$i];
                        $quotationItems=array("quotationId" => $quotation_item_service_id,"Service"=>$Service, "Cost"=>$ServiceCost, "Quantity"=>$ServiceQuantity, "GST"=>$ServiceGST, "IGST"=>$ServiceIGST, "CGST"=>$ServiceCGST, "SGST"=>$ServiceSGST, "Amount"=>$ServiceAmount, "ServiceDescription"=>$ServiceDescription,'created_by' => $user_id, 'created_at' => Now());
                        $quotation_items_update=DB::table('quotation_items')->insert($quotationItems);
                    }
                }
            }
        }
        else
        {
            
            if($request->Product!=''){
                $quotationItemsArr=array();
                for($i=0; $i < count($request->Product); $i++) {
                    $Product=$request->Product[$i];
                    $Cost=$request->Cost[$i];
                    $Quantity=$request->Quantity[$i];
                    $GST=$request->GST[$i];
                    $IGST=$request->IGST[$i];
                    $CGST=$request->CGST[$i];
                    $SGST=$request->SGST[$i];
                    $Amount=$request->Amount[$i];
                  $ProductDescription=$request->ProductDescription[$i];
                  $quotationItemsArr[]=array("Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription);
                  
                }
                $quotationItems=json_encode($quotationItemsArr);
                
            }
            if(isset($quotationItems)){
                $quotationItems;
            }
            else{
                $quotationItems='';

            }
            if($request->Service!=''){

                $quotationItemsServiceArr=array();
                for($i=0; $i < count($request->Service); $i++) {
                    $Service=$request->Service[$i];
                    $ServiceCost=$request->ServiceCost[$i];
                    $ServiceQuantity=$request->ServiceQuantity[$i];
                    $ServiceGST=$request->ServiceGST[$i];
                    $ServiceIGST=$request->ServiceIGST[$i];
                    $ServiceCGST=$request->ServiceCGST[$i];
                    $ServiceSGST=$request->ServiceSGST[$i];
                    $ServiceAmount=$request->ServiceAmount[$i];
                    $ServiceDescription=$request->ServiceDescription[$i];
                    $quotationItemsServiceArr[]=array("Service"=>$Service, "Cost"=>$ServiceCost, "Quantity"=>$ServiceQuantity, "GST"=>$ServiceGST, "IGST"=>$ServiceIGST, "CGST"=>$ServiceCGST, "SGST"=>$ServiceSGST, "Amount"=>$ServiceAmount, "ServiceDescription"=>$ServiceDescription);

                }
                $quotationServiceItems=json_encode($quotationItemsServiceArr);
                
            }
            if(isset($quotationServiceItems)){
                $quotationServiceItems;
            }
            else{
                $quotationServiceItems='';

            }
            $CustomFieldValuesArr=array();
            $AttachmentsFolder="public/uploads/Quotations/";
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
                'client_id' => $request->client_id,
                'date_issue' => $request->qualification_date,
                'date_due' => $request->valid_date,
                'igst_type' => $request->GSTType,
                'total_amount' => $request->TotalAmount,
                'total_igst' => $request->TotalIGST,
                'total_cgst' => $request->TotalCGST,
                'total_sgst' => $request->TotalSGST,
                'grand_total' => $request->GrandTotal,
                'quotation_items' => $quotationItems,
                'quotation_service_items' => $quotationServiceItems,
                'custom_fields' => $CustomFieldJsonValue,
                'created_by' => $user_id, 
                'created_at' => Now()
            );
            // dd($data);
            $quotation_product_service_add=DB::table('quotations')->insertGetId($data);
             if($quotation_product_service_add)
             {
                if($request->Product!='')
                {
                    for($i=0; $i < count($request->Product); $i++) {
                        $Product=$request->Product[$i];
                        $Cost=$request->Cost[$i];
                        $Quantity=$request->Quantity[$i];
                        $GST=$request->GST[$i];
                        $IGST=$request->IGST[$i];
                        $CGST=$request->CGST[$i];
                        $SGST=$request->SGST[$i];
                        $Amount=$request->Amount[$i];
                        $ProductDescription=$request->ProductDescription[$i];
                        $quotationItems=array("quotationId" => $quotation_product_service_add,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $user_id, 'created_at' => Now());
                        $quotation_items_add=DB::table('quotation_items')->insert($quotationItems);
                    }
                }
                if($request->Service!='')
                {
                    for($i=0; $i < count($request->Service); $i++) {
                        $Service=$request->Service[$i];
                        $ServiceCost=$request->ServiceCost[$i];
                        $ServiceQuantity=$request->ServiceQuantity[$i];
                        $ServiceGST=$request->ServiceGST[$i];
                        $ServiceIGST=$request->ServiceIGST[$i];
                        $ServiceCGST=$request->ServiceCGST[$i];
                        $ServiceSGST=$request->ServiceSGST[$i];
                        $ServiceAmount=$request->ServiceAmount[$i];
                        $ServiceDescription=$request->ServiceDescription[$i];
                        $quotationServiceItems=array("quotationId" => $quotation_product_service_add,"Service"=>$Service, "Cost"=>$ServiceCost, "Quantity"=>$ServiceQuantity, "GST"=>$ServiceGST, "IGST"=>$ServiceIGST, "CGST"=>$ServiceCGST, "SGST"=>$ServiceSGST, "Amount"=>$ServiceAmount, "ServiceDescription"=>$ServiceDescription,'created_by' => $user_id, 'created_at' => Now());
                        $quotation_items_add=DB::table('quotation_items')->insert($quotationServiceItems);
                    }
                }
            }
        }
        return redirect('quotations');
    }

    public function quotations_pdf(Request $request) {
        $app_account = DB::table('application_accounts')->where('deleted', 'No')->get();
        $quotation_view=DB::table('quotation_items')->where('QuotationId', base64_decode($request->quotation_id))->select('*')->first();
        $quotation_items=DB::table('quotation_items')->where('QuotationId',base64_decode($request->quotation_id))->get();
        $quotations=DB::table('quotations')->where('quotation_id', base64_decode($request->quotation_id))->select('*')->first();
        $clients = DB::table('clients')->where('client_id', $quotations->client_id)->first();
        $products = DB::table('products')->where('deleted', 'No')->get();
        $services = DB::table('services')->where('deleted', 'No')->get();
        $show = DB::table('quotation_items')->where('QuotationId',$request->QuotationId);
        $path = public_path('mainlogo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $pic = 'data:image/' .$type . ';base64,' . base64_encode($data);
        $pdf = PDF::loadView('quotations.quotations_download',compact('clients','quotation_view','quotations','quotation_items','products','app_account','pic','services'))->setOptions(['defaultFont' => 'sans-serif','isHtml5ParserEnabled' => true,'isRemoteEnabled' => true]);
        
        
        return $pdf->stream('quotation.pdf');
    }


    public function quotation_send_mail(Request $request){

    $quotations=DB::table('quotations')->where('quotation_id',base64_decode($request->quotation_id))->select('*')->first();
    $clients = DB::table('clients')->where('client_id', $quotations->client_id)->first();
    $download_url= route('quotations_pdf', base64_encode($quotations->quotation_id));

    $SendMailController=new SendMailController();
    $data = array('quotation_id'=>$quotations->quotation_id,'to_mail_id' =>'deivavingreen@gmail.com','client_name'=> $clients->client_name,'date_issue' => $quotations->date_issue,'grand_total'=>$quotations->grand_total,
            'subject' => 'subject','download_url'=>$download_url
            );
    $send_mail=$SendMailController->send_mail("quotation",$data);

    return redirect('quotations');
    }
}