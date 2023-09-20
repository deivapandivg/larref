<x-app-layout>
	<section id="tabs-with-icons">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Lead Add
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
												<label class="label-control" for="userinput1">Full Name<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="text" id="userinput1" class="form-control border-primary" placeholder="Full Name" name="lead_name" required>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput2">Mobile Number<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="text" pattern="\+?\d{0,3}[\s\(\-]?([0-9]{2,3})[\s\)\-]?([\s\-]?)([0-9]{3})[\s\-]?([0-9]{2})[\s\-]?([0-9]{2})" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" minlength="10" maxlength="10"  id="MobileNumber" class="form-control border-primary" placeholder="Mobile Number" name="mobile_number" required>
											</div>
											<span id="MobileNumberValid" class="text-center" style="text-align: center;"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput3">Phone Number <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="text" pattern="\+?\d{0,3}[\s\(\-]?([0-9]{2,3})[\s\)\-]?([\s\-]?)([0-9]{3})[\s\-]?([0-9]{2})[\s\-]?([0-9]{2})" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" minlength="10" maxlength="10"  id="userinput3" class="form-control border-primary" placeholder="Alter Mobile Number" name="alter_mobile_number" required>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Email Id <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="email"  id="EmailId" class="form-control border-primary" 
												placeholder="Email Id" name="email_id" required>
											</div>
											<span id="EmailIdValid" class="text-center" style="text-align: center;"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Alter Email Id&nbsp;<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="text"   id="userinput4" class="form-control border-primary" placeholder="Alter Email Id" name="alter_email_id" required>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Age <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="number"  id="userinput4" class="form-control border-primary" placeholder="Enter Age" name="age" required>
											</div>
										</div>
									</div>
								</div>
								<h4 class="form-section"><i class="fa fa-address-card"></i> More Details</h4>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Medium <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2"  name="medium_id" id="medium_id" style="width: 100%" required>
													<option selected="selected" value="">Select Medium</option>
													@foreach($get_mediums as $get_medium)
														<option value="{{ $get_medium->medium_id }}">{{ $get_medium->medium_name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="lead_source_id">Lead Source<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2"  name="source_id" id="source_id" style="width: 100%" required>
													<option selected="selected" value="">Select Lead Source</option>
													@foreach($get_sources as $get_source)
														<option value="{{ $get_source->lead_source_id }}">{{ $get_source->lead_source_name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="lead_sub_source_id">Lead Sub Source<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2"  name="sub_source_id" id="sub_source_id" style="width: 100%">
													<option selected="selected" value="">Select Lead Sub Source</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Campaigns <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2"  name="campaign_id" id="campaign_id" style="width: 100%" required>
													<option selected="selected" value="">Select Campaign</option>
													@foreach($get_campaigns as $get_campaign)
														<option value="{{ $get_campaign->campaign_id }}">{{ $get_campaign->campaign_name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Lead Owners <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2"  name="lead_owner" id="lead_owner" style="width: 100%" required>
													<option selected="selected" value="">Select Lead Owners</option>
													@foreach($get_users as $get_user)
														<option value="{{ $get_user->id }}">{{ $get_user->first_name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Ad Names <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2"  name="ad_name_id" id="ad_name_id" style="width: 100%" required>
													<option selected="selected" value="">Select Ad Names</option>
													@foreach($get_ad_names as $get_ad_name)
														<option value="{{ $get_ad_name->ad_name_id }}">{{ $get_ad_name->ad_name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Lead Status <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2"  name="lead_stages_id" id="lead_stages_id" style="width: 100%" required>
													<option selected="selected" value="">Select Lead Status</option>
													@foreach($get_lead_stages as $get_lead_stage)
														<option value="{{ $get_lead_stage->lead_stage_id }}">{{ $get_lead_stage->lead_stage_name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="lead_sub_stages_id">Lead Sub Status<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2"  name="lead_sub_stages_id" id="lead_sub_stages_id" style="width: 100%">
													<option selected="selected" value="">Select Lead Sub Status</option>
												</select>
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
												<label class="label-control" for="userinput4">Product Category <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2" name="product_category_id" id="product_category_id"  style="width: 100%" required>
													<option selected value="">Select Product Category</option>
													@foreach($get_product_categories as $get_product_categorie)
														<option value="{{ $get_product_categorie->product_category_id }}">{{ $get_product_categorie->product_category_name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Product <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2"  name="product_id" id="product_id"  style="width: 100%">
													<option selected value="">Select Product</option>
													<option value=""></option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<h4 class="form-section"><i class="fa fa-address-card"></i>Address Details</h4>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Country<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2" name="country_id" id="country_id" style="width: 100%" required>
													<option value="0">Select Country</option>
													@foreach($get_countries as $get_country)
														<option value="{{ $get_country->country_id }}">{{ $get_country->country_name }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">State<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2" id="state_id" name="state_id" style="width: 100%">
													<option value="0">Unknown</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">City <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2" id="city_id" name="city_id"  style="width: 100%">
													<option value="0">Unknown</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Pin Code<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="number"  id="userinput3" class="form-control border-primary" placeholder="Pin Code" name="pincode" required>
											</div>
										</div>
									</div>
									<div class="col-md-8">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control " for="userinput3">Address<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<textarea class="form-control border-primary"  placeholder="Address" name="address" required></textarea> 
											</div>
										</div>
									</div>
								</div>
								<h4 class="form-section"><i class="fa fa-address-card"></i>Custom Details</h4>
								<div class="row">
	                        	@foreach($GetCustomFields as $GetCustomField)
		                        <div class="col-lg-4">
		                           <div class="form-group">
		                              <fieldset class="form-group floating-label-form-group"><b>{{ $GetCustomField->field_name }} @if($GetCustomField->required_field=="Yes") <sup style="color:red;font-size:12px;">*</sup>@endif :</b><br>
		                              
		                                 @if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==10 OR $GetCustomField->field_type==6 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==5)
		                                    @foreach($GetCustomFieldTypes as $GetCustomFieldType)
		                                       @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type) 
		                                          <input type="{{ $GetCustomFieldType->field_type }}" class="name form-control" value="@if ($GetCustomField->field_type==5) echo date('Y-m-d');@elseif($GetCustomField->field_type==8) echo date('h:i');@endif" placeholder="{{ $GetCustomField->field_name }}" name="Custom-{{ $GetCustomField->custom_field_id }}">
		                                       @endif
		                                    @endforeach
		                                 @elseif($GetCustomField->field_type==7)
                                    <br>
                                       @php
                                       $field_value=$GetCustomField->field_value;
                                       $RadioArr=json_decode($field_value,true);
                                       @endphp
                                          @foreach($RadioArr as $data)
                                          @foreach ($GetCustomFieldTypes as $GetCustomFieldType)
                                             @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type) 
                                                &nbsp;&nbsp;<input checked type="{{ $GetCustomFieldType->field_type }}" placeholder="{{  $GetCustomField->field_name }}" name="Custom-{{ $GetCustomField->custom_field_id }}" value="<?= $data['FieldValue'] ?>" style="width:15px;height:15px;">&nbsp;&nbsp;<?= $data['FieldValue'] ?>&nbsp;&nbsp;
                                             @endif
                                          @endforeach
                                          @endforeach
		                                       
		                                 @elseif($GetCustomField->field_type==9)
		                                 @php
		                                    $CheckboxArr=json_decode($GetCustomField->field_value, true);@endphp
		                                    @foreach ($CheckboxArr as $Checkbox)
		                                       &nbsp;&nbsp;<input type="{{ $GetCustomField->field_type }}"   placeholder="{{  $GetCustomField->field_name }}" name="Custom-{{ $GetCustomField->custom_field_id }}[]" value="{{ $Checkbox->field_value }}"> {{ $Checkbox->field_value }}&nbsp;&nbsp;
		                                    @endforeach   
		                                 @elseif($GetCustomField->field_type==3)
                                       <select class="select2 form-control" style="width: 100%;" name="Custom-{{ $GetCustomField->custom_field_id }}">
                                          <option value="">Select {{  $GetCustomField->field_name }}</option>
                                          @php
                                          $field_value=$GetCustomField->field_value;
                                          $OptionsArr=json_decode($field_value, true);
                                          @endphp
                                          <?php
                                    
                                    foreach ($OptionsArr as $Options)
                                    {
                                       echo "<option>".$Options['FieldValue']."</option>";
                                    }
                                    ?>
                                           
                                       </select>
		                                 @elseif($GetCustomField->field_type==11)
		                                    <textarea name="Custom-{{ $GetCustomField->custom_field_id }}" class="form-control" placeholder="{{  $GetCustomField->field_name }}"></textarea>
		                                 @endif
		                           </div>
		                        </div>
		                        @endforeach
                     		</div>
							<div class="form-actions right">
								<a href="">
									<button type="button" class="btn btn-danger mr-1">
										<i class="fa fa-times"></i> Cancel
									</button>
								</a>
								<button type="submit" name="AddSubmit" class="btn btn-primary">
									<i class="fa fa-check"></i> Save
								</button>
							</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<x-slot name="page_level_scripts">
	 	<script type="text/javascript">

	 		$(document).on('change', '#source_id', function(){
	 			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				var source_id=$(this).val();
				$.post("source_ajax",{_token:CSRF_TOKEN,source_id:source_id},function(data){
					$("#sub_source_id").html(data);
				});
			});

			$(document).on('change', '#lead_stages_id', function(){
				var lead_stage_id=$(this).val();
				$.post("{{route ('lead_substage_ajax')}}",{_token:"{{ csrf_token() }}",lead_stage_id:lead_stage_id},function(data){
                    $("#lead_sub_stages_id").html(data);
                });
			});
			

	 		$(document).on('change', '#product_category_id', function(){
	 			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				var product_category_id=$(this).val();
				$.post("product_ajax",{_token:CSRF_TOKEN,product_category_id:product_category_id},function(data){
					$("#product_id").html(data);
				});
			});

			$(document).on('change', '#country_id', function(){
	 			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				var country_id=$(this).val();
				$.post("state_ajax",{_token:CSRF_TOKEN,country_id:country_id},function(data){
					$("#state_id").html(data);
				});
			});

			$(document).on('change', '#state_id', function(){
	 			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				var state_id=$(this).val();
				$.post("city_ajax",{_token:CSRF_TOKEN,state_id:state_id},function(data){
					$("#city_id").html(data);
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