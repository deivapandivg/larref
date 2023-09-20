<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class QuotationsAjaxController extends Controller
{
	public function __construct()
   {
      $this->middleware('auth');
   }

    public function quotation_new_product(Request $request){
        $products = DB::table('products')->where('deleted', 'No')->get();
        $services = DB::table('services')->where('deleted', 'No')->get();
        $data ='<tr>
            <td>';
        if($request->input_type==1){
            $data.='<select class="select2 form-control Product"  name="Product[]" style="width: 250px;">
            <option value="">Select Product</option>';
            foreach($products as $product){
                $data.='<option  value="'.$product->product_id.'">'.$product->product_name.'</option>';
        	}
            $data.='</select>
            <textarea class="form-control" style="padding-top:5px;" name="ProductDescription[]" placeholder="Product Description" style="width: 250px;"></textarea>
            </td>
            <td><input type="number" class="form-control Cost" name="Cost[]" value="0" style="width: 110px;">
            </td>
            <td><input type="number" class="form-control Quantity" name="Quantity[]" value="1" placeholder="Quantity" style="width: 110px;"></td>
            <td>
            <input type="number" class="form-control GST" name="GST[]" placeholder="Total" value="0" style="width: 110px;">
            </td>
            <td class="IGSThide '.(($request->GSTTypeIn!='1')?'hidden':'').'"><input type="number" class="form-control IGST" name="IGST[]" value="0" readonly style="width: 110px;"></td>
            <td class="CGSThide '.(($request->GSTTypeIn=='1')?'hidden':'').'"><input type="number" class="form-control CGST" name="CGST[]" value="0" readonly style="width: 110px;"></td>
            <td class="SGSThide '.(($request->GSTTypeIn=='1')?'hidden':'').'"><input type="number" class="form-control SGST" name="SGST[]" value="0" readonly style="width: 110px;"></td>
            <td><input type="number" name="Amount[]" class="form-control Amount" readonly placeholder="Total" value="0" style="width: 110px;"></td>
            <td>
            <span type="button" class="btn btn-danger btn-sm RemovequotationItem text-white">
            <i class="fa fa-trash"></i> 
            </span>
            </td>';
        }
        else
        {
            $data.='<select class="select2 form-control Service"  name="Service[]" style="width: 250px;">
            <option value="">Select Service</option>';
            foreach($services as $service){
                $data.='<option  value="'.$service->service_id.'">'.$service->service_name.'</option>';
            }
            $data.='</select>
            <textarea class="form-control" style="padding-top:5px;" name="ServiceDescription[]" placeholder="Service Description" style="width: 250px;"></textarea>
        </td>
        <td><input type="number" class="form-control Cost" name="ServiceCost[]" value="0" style="width: 110px;">
        </td>
        <td><input type="number" class="form-control Quantity" name="ServiceQuantity[]" value="1" placeholder="Quantity" style="width: 110px;"></td>
        <td>
        <input type="number" class="form-control GST" name="ServiceGST[]" placeholder="Total" value="0" style="width: 110px;">
        </td>
        <td class="IGSThide '.(($request->GSTTypeIn!='1')?'hidden':'').'"><input type="number" class="form-control IGST" name="ServiceIGST[]" value="0" readonly style="width: 110px;"></td>
        <td class="CGSThide '.(($request->GSTTypeIn=='1')?'hidden':'').'"><input type="number" class="form-control CGST" name="ServiceCGST[]" value="0" readonly style="width: 110px;"></td>
        <td class="SGSThide '.(($request->GSTTypeIn=='1')?'hidden':'').'"><input type="number" class="form-control SGST" name="ServiceSGST[]" value="0" readonly style="width: 110px;"></td>
        <td><input type="number" name="ServiceAmount[]" class="form-control Amount" readonly placeholder="Total" value="0" style="width: 110px;"></td>
        <td>
        <span type="button" class="btn btn-danger btn-sm RemovequotationItem text-white">
        <i class="fa fa-trash"></i> 
        </span>';
        }
        $data.='
        </td>
        </tr>';
        echo $data;
    }

    public function quotation_new_gst(Request $request){

        $products = DB::table('products')->where('deleted', 'No')->get();
        $ProductId=$request->Product;
        foreach($products as $product){
        	if($ProductId==$product->product_id){
        		echo $product->gst;
			}
		}
    }

    public function quotation_new_rate(Request $request){
    	
    	
        $products = DB::table('products')->where('deleted', 'No')->get();
        $ProductId=$request->Product;
        foreach($products as $product){
        	if($ProductId==$product->product_id){
        		echo $product->selling_price;
			}
		}
    }

    public function quotation_new_gst_service(Request $request){

        $services = DB::table('services')->where('deleted', 'No')->get();
        $ServiceId=$request->Service;
        foreach($services as $service){
            if($ServiceId==$service->service_id){
                echo $service->gst;
            }
        }
    }

    public function quotation_new_rate_service(Request $request){
        
        $services = DB::table('services')->where('deleted', 'No')->get();
        $serviceId=$request->Service;
        foreach($services as $service){
            if($serviceId==$service->service_id){
                echo $service->service_amount;
            }
        }
    }  
}