<x-app-layout>
	<section id="tabs-with-icons">
		<div class="row match-height">
			<div class="col-xl-12 col-lg-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Fund
							<ol class="breadcrumb mt-0">
								<li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
								</li>
							</ol>
							<!-- <ol class="breadcrumb mt-0"> -->
								<!-- <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary"> Balance : </span>
								</li> -->
								<!-- </ol> -->
							</h4>
							<div class="heading-elements mt-0">
								<ul><span class="btn btn-sm p-0 text-primary"><h5 class="text-primary"><b> Balance : {{ $balance1 }}</b></h5></span></ul>
								<a  class="float-right"><button type="submit" data-toggle="modal" data-target="#AddModal"class="btn btn-primary">
									<i class="fa fa-plus"></i> Add Fund
								</button></a>
								<div class="modal fade" id="AddModal"  role="dialog" aria-labelledby="AddModals" aria-hidden="true">
      								<div class="modal-dialog modal-dialog-centered  modal-md" role="document">
								        <div class="modal-content">
								            <section class="contact-form">
								                <div class="modal-header bg-primary white">
								                  <h5 class="modal-title white">Fund Add </h5>
								                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								                     <span aria-hidden="true">&times;</span>
								                  </button>
								                </div>
												<form method="post" action="{{ route('pettycash_topup_submit') }}" enctype="multipart/form-data">
													@csrf
													<div class="modal-body PageBrandingBacground">
														<div class="row">
															
															<div class="col-lg-6">
																<div class="form-group">
																	<fieldset class="form-group floating-label-form-group"><b> Date <sup class="text-danger" style="font-size: 13px;">*</sup>:</b>
																		<input type="date" value="" id="topup_date" required name="topup_date" class="name form-control" placeholder="Course Code">
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
															<div class="col-lg-6">
																<div class="form-group">
																	<fieldset class="form-group floating-label-form-group"><b>Description <sup class="text-danger" style="font-size: 13px;">*</sup>:</b>
																		<input type="text" id="Title" name="description" class="name form-control" placeholder="Description">
																	</fieldset>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<fieldset class="form-group floating-label-form-group"><b>Amount <sup class="text-danger" style="font-size: 13px;">*</sup>:</b>
																		<input type="number" id="Title" name="amount" class="name form-control" placeholder="Amount">
																	</fieldset>
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
					                        <h5 class="modal-title white">Fund Edit</h5>
					                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					                           <span aria-hidden="true">&times;</span>
					                        </button>
					                     </div>
					                     <form method="post" action="{{ route('pettycash_topup_submit') }}">
					                        @csrf
					                     <div id="EditPettycashModalData"></div>
					                     </form>
					                  </section>
					               </div>
					            </div>
					        </div>
						</div>
					</div>
					<div class="card-content">
                  		<div class="card-body">
                     		<div class="table-responsive">
                        		<table class="table table-striped table-bordered FundTable" style="width:100%;">
									<thead>
										<tr>
											<th> Id</th>
											<th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
											<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
											<th>User</th>
											<th>Description</th>
											<th>Amount</th>
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
											<th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
											<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
											<th>User</th>
											<th>Description</th>
											<th>Amount</th>
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
   	$DeleteTableName="pettycash_topup";
   	$DeleteColumnName="topup_id";
   	@endphp
   	@include('delete')
	<x-slot name="page_level_scripts">
		<script type="text/javascript">
			$(function(){
            var table=$('.FundTable').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('pettycash_topup') }}",
               columns: [
               {data:'topup_id', name:'a.topup_id'},
               {data:'action', name:'a.action', orderable: false, searchable: false},
               {data:'topup_date', name:'a.topup_date'},
               {data:'user_id', name:'d.first_name'},
               {data:'description', name:'a.description'},
               {data:'amount', name:'a.amount'},
               {data:'created_by', name:'b.first_name'},
               {data:'created_at', name:'a.created_at'},
               {data:'updated_by', name:'c.first_name'},
               {data:'updated_at', name:'a.updated_at'},

               ]
            });
         });
         
		$(document).on('click', '.edit_model_btn', function(){
            var topup_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'pettycash_topup_edit/'+topup_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#EditPettycashModalData").html(data);
                  $("#edit_modal_pettycash").modal('show');
               }
            });
         });


         $(document).on('click', '.DeleteDataModal', function(){
            var DeleteColumnValue=$(this).closest("tr").find("td:eq(0)").text();
            $("#DeleteColumnValue").val(DeleteColumnValue);
            $("#DeleteDataModal").modal('show');
         });
		</script>
	</x-slot>
</x-app-layout>