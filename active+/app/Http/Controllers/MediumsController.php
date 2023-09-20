<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Redirect, Response, Session;
use DB;

class MediumsController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function mediums(Request $request){
        if ($request->ajax()) {
            $data = DB::table('mediums')->where('mediums.deleted','No')->select(['mediums.medium_id',
                'mediums.medium_name',
                'users.first_name', 
                'mediums.created_at', 
            ])->join('users', 'users.id', '=', 'mediums.created_by');
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
        return view('mediums.mediums');
    }

    public function medium_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->medium_id))
        {
            $data = array('medium_name' => $request->medium_name, 'updated_by' => $updatedby, 'updated_at' => Now());
            $medium_update=DB::table('mediums')->where('medium_id',$request->medium_id)->update($data);
        }
        else
        {
            $user_id=Auth::id();
            $data = array('medium_name' => $request->medium_name, 'created_by' => $createdby, 'created_at' => Now());
            $medium_add=DB::table('mediums')->insert($data);
        }
        return redirect('mediums');
    }

    public function medium_edit(Request $request){
        $medium_details=DB::table('mediums')->where('medium_id', $request->medium_id)->first();
        $model='<div class="modal-body">
        <input type="hidden" name="medium_id" value="'.$medium_details->medium_id.'">
        <div class="form-group">
        <label for="medium_name" class="form-label">Medium Name *</label>
        <input type="text" class="form-control" id="medium_name" placeholder="Enter Lead Sub Stage Name" name="medium_name" required value="'.$medium_details->medium_name.'">
        </div>
        </div>';
        echo $model;
    }
}
