<x-app-layout>
	<section id="tabs-with-icons">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Lead Edit
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
												<input type="hidden" name="lead_id" value="{{ $lead->lead_id }}">
												<label class="label-control" for="userinput1">Full Name<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="text" id="userinput1" class="form-control border-primary" placeholder="Full Name" name="lead_name" value="{{ $lead->lead_name }}">
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput2">Mobile Number<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="text" pattern="\+?\d{0,3}[\s\(\-]?([0-9]{2,3})[\s\)\-]?([\s\-]?)([0-9]{3})[\s\-]?([0-9]{2})[\s\-]?([0-9]{2})" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" minlength="10" maxlength="10"  id="MobileNumber" class="form-control border-primary" placeholder="Mobile Number" name="mobile_number" value="{{ $lead->mobile_number }}">
											</div>
											<span id="MobileNumberValid" class="text-center" style="text-align: center;"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput3">Phone Number <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="text" pattern="\+?\d{0,3}[\s\(\-]?([0-9]{2,3})[\s\)\-]?([\s\-]?)([0-9]{3})[\s\-]?([0-9]{2})[\s\-]?([0-9]{2})" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" minlength="10" maxlength="10"  id="userinput3" class="form-control border-primary" placeholder="Alter Mobile Number" name="alter_mobile_number"  value="{{ $lead->alter_mobile_number }}">
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Email Id <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="email"  id="EmailId" class="form-control border-primary" 
												placeholder="Email Id" name="email_id" value="{{ $lead->email_id }}">
											</div>
											<span id="EmailIdValid" class="text-center" style="text-align: center;"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Alter Email Id&nbsp;<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="text"   id="userinput4" class="form-control border-primary" placeholder="Alter Email Id" name="alter_email_id" value="{{ $lead->alter_email_id }}">
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Age <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="number"  id="userinput4" class="form-control border-primary" placeholder="Enter Age" name="age" value="{{ $lead->age }}">
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
												<select class="form-control border-primary select2"  name="medium_id" id="medium_id" style="width: 100%">
													<option selected="selected" value="">Select Medium</option>
													@foreach($get_mediums as $get_medium)
														<option value="{{ $get_medium->medium_id }}" {{ $lead->medium_id == $get_medium->medium_id ? 'selected="selected"' : '' }} >{{ $get_medium->medium_name }}
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
												<select class="form-control border-primary select2"  name="source_id" id="source_id" style="width: 100%">
													<option selected="selected" value="">Select Lead Source</option>
													@foreach($get_sources as $get_source)
														<option value="{{ $get_source->lead_source_id }}"  {{ $lead->source_id == $get_source->lead_source_id ? 'selected="selected"' : '' }}>{{ $get_source->lead_source_name }}
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
													@foreach($get_sub_sources as $get_sub_source)
														@if($lead->sub_source_id!=$get_sub_source->lead_sub_source_id)
															<option value="{{ $get_sub_source->lead_sub_source_id}}">{{$get_sub_source->lead_sub_source_name}}	</option>
													 	@else
	                                          <option value="{{ $lead->sub_source_id}}" selected>{{ $get_sub_source->lead_sub_source_name }}</option> 
	                                       @endif
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Campaigns <sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2"  name="campaign_id" id="campaign_id" style="width: 100%">
													<option selected="selected" value="">Select Campaign</option>
													@foreach($get_campaigns as $get_campaign)
														<option value="{{ $get_campaign->campaign_id }}" {{ $lead->campaign_id == $get_campaign->campaign_id ? 'selected="selected"' : '' }}>{{ $get_campaign->campaign_name }}
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
												<select class="form-control border-primary select2"  name="lead_owner" id="lead_owner" style="width: 100%">
													<option selected="selected" value="">Select Lead Owners</option>
													@foreach($get_users as $get_user)
														<option value="{{ $get_user->id }}" {{ $lead->lead_owner == $get_user->id ? 'selected="selected"' : '' }}>{{ $get_user->first_name }}
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
												<select class="form-control border-primary select2"  name="ad_name_id" id="ad_name_id" style="width: 100%">
													<option selected="selected" value="">Select Ad Names</option>
													@foreach($get_ad_names as $get_ad_name)
														<option value="{{ $get_ad_name->ad_name_id }}" {{ $lead->ad_name_id == $get_ad_name->ad_name_id ? 'selected="selected"' : '' }} >{{ $get_ad_name->ad_name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="lead_stage_id">Lead Status<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2"  name="lead_stages_id" id="lead_stages_id" style="width: 100%">
													<option selected="selected" value="">Select Lead Status</option>
													@foreach($get_lead_statuss as $get_lead_status)
														<option value="{{ $get_lead_status->lead_stage_id }}"  {{ $lead->lead_stage_id == $get_lead_status->lead_stage_id ? 'selected="selected"' : '' }}>{{ $get_lead_status->lead_stage_name }}
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
													@foreach ($get_lead_sub_statuss as $get_lead_sub_status)
	                                       @if($lead->lead_sub_stage_id!=$get_lead_sub_status->lead_sub_stage_id)
	                                          <option value="{{ $get_lead_sub_status->lead_sub_stage_id }}">{{ $get_lead_sub_status->lead_sub_stage }}</option>
	                                       @else
	                                          <option value="{{ $lead->lead_sub_stage_id}}" selected>{{ $get_lead_sub_status->lead_sub_stage }}</option> 
	                                       @endif
                                    	@endforeach
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
												<select class="form-control border-primary select2" name="product_category_id" id="product_category_id"  style="width: 100%"	>
													<option selected value="">Select Product Category</option>
													@foreach($get_product_categories as $get_product_categorie)
														<option value="{{ $get_product_categorie->product_category_id }}" {{ $lead->product_category_id == $get_product_categorie->product_category_id ? 'selected="selected"' : '' }} >{{ $get_product_categorie->product_category_name }}
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
													@foreach ($get_products_details as $get_products_detail)
	                                       @if($lead->product_id!=$get_products_detail->product_id)
	                                          <option value="{{ $get_products_detail->product_id }}">{{ $get_products_detail->product_name }}</option>
	                                       @else
	                                          <option value="{{ $lead->product_id}}" selected>{{ $get_products_detail->product_name }}</option> 
	                                       @endif
                                    	@endforeach
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
												<select class="form-control border-primary select2" name="country_id" id="country_id" style="width: 100%" >
													<option value="0">Select Country</option>
													@foreach ($get_countries as $get_country)
	                                       @if ($lead->country_id!=$get_country->country_id)
	                                          <option value="{{ $get_country->country_id }}">{{ $get_country->country_name }}</option>
	                                       @else
	                                          <option value="{{ $lead->country_id }}" selected>{{ $get_country->country_name }}</option> 
	                                       @endif
                                    	@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">State<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<select class="form-control border-primary select2" id="state_id" name="state_id"  style="width: 100%">
													<option value="0">Unknown</option>
													@foreach ($get_states as $get_state)
	                                       @if($lead->state_id!=$get_state->state_id)
	                                          <option value="{{ $get_state->state_id }}">{{ $get_state->state_name }}</option>
	                                       @else
	                                          <option value="{{ $lead->state_id}}" selected>{{ $get_state->state_name }}</option> 
	                                       @endif
                                    	@endforeach
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
													@foreach ($get_cities as $get_city)
	                                       @if($lead->city_id!=$get_city->city_id)
	                                          <option value="{{  $get_city->city_id }}">{{ $get_city->city_name }}</option>
	                                       @else
	                                          <option value="{{ $lead->city_id }}" selected>{{ $get_city->city_name }}</option>
	                                       @endif
                                    	@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control" for="userinput4">Pin Code<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<input type="number"  id="userinput3" class="form-control border-primary" placeholder="Pin Code" name="pincode" value="{{ $lead->pincode }}">
											</div>
										</div>
									</div>
									<div class="col-md-8">
										<div class="form-group row">
											<div class="col-md-12">
												<label class="label-control " for="userinput3">Address<sup class="text-danger" style="font-size: 13px;">*</sup>:</label>
												<textarea class="form-control border-primary"  placeholder="Address" name="address">{{ $lead->address }}</textarea> 
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
                                 <fieldset class="form-group floating-label-form-group"><label>{{ $GetCustomField->field_name }} @if($GetCustomField->required_field=="Yes")<sup style="color:red;font-size:12px;">*</sup>';@endif :</label><br>
                                 
                                    @if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==6 OR $GetCustomField->field_type==5 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==10)
                                       @php
                                          $lead_value=$lead_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($lead_value, true);
                                          $custom_field_id=$GetCustomField->custom_field_id;
                                          @endphp
                                          @foreach ($GetCustomFieldTypes as $GetCustomFieldType)
                                             @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                                @if($CustomFieldsValueArr!='')
                                                   @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))
                                                      
                                                      <input type="<?=$GetCustomFieldType->field_type?>" class="name form-control" value="<?=$CustomFieldsValueArr[$custom_field_id]?>" placeholder="<?=$GetCustomField->field_name?>" name="Custom-<?=$GetCustomField->custom_field_id?>">
                                                      
                                                   @else
                                                      <input type="<?=$GetCustomFieldType->field_type?>" class="name form-control" value="" placeholder="<?=$GetCustomField->field_name?>" name="Custom-<?=$GetCustomField->custom_field_id?>">
                                                   @endif
                                                @else
                                                   <input type="<?=$GetCustomFieldType->field_type?>" class="name form-control" value="" placeholder="<?=$GetCustomField->field_name?>" name="Custom-<?=$GetCustomField->custom_field_id?>">
                                                @endif
                                             @endif
                                          @endforeach
                                    @elseif($GetCustomField->field_type==6)
                                       @php
                                          $lead_value=$lead_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($lead_value, true);
                                          $custom_field_id=$GetCustomField->custom_field_id;
                                       @endphp
                                       @foreach($GetCustomFieldTypes as $GetCustomFieldType)
                                          @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                             @if($CustomFieldsValueArr!='')
                                                @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))
                                                   <?=$CustomFieldsValueArr[$custom_field_id]?>&nbsp;&nbsp;<a href="public/uploads/Leads/<?=$CustomFieldsValueArr[$custom_field_id]?>" target="_blank"><i class="fa fa-eye"></i></a><input type="<?=$GetCustomFieldType->field_type?>" class="name form-control" value="<?=$CustomFieldsValueArr[$custom_field_id]?>" name="Custom-<?=$GetCustomField->custom_field_id?>">
                                                @else
                                                   <input type="<?=$GetCustomFieldType->field_type?>" class="name form-control" value="" placeholder="<?=$GetCustomField->field_name?>" name="Custom-<?=$GetCustomField->custom_field_id?>">
                                                @endif
                                             @else
                                                <input type="<?=$GetCustomFieldType->field_type?>" class="name form-control" value="" placeholder="<?=$GetCustomField->field_name?>" name="Custom-<?=$GetCustomField->custom_field_id?>">
                                             @endif
                                          @endif  
                                       @endforeach
                                    @elseif($GetCustomField->field_type==7)
                                       <br>
                                       @php
                                       $lead_value=$lead_details->custom_fields;
                                       $CustomFieldsValueArr=json_decode($lead_value, true);
                                       $field_value=$GetCustomField->field_value;
                                       $RadioArr=json_decode($field_value,true);
                                       $custom_field_id=$GetCustomField->custom_field_id;
                                       @endphp
                                       @foreach($RadioArr as $Radios)
                                          @foreach ($GetCustomFieldTypes as $GetCustomFieldType)
                                             @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                                @if($CustomFieldsValueArr!='')
                                                   @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))
                                                      @if($CustomFieldsValueArr[$custom_field_id]==$Radios['FieldValue'])
                                                         @php $checked='checked';  @endphp
                                                      @else
                                                         @php $checked=''; @endphp
                                                      @endif
                                                      &nbsp;&nbsp;<input type="{{ $GetCustomFieldType->field_type }}" class="check" name="Custom-{{ $GetCustomField->custom_field_id }}" value="<?= $Radios['FieldValue'] ?>" {{ $checked }}>&nbsp;&nbsp;<?= $Radios['FieldValue'] ?>&nbsp;&nbsp;
                                                   @else
                                                      &nbsp;&nbsp;<input checked type="{{ $GetCustomFieldType->field_type }}" placeholder="{{  $GetCustomField->field_name }}" name="Custom-{{ $GetCustomField->custom_field_id }}" value="<?= $Radios['FieldValue'] ?>" style="width:15px;height:15px;">&nbsp;&nbsp;<?= $Radios['FieldValue'] ?>&nbsp;&nbsp;
                                                   @endif
                                                @else
                                                   &nbsp;&nbsp;<input checked type="{{ $GetCustomFieldType->field_type }}" placeholder="{{  $GetCustomField->field_name }}" name="Custom-{{ $GetCustomField->custom_field_id }}" value="<?= $Radios['FieldValue'] ?>" style="width:15px;height:15px;">&nbsp;&nbsp;<?= $Radios['FieldValue'] ?>&nbsp;&nbsp;
                                                @endif
                                             @endif
                                          @endforeach
                                       @endforeach
                                    @elseif($GetCustomField->field_type==9)
                                       <br>
                                       @php
                                       $field_value=$GetCustomField->field_value;
                                       $CheckboxArr=json_decode($field_value, true);
                                       $lead_value=$lead_details->custom_fields;
                                       $CustomFieldsValueArr=json_decode($lead_value, true);
                                       $custom_field_id=$GetCustomField->custom_field_id;
                                       @endphp
                                       @foreach($CheckboxArr as $Checkbox)
                                          @foreach($GetCustomFieldTypes as $GetCustomFieldType)
                                             @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                                @if($CustomFieldsValueArr!='')
                                                   @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))
                                                      @if(in_array($Checkbox['FieldValue'], explode(",", $CustomFieldsValueArr[$custom_field_id])))
                                                         @php $checked='checked'; @endphp
                                                      @else
                                                         @php $checked=''; @endphp
                                                      @endif
                                                      &nbsp;&nbsp;<input type="{{ $GetCustomFieldType->field_type }}" name="Custom-{{ $GetCustomField->custom_field_id }}[]" value="<?= $Checkbox['FieldValue'] ?>" {{ $checked }}>&nbsp;&nbsp;<?= $Checkbox['FieldValue']?>
                                                   @else
                                                   &nbsp;&nbsp;<input type="{{ $GetCustomFieldType->field_type }}" name="Custom-{{ $GetCustomField->custom_field_id }}[]" value="<?= $Checkbox['FieldValue'] ?>" style="width:15px;height:15px;">&nbsp;&nbsp;<?= $Checkbox['FieldValue'] ?>&nbsp;&nbsp;
                                                   @endif
                                                @else
                                                   &nbsp;&nbsp;<input type="{{ $GetCustomFieldType->field_type }}" name="Custom-{{ $GetCustomField->custom_field_id }}[]" value="<?= $Checkbox['FieldValue'] ?>" style="width:15px;height:15px;">&nbsp;&nbsp;<?= $Checkbox['FieldValue'] ?>&nbsp;&nbsp; 
                                                @endif
                                             @endif
                                          @endforeach
                                       @endforeach
                                    @elseif($GetCustomField->field_type==3)
                                       <select class="select2 form-control" style="width: 100%;" name="Custom-{{ $GetCustomField->custom_field_id }}">
                                          <option value="">Select {{ $GetCustomField->field_name }}</option>
                                          @php
                                          $field_value=$GetCustomField->field_value;
                                          $OptionsArr=json_decode($field_value, true);
                                          $lead_value=$lead_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($lead_value, true);
                                          $custom_field_id=$GetCustomField->custom_field_id;
                                          @endphp
                                          @foreach($OptionsArr as $Options)
                                             @if($CustomFieldsValueArr!='')
                                                @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))
                                                   @if($CustomFieldsValueArr[$custom_field_id]==$Options['FieldValue'])
                                                    @php $selected='selected'; @endphp
                                                   @else
                                                    @php $selected=''; @endphp
                                                   @endif
                                                   <option value="<?= $Options['FieldValue'] ?>" {{ $selected }}><?= $Options['FieldValue']?></option>
                                                   
                                                @else
                                                   <option><?= $Options['FieldValue']?></option>
                                                @endif
                                             @else
                                                <option><?= $Options['FieldValue']?></option>
                                             @endif
                                          @endforeach
                                       </select>
                                    @elseif($GetCustomField->field_type==11)
                                       @php
                                          
                                          $lead_value=$lead_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($lead_value, true);
                                          $custom_field_id=$GetCustomField->custom_field_id;
                                          @endphp
                                             @if($CustomFieldsValueArr!='')
                                                @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))
                                                   
                                                   <textarea name="Custom-<?= $GetCustomField->custom_field_id ?>" class="form-control" placeholder="<?=$GetCustomField->field_name?>"><?=$CustomFieldsValueArr[$custom_field_id]?></textarea>
                                                   
                                                @else
                                                   <textarea name="Custom-<?= $GetCustomField->custom_field_id ?>" class="form-control" placeholder="<?=$GetCustomField->field_name?>"></textarea>
                                                @endif
                                             @else
                                                <textarea name="Custom-<?= $GetCustomField->custom_field_id ?>" class="form-control" placeholder="<?=$GetCustomField->field_name?>"></textarea>
                                             @endif
                                          
                                    @endif
                              </div>
                           </div>
                        @endforeach
		                        @else
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
		                        @endif
		                    	</div>
								<div class="form-actions right">
									<a href="{{ route('leads') }}">
										<button type="button" class="btn btn-danger mr-1">
											<i class="fa fa-times"></i> Cancel
										</button>
									</a>
									<button type="submit" name="AddSubmit" class="btn btn-primary">
										<i class="fa fa-check"></i> Update
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
				var source_id=$(this).val();
				$.post("{{route ('source_ajax')}}",{_token:"{{ csrf_token()}}",source_id:source_id},function(data){
					$("#sub_source_id").html(data);
				});
			});
	 		$(document).on('change', '#product_category_id', function(){
				var product_category_id=$(this).val();
				$.post("{{route ('product_ajax')}}",{_token:"{{ csrf_token()}}",product_category_id:product_category_id},function(data){
					$("#product_id").html(data);
				});
			});
			$(document).on('change', '#country_id', function(){
				var country_id=$(this).val();
				$.post("{{route ('state_ajax')}}",{_token:"{{ csrf_token()}}",country_id:country_id},function(data){
					$("#state_id").html(data);
				});
			});
			$(document).on('change', '#state_id', function(){
				var state_id=$(this).val();
				$.post("{{route ('city_ajax')}}",{_token:"{{ csrf_token()}}",state_id:state_id},function(data){
					$("#city_id").html(data);
				});
			});
			$(document).on('change', '#lead_stages_id', function(){
				var lead_stage_id=$(this).val();
				$.post("{{route ('lead_substage_ajax')}}",{_token:"{{ csrf_token() }}",lead_stage_id:lead_stage_id},function(data){
                    $("#lead_sub_stages_id").html(data);
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