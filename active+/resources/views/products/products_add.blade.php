<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Product Add
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>
               <div class="card-body">
                  <form class="form" method="POST" enctype="multipart/form-data" action="{{ route('products_submit') }}">
                     @csrf
                     <div class="form-body">
                        <h4 class="form-section"><i class="fa fa-book"></i> Product Details</h4>
                        <div class="row">
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Product Code <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="text" id="" required name="product_code" class="name form-control" placeholder="Product Code">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Product Category Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <select class="form-control border-primary select2 form-select" name="product_category_id" data-placeholder="Choose one" style="width:100%;">
                                       <option selected>Select</option>
                                       @foreach ($product_category_lists as $product_category_list)
                                       <option value="{{  $product_category_list->product_category_id }}">{{ $product_category_list->product_category_name }}</option>
                                       @endforeach
                                    </select>
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Product Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="text" id="" required name="product_name" class="name form-control" placeholder="Product Name">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Cost Price <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="text" id="" required name="cost_price" class="name form-control" placeholder="Cost Price">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Selling Price <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="text" id="" required name="selling_price" class="name form-control" placeholder="Selling Price">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>GST <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="number" id="" required name="gst" class="name form-control" placeholder="GST">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Quantity <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="number" id="quantity" required name="quantity" class="name form-control" placeholder="quantity">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Product Description <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <textarea id="" required name="product_description" class="name form-control" placeholder="Product Description"></textarea>
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Products Images :</b>
                                    <center>
                                       <table id="AddImageTable" width="50%">
                                          <tbody id="ImageTBodyAdd">
                                             <tr class="add_row">
                                                <td width="100%"><input name="product_images[]" type="file" multiple></td>
                                                <td width="20%"><button class="btn btn-success btn-sm" type="button" id="add" title='Add new file'><i class="fa fa-plus"></i></button></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </center>
                                 </fieldset>
                              </div>
                           </div>
                        </div>
                        <h4 class="form-section"><i class="fa fa-book"></i> Custom Details</h4>
                        <div class="row">
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
                                    &nbsp;&nbsp;<input checked type="{{ $GetCustomFieldType->field_type }}" placeholder="{{  $GetCustomField->field_name }}" name="Custom-{{ $GetCustomField->custom_field_id }}" value="<?= $data['FieldValue'] ?>" style="width:15px;height:15px;">&nbsp;&nbsp;<?= $data['FieldValue'] ?>&nbsp;&nbsp;
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
                                       <option value="">Select {{ $GetCustomField->field_name }}</option>
                                       @php
                                       $field_value=$GetCustomField->field_value;
                                       $OptionsArr=json_decode($field_value, true);
                                       @endphp
                                       <?php

                                       foreach ($OptionsArr as $Options) {
                                          echo "<option>" . $Options['FieldValue'] . "</option>";
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
                     </div>
                     <div class="form-actions right">
                        <a href="{{ route('products') }}">
                           <button type="button" class="btn btn-danger mr-1">
                              <i class="fa fa-times"></i> Close
                           </button>
                        </a>
                        <button type="submit" class="btn btn-primary">
                           <i class="fa fa-check"></i> Save
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
         // Append new row In Add Form
         $('#AddImageTable').on('click', "#add", function(e) {
            $('#ImageTBodyAdd').append('<tr class="add_row"><td><input  name="product_images[]" type="file" multiple /></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
            e.preventDefault();
         });
         // Delete row In Add Form
         $('#AddImageTable').on('click', "#delete", function(e) {
            if (!confirm("Are you sure you want to remove this file?"))
               return false;
            $(this).closest('tr').remove();
            e.preventDefault();
         });
      </script>
   </x-slot>
   <style type="text/css">
      form .form-section {
         line-height: 3rem;
         margin-bottom: 20px;
         color: #82a3de;
         border-bottom: 1px solid #9cb6e5;
      }
   </style>
</x-app-layout>