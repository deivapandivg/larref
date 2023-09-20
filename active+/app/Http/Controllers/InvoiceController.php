<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use PDF;
use Redirect, Response, Session;
use DataTables;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function invoices(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('invoices as a')->where('a.deleted', 'No')->select([
                'a.invoice_id',
                'd.client_name',
                'a.grand_total',
                'a.date_issue',
                'b.first_name as created_by',
                'a.created_at',
                'c.first_name as updated_by',
                'a.updated_at',
            ])->leftjoin('users as b', 'b.id', '=', 'a.created_by')->leftjoin('users as c', 'c.id', '=', 'a.updated_by')->leftjoin('clients as d', 'd.client_id', '=', 'a.client_id');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('invoices_view', base64_encode($row->invoice_id)) . '" class="vg-btn-ssp-success view_model_btn text-center" data-toggle="tooltip" data-placement="right" title="View" data-original-title="View"><i class="fa fa-eye  text-white text-center"></i></a>';
                    $btn .= '&nbsp;&nbsp;&nbsp;<a href="' . route('invoices_edit', base64_encode($row->invoice_id)) . '" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                    $btn .= '&nbsp;&nbsp;&nbsp;<a target="_blank" href="' . route('invoices_pdf', base64_encode($row->invoice_id)) . '" class="vg-btn-ssp-warning text-center" data-toggle="tooltip" data-placement="right" title="PDF" data-original-title="PDF"><i class="fa fa-solid fa-file-pdf"></i></a>';
                    $btn .= '&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="Delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('invoices.invoices');
    }

    public function invoices_add(Request $request)
    {

        $clients = DB::table('clients')->where('deleted', 'No')->get();
        $products = DB::table('products')->where('deleted', 'No')->get();
        $GetCustomFields = DB::table('custom_fields')->where('field_page', 33)->where('deleted', 'No')->get();
        $GetCustomFieldTypes = DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        return view('invoices.invoices_add', compact('clients', 'products', 'GetCustomFields', 'GetCustomFieldTypes'));
    }

    public function invoices_edit(Request $request)
    {
        $invoice_edits = DB::table('invoice_items')->where('invoiceId', base64_decode($request->invoice_id))->select('*')->get();
        $invoices = DB::table('invoices')->where('invoice_id', base64_decode($request->invoice_id))->select('*')->get();
        $clients = DB::table('clients')->where('deleted', 'No')->get();
        $products = DB::table('products')->where('deleted', 'No')->get();
        $services = DB::table('services')->where('deleted', 'No')->get();
        $GetCustomFields = DB::table('custom_fields')->where('field_page', 33)->where('deleted', 'No')->get();
        $GetCustomFieldTypes = DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $invoice_details = DB::table('invoices')->where('invoice_id', base64_decode($request->invoice_id))->first();
        return view('invoices.invoices_edit', compact('invoice_edits', 'invoices', 'clients', 'products', 'services', 'GetCustomFields', 'GetCustomFieldTypes', 'invoice_details'));
    }

    public function invoices_view(Request $request)
    {
        $invoice_view = DB::table('invoice_items')->where('invoiceId', base64_decode($request->invoice_id))->select('*')->first();
        $invoice_items = DB::table('invoice_items')->where('invoiceId', base64_decode($request->invoice_id))->get();
        $invoices = DB::table('invoices')->where('invoice_id', base64_decode($request->invoice_id))->select('*')->first();
        $clients = DB::table('clients')->where('client_id', $invoices->client_id)->get();
        $products = DB::table('products')->where('deleted', 'No')->get();
        $services = DB::table('services')->where('deleted', 'No')->get();
        $GetCustomFields = DB::table('custom_fields')->where('field_page', 33)->where('deleted', 'No')->get();
        $GetCustomFieldTypes = DB::table('custom_fieldtype')->where('deleted', 'No')->get();
        $invoice_details = DB::table('invoices')->where('invoice_id', base64_decode($request->invoice_id))->first();
        return view('invoices.invoices_view', compact('invoices', 'clients', 'products', 'invoice_view', 'invoice_items', 'services', 'GetCustomFields', 'GetCustomFieldTypes', 'invoice_details'));
    }

    public function invoices_submit(Request $request)
    {

        $user_id = Auth::id();
        if (isset($request->invoice_id)) {
            if ($request->Product != '') {
                $invoiceItemsArr = array();
                for ($i = 0; $i < count($request->Product); $i++) {
                    $Product = $request->Product[$i];
                    $Cost = $request->Cost[$i];
                    $Quantity = $request->Quantity[$i];
                    $GST = $request->GST[$i];
                    $IGST = $request->IGST[$i];
                    $CGST = $request->CGST[$i];
                    $SGST = $request->SGST[$i];
                    $Amount = $request->Amount[$i];
                    $ProductDescription = $request->ProductDescription[$i];
                    $invoiceItemsArr[] = array("Product" => $Product, "Cost" => $Cost, "Quantity" => $Quantity, "GST" => $GST, "IGST" => $IGST, "CGST" => $CGST, "SGST" => $SGST, "Amount" => $Amount, "ProductDescription" => $ProductDescription);
                    $product_details = DB::table('products')->where('product_id', $Product)->first();
                    DB::table('products')->where('product_id', $Product)->update([
                        'quantity' => ($product_details->quantity - $Quantity)
                    ]);
                }
                $invoiceItems = json_encode($invoiceItemsArr);
                $CustomFieldValuesArr = array();

                $AttachmentsFolder = "public/uploads/Invoices/";
                if (!file_exists($AttachmentsFolder)) {
                    mkdir($AttachmentsFolder, 0777, true);
                }
                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'Custom-') !== false) {
                        if (is_array($value)) {
                            $value = implode(",", $value);
                        }
                        $CustomFieldValuesArr += array(str_replace("Custom-", "", $key) => $value);
                    }
                }
                foreach ($_FILES as $key => $value) {
                    if (strpos($key, 'Custom-') !== false) {
                        $FileTempName = $_FILES[$key]['tmp_name'];
                        $FileName = $_FILES[$key]['name'];
                        $FilePath = $AttachmentsFolder . '/' . $FileName;
                        move_uploaded_file($FileTempName, $FilePath);
                        $CustomFieldValuesArr += array(str_replace("Custom-", "", $key) => $FileName);
                    }
                }
                $CustomFieldJsonValue = json_encode($CustomFieldValuesArr);
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
                    'invoice_items' => $invoiceItems,
                    'custom_fields' => $CustomFieldJsonValue,
                    'updated_by' => $user_id,
                    'updated_at' => Now()
                );

                $invoice_update = DB::table('invoices')->where('invoice_id', $request->invoice_id)->update($data);

                if (isset($invoice_update)) {
                    $invoice_product_items = DB::table('invoice_items')->where('invoiceId', $request->invoice_id)->get();
                    foreach ($invoice_product_items as $invoice_product_item) {
                        if ($invoice_product_item->Product != '') {
                            $invoice_service_items_delete = DB::table('invoice_items')->where('invoiceId', $request->invoice_id)->delete();
                        }
                    }
                    $invoiceIdNew = DB::table('invoices')->select('*')->where('invoice_id', $request->invoice_id)->first();
                    $invoice_item_id = $invoiceIdNew->invoice_id;
                    for ($i = 0; $i < count($request->Product); $i++) {
                        $Product = $request->Product[$i];
                        $Cost = $request->Cost[$i];
                        $Quantity = $request->Quantity[$i];
                        $GST = $request->GST[$i];
                        $IGST = $request->IGST[$i];
                        $CGST = $request->CGST[$i];
                        $SGST = $request->SGST[$i];
                        $Amount = $request->Amount[$i];
                        $ProductDescription = $request->ProductDescription[$i];
                        $invoiceItems = array("invoiceId" => $invoice_item_id, "Product" => $Product, "Cost" => $Cost, "Quantity" => $Quantity, "GST" => $GST, "IGST" => $IGST, "CGST" => $CGST, "SGST" => $SGST, "Amount" => $Amount, "ProductDescription" => $ProductDescription, 'created_by' => $user_id, 'created_at' => Now());
                        $invoice_items_update = DB::table('invoice_items')->insert($invoiceItems);
                        $product_details = DB::table('products')->where('product_id', $Product)->first();
                        DB::table('products')->where('product_id', $Product)->update([
                            'quantity' => ($product_details->quantity - $Quantity)
                        ]);
                    }
                }
            }
            if ($request->Service != '') {
                $invoiceItemsServiceArr = array();
                for ($i = 0; $i < count($request->Service); $i++) {
                    $Service = $request->Service[$i];
                    $ServiceCost = $request->ServiceCost[$i];
                    $ServiceQuantity = $request->ServiceQuantity[$i];
                    $ServiceGST = $request->ServiceGST[$i];
                    $ServiceIGST = $request->ServiceIGST[$i];
                    $ServiceCGST = $request->ServiceCGST[$i];
                    $ServiceSGST = $request->ServiceSGST[$i];
                    $ServiceAmount = $request->ServiceAmount[$i];
                    $ServiceDescription = $request->ServiceDescription[$i];
                    $invoiceItemsServiceArr[] = array("Service" => $Service, "Cost" => $ServiceCost, "Quantity" => $ServiceQuantity, "GST" => $ServiceGST, "IGST" => $ServiceIGST, "CGST" => $ServiceCGST, "SGST" => $ServiceSGST, "Amount" => $ServiceAmount, "ServiceDescription" => $ServiceDescription);
                }
                $invoiceServiceItems = json_encode($invoiceItemsServiceArr);
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
                    'invoice_service_items' => $invoiceServiceItems,
                    'updated_by' => $user_id,
                    'updated_at' => Now()
                );

                $invoice_service_update = DB::table('invoices')->where('invoice_id', $request->invoice_id)->update($data);
                if (isset($invoice_service_update)) {
                    $invoice_service_items = DB::table('invoice_items')->where('invoiceId', $request->invoice_id)->get();
                    foreach ($invoice_service_items as $invoice_service_item) {
                        if ($invoice_service_item->Service != '') {
                            $invoice_service_items_delete = DB::table('invoice_items')->where('invoiceId', $request->invoice_id)->delete();
                        }
                    }
                    $invoiceIdNew = DB::table('invoices')->select('*')->where('invoice_id', $request->invoice_id)->first();
                    $invoice_item_service_id = $invoiceIdNew->invoice_id;
                    for ($i = 0; $i < count($request->Service); $i++) {
                        $Service = $request->Service[$i];
                        $ServiceCost = $request->ServiceCost[$i];
                        $ServiceQuantity = $request->ServiceQuantity[$i];
                        $ServiceGST = $request->ServiceGST[$i];
                        $ServiceIGST = $request->ServiceIGST[$i];
                        $ServiceCGST = $request->ServiceCGST[$i];
                        $ServiceSGST = $request->ServiceSGST[$i];
                        $ServiceAmount = $request->ServiceAmount[$i];
                        $ServiceDescription = $request->ServiceDescription[$i];
                        $invoiceItems = array("invoiceId" => $invoice_item_service_id, "Service" => $Service, "Cost" => $ServiceCost, "Quantity" => $ServiceQuantity, "GST" => $ServiceGST, "IGST" => $ServiceIGST, "CGST" => $ServiceCGST, "SGST" => $ServiceSGST, "Amount" => $ServiceAmount, "ServiceDescription" => $ServiceDescription, 'created_by' => $user_id, 'created_at' => Now());
                        $invoice_items_update = DB::table('invoice_items')->insert($invoiceItems);
                    }
                }
            }
        } else {

            if ($request->Product != '') {
                $invoiceItemsArr = array();
                for ($i = 0; $i < count($request->Product); $i++) {
                    $Product = $request->Product[$i];
                    $Cost = $request->Cost[$i];
                    $Quantity = $request->Quantity[$i];
                    $GST = $request->GST[$i];
                    $IGST = $request->IGST[$i];
                    $CGST = $request->CGST[$i];
                    $SGST = $request->SGST[$i];
                    $Amount = $request->Amount[$i];
                    $ProductDescription = $request->ProductDescription[$i];
                    $invoiceItemsArr[] = array("Product" => $Product, "Cost" => $Cost, "Quantity" => $Quantity, "GST" => $GST, "IGST" => $IGST, "CGST" => $CGST, "SGST" => $SGST, "Amount" => $Amount, "ProductDescription" => $ProductDescription);
                }
                $invoiceItems = json_encode($invoiceItemsArr);
            }
            if (isset($invoiceItems)) {
                $invoiceItems;
            } else {
                $invoiceItems = '';
            }
            if ($request->Service != '') {
                $invoiceItemsServiceArr = array();
                for ($i = 0; $i < count($request->Service); $i++) {
                    $Service = $request->Service[$i];
                    $ServiceCost = $request->ServiceCost[$i];
                    $ServiceQuantity = $request->ServiceQuantity[$i];
                    $ServiceGST = $request->ServiceGST[$i];
                    $ServiceIGST = $request->ServiceIGST[$i];
                    $ServiceCGST = $request->ServiceCGST[$i];
                    $ServiceSGST = $request->ServiceSGST[$i];
                    $ServiceAmount = $request->ServiceAmount[$i];
                    $ServiceDescription = $request->ServiceDescription[$i];
                    $invoiceServiceItems = array("Service" => $Service, "Cost" => $ServiceCost, "Quantity" => $ServiceQuantity, "GST" => $ServiceGST, "IGST" => $ServiceIGST, "CGST" => $ServiceCGST, "SGST" => $ServiceSGST, "Amount" => $ServiceAmount, "ServiceDescription" => $ServiceDescription);
                }
                $invoiceServiceItems = json_encode($invoiceItemsServiceArr);
            }
            if (isset($invoiceServiceItems)) {
                $invoiceServiceItems;
            } else {
                $invoiceServiceItems = '';
            }
            $CustomFieldValuesArr = array();
            $AttachmentsFolder = "public/uploads/Invoices/";
            if (!file_exists($AttachmentsFolder)) {
                mkdir($AttachmentsFolder, 0777, true);
            }

            foreach ($_POST as $key => $value) {
                if (strpos($key, 'Custom-') !== false) {
                    if (is_array($value)) {
                        $value = implode(",", $value);
                    }
                    $CustomFieldValuesArr += array(str_replace("Custom-", "", $key) => $value);
                }
            }
            foreach ($_FILES as $key => $value) {
                if (strpos($key, 'Custom-') !== false) {
                    $FileTempName = $_FILES[$key]['tmp_name'];
                    $FileName = $_FILES[$key]['name'];
                    $FilePath = $AttachmentsFolder . '/' . $FileName;
                    move_uploaded_file($FileTempName, $FilePath);
                    $CustomFieldValuesArr += array(str_replace("Custom-", "", $key) => $FileName);
                }
            }
            $CustomFieldJsonValue = json_encode($CustomFieldValuesArr);
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
                'invoice_items' => $invoiceItems,
                'invoice_service_items' => $invoiceServiceItems,
                'custom_fields' => $CustomFieldJsonValue,
                'created_by' => $user_id,
                'created_at' => Now()
            );

            $invoice_product_service_add = DB::table('invoices')->insertGetId($data);
            if ($invoice_product_service_add) {
                if ($request->Product != '') {
                    for ($i = 0; $i < count($request->Product); $i++) {
                        $Product = $request->Product[$i];
                        $Cost = $request->Cost[$i];
                        $Quantity = $request->Quantity[$i];
                        $GST = $request->GST[$i];
                        $IGST = $request->IGST[$i];
                        $CGST = $request->CGST[$i];
                        $SGST = $request->SGST[$i];
                        $Amount = $request->Amount[$i];
                        $ProductDescription = $request->ProductDescription[$i];
                        $invoiceItems = array("invoiceId" => $invoice_product_service_add, "Product" => $Product, "Cost" => $Cost, "Quantity" => $Quantity, "GST" => $GST, "IGST" => $IGST, "CGST" => $CGST, "SGST" => $SGST, "Amount" => $Amount, "ProductDescription" => $ProductDescription, 'created_by' => $user_id, 'created_at' => Now());
                        $invoice_items_add = DB::table('invoice_items')->insert($invoiceItems);
                    }
                }
                if ($request->Service != '') {
                    for ($i = 0; $i < count($request->Service); $i++) {
                        $Service = $request->Service[$i];
                        $ServiceCost = $request->ServiceCost[$i];
                        $ServiceQuantity = $request->ServiceQuantity[$i];
                        $ServiceGST = $request->ServiceGST[$i];
                        $ServiceIGST = $request->ServiceIGST[$i];
                        $ServiceCGST = $request->ServiceCGST[$i];
                        $ServiceSGST = $request->ServiceSGST[$i];
                        $ServiceAmount = $request->ServiceAmount[$i];
                        $ServiceDescription = $request->ServiceDescription[$i];
                        $invoiceServiceItems = array("invoiceId" => $invoice_product_service_add, "Service" => $Service, "Cost" => $ServiceCost, "Quantity" => $ServiceQuantity, "GST" => $ServiceGST, "IGST" => $ServiceIGST, "CGST" => $ServiceCGST, "SGST" => $ServiceSGST, "Amount" => $ServiceAmount, "ServiceDescription" => $ServiceDescription, 'created_by' => $user_id, 'created_at' => Now());
                        $invoice_items_add = DB::table('invoice_items')->insert($invoiceServiceItems);
                    }
                }
            }
        }
        return redirect('invoices');
    }

    public function invoices_pdf(Request $request)
    {
        $app_account = DB::table('application_accounts')->where('deleted', 'No')->get();
        $products = DB::table('products')->where('deleted', 'No')->get();
        $services = DB::table('services')->where('deleted', 'No')->get();
        $invoice_view = DB::table('invoice_items')->where('invoiceId', base64_decode($request->invoice_id))->select('*')->first();
        $invoice_items = DB::table('invoice_items')->where('invoiceId', base64_decode($request->invoice_id))->get();
        $invoices = DB::table('invoices')->where('invoice_id', base64_decode($request->invoice_id))->select('*')->first();
        $clients = DB::table('clients')->where('client_id', $invoices->client_id)->first();
        $show = DB::table('invoice_items')->where('invoiceId', $request->invoiceId);
        $path = public_path('mainlogo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $pic = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $pdf = PDF::loadView('invoices.invoices_download', compact('clients', 'invoice_view', 'invoices', 'invoice_items', 'products', 'app_account', 'pic', 'services'))->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream('invoice.pdf');
    }

    public function invoice_send_mail(Request $request)
    {
        $invoices = DB::table('invoices')->where('invoice_id', base64_decode($request->invoice_id))->select('*')->first();
        $clients = DB::table('clients')->where('client_id', $invoices->client_id)->first();
        $download_url = route('invoices_pdf', base64_encode($invoices->invoice_id));
        $SendMailController = new SendMailController();
        $data = array(
            'invoice_id' => $invoices->invoice_id, 'to_mail_id' => 'deivavingreen@gmail.com', 'client_name' => $clients->client_name, 'date_issue' => $invoices->date_issue, 'grand_total' => $invoices->grand_total,
            'subject' => 'subject', 'download_url' => $download_url
        );
        $send_mail = $SendMailController->send_mail("invoice", $data);

        return redirect('invoices');
    }
}
