<x-app-layout>
	<section id="tabs-with-icons">
		<div class="row match-height">
			<div class="col-xl-12 col-lg-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Expense
							<ol class="breadcrumb mt-0">
								<li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
								</li>
							</ol>
							<!-- <ol class="breadcrumb mt-0"> -->
								<!-- <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary"> Balance : {{ $balance1 }}</span>
								</li> -->
								<!-- </ol> -->
							</h4>
							<div class="heading-elements mt-0">
								<ul class="offset-5"><span class="btn btn-sm p-0 text-primary"><h5 class="text-primary"><b> Balance : {{ $balance1 }}</b></h5></span></ul>
								@if($balance1==0)
									<a class="float-right"><button type="submit" class="btn btn-primary addexpense">
									<i class="fa fa-plus"></i> Add Expense
									</button></a>
								@else
									<a class="float-right"><button type="submit" data-toggle="modal" data-target="#AddModal"class="btn btn-primary">
										<i class="fa fa-plus"></i> Add Expense
									</button></a>
								@endif
								<a href="{{ route('pettycash_topup') }}"><button type="submit" class="btn btn-primary">
									<i class="fa fa-plus"></i> Add Fund
								</button></a>
								<div class="modal fade" id="AddModal"  role="dialog" aria-labelledby="AddModals" aria-hidden="true">
      								<div class="modal-dialog modal-dialog-centered  modal-md" role="document">
								        <div class="modal-content">
								            <section class="contact-form">
								                <div class="modal-header bg-primary white">
								                  <h5 class="modal-title white">Expense Add</h5>
								                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								                     <span aria-hidden="true">&times;</span>
								                  </button>
								                </div>
												<form method="post" action="{{ route('pettycash_submit') }}" enctype="multipart/form-data">
													@csrf
													<div class="modal-body PageBrandingBacground">
														<div class="row">
															<div class="col-lg-12">
																<div class="form-group">
																	<fieldset class="form-group floating-label-form-group"><b> Balance <sup class="text-danger" style="font-size: 13px;">*</sup>:</b> {{ $balance1 }}
																</fieldset>
															</div>
														</div>
														<div class="col-lg-6">
															<div class="form-group">
																<fieldset class="form-group floating-label-form-group"><b> Date <sup class="text-danger" style="font-size: 13px;">*</sup>:</b>
																	<input type="date" value="" id="pettycash_date" required name="pettycash_date" class="name form-control" placeholder="Course Code">
																	<span id="AddError" class="text-center" style="text-align: center;"></span>
																</fieldset>
															</div>
														</div>
														<div class="col-lg-6">
															<div class="form-group">
																<fieldset class="form-group floating-label-form-group"><b>User <sup class="text-danger" style="font-size: 13px;">*</sup>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
																	<select class="form-control border-primary select2 form-select" name="user_id" data-placeholder="Choose one" style="width:100%;">
																		<option selected="selected" disabled="disabled">Select </option>
																			@foreach ($users as $user)
									                                          <option value="{{  $user->id }}">{{ $user->first_name }}</option>
									                                        @endforeach
																	</select>
																</fieldset>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-lg-6">
															<div class="form-group">
																<fieldset class="form-group floating-label-form-group"><b>Particulars <sup class="text-danger" style="font-size: 13px;">*</sup>:</b>
																	<input type="text" id="Particulars" required name="particulars" class="name form-control" placeholder="Particulars">
																	<span id="AddError1" class="text-center" style="text-align: center;"></span>
																</fieldset>
															</div>
														</div>
														<div class="col-lg-6">
															<div class="form-group">
																<fieldset class="form-group floating-label-form-group"><b>Amount <sup class="text-danger" style="font-size: 13px;">*</sup>:</b>
																	<input type="number" id="Title" name="amount" class="name form-control" placeholder="Amount" max="<?=$balance1 ?>" required>
																</fieldset>
															</div>
														</div>
														<div class="col-lg-6">
															<div class="form-group">
																<fieldset class="form-group floating-label-form-group"><b>Voucher Number :</b>
																	<input type="number" id="Title" name="voucher_number" class="name form-control" placeholder="Voucher Number">
																</fieldset>
															</div>
														</div>
														<div class="col-lg-6">
															<div class="form-group">
																<fieldset class="form-group floating-label-form-group"><b>Bill / Receipt Number :</b>
																	<input type="number" id="Title" name="bill_receipt_number" class="name form-control" placeholder="Bill / Receipt Number">
																</fieldset>
															</div>
														</div>
													</div>
													<div class="modal-body">
														<div class="row">
															<div class="col-lg-12">
																<div class="form-group">
																	<fieldset class="form-group floating-label-form-group"><b>Upload Bills & Vouchers :</b>
																		<center>
																			<table id="AddImageTable" width="50%">
																				<tbody id="ImageTBodyAdd">
																					<tr class="add_row">
																						<td width="100%"><input name="bill_upload[]" type="file" multiple></td>
																						<td width="20%"><button class="btn btn-success btn-sm" type="button" id="add" title='Add new file'><i class="fa fa-plus"></i></button></td>
																					</tr>
																				</tbody>
																			</table>
																		</center>
																	</fieldset>
																</div>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
															<i class="fa fa-times"></i> Close
														</button>
														<button type="submit" name="submit" class="btn btn-primary btn-md ProductCodeBtn">
															<i class="fa fa-check"></i> Add
														</button>
													</div>
												</div>
											</form>
										</section>
									</div>
								</div>
							</div>
							<div class="modal fade" id="edit_modal_pettycash"  role="dialog" aria-labelledby="edit_modal_pettycash" aria-hidden="true">
				            <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
				               <div class="modal-content">
				                  <section class="contact-form">
				                     <div class="modal-header bg-primary white">
				                        <h5 class="modal-title white">Expense Edit</h5>
				                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				                           <span aria-hidden="true">&times;</span>
				                        </button>
				                     </div>
				                     <form method="post" action="{{ route('pettycash_submit') }}" enctype="multipart/form-data">
				                        @csrf
				                     <div id="EditPettycashModalData"></div>
				                     </form>
				                  </section>
				               </div>
				            </div>
					      </div>
							<div class="modal modal_outer right_modal fade" id="pettycash_view_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
						      <div class="modal-dialog" role="document">
						         <div class="modal-content ">
						            <div class="modal-header bg-primary white">
						               <h2 class="modal-title white">Expense Quick View :</h2>
						               <button type="button" class="btn btn-lg close" data-dismiss="modal" aria-label="Close">
						                  <span aria-hidden="true">&times;</span>
						               </button>
						            </div>
						            <div class="modal-body get_quote_view_modal_body">
						               <form method="post" id="get_quote_frm">
						               </form>
						            </div>
						         </div>
						      </div>
						   </div>
						</div>
					</div>
					<div class="card-content">
                  		<div class="card-body">
                     		<div class="table-responsive">
                        		<table class="table table-striped table-bordered ExpenseTable" style="width:100%;">
									<thead>
										<tr>
											<th> Id</th>
											<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
											<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
											<th>Particulars</th>
											<th>Expense Amount</th>
											<th>Balance</th>
											<th>VoucherNumber</th>
											<th>BillReceiptNumber</th>
											<th>User</th>
											<th>Created By</th>
											<th>Created At</th>
											<th>Updated By</th>
											<th>Updated At</th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<thead>
										<tr>
											<th> Id</th>
											<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
											<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
											<th>Particulars</th>
											<th>Expense Amount</th>
											<th>Balance</th>
											<th>VoucherNumber</th>
											<th>BillReceiptNumber</th>
											<th>User</th>
											<th>Created By</th>
											<th>Created At</th>
											<th>Updated By</th>
											<th>Updated At</th>
										</tr>
									</thead>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	@php
   	$DeleteTableName="pettycash";
   	$DeleteColumnName="pettycash_id";
   	@endphp
   	@include('delete')
	<x-slot name="page_level_scripts">
		<script type="text/javascript">
			$(function(){
            var table=$('.ExpenseTable').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('pettycash') }}",
               columns: [
               {data:'pettycash_id', name:'a.pettycash_id'},
               {data:'action', name:'a.action', orderable: false, searchable: false},
               {data:'pettycash_date', name:'a.pettycash_date'},
               {data:'particulars', name:'a.particulars'},
               {data:'amount', name:'a.amount'},
               {data:'balance_history', name:'a.balance_history'},
               {data:'voucher_number', name:'a.voucher_number'},
               {data:'bill_receipt_number', name:'a.bill_receipt_number'},
               {data:'user_id', name:'d.first_name'},
               {data:'created_by', name:'b.first_name'},
               {data:'created_at', name:'a.created_at'},
               {data:'updated_by', name:'c.first_name'},
               {data:'updated_at', name:'a.updated_at'},

               ]
            });
         });
         
			$(document).on('click', '.edit_model_btn', function(){
            var pettycash_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'pettycash_edit/'+pettycash_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#EditPettycashModalData").html(data);
                  $("#edit_modal_pettycash").modal('show');
               }
            });
         });

			$(document).on('click', '.PettycashViewModal', function(){
            var pettycash_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: "{{ route('pettycash_modal_view',"") }}/"+pettycash_id,
               type: "post",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#get_quote_frm").html(data);
                  $("#pettycash_view_modal").modal('show');
               }
            });
         });

         $(document).on('click', '.DeleteDataModal', function(){
            var DeleteColumnValue=$(this).closest("tr").find("td:eq(0)").text();
            $("#DeleteColumnValue").val(DeleteColumnValue);
            $("#DeleteDataModal").modal('show');
         });

         $(document).on('click', '#add', function(e) {
	   		$('#ImageTBodyAdd').append('<tr class="add_row"><td><input  name="bill_upload[]" type="file" multiple /></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
	   		e.preventDefault();
			});

			$(document).on('click', '#add', function(e) {
	   		$('#ImageTBodyEdit').append('<tr class="add_row"><td><input  name="bill_upload[]" type="file" multiple /></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
	   		e.preventDefault();
			});

			$(document).on('click', '#delete', function(e){
				$(this).closest("tr").remove();
				e.preventDefault();
			});

			$(document).on('click','.addexpense',function(){
				alert("Insufficient Pettycash Balance!");
			});
		</script>
		<style type="text/css">
			.placeholder {
  				color:rgb(133,130,130);
  				opacity: 1;
  			}
		</style>
	</x-slot>
</x-app-layout>