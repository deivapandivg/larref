<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;


class ApplicationController extends Controller
{
   public function __construct()
   {
       $this->middleware('auth');
   }

    public function application_list(Request $request){
      if ($request->ajax()) {
            $data = DB::table('application_accounts')->where('deleted','No')->select(['app_account_id',
               'account_name',
               'brand_name',
            ]);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<div class="text-center"><div class="text-center"><a href="'.route('app_account_edit',$row->app_account_id).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
      return view('application.app_list');
   } 

  public function app_account_add()
   {
      return view('application.app_account_add');
   } 

   public function app_account_submit(Request $request)
   {
      // dd($request->all());
      $createdby = Auth::id();
      if ($image = $request->file('brand_logo')) {
            $destinationPath = 'public/brand_logo/';
            $logo = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $logo);
            // $input['image'] = "$profileImage";
      }
      if ($branding_icon = $request->file('brand_icon')) {
            $destinationPath = 'public/brand_logo_icon/';
            $icon = date('YmdHis') . "." . $branding_icon->getClientOriginalExtension();
            $branding_icon->move($destinationPath, $icon);
            // $input['image'] = "$profileImage";
      }
      if (isset($request->app_account_id))
      {
         $data = array('account_name' => $request->account_name, 'brand_name' => $request->company_name, 'mail_id' => $request->mail_id, 'web_site' => $request->web_site, 'mobile_number' => $request->mobile_number,'alter_mobile_number' => $request->alter_mobile_number,'address' => $request->address, 'gst_number' => $request->gst_number,'brand_logo' => $logo, 'branding_icon' => $icon,'created_by' => $createdby, 'created_at' => Now());
          $app_acc_update=DB::table('application_accounts')->where('app_account_id',$request->app_account_id)->update($data);
      }
       else
       {
         $data = array('account_name' => $request->account_name, 'brand_name' => $request->company_name, 'mail_id' => $request->mail_id, 'web_site' => $request->web_site, 'mobile_number' => $request->mobile_number,'alter_mobile_number' => $request->alter_mobile_number,'address' => $request->address, 'gst_number' => $request->gst_number,'brand_logo' => $logo, 'branding_icon' => $icon,'created_by' => $createdby, 'created_at' => Now());
         // dd($data);
         $app_acc_add=DB::table('application_accounts')->insert($data);
       }
      
      return redirect('application_list');
   }

   public function app_account_edit(Request $request)
   {
      $app_acc_details=DB::table('application_accounts')->select('*')->where('app_account_id',$request->app_account_id)->first();
      return view('application.app_account_edit',compact('app_acc_details'));
   } 

}
