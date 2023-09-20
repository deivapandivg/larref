<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Redirect, Response, Session;
use DB;

class CommunicationMediumController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function communication_mediums(Request $request){
        if ($request->ajax()) {
            $data = DB::table('communication_mediums')->where('communication_mediums.deleted','No')->select(['communication_mediums.communication_medium_id',
                'communication_mediums.communication_medium',
                'communication_mediums.description',
                'communication_mediums.description',
                'users.first_name', 
                'communication_mediums.created_at', 
            ])->join('users', 'users.id', '=', 'communication_mediums.created_by');
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
        return view('communication_mediums.communication_mediums');
    }
     public function communication_mediums_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->communication_medium_id))
        {
            $data = array('communication_medium' => $request->communication_medium,'description' => $request->description, 'updated_by' => $updatedby, 'updated_at' => Now());
            $communication_medium_update=DB::table('communication_mediums')->where('communication_medium_id',$request->communication_medium_id)->update($data);
        }
        else
        {
            $data = array('communication_medium' => $request->communication_medium,'description' => $request->description, 'created_by' => $createdby, 'created_at' => Now());
            $communication_medium_add=DB::table('communication_mediums')->insert($data);
        }
        return redirect('communication_mediums');
    }

     public function communication_medium_edit(Request $request){
        $communication_medium_details=DB::table('communication_mediums')->where('communication_medium_id', $request->communication_medium_id)->first();
        $model='<div class="modal-body">
        <input type="hidden" name="communication_medium_id" value="'.$communication_medium_details->communication_medium_id.'">
        <div class="form-group">
        <label for="medium_name" class="form-label">Communication Medium Name *</label>
        <input type="text" class="form-control" id="communication_medium" placeholder="Communication Medium" name="communication_medium" required value="'.$communication_medium_details->communication_medium.'">
        </div>
        <div class="form-group">
        <label for="description" class="form-label">Description</label>
        <textarea  class="form-control" id="description" placeholder="Description" name="description" >'.$communication_medium_details->description.'</textarea>
        </div>
        </div>';
        echo $model;
    }

}
