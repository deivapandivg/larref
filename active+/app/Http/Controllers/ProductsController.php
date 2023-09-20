<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class ProductsController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }

   public function products(Request $request)
   {
      if ($request->ajax()) {
         $data = DB::table('products as a')->where('a.deleted', 'No')->select([
            'a.product_id',
            'd.product_category_name as product_category_id',
            'a.product_name',
            'b.first_name as created_by',
            'a.created_at',
            'c.first_name as updated_by',
            'a.updated_at',
            'a.quantity',
         ])->leftjoin('users as b', 'b.id', '=', 'a.created_by')->leftjoin('users as c', 'c.id', '=', 'a.updated_by')->leftjoin('product_categories as d', 'd.product_category_id', '=', 'a.product_category_id');
         return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

               $btn = '<a class="vg-btn-ssp-success text-center ProductModalView" data-toggle="tooltip" data-placement="right" title="view" data-original-title="view"><i class="fa fa-eye  text-white text-center"></i></a>';
               $btn .= '&nbsp;&nbsp;&nbsp;<a href="' . route('products_edit', base64_encode($row->product_id)) . '" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
               $btn .= '&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
               return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
      }

      return view('products.products');
   }

   public function products_submit(Request $request)
   {
      $createdby = Auth::id();
      $updatedby = Auth::id();


      if (isset($request->product_id)) {
         
         $CustomFieldValuesArr = array();

         $AttachmentsFolder = "public/uploads/Products/";
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
         $data = array('product_code' => $request->product_code, 'product_category_id' => $request->product_category_id, 'product_id' => $request->product_id, 'product_name' => $request->product_name, 'cost_price' => $request->cost_price, 'selling_price' => $request->selling_price, 'gst' => $request->gst, 'product_description' => $request->product_description, 'custom_fields' => $CustomFieldJsonValue, 'updated_by' => $updatedby, 'updated_at' => Now(), 'quantity' => $request->quantity);
         
         $product_update = DB::table('products')->where('product_id', $request->product_id)->update($data);

         $DeleteFilesItems = DB::table('product_attachments')->where('product_id', $request->product_id)->delete();

         if(isset($product_update))
         {
            
            $productIdNew=DB::table('products')->select('*')->where('product_id',$request->product_id)->first();
            $product_attachment_id=$productIdNew->product_id;

            if ($request->existing_product_images!="") {

               $old_files=[];

               foreach ($request->existing_product_images as $oldfile) {

                  $name = $oldfile;
                  
                  $old_files[] = $name;
                  
               }
               

               for ($i = 0; $i < count($old_files); $i++) {

                  $OldFilesArr = $old_files[$i];
                  
                  $old_attachment_add = array('product_id' => $product_attachment_id, 'attachment' => $OldFilesArr, 'created_by' => $createdby, 'created_at' => Now());
                  $old_product_attachment_add = DB::table('product_attachments')->insert($old_attachment_add);

               }
            }

            if($request->product_images!=""){

               $files = [];
               foreach ($request->file('product_images') as $file) {
                  $name = time() . rand(1, 100) . '.' . $file->extension();
                  $file->move(public_path('product_images'), $name);
                  $files[] = $name;
               }

               for ($i = 0; $i < count($files); $i++) {

                  $attachments = $files[$i];
                  $attachment_add = array('product_id' => $product_attachment_id, 'attachment' => $attachments, 'created_by' => $createdby, 'created_at' => Now());
                  $product_attachment_add = DB::table('product_attachments')->insert($attachment_add);

               }
            }
         }
      } 
      else 
      {

         $CustomFieldValuesArr = array();
         $AttachmentsFolder = "public/uploads/Products/";
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
         $data = array('product_code' => $request->product_code, 'product_category_id' => $request->product_category_id, 'product_id' => $request->product_id, 'product_name' => $request->product_name, 'cost_price' => $request->cost_price, 'selling_price' => $request->selling_price, 'gst' => $request->gst, 'product_description' => $request->product_description, 'custom_fields' => $CustomFieldJsonValue, 'created_by' => $createdby, 'created_at' => Now(), 'quantity' => $request->quantity);

         $product_add = DB::table('products')->insertGetId($data);

         if($request->product_images){

            $files = [];
            foreach ($request->file('product_images') as $file) {
               $name = time() . rand(1, 100) . '.' . $file->extension();
               $file->move(public_path('product_images'), $name);
               $files[] = $name;
            }

            for ($i = 0; $i < count($files); $i++) {
               $attachments = $files[$i];
               $attachment_add = array('product_id' => $product_add, 'attachment' => $attachments, 'created_by' => $createdby, 'created_at' => Now());

               $product_attachment_add = DB::table('product_attachments')->insert($attachment_add);
            }
         }
         
      }
      return redirect('products');
   }

   public function products_add()
   {
      $product_category_lists = DB::table('product_categories')->select(DB::raw('*'))
         ->where('deleted', 'No')->get();
      $GetCustomFields = DB::table('custom_fields')->where('field_page', 27)->where('deleted', 'No')->get();
      $GetCustomFieldTypes = DB::table('custom_fieldtype')->where('deleted', 'No')->get();
      return view('products.products_add', compact('GetCustomFields', 'GetCustomFieldTypes', 'product_category_lists'));
   }

   public function products_edit(Request $request)
   {
      $products_details = DB::table('products')->where('product_id', base64_decode($request->product_id))->first();
      $products_images_details = DB::table('product_attachments')->where('product_id', base64_decode($request->product_id))->get();
      $product_category_details = DB::table('product_categories')->select('*')->where('deleted', 'No')->get();
      $GetCustomFields = DB::table('custom_fields')->where('field_page', 27)->where('deleted', 'No')->get();
      $GetCustomFieldTypes = DB::table('custom_fieldtype')->where('deleted', 'No')->get();
      return view('products.products_edit', compact('products_details', 'products_images_details', 'product_category_details', 'GetCustomFields', 'GetCustomFieldTypes'));
   }

   public function products_view(Request $request)
   {
      $products_details = DB::table('products')->where('product_id', base64_decode($request->product_id))->first();
      $product_attachment_details = DB::table('product_attachments')->where('product_id', base64_decode($request->product_id))->get();
      $product_category_details = DB::table('product_categories')->where('product_category_id', $products_details->product_category_id)->first();
      $GetCustomFields = DB::table('custom_fields')->where('field_page', 27)->where('deleted', 'No')->get();
      $GetCustomFieldTypes = DB::table('custom_fieldtype')->where('deleted', 'No')->get();
      return view('products.products_view', compact('products_details', 'product_attachment_details', 'product_category_details', 'GetCustomFields', 'GetCustomFieldTypes'));
   }

   public function products_modal_view(Request $request)
   {
      $products_details = DB::table('products')->where('product_id', $request->product_id)->first();
      $product_attachment_details = DB::table('product_attachments')->where('product_id', $request->product_id)->get();
      $product_category_details = DB::table('product_categories')->where('product_category_id', $products_details->product_category_id)->first();
      $GetCustomFields = DB::table('custom_fields')->where('field_page', 27)->where('deleted', 'No')->get();
      $GetCustomFieldTypes = DB::table('custom_fieldtype')->where('deleted', 'No')->get();
      $model = '
        <div class="row">
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>Product Code </b>
                    <p>' . $products_details->product_code . '</p>
                 </fieldset>
              </div>
           </div>
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>Product Category Name </b>
                     <p>' . $product_category_details->product_category_name . '</p>
                 </fieldset>
              </div>
           </div>
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>Product Name </b>
                     <p>' . $products_details->product_name . '</p>
                 </fieldset>
              </div>
           </div>
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>Cost Price </b>
                    <p>' . $products_details->cost_price . '</p>
                 </fieldset>
              </div>
           </div>
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>Selling Price </b>
                    <p>' . $products_details->selling_price . '</p>
                 </fieldset>
              </div>
           </div>
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>GST </b>
                    <p>' . $products_details->gst . '</p>
                 </fieldset>
              </div>
           </div>
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>Upload Bills & Vouchers :</b><br>';
      foreach ($product_attachment_details as $product_attachment_detail) {
         $model .= '<a href="public/product_images/' . $product_attachment_detail->attachment . '" target="_blank"><button type="button" class="btn btn-sm btn-primary"  title="View Attachment">
                        <i class="fa fa-eye"></i></button></a>' . $product_attachment_detail->attachment . '<br><br>';
      }
      $model .= '</fieldset>
              </div>
           </div>
           <div class="col-lg-6">
              <div class="form-group">
                 <fieldset class="form-group floating-label-form-group"><b>Product Description </b>
                    <p>' . $products_details->product_description . '</p>
                 </fieldset>
              </div>
           </div>
           <div class="col-md-12">
               <div class="form-group">
                  <center>
                  <a href="' . route('products_view', base64_encode($products_details->product_id)) . '" target="_blank">
                     <p><b><u>View More Details</u> </b></p>
                  </a>
               </center>
               </div>
            </div> 
        </div>

        ';
      echo $model;
   }

   public function delete(Request $request)
   {
      if (isset($request->deleted)) {

         $user_id = Auth::id();
         $updated_at = now();
         $deleted = $request->deleted;
         $DeleteTableName = $request->DeleteTableName;


         $data = array('deleted' => $request->deleted, 'deleted_reason' => $request->DeleteReason, 'updated_by' => $user_id, 'updated_at' => $updated_at);

         $UpdateDeleteData = DB::table($DeleteTableName)->where($request->DeleteColumnName, '=', $request->DeleteColumnValue)->update($data);
         if (isset($UpdateDeleteData)) {
            Alert::success('Done!', 'Data Deleted Successfully');
         } else {
            Alert::error('Error Title', 'Error Message');
         }
         return back()->withInput();
      }
   }
}
