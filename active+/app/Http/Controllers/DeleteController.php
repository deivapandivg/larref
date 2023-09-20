<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class DeleteController extends Controller
{
	public function __construct()
    {
      $this->middleware('auth');
    }

	public function delete(Request $request){
		if(isset($request->deleted))
		{
			
			$user_id=Auth::id();
			$updated_at=now();
			$deleted=$request->deleted;
			$DeleteTableName=$request->DeleteTableName;
			
				$data = array('deleted' => $request->deleted, 'deleted_reason' => $request->DeleteReason, 'updated_by' => $user_id, 'updated_at' => $updated_at);

            	$UpdateDeleteData=DB::table($DeleteTableName)->where($request->DeleteColumnName, '=', $request->DeleteColumnValue)->update($data);
            if($UpdateDeleteData!="")
			{
            	Alert::success('Success', 'Data Deleted Successfully !');
	            
	        }
	        else{
            	Alert::error('Error Occurs !', 'Something went wrong!');

	        }
			return back()->withInput();
		}
	}
}
