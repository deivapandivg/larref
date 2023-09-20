<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Service View
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>   
               <div class="card-body">
                  <form class="form">
                     <div class="form-body">
                        <h4 class="form-section"><i class="fa fa-book"></i> Service Details</h4>
                        <div class="row">
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Service Code :</b>
                                    <p>{{ $services_details->service_code }}</p>
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Service Category Name :</b>
                                    <p>{{ $service_category_details->service_category_name }}</p>
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Service Name :</b>
                                    <p>{{ $services_details->service_name }}</p>
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Service Amount :</b>
                                    <p>{{ $services_details->service_amount }}</p>
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>GST :</b>
                                    <p>{{ $services_details->gst }}</p>
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Service Description :</b>
                                     <p>{{ $services_details->service_description }}</p>
                                 </fieldset>
                              </div>
                           </div>
                        </div>
                        <h4 class="form-section"><i class="fa fa-book"></i> Custom Details</h4>
                        <div class="row">
                        @if($services_details->custom_fields!='')
                        @foreach($GetCustomFields as $GetCustomField)
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label class="label-control"><?= $GetCustomField->field_name ?>:</label><br>
                                 @if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==10 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==5)
                                 @php 
                                    $services_value=$services_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($services_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp 
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr)) <?= $CustomFieldsValueArr[$custom_field_id] ?> @endif </p>
                                    @if($GetCustomField->field_type==6)
                                       <a href="<?= $CustomFieldsValueArr[$custom_field_id] ?>">Click to View</a>
                                    @endif
                                 @elseif($GetCustomField->field_type==6)
                                    @php
                                          $services_value=$services_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($services_value, true);
                                          $custom_field_id=$GetCustomField->custom_field_id;
                                       @endphp
                                       @foreach($GetCustomFieldTypes as $GetCustomFieldType)
                                          @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                             @if($CustomFieldsValueArr!='')
                                                @if(array_key_exists($custom_field_id,$CustomFieldsValueArr)) 
                                                   <p><?= $CustomFieldsValueArr[$custom_field_id] ?><a href="public/uploads/servicess/<?= $CustomFieldsValueArr[$custom_field_id] ?>">Click to View</a></p>
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
                                    $services_value=$services_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($services_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif</p>
                                    
                                 @elseif($GetCustomField->field_type==9)
                                    @php 
                                    $services_value=$services_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($services_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))&nbsp;&nbsp;<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif </p>
                                    
                                 @elseif($GetCustomField->field_type==3)
                                 
                                    @php
                                    $field_value=$GetCustomField->field_value;
                                    $OptionsArr=json_decode($field_value, true);
                                    $services_value=$services_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($services_value, true);
                                    $custom_field_id=$GetCustomField->custom_field_id; 
                                    @endphp
                                    <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif</p>
                                    
                                 @elseif($GetCustomField->field_type==11)
                                    @php
                                    $services_value=$services_details->custom_fields;
                                    $CustomFieldsValueArr=json_decode($services_value, true);
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
                        <a href="{{ route('services') }}">
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