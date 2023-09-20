<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class PettyCashController extends Controller
{
	public function __construct()
   {
      $this->middleware('auth');
   }

	public function pettycash(Request $request){
		if ($request->ajax()) {
            $data = DB::table('pettycash as a')->where('a.deleted','No')->select(['a.pettycash_id',
                'a.pettycash_date',
                'a.particulars',
                'a.amount',
                'a.balance_history',
                'a.voucher_number',
                'a.bill_receipt_number',
                'd.first_name as user_id',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftJoin('users as b','a.created_by', '=', 'b.id')->leftJoin('users as c','a.updated_by', '=', 'c.id')->leftJoin('users as d','a.user_id', '=', 'd.id');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<a class="vg-btn-ssp-success PettycashViewModal text-center" data-toggle="tooltip" data-placement="right" title="View" data-original-title="View"><i class="fa fa-eye text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                return $btn;
            })
            ->addIndexColumn()
            ->addColumn('amount', function($row){

                $btn ='<p class="text-center" style="color:#DC2531;">'.$row->amount.'</p>';

                return $btn;
            })
            ->rawColumns(['action','amount'])
            ->make(true);
        }
        $pettycash_amounts = DB::table('pettycash')->where('deleted', 'No')->Sum('amount');
        $pettycash_topup_amounts = DB::table('pettycash_topup')->where('deleted', 'No')->Sum('amount');
        function RemoveSpecialChar($str)
		{
    	$res = preg_replace('/[-\@\.\;\" "]+/', '', $str);
    	return $res;
		}
		$balances = $pettycash_amounts-$pettycash_topup_amounts;
		$balance1 = RemoveSpecialChar($balances);
        $users = DB::table('users')->where('deleted', 'No')->get();
		return view('pettycash.pettycash',compact('users','balance1'));
	}

	public function pettycash_edit(Request $request){
		$pettycash_attachment_details=DB::table('pettycash_attachments')->where('pettycash_id', $request->pettycash_id)->get();
		$pettycash_details=DB::table('pettycash')->where('pettycash_id', $request->pettycash_id)->first();
        $users=DB::table('users')->select('*')->where('deleted', 'No')->get();
        $model='<div class="modal-body">
        <input type="hidden" name="pettycash_id" value="'.$pettycash_details->pettycash_id.'">
        <div class="row">
			<div class="col-lg-12">
				<div class="form-group">
					<fieldset class="form-group floating-label-form-group"><b> Balance <sup class="text-danger" style="font-size: 13px;">*</sup>:</b>  
				</fieldset>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<fieldset class="form-group floating-label-form-group"><b> Date <sup class="text-danger" style="font-size: 13px;">*</sup>:</b>
					<input type="date" id="pettycash_date" required name="pettycash_date" class="name form-control" placeholder="Course Code" value="'.$pettycash_details->pettycash_date.'">
					<span id="AddError" class="text-center" style="text-align: center;"></span>
				</fieldset>
			</div>
		</div>
        <div class="col-lg-6">
			<div class="form-group">
				<fieldset class="form-group floating-label-form-group"><b>User <sup class="text-danger" style="font-size: 13px;">*</sup>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
					<select class="form-control border-primary select2 form-select" name="user_id" data-placeholder="Choose one" style="width:100%;">
						<option selected="selected" disabled="disabled">Select </option>';
	        		foreach($users as $user){
	            		if($user->id==$pettycash_details->user_id){ $selected='selected'; }else{ $selected=''; }
	            			$model.='<option value="'.$user->id.'" '.$selected.'>'.$user->first_name.'</option>';
	        		}
	        		$model.='</select>
        		</fieldset>
       		</div>
        </div>
		<div class="col-lg-6">
			<div class="form-group">
				<fieldset class="form-group floating-label-form-group"><b>Particulars <sup class="text-danger" style="font-size: 13px;">*</sup>:</b>
					<input type="text" id="Particulars" required name="particulars" class="name form-control" placeholder="Particulars" value="'.$pettycash_details->particulars.'">
					<span id="AddError1" class="text-center" style="text-align: center;"></span>
				</fieldset>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<fieldset class="form-group floating-label-form-group"><b>Amount :</b>
					<input type="number" id="Title" name="amount" class="name form-control" placeholder="Amount" value="'.$pettycash_details->amount.'">
				</fieldset>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<fieldset class="form-group floating-label-form-group"><b>Voucher Number :</b>
					<input type="number" id="Title" name="voucher_number" class="name form-control" placeholder="Voucher Number" value="'.$pettycash_details->voucher_number.'">
				</fieldset>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<fieldset class="form-group floating-label-form-group"><b>Bill / Receipt Number :</b>
					<input type="number" id="Title" name="bill_receipt_number" class="name form-control" placeholder="Bill / Receipt Number" value="'.$pettycash_details->bill_receipt_number.'">
				</fieldset>
			</div>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<fieldset class="form-group floating-label-form-group"><b>Upload Bills & Vouchers :</b>
							<center>
								<table id="AddImageTable" width="50%">
									<tbody id="ImageTBodyEdit">';
									foreach($pettycash_attachment_details as $pettycash_attachment_detail)
									{
										$model.='<tr class="add_row">
											<td width="100%"><input name="existing_bill_upload[]" type="hidden" value="'.$pettycash_attachment_detail->attachment.'" style="width:100px;">'.$pettycash_attachment_detail->attachment.'</td>
											<td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td>
											<td><a href="public/bill_uploads/'.$pettycash_attachment_detail->attachment.'" target="_blank"><button type="button" class="btn btn-primary btn-sm" id="view" title="View file"><i class="fa fa-eye"></i></button></a></td>
											';
									}
										$model.='</tr>
										<tr>
										<td width="100%"><input name="bill_upload[]" type="file" multiple></td>
											<td width="20%"><button class="btn btn-success btn-sm" type="button" id="add" title="Add new file"><i class="fa fa-plus"></i></button></td></tr>
									</tbody>
								</table>
							</center>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
				<i class="fa fa-times"></i> Close
			</button>
			<button type="submit" name="submit" class="btn btn-primary btn-md ProductCodeBtn">
				<i class="fa fa-check"></i> Update
			</button>
		</div>';
        echo $model;
	}

	public function pettycash_modal_view(Request $request){
		$pettycash_details=DB::table('pettycash')->where('pettycash_id', $request->pettycash_id)->first();
		$pettycash_attachment_details=DB::table('pettycash_attachments')->where('pettycash_id', $request->pettycash_id)->get();
        $users=DB::table('users')->select('*')->where('users.deleted', 'No')->join('pettycash', 'pettycash.user_id', '=', 'users.id')->first();
        $model='<div class="modal-body">
        <input type="hidden" name="pettycash_id" value="'.$pettycash_details->pettycash_id.'">
        <div class="row">
			
		<div class="col-lg-6">
			<div class="form-group">
				<fieldset class="form-group floating-label-form-group"><b> Date :</b>
					<p>'.$pettycash_details->pettycash_date.'</p>
				</fieldset>
			</div>
		</div>
        <div class="col-lg-6">
			<div class="form-group">
				<fieldset class="form-group floating-label-form-group"><b>User :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
					<p>'.$users->first_name.'</p>
        		</fieldset>
       		</div>
        </div>
		<div class="col-lg-6">
			<div class="form-group">
				<fieldset class="form-group floating-label-form-group"><b>Particulars :</b>
					<p>'.$pettycash_details->particulars.'</p>
				</fieldset>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<fieldset class="form-group floating-label-form-group"><b>Amount :</b>
					<p>'.$pettycash_details->amount.'</p>
				</fieldset>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<fieldset class="form-group floating-label-form-group"><b>Voucher Number :</b>
					<p>'.$pettycash_details->voucher_number.'</p>
				</fieldset>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<fieldset class="form-group floating-label-form-group"><b>Bill / Receipt Number :</b>
					<p>'.$pettycash_details->bill_receipt_number.'</p>
				</fieldset>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="form-group">
				<fieldset class="form-group floating-label-form-group"><b>Upload Bills & Vouchers :</b><br><br>';
					foreach($pettycash_attachment_details as $pettycash_attachment_detail)
					{
                        $model.='<a href="public/bill_uploads/'.$pettycash_attachment_detail->attachment.'" target="_blank"><button type="button" class="btn btn-sm btn-primary"  title="View Attachment">
                        <i class="fa fa-eye"></i></button></a>'.$pettycash_attachment_detail->attachment.'<br><br>';
                    }
				$model.'=</fieldset>
			</div>
		</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
				<i class="fa fa-times"></i> Close
			</button>
		</div>';
        echo $model;
	}

	public function pettycash_submit(Request $request){

		$createdby = Auth::id();
        $updatedby = Auth::id();
        
        if(isset($request->pettycash_id))
        {
        	// dd($request->all());
        	$pettycash_edit_amounts = DB::table('pettycash')->where('pettycash_id', $request->pettycash_id)->where('deleted', 'No')->Sum('amount');
            $pettycash_amounts = DB::table('pettycash')->where('deleted', 'No')->Sum('amount');
	        $pettycash_topup_amounts = DB::table('pettycash_topup')->where('deleted', 'No')->Sum('amount');
	        function RemoveSpecialChar($str)
			{
	    	$res = preg_replace('/[-\@\.\;\" "]+/', '', $str);
	    	return $res;
			}
			$balances = $pettycash_edit_amounts+$pettycash_topup_amounts-$pettycash_amounts-$request->amount;
			$balance1 = RemoveSpecialChar($balances);

            $data = array('pettycash_date' => $request->pettycash_date,
            'particulars' => $request->particulars,
            'voucher_number' => $request->voucher_number,
            'bill_receipt_number' => $request->bill_receipt_number,
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'balance_history' => $balance1,
            'created_by' => $createdby, 
            'created_at' => Now());

            $pettycash_update=DB::table('pettycash')->where('pettycash_id',$request->pettycash_id)->update($data);

            if(isset($pettycash_update))
	        {
	            
	            $pettycashIdNew=DB::table('pettycash')->select('*')->where('pettycash_id',$request->pettycash_id)->first();
	            $pettycash_attachment_id=$pettycashIdNew->pettycash_id;

	            $DeleteFilesItems = DB::table('pettycash_attachments')->where('pettycash_id', $request->pettycash_id)->delete();
	            
	            if ($request->existing_bill_upload!="") {

	               $old_files=[];

	               foreach ($request->existing_bill_upload as $oldfile) {
	                  $name = $oldfile;
	                  
	                  $old_files[] = $name;
	               }
	               

	               for ($i = 0; $i < count($old_files); $i++) {

	                  $OldFilesArr = $old_files[$i];
	                  
	                  $old_attachment_add = array('pettycash_id' => $pettycash_attachment_id, 'attachment' => $OldFilesArr, 'created_by' => $createdby, 'created_at' => Now());

	                  $old_pettycash_attachment_add = DB::table('pettycash_attachments')->insert($old_attachment_add);

	               }
	           	}

               	if($request->bill_upload!=""){

                  	$files = [];
                  	foreach ($request->file('bill_upload') as $file) {
                 		$name = time() . rand(1, 100) . '.' . $file->extension();
                 		$file->move(public_path('bill_uploads'), $name);
                     	$files[] = $name;
                  	}

                  	for ($i = 0; $i < count($files); $i++) {

                 		$attachments = $files[$i];
                     	$attachment_add = array('pettycash_id' => $pettycash_attachment_id, 'attachment' => $attachments, 'created_by' => $createdby, 'created_at' => Now());
                     	$pettycash_attachment_add = DB::table('pettycash_attachments')->insert($attachment_add);
                     
                  	}
               	}
	        }
        }
        else
        {
	       	
	       	// dd($request->all());
        	$pettycash_amounts = DB::table('pettycash')->where('deleted', 'No')->Sum('amount');
	        $pettycash_topup_amounts = DB::table('pettycash_topup')->where('deleted', 'No')->Sum('amount');
	        function RemoveSpecialChar($str)
			{
	    	$res = preg_replace('/[-\@\.\;\" "]+/', '', $str);
	    	return $res;
			}
			$balances = $pettycash_topup_amounts-$pettycash_amounts-$request->amount;
			$balance1 = RemoveSpecialChar($balances);

            $data = array('pettycash_date' => $request->pettycash_date,
            'particulars' => $request->particulars,
            'voucher_number' => $request->voucher_number,
            'bill_receipt_number' => $request->bill_receipt_number,
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'balance_history' => $balance1,
            'created_by' => $createdby, 
            'created_at' => Now());

            $pettycash_add=DB::table('pettycash')->insertGetId($data);
            if($request->bill_upload!=""){
        		$files=[];
	        	foreach($request->file('bill_upload') as $file)
	        	{
	        		$name = time() . rand(1, 100) . '.' . $file->extension();
	        		$file->move(public_path('bill_uploads'),$name);
	        		$files[] = $name;
	        	}
	        	for($i = 0; $i < count($files); $i++){
	            	$attachments = $files[$i];
					$attachment_add = array('pettycash_id' => $pettycash_add, 'attachment' => $attachments, 'created_by' => $createdby, 'created_at' => Now());
					// dd($attachment_add);

					$pettycash_attachment_add = DB::table('pettycash_attachments')->insert($attachment_add);
				}
			}
            
        }
        return redirect('pettycash');
            
	}

	public function pettycash_topup(Request $request){

		if ($request->ajax()) {
            $data = DB::table('pettycash_topup as a')->where('a.deleted','No')->select(['a.topup_id',
                'a.topup_date',
                'd.first_name as user_id',
                'a.description',
                'a.amount',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftJoin('users as b','a.created_by', '=', 'b.id')->leftJoin('users as c','a.updated_by', '=', 'c.id')->leftJoin('users as d','a.user_id', '=', 'd.id');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<a class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a>';
                return $btn;
            })
            ->addIndexColumn()
            ->addColumn('amount', function($row){

                $btn ='<p class="text-center" style="color:#0F7D00;">'.$row->amount.'</p>';

                return $btn;
            })
            ->rawColumns(['action','amount'])
            ->make(true);
        }
        $pettycash_amounts = DB::table('pettycash')->where('deleted', 'No')->Sum('amount');
        $pettycash_topup_amounts = DB::table('pettycash_topup')->where('deleted', 'No')->Sum('amount');
        function RemoveSpecialChar($str)
		{
    	$res = preg_replace('/[-\@\.\;\" "]+/', '', $str);
    	return $res;
		}
		$balances = $pettycash_amounts-$pettycash_topup_amounts;
		$balance1 = RemoveSpecialChar($balances);
        $users = DB::table('users')->where('deleted', 'No')->get();
		return view('pettycash.pettycash_topup',compact('users','balance1'));
	}

	
	public function pettycash_topup_edit(Request $request){
		$pettycash_topup_details=DB::table('pettycash_topup')->where('topup_id', $request->topup_id)->first();
        $users=DB::table('users')->select('*')->where('deleted', 'No')->get();
        $model='<div class="modal-body">
        <input type="hidden" name="topup_id" value="'.$pettycash_topup_details->topup_id.'">
        <div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<fieldset class="form-group floating-label-form-group"><b> Date <sup class="text-danger" style="font-size: 13px;">*</sup>:</b>
						<input type="date" id="topup_date" required name="topup_date" class="name form-control" value="'.$pettycash_topup_details->topup_date.'">
						<span id="AddError" class="text-center" style="text-align: center;"></span>
					</fieldset>
				</div>
			</div>
	        <div class="col-lg-6">
				<div class="form-group">
					<fieldset class="form-group floating-label-form-group"><b>User <sup class="text-danger" style="font-size: 13px;">*</sup>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
						<select class="form-control border-primary select2 form-select" name="user_id" data-placeholder="Choose one" style="width:100%;">
							<option selected="selected" disabled="disabled">Select </option>';
		        		foreach($users as $user){
		            		if($user->id==$pettycash_topup_details->user_id){ $selected='selected'; }else{ $selected=''; }
		            			$model.='<option value="'.$user->id.'" '.$selected.'>'.$user->first_name.'</option>';
		        		}
		        		$model.='</select>
	        		</fieldset>
	       		</div>
	        </div>
			<div class="col-lg-6">
				<div class="form-group">
					<fieldset class="form-group floating-label-form-group"><b>Description <sup class="text-danger" style="font-size: 13px;">*</sup>:</b>
						<input type="text" id="Title" name="description" class="name form-control" placeholder="Description" value="'.$pettycash_topup_details->description.'">
					</fieldset>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<fieldset class="form-group floating-label-form-group"><b>Amount <sup class="text-danger" style="font-size: 13px;">*</sup>:</b>
						<input type="number" id="Title" name="amount" class="name form-control" placeholder="Amount" value="'.$pettycash_topup_details->amount.'">
					</fieldset>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
				<i class="fa fa-times"></i> Close
			</button>
			<button type="submit" name="submit" class="btn btn-primary btn-md ProductCodeBtn">
				<i class="fa fa-check"></i> Update
			</button>
		</div>';
        echo $model;
	}

	public function pettycash_topup_submit(Request $request){

		$createdby = Auth::id();
        $updatedby = Auth::id();
         
        if(isset($request->topup_id))
        {

            $data = array('topup_date' => $request->topup_date,
            'user_id' => $request->user_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'updated_by' => $updatedby, 
            'updated_at' => Now());
            $pettycash_topup_update=DB::table('pettycash_topup')->where('topup_id',$request->topup_id)->update($data);
            
        }
        else
        {
            
            $data = array('topup_date' => $request->topup_date,
            'user_id' => $request->user_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'created_by' => $createdby, 
            'created_at' => Now());

            $pettycash_add=DB::table('pettycash_topup')->insert($data);
             
        }
        return redirect('pettycash_topup');
	}

}