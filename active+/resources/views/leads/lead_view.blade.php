<x-app-layout>
	<section id="tabs-with-icons">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Lead View
							<ol class="breadcrumb mt-0">
								<li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
								</li>
							</ol>
						</h4>
					</div>
					<div class="card-body">
						<form class="form form-horizontal" method="post" enctype="multipart/form-data" action="{{ route('leads_submit') }}">@csrf
							<div class="form-body">
								<h4 class="form-section"><i class="fa fa-book"></i> Lead Details</h4>
								<!-- <hr class="bg-primary" style="height:1px;"> -->
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput1">Full Name :</label>
												@if(isset($lead->lead_name))
												<p>{{ $lead->lead_name }}</p>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput2">Mobile Number :</label>
												@if(isset($masked_mobile_number))
												<p>{{ $masked_mobile_number }}</p>
												@endif
											</div>
											<span id="MobileNumberValid" class="text-center" style="text-align: center;"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput3">Alter Number  :</label>
												@if(isset($masked_alter_mobile_number))
												<p>{{ $masked_alter_mobile_number }}</p>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Email Id  :</label>
												@if(isset($masked_email_id))
												<p>{{ $masked_email_id }}</p>
												@endif
											</div>
											<span id="EmailIdValid" class="text-center" style="text-align: center;"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Alter Email Id&nbsp; :</label>
												@if(isset($masked_alter_email_id))
												<p>{{ $masked_alter_email_id }}</p>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Age  :</label>
												@if(isset($lead->age))
												<p>{{ $lead->age }}</p>
												@endif
											</div>
										</div>
									</div>
								</div>
								<h4 class="form-section"><i class="fa fa-address-card"></i> More Details</h4>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Medium  :</label>
												@if(isset($get_mediums->medium_name))
												<p>{{ $get_mediums->medium_name }}</p>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="lead_source_id">Lead Source :</label>
												@if(isset($get_mediums->lead_source_name))
												<p>{{ $get_mediums->lead_source_name }}</p>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="lead_sub_source_id">Lead Sub Source :</label>
												@if(isset($get_sub_sources->lead_sub_source_name))
												<p>{{ $get_sub_sources->lead_sub_source_name }}</p>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Campaigns  :</label>
												@if(isset($get_campaigns->campaign_name))
												<p>{{ $get_campaigns->campaign_name }}</p>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Lead Owners  :</label>
												@if(isset($get_users->first_name))
												<p>{{ $get_users->first_name }}</p>
												@endif
											</div>
										</div>
									</div>
								</div>
								<h4 class="form-section"><i class="fa fa-shopping-cart"></i>Interested / Product Details
								</h4>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Product  :</label>
												@if(isset($get_products->product_name))
												<p>{{ $get_products->product_name }}</p>
												@endif
											</div>
										</div>
									</div>
								</div>
								<h4 class="form-section"><i class="fa fa-address-card"></i>Address Details</h4>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Country :</label>
												@if(isset($get_countries->country_name))
												<p>{{ $get_countries->country_name }}</p>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">State :</label>
												@if(isset($get_states->state_name))
												<p>{{ $get_states->state_name }}</p>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">City  :</label>
												@if(isset($get_cities->city_name))
												<p>{{ $get_cities->city_name }}</p>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Pin Code :</label>
												@if(isset($lead->pincode))
												<p>{{ $lead->pincode }}</p>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-8">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control " for="userinput3">Address :</label>
												@if(isset($lead->address))
												<p>{{ $lead->address }}</p>
												@endif 
											</div>
										</div>
									</div>
								</div>
								<h4 class="form-section"><i class="fa fa-book"></i> Custom Details</h4>
                        <div class="row">
                        @if($lead_details->custom_fields!='')
                        @foreach($GetCustomFields as $GetCustomField)
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label class="label-control"><?= $GetCustomField->field_name ?>:</label><br>
                                 @if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==10 OR $GetCustomField->field_type==6 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==5)
                                 @php 
                                    $lead_value=$lead_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($lead_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp 
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr)) <?= $CustomFieldsValueArr[$custom_field_id] ?> @endif </p>
                                    @if($GetCustomField->field_type==6)
                                       <a href="<?= $CustomFieldsValueArr[$custom_field_id] ?>">Click to View</a>
                                    @endif
                                 @endif
                                 @if($GetCustomField->field_type==6) 
                                 
                                    @if($GetCustomField->field_type==6)
                                    
                                       <a href="<?= $CustomFieldsValueArr[$custom_field_id] ?>">Click to View</a>
                                    @endif
                                 @elseif($GetCustomField->field_type==7)
                                    @php 
                                    $lead_value=$lead_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($lead_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif</p>
                                    
                                 @elseif($GetCustomField->field_type==9)
                                    @php 
                                    $lead_value=$lead_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($lead_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))&nbsp;&nbsp;<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif </p>
                                    
                                 @elseif($GetCustomField->field_type==3)
                                 
                                    @php
                                    $field_value=$GetCustomField->field_value;
                                    $OptionsArr=json_decode($field_value, true);
                                    $lead_value=$lead_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($lead_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif</p>
                                    
                                 @elseif($GetCustomField->field_type==11)
                                    @php
                                    $lead_value=$lead_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($lead_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p>@if(array_key_exists($custom_field_id,$CustomFieldsValueArr))<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif</p>
                                 @endif
                              </div>
                           </div>
                        @endforeach
                        @endif
                        </div>
								<!-- <div class="form-actions right">
									<a href="">
										<button type="button" class="btn btn-danger mr-1">
											<i class="fa fa-times"></i> Cancel
										</button>
									</a>
									<button type="submit" name="AddSubmit" class="btn btn-primary">
										<i class="fa fa-check"></i> Save
									</button>
								</div> -->
							</div>
						</form><hr>
						<h4>Lead Details</h4><br>
						 <ul class="nav nav-tabs nav-underline no-hover-bg" role="tablist">
                     <li class="nav-item">
                        <a class="nav-link active" id="base-tab31" data-toggle="tab" aria-controls="tab31" href="#tab31" role="tab" aria-selected="true"><i class="fa fa-clock"></i>TimeLine</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" id="base-tab32" data-toggle="tab" aria-controls="tab32" href="#tab32" role="tab" aria-selected="false"><i class="fa fa-comments"></i>Sms</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" id="base-tab33" data-toggle="tab" aria-controls="tab33" href="#tab33" role="tab" aria-selected="false"><i class="fa  fa-envelope"></i>Mail</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" id="base-tab34" data-toggle="tab" aria-controls="tab34" href="#tab34" role="tab" aria-selected="false"><i class="fab fa-whatsapp"></i>Whatsapp</a>
                     </li>
                  </ul>
                     <hr>
                     <div class="tab-content px-1 pt-1">
                        <div class="tab-pane active" id="tab31" role="tabpanel" aria-labelledby="base-tab31">
                          <table class="table table-responsive table-striped table-bordered timeline_table" style="width: 100%;">
										<thead>
											<tr>
												<th>TimeLine Id</th>
	                                 <th>LeadName</th>
	                                 <th>MobileNumber</th>
	                                 <th>Communicate Medium</th>
	                                 <th>Communicate Type</th>
	                                 <th>Lead Stage</th>
	                                 <th>Lead SubStage</th>
	                                 <th>Comments</th>
	                                 <th>Created At</th>
	                                 <th>Created By</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
										<tfoot>
											<tr>
												<th>TimeLine Id</th>
	                                 <th>LeadName</th>
	                                 <th>MobileNumber</th>
	                                 <th>Communicate Medium</th>
	                                 <th>Communicate Type</th>
	                                 <th>Lead Stage</th>
	                                 <th>Lead SubStage</th>
	                                 <th>Comments</th>
	                                 <th>Created At</th>
	                                 <th>Created By</th>
											</tr>
										</tfoot>
									</table>
                           </div>
                           <div class="tab-pane" id="tab32" role="tabpanel" aria-labelledby="base-tab32">
                              <table class="table table-striped table-bordered sms_table" style="width: 100%;">
										<thead>
											<tr>
												<th>Log Id</th>
	                                 <th>MobileNumber</th>
	                                 <th>Content</th>
	                                 <th>Created At</th>
	                                 <th>Created By</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
										<tfoot>
											<tr>
												<th>Log Id</th>
	                                 <th>MobileNumber</th>
	                                 <th>Content</th>
	                                 <th>Created At</th>
	                                 <th>Created By</th>
											</tr>
										</tfoot>
									</table>
                        </div>
                        <div class="tab-pane" id="tab33" role="tabpanel" aria-labelledby="base-tab33">
                            <table class="table table-striped table-bordered mail_table" style="width: 100%;">
										<thead>
											<tr>
												<th>Log Id</th>
	                                 <th>Email</th>
	                                 <th>Subject</th>
	                                 <th>Comments</th>
	                                 <th>Created At</th>
	                                 <th>Created By</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
										<tfoot>
											<tr>
												<th>Log Id</th>
	                                 <th>Email</th>
	                                 <th>Subject</th>
	                                 <th>Comments</th>
	                                 <th>Created At</th>
	                                 <th>Created By</th>
											</tr>
										</tfoot>
									</table>
                           </div>
                           <div class="tab-pane" id="tab34" role="tabpanel" aria-labelledby="base-tab34">
                             <table class="table table-striped table-bordered whatsapp_table" style="width: 100%;">
										<thead>
											<tr>
												<th>Log Id</th>
	                                 <th>MobileNumber</th>
	                                 <th>Content</th>
	                                 <th>Created At</th>
	                                 <th>Created By</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
										<tfoot>
											<tr>
												<th>Log Id</th>
	                                 <th>MobileNumber</th>
	                                 <th>Content</th>
	                                 <th>Created At</th>
	                                 <th>Created By</th>
											</tr>
										</tfoot>
									</table>
                        </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<x-slot name="page_level_scripts">
	 	<script type="text/javascript">
	 		 $(function () {
            var table = $('.timeline_table').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('lead_view',$lead->lead_id) }}",
               columns: [
               {data: 'timeline_id', name: 'a.timeline_id'},
	            {data: 'lead_name', name: 'c.lead_name'},
	            {data: 'mobile_number', name: 'c.mobile_number'},
	            {data: 'communication_medium', name: 'd.communication_medium'},
	            {data: 'communication_type', name: 'e.communication_type'},
	            {data: 'lead_stage_name', name: 'f.lead_stage_name'},
	            {data: 'lead_sub_stage', name: 'g.lead_sub_stage'},
	            {data: 'description', name: 'a.description'},
	            {data: 'first_name', name: 'b.first_name'},
	            {data: 'created_at', name: 'a.created_at'},
               ]
            });
         });

	 		 $(function () {
            var table = $('.sms_table').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('sms_view',$lead->lead_id) }}",
               columns: [
               {data: 'log_id', name: 'a.log_id'},
	            {data: 'mobile_number', name: 'a.mobile_number'},
	            {data: 'content', name: 'a.content'},
	            {data: 'created_at', name: 'a.created_at'},
	            {data: 'created_by', name: 'b.first_name'},
               ]
            });
         });
	 		  $(function () {
            var table = $('.mail_table').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('mail_view',$lead->lead_id) }}",
               columns: [
               {data: 'log_id', name: 'a.log_id'},
	            {data: 'email', name: 'a.email'},
	            {data: 'subject', name: 'a.subject'},
	            {data: 'comments', name: 'a.comments'},
	            {data: 'created_at', name: 'a.created_at'},
	            {data: 'created_by', name: 'b.first_name'},
               ]
            });
         });
	 		   $(function () {
            var table = $('.whatsapp_table').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('whatsapp_view',$lead->lead_id) }}",
               columns: [
               {data: 'log_id', name: 'a.log_id'},
	            {data: 'mobile_number', name: 'a.mobile_number'},
	            {data: 'content', name: 'a.content'},
	            {data: 'created_at', name: 'a.created_at'},
	            {data: 'created_by', name: 'b.first_name'},
               ]
            });
         });
	 	</script>
	</x-slot>
  <style type="text/css">
      form .form-section
   {
      line-height: 3rem;
      margin-bottom: 20px;
      color: #82a3de;
      border-bottom: 1px solid #9cb6e5;
   }
 </style>
</x-app-layout>