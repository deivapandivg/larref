<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Service Edit
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>   
               <div class="card-body">
                  <form class="form" method="POST" enctype="multipart/form-data" action="{{ route('services_submit') }}">
                     @csrf
                     <input type="hidden" name="service_id" value="{{ $services_details->service_id }}">
                     <div class="form-body">
                        <h4 class="form-section"><i class="fa fa-book"></i> Service Details</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Service Code <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="text" id="" required name="service_code" class="name form-control" placeholder="Service Code" value="{{ $services_details->service_code }}">
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Service Category Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                        <select class="form-control border-primary select2 form-select" name="service_category_id" data-placeholder="Choose one" style="width:100%;">
                                            <option selected disabled>Select</option>
                                            @foreach($service_category_details as $service_category_detail)
                                                <option value="{{ $service_category_detail->service_category_id }}" {{ $service_category_detail->service_category_id==$services_details->service_category_id ? 'selected' : '' }}>{{ $service_category_detail->service_category_name }}</option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Service Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                        <input type="text" id="" required name="service_name" class="name form-control" placeholder="Service Name" value="{{ $services_details->service_name }}">
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Service Amount <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="text" id="" required name="service_amount" class="name form-control" placeholder="Service Amount" value="{{ $services_details->service_amount }}">
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>GST <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="number" id="" required name="gst" class="name form-control" placeholder="GST" value="{{ $services_details->gst }}">
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-12">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Service Description <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                        <textarea id="" required name="service_description" class="name form-control" placeholder="Service Description">{{ $services_details->service_description }}</textarea>
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
                                 <fieldset class="form-group floating-label-form-group"><label>{{ $GetCustomField->field_name }} @if($GetCustomField->required_field=="Yes")<sup style="color:red;font-size:12px;">*</sup>@endif :</label><br>
                                 
                                    @if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==5 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==10)
                                       @php
                                          $services_value=$services_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($services_value, true);
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
                                       $services_value=$services_details->custom_fields;
                                       $CustomFieldsValueArr=json_decode($services_value, true);
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
                                       $services_value=$services_details->custom_fields;
                                       $CustomFieldsValueArr=json_decode($services_value, true);
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
                                          $services_value=$services_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($services_value, true);
                                          $custom_field_id=$GetCustomField->custom_field_id;
                                       @endphp
                                       @foreach($GetCustomFieldTypes as $GetCustomFieldType)
                                          @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                             @if($CustomFieldsValueArr!='')
                                                @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))
                                                   <?=$CustomFieldsValueArr[$custom_field_id]?>&nbsp;&nbsp;<a href="public/uploads/servicess/<?=$CustomFieldsValueArr[$custom_field_id]?>" target="_blank"><i class="fa fa-eye"></i></a><input type="<?=$GetCustomFieldType->field_type?>" class="name form-control" value="<?=$CustomFieldsValueArr[$custom_field_id]?>" name="Custom-<?=$GetCustomField->custom_field_id?>">
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
                                          $services_value=$services_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($services_value, true);
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
                                          
                                          $services_value=$services_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($services_value, true);
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
                        
                           <a href="{{ route('services') }}">
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