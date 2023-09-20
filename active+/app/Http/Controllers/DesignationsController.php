<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class DesignationsController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function designations(Request $request){
        if ($request->ajax()) {
            $data = DB::table('designations as a')->where('a.deleted','No')->select(['a.designation_id',
                'a.designation_name',
                'a.access_menus', 
                'a.description', 
                'b.first_name as created_by', 
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<div class="text-center"><div class="text-center"><a href="'.route('designation_edit',base64_encode($row->designation_id)).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('designations.designations');
    }

    public function designation_add(){
        $get_menu_groups= DB::table('menus as a')->select('a.menu_id','a.menu_group_id','a.menu_name','a.menu_icon','a.menu_link','b.menu_group_name','b.menu_group_icon')->where('a.menu_group_id', '!=', 0)->leftjoin('menu_groups as b', 'b.menu_group_id', '=', 'a.menu_group_id')->get();
        $CompletedMenuGroupsArr=array();
        // $auth_designation_id = Auth::user()->designation_id;
        $designations=DB::table('designations')->where('deleted','No')->select('*')->first();
        $access_menus=$designations->access_menus;
        if ($access_menus!="") 
        {
            $access_menus_arr = explode(',', $designations->access_menus);

            $CompletedMenuGroupsArr=array();

            $get_menus= DB::table('menus as a')->select('a.menu_id','a.menu_group_id','a.menu_name','a.menu_icon','a.menu_link','b.menu_group_name','b.menu_group_icon')->whereIn('a.menu_id', $access_menus_arr)->where('a.deleted','No')->leftjoin('menu_groups as b', 'b.menu_group_id', '=', 'a.menu_group_id')->orderby('menu_group_id')->get();
        }
        foreach($get_menus as $get_menu){
                $menu_group_id=$get_menu->menu_group_id;
                $child_menus=DB::table('menus')->select('menu_id','menu_name','menu_link','menu_icon','menu_group_id')->whereIn('menu_id', $access_menus_arr)->get();
        }
        return view('designations.designation_add',compact('get_menu_groups','get_menus','CompletedMenuGroupsArr','designations','access_menus','child_menus'));
    }

    public function designation_submit(Request $request){
        $created_by = Auth::id();
        $list_data_id_array = $request->list_data_array;
        $designation_name = $request->designation_name;
        $description = $request->description;
        $menues_get=implode(",",$list_data_id_array);
        for($i=0;$i<count($list_data_id_array);$i++)
        {
        $j=$i+1;
        $list_data_id=$list_data_id_array[$i];
        $row_order=$j;
        $update_menu_order=array('row_order' => $row_order);
        $update = DB::table('menus')->where('menu_id', $list_data_id)->update($update_menu_order);
        }
        $designation_add = array('designation_name' => $designation_name, 'access_menus' => $menues_get, 'description' => $description, 'created_by' => $created_by, 'created_at' => now());
        $desgination_insert_query = DB::table('designations')->insert($designation_add);
        return redirect('designations');
    }

    public function designation_edit(Request $request){
        $get_menu_groups= DB::table('menus as a')->select('a.menu_id','a.menu_group_id','a.menu_name','a.menu_icon','a.menu_link','b.menu_group_name','b.menu_group_icon')->where('a.menu_group_id', '!=', 0)->leftjoin('menu_groups as b', 'b.menu_group_id', '=', 'a.menu_group_id')->get();
        $designation_id = base64_decode($request->designation_id);
        $auth_designation_id = Auth::user()->designation_id;
        $designation_lists = DB::table('designations')->select('designation_id', 'designation_name', 'access_menus', 'description')->where('designation_id', $designation_id)->first();
        $designations=DB::table('designations')->where('deleted','No')->select('*')->first();
        $access_menus=$designations->access_menus;
        $access_menu=$designation_lists->access_menus;
        $access_menu_arr = explode(',', $designation_lists->access_menus);

        if ($access_menus!="") 
        {
            $access_menus_arr = explode(',', $designations->access_menus);

            $CompletedMenuGroupsArr=array();

            $get_menus= DB::table('menus as a')->select('a.menu_id','a.menu_group_id','a.menu_name','a.menu_icon','a.menu_link','b.menu_group_name','b.menu_group_icon')->where('a.deleted','No')->leftjoin('menu_groups as b', 'b.menu_group_id', '=', 'a.menu_group_id')->orderby('row_order')->get();
        }
        foreach($get_menus as $get_menu){
                $menu_group_id=$get_menu->menu_group_id;
                $menu_id=$get_menu->menu_id;
                $child_menus=DB::table('menus')->select('menu_id','menu_name','menu_link','menu_icon','menu_group_id')->where('menus.deleted','No')->get();
        }
        
        return view('designations.designation_edit',compact('get_menu_groups','get_menus','CompletedMenuGroupsArr','designations','designation_lists','access_menus','child_menus','access_menu_arr','access_menus_arr'));    
    }

    public function designation_edit_submit(Request $request){
        $designation_id=$request->designation_id;
        $updated_by = Auth::id();
        $list_data_id_array = $request->list_data_array;
        $designation_name = $request->designation_name;
        $description = $request->description;
        $menues_get=implode(",",$list_data_id_array);
        for($i=0;$i<count($list_data_id_array);$i++)
        {
        $j=$i+1;
        $list_data_id=$list_data_id_array[$i];
        $row_order=$j;
        $update_menu_order=array('row_order' => $row_order);
        $update = DB::table('menus')->where('menu_id', $list_data_id)->update($update_menu_order);
        }
        $designation_add = array('designation_name' => $designation_name, 'access_menus' => $menues_get, 'description' => $description, 'updated_by' => $updated_by, 'updated_at' => now());
        $desgination_update_query = DB::table('designations')->where('designation_id',$designation_id)->update($designation_add);
        return redirect('designations');
    }
}
