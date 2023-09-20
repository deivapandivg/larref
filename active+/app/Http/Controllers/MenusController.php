<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class MenusController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function menus(Request $request){
        if ($request->ajax()) {
            $data = DB::table('menus as a')->where('a.deleted','No')->select(['a.menu_id',
                'd.menu_group_name as menu_group_id',
                'a.menu_name',
                'a.menu_icon',
                'a.menu_link',
                'a.description',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('menu_groups as d', 'd.menu_group_id', '=', 'a.menu_group_id');
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
        $menu_group_list = DB::table('menu_groups')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        return view('menus.menus',compact('menu_group_list'));
    }

    public function menus_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->menu_id))
        {
            $data = array('menu_group_id' => $request->menugroupid, 'menu_name' => $request->menu_name, 'menu_icon' => $request->menu_icon, 'menu_link' =>$request->menu_link, 'description' =>$request->description, 'updated_by' => $createdby, 'created_at' => Now());
            $lead_status_update=DB::table('menus')->where('menu_id',$request->menu_id)->update($data);
        }
        else
        {
            $data = array('menu_group_id' => $request->menugroupid, 'menu_name' => $request->menu_name, 'menu_icon' => $request->menu_icon, 'menu_link' =>$request->menu_link, 'description' =>$request->description, 'created_by' => $createdby, 'created_at' => Now());

            $lead_status_add=DB::table('menus')->insert($data);
        }
        return redirect('menus');
    }

    public function menus_edit(Request $request){
        $menus_details=DB::table('menus')->where('menu_id', $request->menu_id)->first();
        $menu_groups_details = DB::table('menu_groups')->select('*')->where('deleted','No')->get();
        $model='<div class="modal-body">
        <input type="hidden" name="menu_id" value="'.$menus_details->menu_id.'">
        <div class="row">
        <div class="col-lg-6">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Menu Group Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <select class="form-control select2-show-search form-select select2" name="menugroupid" data-placeholder="Choose one">
        <option >Select</option>';
        foreach($menu_groups_details as $menu_groups_detail)
        {
            if($menu_groups_detail->menu_group_id==$menus_details->menu_group_id){ $selected='selected'; }else{ $selected=''; }
            $model.='<option value="'.$menu_groups_detail->menu_group_id.'" '.$selected.'>'.$menu_groups_detail->menu_group_name.'</option>';
        }
        $model.='</select>
        </fieldset>
        </div>
        </div>
        <div class="col-lg-6">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Menu Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" id="" required name="menu_name" class="name form-control" placeholder="Menu Name" value="'.$menus_details->menu_name.'">
        </fieldset>
        </div>
        </div>
        <div class="col-lg-6">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Menu Icon <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" id="" required name="menu_icon" class="name form-control" placeholder="Menu Icon" value="'.$menus_details->menu_icon.'">
        </fieldset>
        </div>
        </div>
        <div class="col-lg-6">

        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Menu Link <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" class="form-control" id="" placeholder="Menu Link" name="menu_link" value="'.$menus_details->menu_link.'">
        </fieldset>
        </div>
        </div>
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Description <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <textarea  required name="description" class="name form-control" rows="4" placeholder="Description">'.$menus_details->description.'</textarea> 
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
        </div>';
        echo $model;
    }
}
