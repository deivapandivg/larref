<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class OfficeLocationsController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function office_locations(Request $request){
        if ($request->ajax()) {
            $data = DB::table('office_locations as a')->where('a.deleted','No')->select(['a.office_location_id',
                'a.office_location_name',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by');
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
        return view('office_locations.office_locations');
    }

    public function office_locations_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();
        if(isset($request->office_location_id))
        {
            $data = array('office_location_id' => $request->office_location_id, 'office_location_name' => $request->office_location_name, 'updated_by' => $updatedby, 'updated_at' => Now());
            $office_location_update=DB::table('office_locations')->where('office_location_id',$request->office_location_id)->update($data);
            if($office_location_update) 
            {
                $value = [ 'type' => 'success', 'message' => 'New Data Updated!', 'title' => 'Success!'];
            } 
            else 
            {
                $value = ['type' => 'error', 'message' => 'Something went wrong. Try again later.', 'title' => 'Error!'];
            }
        }
        else
        {
            $data = array('office_location_id' => $request->office_location_id, 'office_location_name' => $request->office_location_name, 'created_by' => $createdby, 'created_at' => Now());

            $office_location_add=DB::table('office_locations')->insert($data);

            if($office_location_add) 
            {
                $value = [ 'type' => 'success', 'message' => 'Data Added!', 'title' => 'Success!'];
            } 
            else 
            {
                $value = ['type' => 'error', 'message' => 'Something went wrong. Try again later.', 'title' => 'Error!'];
            }
        }
        return redirect('office_locations')->with($value);
    }

    public function office_locations_edit(Request $request){
        $office_locations_details=DB::table('office_locations')->where('office_location_id', $request->office_location_id)->first();
        $model='<div class="modal-body">
        <input type="hidden" name="office_location_id" value="'.$office_locations_details->office_location_id.'">
        <div class="row">
        <div class="col-lg-12">
        <div class="form-group">
        <fieldset class="form-group floating-label-form-group"><b>Office Location Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
        <input type="text" id="" required name="office_location_name" class="name form-control" placeholder="Menu Name" value="'.$office_locations_details->office_location_name.'">
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
