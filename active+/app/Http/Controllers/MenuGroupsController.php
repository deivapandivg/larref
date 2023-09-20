<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use Redirect, Response, Session;

class MenuGroupsController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }

   public function menu_groups(Request $request){
      if ($request->ajax()) {
         $data = DB::table('menu_groups as a')->where('a.deleted','No')->select(['a.menu_group_id',
             'a.menu_group_name',
             'a.menu_group_icon',
             'a.description', 
             'b.first_name as created_by', 
             'a.created_at', 
             'c.first_name as updated_by', 
             'a.updated_at', 
         ])->leftJoin('users as b','a.created_by', '=', 'b.id')->leftJoin('users as c','a.updated_by', '=', 'c.id');
         return Datatables::of($data)
         ->addIndexColumn()
         ->addColumn('action', function($row){

            $btn ='<div class="text-center"><div class="text-center"><a class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
            $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';

             return $btn;
         })
         ->rawColumns(['action'])
         ->make(true);
      }
      return view('menu_groups.menu_groups');
   }

   public function menu_groups_submit(Request $request){
      $user_id = Auth::id();
      if(isset($request->menu_group_id))
      {
         $data = array('menu_group_name' => $request->menu_group_name, 'menu_group_icon' => $request->menu_group_icon, 'description' => $request->description, 'updated_by' => $user_id, 'updated_at' => Now());

         $update=DB::table('menu_groups')->where('menu_group_id',$request->menu_group_id)->update($data);
      }
      else
      {
         $data = array('menu_group_name' => $request->menu_group_name, 'menu_group_icon' => $request->menu_group_icon, 'description' => $request->description, 'created_by' => $user_id, 'created_at' => Now());

         $add=DB::table('menu_groups')->insert($data);
      }
      return Redirect('menu_groups');
   }

   public function menu_group_edit(Request $request)
   {
      $menu_group_details=DB::table('menu_groups')->where('menu_group_id',$request->menu_group_id)->first();
      $data='<div class="modal-body">
            <input type="hidden" name="menu_group_id" value="'.$menu_group_details->menu_group_id.'">
            <div class="row">
               <div class="col-lg-12">
                  <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Menu Group Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                     <input type="text" id="" required name="menu_group_name" class="name form-control" placeholder="Menu Group Name" value="'.$menu_group_details->menu_group_name.'">
                     </fieldset>
                  </div>
               </div>
               <div class="col-lg-12">
                  <div class="form-group">
                    <fieldset class="form-group floating-label-form-group"><b>Menu Group Icon <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                     <input type="text" id="" required name="menu_group_icon" class="name form-control" placeholder="Menu Group Icon" value="'.$menu_group_details->menu_group_icon.'">
                     </fieldset>
                  </div>
               </div>
               <div class="col-lg-12">
                  <div class="form-group">
                     <fieldset class="form-group floating-label-form-group"><b>Description <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                        <textarea  required name="description" class="name form-control" rows="4">'.$menu_group_details->description.'</textarea> 
                     </fieldset>
                  </div>
               </div>
            </div> 
         </div>';
      echo $data;
   }

}
