<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Vendor View
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>   
               <div class="card-body">
                  <form class="form">
                     <div class="form-body">
                        <h4 class="form-section"><i class="fa fa-book"></i> Vendor Details</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Vendor Name :</b>
                                       <p>{{ $vendors_details->vendor_name }}</p>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Contact Person Name :</b>
                                       <p>{{ $vendors_details->contact_person_name }}</p>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Official Mobile Number :</b>
                                       <p>{{ $vendors_details->official_mobile_number }}</p>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Contact Person Number :</b>
                                       <p>{{ $vendors_details->contact_person_number }}</p>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Alter Mobile Number :</b>
                                       <p>{{ $vendors_details->alter_mobile_number }}</p>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Email Id :</b>
                                       <p>{{ $vendors_details->email_id }}</p>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Alter Email :</b>
                                       <p>{{ $vendors_details->alter_email_id }}</p>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Gst Number :</b>
                                       <p>{{ $vendors_details->gst_number }}</p>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b> Address :</b>
                                       <p>{{ $vendors_details->address }}</p>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <h4 class="form-section"><i class="fa fa-book"></i> Custom Details</h4>
                        <div class="row">
                        @if($vendors_details->custom_fields!='')
                        @foreach($GetCustomFields as $GetCustomField)
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label class="label-control"><?= $GetCustomField->field_name ?>:</label><br>
                                 @if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==10 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==5)
                                 @php 
                                    $vendors_value=$vendors_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($vendors_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp 
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr)) <?= $CustomFieldsValueArr[$custom_field_id] ?> @endif </p>
                                    @if($GetCustomField->field_type==6)
                                       <a href="<?= $CustomFieldsValueArr[$custom_field_id] ?>">Click to View</a>
                                    @endif
                                 @elseif($GetCustomField->field_type==6)
                                    @php
                                          $vendors_value=$vendors_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($vendors_value, true);
                                          $custom_field_id=$GetCustomField->custom_field_id;
                                       @endphp
                                       @foreach($GetCustomFieldTypes as $GetCustomFieldType)
                                          @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                             @if($CustomFieldsValueArr!='')
                                                @if(array_key_exists($custom_field_id,$CustomFieldsValueArr)) 
                                                   <p><?= $CustomFieldsValueArr[$custom_field_id] ?><a href="public/uploads/vendorss/<?= $CustomFieldsValueArr[$custom_field_id] ?>">Click to View</a></p>
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
                                    $vendors_value=$vendors_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($vendors_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif</p>
                                    
                                 @elseif($GetCustomField->field_type==9)
                                    @php 
                                    $vendors_value=$vendors_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($vendors_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))&nbsp;&nbsp;<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif </p>
                                    
                                 @elseif($GetCustomField->field_type==3)
                                 
                                    @php
                                    $field_value=$GetCustomField->field_value;
                                    $OptionsArr=json_decode($field_value, true);
                                    $vendors_value=$vendors_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($vendors_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif</p>
                                    
                                 @elseif($GetCustomField->field_type==11)
                                    @php
                                    $vendors_value=$vendors_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($vendors_value, true);
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
                        <a href="{{ route('vendors') }}">
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