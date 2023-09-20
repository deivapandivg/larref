<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use PDF;
use Redirect, Response, Session;
use DataTables;

class ProformaInvoiceController extends Controller
{
    public function __construct()
    {

      $this->middleware('auth');

    }

    public function proforma_invoices(Request $request){

        if ($request->ajax()) {
            
            $data = DB::table('proforma_invoices as a')->where('a.deleted','No')->select(['a.proforma_invoice_id',
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

                $btn ='<a href="'.route('proforma_invoices_view',base64_encode($row->proforma_invoice_id)).'" class="vg-btn-ssp-success view_model_btn text-center" data-toggle="tooltip" data-placement="right" title="View" data-original-title="View"><i class="fa fa-eye  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a href="'.route('proforma_invoices_edit',base64_encode($row->proforma_invoice_id)).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a target="_blank" href="'.route('proforma_invoices_pdf',base64_encode($row->proforma_invoice_id)).'" class="vg-btn-ssp-warning text-center" data-toggle="tooltip" data-placement="right" title="PDF" data-original-title="PDF"><i class="fa fa-solid fa-file-pdf"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="Delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('proforma_invoices.proforma_invoices');
    }

    public function proforma_invoices_add(Request $request){

        $clients = DB::table('clients')->where('deleted', 'No')->get();
        $products = DB::table('products')->where('deleted', 'No')->get();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 34)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('proforma_invoices.proforma_invoices_add',compact('clients','products','GetCustomFields','GetCustomFieldTypes'));
    }

    public function proforma_invoices_edit(Request $request){
        $proforma_invoice_edits=DB::table('proforma_invoice_items')->where('proforma_invoiceId', base64_decode($request->proforma_invoice_id))->select('*')->get();
        $proforma_invoices=DB::table('proforma_invoices')->where('proforma_invoice_id', base64_decode($request->proforma_invoice_id))->select('*')->get();
        $clients = DB::table('clients')->where('deleted', 'No')->get();
        $products = DB::table('products')->where('deleted', 'No')->get();
        $services = DB::table('services')->where('deleted', 'No')->get();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 34)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $proforma_invoice_details=DB::table('proforma_invoices')->where('proforma_invoice_id', base64_decode($request->proforma_invoice_id))->first();
        return view('proforma_invoices.proforma_invoices_edit',compact('proforma_invoice_edits','proforma_invoices','clients','products','services','proforma_invoice_details','GetCustomFields','GetCustomFieldTypes'));
    }

    public function proforma_invoices_view(Request $request){
        $proforma_invoice_view=DB::table('proforma_invoice_items')->where('proforma_invoiceId', base64_decode($request->proforma_invoice_id))->select('*')->first();
        $proforma_invoice_items=DB::table('proforma_invoice_items')->where('proforma_invoiceId',base64_decode($request->proforma_invoice_id))->get();
        $proforma_invoices=DB::table('proforma_invoices')->where('proforma_invoice_id', base64_decode($request->proforma_invoice_id))->select('*')->first();
        $clients = DB::table('clients')->where('client_id', $proforma_invoices->client_id)->get();
        $products = DB::table('products')->where('deleted', 'No')->get();
        $services = DB::table('services')->where('deleted', 'No')->get();
        $GetCustomFields=DB::table('custom_fields')->where('field_page', 34)->where('deleted', 'No')->get();
        $GetCustomFieldTypes=DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $proforma_invoice_details=DB::table('proforma_invoices')->where('proforma_invoice_id', base64_decode($request->proforma_invoice_id))->first();
        return view('proforma_invoices.proforma_invoices_view',compact('proforma_invoices','clients','products','proforma_invoice_view','proforma_invoice_items','services','proforma_invoice_details','GetCustomFields','GetCustomFieldTypes'));
    }

    public function proforma_invoices_submit(Request $request){

        $user_id = Auth::id();

        if(isset($request->proforma_invoice_id))
        {
            if($request->Product!=''){
                $proforma_invoiceItemsArr=array();
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
                    $proforma_invoiceItemsArr[]=array("Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription);
                  
                }
              $proforma_invoiceItems=json_encode($proforma_invoiceItemsArr);
              $CustomFieldValuesArr=array();

                $AttachmentsFolder="public/uploads/Proforma_Invoices/";
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
                'proforma_invoice_items' => $proforma_invoiceItems,
                'custom_fields'=> $CustomFieldJsonValue,
                'updated_by' => $user_id, 
                'updated_at' => Now()
             );

             $proforma_invoice_update=DB::table('proforma_invoices')->where('proforma_invoice_id',$request->proforma_invoice_id)->update($data);

                if(isset($proforma_invoice_update))
                 {
                    $proforma_invoice_product_items=DB::table('proforma_invoice_items')->where('proforma_invoiceId',$request->proforma_invoice_id)->get();
                  foreach($proforma_invoice_product_items as $proforma_invoice_product_item){
                    if($proforma_invoice_product_item->Product!=''){
                    $proforma_invoice_service_items_delete=DB::table('proforma_invoice_items')->where('proforma_invoiceId',$request->proforma_invoice_id)->delete();
                    }
                  }
                  $proforma_invoiceIdNew=DB::table('proforma_invoices')->select('*')->where('proforma_invoice_id',$request->proforma_invoice_id)->first();
                  $proforma_invoice_item_id=$proforma_invoiceIdNew->proforma_invoice_id;
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
                        $proforma_invoiceItems=array("proforma_invoiceId" => $proforma_invoice_item_id,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $user_id, 'created_at' => Now());
                        $proforma_invoice_items_update=DB::table('proforma_invoice_items')->insert($proforma_invoiceItems);
                    }
                }
            }
            if($request->Service!=''){
                $proforma_invoiceItemsServiceArr=array();
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
                  $proforma_invoiceItemsServiceArr[]=array("Service"=>$Service, "Cost"=>$ServiceCost, "Quantity"=>$ServiceQuantity, "GST"=>$ServiceGST, "IGST"=>$ServiceIGST, "CGST"=>$ServiceCGST, "SGST"=>$ServiceSGST, "Amount"=>$ServiceAmount, "ServiceDescription"=>$ServiceDescription);
                  
                }
              $proforma_invoiceServiceItems=json_encode($proforma_invoiceItemsServiceArr);
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
                'proforma_invoice_service_items' => $proforma_invoiceServiceItems,
                'updated_by' => $user_id, 
                'updated_at' => Now()
             );

             $proforma_invoice_service_update=DB::table('proforma_invoices')->where('proforma_invoice_id',$request->proforma_invoice_id)->update($data);
             if(isset($proforma_invoice_service_update))
                 {
                  $proforma_invoice_service_items=DB::table('proforma_invoice_items')->where('proforma_invoiceId',$request->proforma_invoice_id)->get();
                  foreach($proforma_invoice_service_items as $proforma_invoice_service_item){
                    if($proforma_invoice_service_item->Service!=''){
                    $proforma_invoice_service_items_delete=DB::table('proforma_invoice_items')->where('proforma_invoiceId',$request->proforma_invoice_id)->delete();
                    }
                  }
                  $proforma_invoiceIdNew=DB::table('proforma_invoices')->select('*')->where('proforma_invoice_id',$request->proforma_invoice_id)->first();
                  $proforma_invoice_item_service_id=$proforma_invoiceIdNew->proforma_invoice_id;
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
                        $proforma_invoiceItems=array("proforma_invoiceId" => $proforma_invoice_item_service_id,"Service"=>$Service, "Cost"=>$ServiceCost, "Quantity"=>$ServiceQuantity, "GST"=>$ServiceGST, "IGST"=>$ServiceIGST, "CGST"=>$ServiceCGST, "SGST"=>$ServiceSGST, "Amount"=>$ServiceAmount, "ServiceDescription"=>$ServiceDescription,'created_by' => $user_id, 'created_at' => Now());
                        $proforma_invoice_items_update=DB::table('proforma_invoice_items')->insert($proforma_invoiceItems);
                    }
                }
            }

        }
        else
        {
            
            if($request->Product!=''){
                $proforma_invoiceItemsArr=array();
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
                  $proforma_invoiceItemsArr[]=array("Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription);
                  
                }
                $proforma_invoiceItems=json_encode($proforma_invoiceItemsArr);
                
            }
            if(isset($proforma_invoiceItems)){
                $proforma_invoiceItems;
            }
            else{
                $proforma_invoiceItems='';
            }
            if($request->Service!=''){
                $proforma_invoiceItemsServiceArr=array();
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
                    $proforma_invoiceServiceItems=array("Service"=>$Service, "Cost"=>$ServiceCost, "Quantity"=>$ServiceQuantity, "GST"=>$ServiceGST, "IGST"=>$ServiceIGST, "CGST"=>$ServiceCGST, "SGST"=>$ServiceSGST, "Amount"=>$ServiceAmount, "ServiceDescription"=>$ServiceDescription);
                  
                }
                $proforma_invoiceServiceItems=json_encode($proforma_invoiceItemsServiceArr);
            }
            if(isset($proforma_invoiceServiceItems)){
                $proforma_invoiceServiceItems;
            }
            else{
                $proforma_invoiceServiceItems='';
            }
            $CustomFieldValuesArr=array();
            $AttachmentsFolder="public/uploads/Proforma_Invoices/";
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
                'proforma_invoice_items' => $proforma_invoiceItems,
                'proforma_invoice_service_items' => $proforma_invoiceServiceItems,
                'custom_fields' => $CustomFieldJsonValue,
                'created_by' => $user_id, 
                'created_at' => Now()
            );

            $proforma_invoice_product_service_add=DB::table('proforma_invoices')->insertGetId($data);
             if($proforma_invoice_product_service_add)
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
                        $proforma_invoiceItems=array("proforma_invoiceId" => $proforma_invoice_product_service_add,"Product"=>$Product, "Cost"=>$Cost, "Quantity"=>$Quantity, "GST"=>$GST, "IGST"=>$IGST, "CGST"=>$CGST, "SGST"=>$SGST, "Amount"=>$Amount, "ProductDescription"=>$ProductDescription,'created_by' => $user_id, 'created_at' => Now());
                        $proforma_invoice_items_add=DB::table('proforma_invoice_items')->insert($proforma_invoiceItems);
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
                        $proforma_invoiceServiceItems=array("proforma_invoiceId" => $proforma_invoice_product_service_add,"Service"=>$Service, "Cost"=>$ServiceCost, "Quantity"=>$ServiceQuantity, "GST"=>$ServiceGST, "IGST"=>$ServiceIGST, "CGST"=>$ServiceCGST, "SGST"=>$ServiceSGST, "Amount"=>$ServiceAmount, "ServiceDescription"=>$ServiceDescription,'created_by' => $user_id, 'created_at' => Now());
                        $proforma_invoice_items_add=DB::table('proforma_invoice_items')->insert($proforma_invoiceServiceItems);
                    }
                }
            }
        }
        return redirect('proforma_invoices');
    }

    public function proforma_invoices_pdf(Request $request) {
        $app_account = DB::table('application_accounts')->where('deleted', 'No')->get();
        
        $products = DB::table('products')->where('deleted', 'No')->get();
        $services = DB::table('services')->where('deleted', 'No')->get();
        $proforma_invoice_view=DB::table('proforma_invoice_items')->where('proforma_invoiceId', base64_decode($request->proforma_invoice_id))->select('*')->first();
        $proforma_invoice_items=DB::table('proforma_invoice_items')->where('proforma_invoiceId',base64_decode($request->proforma_invoice_id))->get();
        $proforma_invoices=DB::table('proforma_invoices')->where('proforma_invoice_id', base64_decode($request->proforma_invoice_id))->select('*')->first();
        $clients = DB::table('clients')->where('client_id', $proforma_invoices->client_id)->first();

        $show = DB::table('proforma_invoice_items')->where('proforma_invoiceId',$request->proforma_invoiceId);
        $path = public_path('mainlogo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $pic = 'data:image/' .$type . ';base64,' . base64_encode($data);
        $pdf = PDF::loadView('proforma_invoices.proforma_invoices_download',compact('clients','proforma_invoice_view','proforma_invoices','proforma_invoice_items','products','app_account','pic','services'))->setOptions(['defaultFont' => 'sans-serif','isHtml5ParserEnabled' => true,'isRemoteEnabled' => true]);
        
        return $pdf->stream('proforma_invoice.pdf');
    }

    public function proforma_invoice_send_mail(Request $request){
    $proforma_invoices=DB::table('proforma_invoices')->where('proforma_invoice_id', base64_decode($request->proforma_invoiceId))->select('*')->first();
    $clients = DB::table('clients')->where('client_id', $proforma_invoices->client_id)->first();
    $download_url= route('proforma_invoices_pdf', base64_encode($proforma_invoices->proforma_invoice_id));
    
    $SendMailController=new SendMailController();
    $data = array('proforma_invoice_id'=>$proforma_invoices->proforma_invoice_id,'to_mail_id' =>'deivavingreen@gmail.com','client_name'=> $clients->client_name,'date_issue' => $proforma_invoices->date_issue,'grand_total'=>$proforma_invoices->grand_total,
            'subject' => 'subject','download_url'=>$download_url
            );
    $send_mail=$SendMailController->send_mail("proforma_invoice",$data);

    return redirect('proforma_invoices');
    }
}