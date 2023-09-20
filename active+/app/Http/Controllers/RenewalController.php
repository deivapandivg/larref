<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class RenewalController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function renewals(Request $request){
         if ($request->ajax()) {
         $data = DB::table('renewals as a')->where('a.deleted','No')->select([
            'a.renewal_id',
            'd.client_name as client_id',
            'a.domain_name',
            'a.hosting_name',
            'a.domain_create',
            'a.domain_renewal',
            'b.first_name as created_by',
            'a.created_at', 
            'c.first_name as updated_by', 
            'a.updated_at', 
         ])->leftJoin('users as b','b.id', '=', 'a.created_by')->leftJoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('clients as d','d.client_id', '=', 'a.client_id');
         return Datatables::of($data)
         ->addIndexColumn()
         ->addColumn('action', function($row){

             $btn ='<a class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
             $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
             // $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-success ViewDataModal text-center" data-toggle="tooltip" data-placement="right" title="view" data-original-title="view"><i class="fa fa-eye text-white text-center"></i></a>';
             return $btn;
         })
         ->rawColumns(['action'])
         ->make(true);
      }
       $clients = DB::table('clients')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        return view('renewals.renewal',compact('clients'));
    }

     public function renewals_submit(Request $request)
    {
        $user_id = Auth::id();
        if(isset($request->renewal_id))
        {
            $data = array('client_id' => $request->client_id,'domain_name' => $request->domain_name, 'hosting_name' => $request->hosting_name,'domain_create' => $request->domain_create,'domain_renewal' => $request->domain_renewal,'updated_by' => $user_id, 'updated_at' => Now());

            $renewal_update=DB::table('renewals')->where('renewal_id',$request->renewal_id)->update($data);
        }
        else
        {
            $data = array('client_id' => $request->client_id,'domain_name' => $request->domain_name, 'hosting_name' => $request->hosting_name,'domain_create' => $request->domain_create,'domain_renewal' => $request->domain_renewal,'created_by' => $user_id, 'created_at' => Now());

            $renewal_add=DB::table('renewals')->insert($data);
        }

        return redirect('renewals');
    }

    public function renewal_edit(Request $request){
        $renewals_details=DB::table('renewals')->where('renewal_id', $request->renewal_id)->first();
        $client_details=DB::table('clients')->select('*')->where('deleted', 'No')->get();
        $model='<div class="modal-body">
        <input type="hidden" name="renewal_id" value="'.$renewals_details->renewal_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Client Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <select class="form-control select2-show-search form-select" name="client_id" data-placeholder="Choose one">
        <option selected>Select</option>';
        foreach($client_details as $client_detail){
            if($client_detail->client_id==$renewals_details->client_id){ $selected='selected'; }else{ $selected=''; }
            $model.='<option value="'.$client_detail->client_id.'" '.$selected.'>'.$client_detail->client_name.'</option>';
        }
        $model.='</select>
        </fieldset>
        </div>
        </div>
        <div class="col-lg-12">
           <div class="form-group">
              <fieldset class="form-group floating-label-form-group"><b>Domain Name :</b>
                 <input type="text" required name="domain_name" class="name form-control" placeholder="Domain Name" value="'.$renewals_details->domain_name.'">
              </fieldset>
           </div>
        </div>
        <div class="col-lg-12">
           <div class="form-group">
              <fieldset class="form-group floating-label-form-group"><b> Hosting Name :</b>
                 <input type="text" name="hosting_name" class="name form-control" placeholder="Hosting Name" value="'.$renewals_details->hosting_name.'">
              </fieldset>
           </div>
        </div>
        <div class="col-lg-12">
           <div class="form-group">
              <fieldset class="form-group floating-label-form-group"><b> Domain Created :</b>
                 <input type="date"  name="domain_create" class="name form-control" placeholder="" value="'.$renewals_details->domain_create.'">
              </fieldset>
           </div>
        </div>
        <div class="col-lg-12">
           <div class="form-group">
              <fieldset class="form-group floating-label-form-group"><b>Domain Renewal :</b>
                 <input type="date"  name="domain_renewal" class="name form-control" placeholder="" value="'.$renewals_details->domain_renewal.'">
              </fieldset>
           </div>
        </div>
     </div> 
  </div>
  <div class="modal-footer">
     <button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
        <i class="fa fa-times"></i> Close
     </button>
     <button type="submit" class="btn btn-primary btn-md">
        <i class="fa fa-check"></i> Update
     </button>
  </div>
        </div>';
        echo $model;
    }
    
}
