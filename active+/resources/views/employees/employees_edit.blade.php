<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Employees Edit
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>   
               <div class="card-body">
                  <form class="form" method="POST" enctype="multipart/form-data" action="{{ route('employees_submit',['id' => $user_details->id]) }}">
                     @csrf
                     <input type="hidden" name="id" value="{{ $user_details->id }}">
                     <div class="form-body">
                        <h4 class="form-section"><i class="fa fa-book"></i> Employee Details</h4>
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group ">
                                 <label class="label-control">First Name :</label>
                                 <input type="text" id="" class="form-control border-primary" placeholder="First Name" name="first_name" value="{{ $user_details->first_name }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Last Name :</label>
                                 <input type="text" id="" class="form-control border-primary" placeholder="Last Name" name="last_name" value="{{ $user_details->last_name }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Employee Code :</label>
                                 <input type="text" id="" class="form-control border-primary" placeholder="Employee Code" name="employee_code" value="{{ $user_details->employee_code }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Employee Name :</label>
                                 <input type="text" id="" class="form-control border-primary" placeholder="Employee Full Name" name="employee_name"  value="{{ $user_details->employee_name }}">
                              </div>
                           </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                    <label class="label-control">Gender :</label><br>
                                    <div class="d-inline-block custom-control custom-radio">
                                       <input type="radio" checked name="gender" id="Men" value="1" {{$user_details->gender == '1' ? 'checked':''}}> Male</option>
                                    </div>&nbsp;&nbsp;
                                    <div class="d-inline-block custom-control custom-radio">
                                       <input type="radio" name="gender" id="Women" value="2" {{$user_details->gender == '2' ? 'checked':''}}> Female</option>
                                    </div>
                                     <div class="d-inline-block custom-control custom-radio">
                                       <input type="radio" name="gender" id="others" value="3" {{$user_details->gender == '3' ? 'checked':''}}> Others</option>
                                    </div>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Date Of Birth :</label>
                                 <input type="date" id="" class="form-control border-primary" 
                                 placeholder="" name="date_of_birth" value="{{ $user_details->date_of_birth }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                    <label class="label-control">Personal Mail Id :</label>
                                    <input type="email" id="" class="form-control border-primary" placeholder="Mail Id" name="personal_mail_id" value="{{ $user_details->personal_mail_id }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                    <label class="label-control">Official Mail Id :</label>
                                    <input type="email" id="" class="form-control border-primary" placeholder="Mail Id" name="official_mail_id" value="{{ $user_details->email }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Personal Mobile Number :</label>
                                 <input type="number" id="" class="form-control border-primary" placeholder="Personal Mobile Number" name="personal_mobile_number" value="{{ $user_details->personal_mobile_number }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Emergency Contact Number :</label>
                                 <input type="number" id="" class="form-control border-primary" placeholder="Emergency Contact Number" name="emergency_contact_number" value="{{ $user_details->emergency_contact_number }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Blood Group :</label>
                                 <input type="text" id="" class="form-control border-primary" placeholder="Blood Group" name="blood_group" value="{{ $user_details->blood_group }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Date Of Joining :</label>
                                 <input type="date" id="" class="form-control border-primary" placeholder="" name="date_of_joining" value="{{ $user_details->date_of_joining }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Designation :</label>
                                 <select class="form-control border-primary select2 form-select" name="designation_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($designation_lists as $designation_list)
                                       <option value="{{ $designation_list->designation_id }}" {{ $designation_list->designation_id == $user_details->designation_id ? 'selected' : '' }}>{{ $designation_list->designation_name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div><div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Department :</label>
                                 <select class="form-control border-primary select2 form-select" name="department_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($department_lists as $department_list)
                                    <option value="{{  $department_list->department_id }}" {{ $department_list->department_id == $user_details->department_id ? 'selected':'' }}>{{ $department_list->department_name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Team :</label>
                                 <select class="form-control border-primary select2 form-select" name="team_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($team_lists as $team_list)
                                    <option value="{{  $team_list->team_id }}" {{ $team_list->team_id == $user_details->team_id ? 'selected':'' }}>{{ $team_list->team_name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Reporting To :</label>
                                 <select class="form-control border-primary select2 form-select" name="reporting_to_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($reporting_to_lists as $reporting_to_list)
                                    <option value="{{  $reporting_to_list->id }}" {{ $reporting_to_list->id == $user_details->reporting_to_id ? 'selected':'' }}>{{ $reporting_to_list->first_name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label class="label-control">Shift Name :</label>
                                 <select class="form-control border-primary select2 form-select" name="shift_id" id="shift_id" data-placeholder="Choose one" style="width:100%;" required>
                                    <option selected disabled>Select Shift</option>
                                    @foreach ($shift_lists as $shift_list)
                                    <option value="{{  $shift_list->shift_id }}" {{ $user_details->shift_id==$shift_list->shift_id ? 'selected' : '' }}>{{ $shift_list->shift_name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label class="label-control">Shift Timing :</label>
                                 <select class="form-control border-primary select2 form-select" name="shift_timing_id" id="shift_timing" data-placeholder="Choose one" style="width:100%;" required>
                                    <option selected disabled>Select Timing</option>
                                    @foreach ($shift_timing_lists as $shift_timing_list)
                                       <option value="{{ $user_details->shift_timing_id }}" {{ $user_details->shift_id==$shift_timing_list->shift_id ? 'selected' : '' }}>{{ $shift_timing_list->shift_timing }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Profile Upload :</label>
                                 <input type="file" class="form-control border-primary" placeholder="Profile Upload" name="profile_upload" accept="image/*" value="">
                              </div>
                           </div>
                           @if($user_details->profile_upload!='')
                              <div class="col-md-4">
                                    <a href="{{ asset('public/profile_uploads/' . $user_details->profile_upload) }}" target="target"><img class="rounded" height="100" width="100" src="{{ asset('public/profile_uploads/' . $user_details->profile_upload) }}"></a>
                              </div>
                              <!-- </div> -->
                           @endif
                        </div>
                         <h4 class="form-section"><i class="fa fa-address-card"></i> Address Details</h4>
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Country :</label>
                                 <select class="form-control border-primary select2 form-select country_id" id="country_id" name="country_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected="selected" disabled="disabled">Select Country</option>
                                    @foreach ($country_lists as $country_list)
                                       @if ($user_details->country_id!=$country_list->country_id)
                                          <option value="{{  $country_list->country_id }}">{{ $country_list->country_name }}</option>
                                       @else
                                          <option value="{{ $user_details->country_id }}" selected>{{ $country_list->country_name }}</option> 
                                       @endif
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">State :</label>
                                 <select class="form-control border-primary select2 form-select" id="state_id" name="state_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected="selected">Select State</option>
                                    @foreach ($state_lists as $state_list)
                                       @if($user_details->state_id!=$state_list->state_id)
                                          <option value="{{  $state_list->state_id }}">{{ $state_list->state_name }}</option>
                                       @else
                                          <option value="{{ $user_details->state_id}}" selected>{{ $state_list->state_name }}</option> 
                                       @endif
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">City :</label>
                                 <select class="form-control border-primary select2 form-select" id="city_id" name="city_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected="selected">Select City</option>
                                    @foreach ($city_lists as $city_list)
                                       @if($user_details->city_id!=$city_list->city_id)
                                          <option value="{{  $city_list->city_id }}">{{ $city_list->city_name }}</option>
                                       @else
                                          <option value="{{ $user_details->city_id }}" selected>{{ $city_list->city_name }}</option>
                                       @endif
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Pin Code :</label>
                                 <input type="number" id="" class="form-control border-primary" placeholder="Pin Code" name="pin_code" value="{{ $user_details->pin_code }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Address :</label>
                                 <textarea class="form-control" placeholder="Flat no, Street Name, Area" name="address">{{ $user_details->address }}</textarea>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Address Upload : <a href="{{ asset('public/address_uploads/' . $user_details->address_upload) }}" target="target"><i class="fa fa-eye"></i></a></label>
                                 <input type="file" class="form-control border-primary" placeholder="Address Upload" name="address_upload">
                              </div>
                           </div>
                           <!-- @if($user_details->address_upload!='')
                              <div class="col-md-4">
                                    <a href="{{ asset('public/address_uploads/' . $user_details->address_upload) }}" target="target"><img class="rounded" height="100" width="100" src="{{ asset('public/address_uploads/' . $user_details->address_upload) }}"></a>
                              </div>
                              </div> -->
                           <!-- @endif -->
                        </div>
                        <h4 class="form-section"><i class="fa fa-book"></i> Custom Details</h4>
                        <div class="row">
                        @if($employee_details->custom_fields!='')
                        @foreach($GetCustomFields as $GetCustomField)
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><label>{{ $GetCustomField->field_name }} @if($GetCustomField->required_field=="Yes")<sup style="color:red;font-size:12px;">*</sup>';@endif :</label><br>
                                 
                                    @if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==5 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==10)
                                       @php
                                          $employee_value=$employee_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($employee_value, true);
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
                                    @elseif($GetCustomField->field_type==7)
                                       <br>
                                       @php
                                       $employee_value=$employee_details->custom_fields;
                                       $CustomFieldsValueArr=json_decode($employee_value, true);
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
                                       $employee_value=$employee_details->custom_fields;
                                       $CustomFieldsValueArr=json_decode($employee_value, true);
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
                                    @elseif($GetCustomField->field_type==6)
                                       @php
                                          $employee_value=$employee_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($employee_value, true);
                                          $custom_field_id=$GetCustomField->custom_field_id;
                                       @endphp
                                       @foreach($GetCustomFieldTypes as $GetCustomFieldType)
                                          @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                             @if($CustomFieldsValueArr!='')
                                                @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))
                                                   <?=$CustomFieldsValueArr[$custom_field_id]?>&nbsp;&nbsp;<a href="public/uploads/Employees/<?=$CustomFieldsValueArr[$custom_field_id]?>" target="_blank"><i class="fa fa-eye"></i></a><input type="<?=$GetCustomFieldType->field_type?>" class="name form-control" value="<?=$CustomFieldsValueArr[$custom_field_id]?>" name="Custom-<?=$GetCustomField->custom_field_id?>">
                                                @else
                                                   <input type="<?=$GetCustomFieldType->field_type?>" class="name form-control" value="" placeholder="<?=$GetCustomField->field_name?>" name="Custom-<?=$GetCustomField->custom_field_id?>">
                                                @endif
                                             @else
                                                <input type="<?=$GetCustomFieldType->field_type?>" class="name form-control" value="" placeholder="<?=$GetCustomField->field_name?>" name="Custom-<?=$GetCustomField->custom_field_id?>">
                                             @endif
                                          @endif  
                                       @endforeach
                                    @elseif($GetCustomField->field_type==3)
                                       <select class="select2 form-control" style="width: 100%;" name="Custom-{{ $GetCustomField->custom_field_id }}">
                                          <option value="">Select {{ $GetCustomField->field_name }}</option>
                                          @php
                                          $field_value=$GetCustomField->field_value;
                                          $OptionsArr=json_decode($field_value, true);
                                          $employee_value=$employee_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($employee_value, true);
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
                                          
                                          $employee_value=$employee_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($employee_value, true);
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
                                 <fieldset class="form-group floating-label-form-group"><label>{{ $GetCustomField->field_name }} @if($GetCustomField->required_field=="Yes") <sup style="color:red;font-size:12px;">*</sup>@endif :</label><br>
                                 
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
                                                &nbsp;&nbsp;<input type="{{ $GetCustomFieldType->field_type }}" placeholder="{{  $GetCustomField->field_name }}" name="Custom-{{ $GetCustomField->custom_field_id }}" value="<?= $data['FieldValue'] ?>" style="width:15px;height:15px;">&nbsp;&nbsp;<?= $data['FieldValue'] ?>&nbsp;&nbsp;
                                             @endif
                                          @endforeach
                                          @endforeach
                                          
                                    @elseif($GetCustomField->field_type==9)
                                    <br>
                                    @php
                                       $field_value=$GetCustomField->field_value;
                                       $CheckboxArr=json_decode($field_value, true);@endphp
                                       
                                       @foreach ($CheckboxArr as $Checkbox)
                                          @foreach ($GetCustomFieldTypes as $GetCustomFieldType)
                                             @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type) 
                                                &nbsp;&nbsp;<input type="{{ $GetCustomFieldType->field_type }}" name="Custom-{{ $GetCustomField->custom_field_id }}[]" value="<?= $Checkbox['FieldValue'] ?>" style="width:15px;height:15px;">&nbsp;&nbsp;<?= $Checkbox['FieldValue'] ?>&nbsp;&nbsp;
                                             @endif
                                          @endforeach
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
                     </div>
                     <div class="form-actions right">
                        
                           <a href="{{ route('employees') }}">
                           <button type="button" class="btn btn-danger mr-1">
                              <i class="fa fa-times"></i> Close
                           </button>
                        </a>
                        <button type="submit" class="btn btn-primary">
                           <i class="fa fa-check"></i> Update
                        </button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
   <x-slot name="page_level_scripts">
      <script type="text/javascript">

      $(document).ready(function(){
         $('#country_id').on('change',function(){
            var country_id=$(this).val();
            $.ajax({
               url: 'country_id/'+country_id,
               type: "GET",
               // data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#state_id").html(data);
               }
            });
         });
      });
      
      $(document).ready(function(){
         $('#state_id').on('change',function(){
            var state_id=$(this).val();
            $.ajax({
               url: 'state_id/'+state_id,
               type: "GET",
               // data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#city_id").html(data);
               }
            });
         });
      });

      
         $('#shift_id').on('change',function(){
            var shift_id=$(this).val();
            $.ajax({
               url: 'shift_id/'+shift_id,
               type: "GET",
               success:function(data){
                  $('#shift_timing').html(data);
               }
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