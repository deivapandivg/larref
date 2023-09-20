<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Employees View
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
                                 <p>{{ $user_details->first_name }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Last Name :</label>
                                 <p>{{ $user_details->last_name }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Employee Code :</label>
                                 <p>{{ $user_details->employee_code }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Employee Name :</label>
                                 <p>{{ $user_details->employee_name }}</p>
                              </div>
                           </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Gender :</label>
                                    <p>{{$user_details->gender == '1' ? 'Male':''}}</p>
                                 
                                    <p>{{$user_details->gender == '2' ? 'Female':''}}</p>
                                 
                                    <p>{{$user_details->gender == '3' ? 'Others':''}}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Date Of Birth :</label>
                                 <p>{{ $user_details->date_of_birth }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                    <label class="label-control">Personal Mail Id :</label>
                                    <p>{{ $user_details->personal_mail_id }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                    <label class="label-control">Official Mail Id :</label>
                                    <p>{{ $user_details->email }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Personal Mobile Number :</label>
                                 <p>{{ $user_details->personal_mobile_number }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Emergency Contact Number :</label>
                                 <p>{{ $user_details->emergency_contact_number }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Blood Group :</label>
                                 <p>{{ $user_details->blood_group }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Date Of Joining :</label>
                                 <p>{{ $user_details->date_of_joining }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Designation :</label>
                                 <p>{{ $designation_lists->designation_name }}</p>
                              </div>
                           </div><div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Department :</label>
                                 <p>{{ $department_lists->department_name }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Team :</label>
                                 <p>{{ $team_lists->team_name }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Reporting To :</label>
                                 <p>{{ $reporting_to_lists->first_name }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Profile Upload : <br><a href="{{ asset('public/profile_uploads/' . $user_details->profile_upload) }}" target="target"><i class="fa fa-eye"></i></a></label>
                              </div>
                           </div> 
                        </div>
                         <h4 class="form-section"><i class="fa fa-address-card"></i> Address Details</h4>
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Country :</label>
                                 <p>{{ $country_lists->country_name }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">State :</label>
                                 <p>{{ $state_lists->state_name }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">City :</label>
                                 <p>{{ $city_lists->city_name }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Pin Code :</label>
                                 <p>{{ $user_details->pin_code }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Address :</label>
                                 <p>{{ $user_details->address }}</p>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control">Address Upload : <br><a href="{{ asset('public/address_uploads/' . $user_details->address_upload) }}" target="target"><i class="fa fa-eye"></i></a></label>
                              </div>
                           </div>
                        </div>
                        <h4 class="form-section"><i class="fa fa-book"></i> Custom Details</h4>
                        <div class="row">
                        @if($employee_details->custom_fields!='')
                        @foreach($GetCustomFields as $GetCustomField)
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label class="label-control"><?= $GetCustomField->field_name ?>:</label><br>
                                 @if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==10 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==5)
                                 @php 
                                    $employee_value=$employee_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($employee_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp 
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr)) <?= $CustomFieldsValueArr[$custom_field_id] ?> @endif </p>
                                    @if($GetCustomField->field_type==6)
                                       <a href="<?= $CustomFieldsValueArr[$custom_field_id] ?>">Click to View</a>
                                    @endif
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
                                                   <p><?= $CustomFieldsValueArr[$custom_field_id] ?><a href="public/uploads/Employees/<?= $CustomFieldsValueArr[$custom_field_id] ?>">Click to View</a></p>
                                                @else
                                                   <p><?= $CustomFieldsValueArr[$custom_field_id] ?></p>
                                                @endif
                                             @else
                                                <p><?= $CustomFieldsValueArr[$custom_field_id] ?></p>
                                             @endif
                                          @endif
                                       @endforeach
                                 @elseif($GetCustomField->field_type==7)
                                    @php 
                                    $employee_value=$employee_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($employee_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif</p>
                                    
                                 @elseif($GetCustomField->field_type==9)
                                    @php 
                                    $employee_value=$employee_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($employee_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))&nbsp;&nbsp;<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif </p>
                                    
                                 @elseif($GetCustomField->field_type==3)
                                 
                                    @php
                                    $field_value=$GetCustomField->field_value;
                                    $OptionsArr=json_decode($field_value, true);
                                    $employee_value=$employee_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($employee_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif</p>
                                    
                                 @elseif($GetCustomField->field_type==11)
                                    @php
                                    $employee_value=$employee_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($employee_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p>@if(array_key_exists($custom_field_id,$CustomFieldsValueArr))<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif</p>
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
                              <i class="fa fa-arrow-left"></i> Back
                           </button>
                        </a>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
   <x-slot name="page_level_scripts">
      <script type="text/javascript">

      
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