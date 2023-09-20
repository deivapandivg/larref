<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Vendor Edit
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>   
               <div class="card-body">
                  <form class="form" method="POST" enctype="multipart/form-data" action="{{ route('vendors_submit') }}">
                     @csrf
                     <input type="hidden" name="vendor_id" value="{{ $vendors_details->vendor_id }}">
                     <div class="form-body">
                        <h4 class="form-section"><i class="fa fa-book"></i> Vendor Details</h4>
                        <div class="row">
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Vendor Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="text" id="" required name="vendor_name" class="name form-control" placeholder="Vendor Name" value="{{ $vendors_details->vendor_name }}">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Contact Person Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="text" id="" required name="contact_person_name" class="name form-control" placeholder="Contact Person Name" value="{{ $vendors_details->contact_person_name }}">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Official Mobile Number <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="number" id="" required name="official_mobile_number" class="name form-control" placeholder="Official Mobile Number" value="{{ $vendors_details->official_mobile_number }}">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Contact Person Number <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="number" id="" required name="contact_person_number" class="name form-control" placeholder="Contact Person Number" value="{{ $vendors_details->contact_person_number }}">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Alter Mobile Number <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="number" id="" required name="alter_mobile_number" class="name form-control" placeholder="Alter Mobile Number" value="{{ $vendors_details->alter_mobile_number }}">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Email Id <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="text" id="" required name="email_id" class="name form-control" placeholder="Email Id" value="{{ $vendors_details->email_id }}">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Alter Email <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="text" id="" required name="alter_email_id" class="name form-control" placeholder="Alter Email" value="{{ $vendors_details->alter_email_id }}">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Gst Number <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="text" id="" required name="gst_number" class="name form-control" placeholder="Gst Number" value="{{ $vendors_details->gst_number }}">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b> Address <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <textarea id="" required name="address" class="name form-control" placeholder="vendor Address">{{ $vendors_details->address }}</textarea>
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
                                 <fieldset class="form-group floating-label-form-group"><label>{{ $GetCustomField->field_name }} @if($GetCustomField->required_field=="Yes")<sup style="color:red;font-size:12px;">*</sup>@endif :</label><br>
                                 
                                    @if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==5 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==10)
                                       @php
                                          $vendors_value=$vendors_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($vendors_value, true);
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
                                       $vendors_value=$vendors_details->custom_fields;
                                       $CustomFieldsValueArr=json_decode($vendors_value, true);
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
                                       $vendors_value=$vendors_details->custom_fields;
                                       $CustomFieldsValueArr=json_decode($vendors_value, true);
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
                                          $vendors_value=$vendors_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($vendors_value, true);
                                          $custom_field_id=$GetCustomField->custom_field_id;
                                       @endphp
                                       @foreach($GetCustomFieldTypes as $GetCustomFieldType)
                                          @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                             @if($CustomFieldsValueArr!='')
                                                @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))
                                                   <?=$CustomFieldsValueArr[$custom_field_id]?>&nbsp;&nbsp;<a href="public/uploads/vendorss/<?=$CustomFieldsValueArr[$custom_field_id]?>" target="_blank"><i class="fa fa-eye"></i></a><input type="<?=$GetCustomFieldType->field_type?>" class="name form-control" value="<?=$CustomFieldsValueArr[$custom_field_id]?>" name="Custom-<?=$GetCustomField->custom_field_id?>">
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
                                          $vendors_value=$vendors_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($vendors_value, true);
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
                                          
                                          $vendors_value=$vendors_details->custom_fields;
                                          $CustomFieldsValueArr=json_decode($vendors_value, true);
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
                        
                           <a href="{{ route('vendors') }}">
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