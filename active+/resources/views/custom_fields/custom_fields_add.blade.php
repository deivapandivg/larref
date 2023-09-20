<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Custom Field Add
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>   
               <div class="card-body">
                  <form class="form" method="POST" enctype="multipart/form-data" action="{{ route('custom_field_submit') }}">
                     @csrf
                     <div class="form-body">
                        <h4 class="form-section"><i class="fa fa-book"></i> Custom Field Details</h4>
                        <div class="row">
                           <div class="col-lg-3">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Module Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <select class="form-control border-primary select2 form-select" name="menu_id" data-placeholder="Choose one" style="width:100%;">
                                       <option selected>Select Module</option>
                                       @foreach ($menus as $menu)
                                       <option value="{{  $menu->menu_id }}">{{ $menu->menu_name }}</option>
                                       @endforeach
                                    </select>
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-3">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Field Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <input type="text" id="field_name" required name="field_name" class="name form-control" placeholder="Field Name">
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-3">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Custom Field Type <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <select class="form-control border-primary select2 form-select" name="field_type_id" id="field_type_id" data-placeholder="Choose one" style="width:100%;">
                                       <option selected disabled>Select</option>
                                       @foreach ($field_types as $field_type)
                                       <option value="{{  $field_type->field_type_id }}">{{ $field_type->field_type }}</option>
                                       @endforeach
                                    </select>
                                 </fieldset>
                              </div>
                           </div>
                           <div class="col-lg-3">
                              <div class="form-group">
                                  <fieldset class="form-group floating-label-form-group"><b>Required :</b>
                                    &nbsp;&nbsp;<input type="radio" name="RequiredField" value="Yes"> Yes&nbsp;&nbsp;
                                 <input type="radio" name="RequiredField" value="No" checked> No
                                 </fieldset>
                              </div>
                           </div>
                           <!-- <div class="col-lg-3">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b></b>
                                    <center>
                                       <table id="AddImageTable" width="50%">
                                          <tbody id="ImageTBodyAdd">
                                             <tr class="add_row">
                                                <td width="20%"><button class="btn btn-primary btn-sm AddNewField" type="button" id="add" title='Add new file'><i class="fa fa-plus"> Add Field</i></button></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </center>
                                 </fieldset>
                              </div>
                           </div> -->
                           <div id="ValuesGet">
                              
                           </div>
                           <table class="table-responsive table" border="0" id="mytable">
                              <thead>
                                 
                              </thead>
                              <tbody id="InvoiceItems" width="100%">
                                 <div id="ValuesGet">
                              
                                 </div>
                              </tbody>
                              <tfoot>
                                 
                              </tfoot>
                           </table>
                        </div> 
                     </div>
                     <div class="form-actions right">
                        <a href="{{ route('custom_fields') }}">
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
         $(".AddNewField").click(function(){
            $.post("custom_new_field",{"_token": "{{ csrf_token() }}"},function(data){
               $("#InvoiceItems").append(data);
               $("tr .select2").select2();
            });
         });

         $("#field_type_id").change(function(){

            var field_type_id=$(this).val();
            if (field_type_id==3) {
            $.post("{{ route('custom_fields_dropdown') }}",{"_token":"{{ csrf_token() }}","field_type_id":field_type_id},
               function(data){
                  $("#ValuesGet").html(data);
               });
            }
            else if(field_type_id==7)
            {
            $.post("{{ route('custom_fields_radio') }}",{"_token":"{{ csrf_token() }}","field_type_id":field_type_id},
               function(data){
                  $("#ValuesGet").html(data);
               });
            }
             else if(field_type_id==9)
            {
            $.post("{{ route('custom_fields_checkbox') }}",{"_token":"{{ csrf_token() }}","field_type_id":field_type_id},
               function(data){
                  $("#ValuesGet").html(data);
               });
            }
            else
            {
               $("#ValuesGet").html("");
            }
         });

         $("#field_types_id").change(function(){
            alert();
            var field_type_id=$(this).val();
            if (field_type_id==3) {
            $.post("{{ route('custom_fields_dropdown') }}",{"_token":"{{ csrf_token() }}","field_type_id":field_type_id},
               function(data){
                  $("#ValuesGet").html(data);
               });
            }
            else if(field_type_id==7)
            {
            $.post("{{ route('custom_fields_radio') }}",{"_token":"{{ csrf_token() }}","field_type_id":field_type_id},
               function(data){
                  $("#ValuesGet").html(data);
               });
            }
             else if(field_type_id==9)
            {
            $.post("{{ route('custom_fields_checkbox') }}",{"_token":"{{ csrf_token() }}","field_type_id":field_type_id},
               function(data){
                  $("#ValuesGet").html(data);
               });
            }
            else
            {
               $("#ValuesGet").html("");
            }
         });

         $(document).on('change', '#edit_field_type_id', function(){
            var field_type_id=$(this).val();
            if (field_type_id==3) {
            $.post("{{ route('custom_fields_dropdown') }}",{"_token":"{{ csrf_token() }}","field_type_id":field_type_id},
               function(data){
                  $("#EditValuesGet").html(data);
               });
            }
            else if(field_type_id==7)
            {
            $.post("{{ route('custom_fields_radio') }}",{"_token":"{{ csrf_token() }}","field_type_id":field_type_id},
               function(data){
                  $("#EditValuesGet").html(data);
               });
            }
             else if(field_type_id==9)
            {
            $.post("{{ route('custom_fields_checkbox') }}",{"_token":"{{ csrf_token() }}","field_type_id":field_type_id},
               function(data){
                  $("#EditValuesGet").html(data);
               });
            }
            else
            {
               $("#EditValuesGet").html("");
            }
         });

         $(document).on('click', '#AddMoreOption', function(){
            var i =1;
            i++;
            $('#Dynamic_Field_Option').append('<tr id="row'+i+'"><td><fieldset class="form-group floating-label-form-group"><b>Option :</b><input type="text" class="form-control border-primary" placeholder="Option Value" name="OptionValue[]"></fieldset></td><td><button type="button" name="remove" id="'+i+'" class="btn  btn-danger btn_remove">X</button></td></tr>');  
         });
         $(document).on('click', '#AddMoreRadio', function(){
            var i =1;
            i++;
            $('#Dynamic_Field_Radio').append('<tr id="row'+i+'"><td><fieldset class="form-group floating-label-form-group"><b>Radio Value :</b><input type="text" class="form-control border-primary" placeholder="Radio Value" name="OptionValue[]"></fieldset></td><td><button type="button" name="remove" id="'+i+'" class="btn  btn-danger btn_remove1">X</button></td></tr>');  
         });
         $(document).on('click', '#AddMoreCheckbox', function(){
            var i =1;
            i++;
            $('#Dynamic_Field_Checkbox').append('<tr id="row'+i+'"><td><fieldset class="form-group floating-label-form-group"><b>Checkbox Value :</b><input type="text" class="form-control border-primary" placeholder="Checkbox Value" name="OptionValue[]"></fieldset></td><td><button type="button" name="remove" id="'+i+'" class="btn  btn-danger btn_remove2">X</button></td></tr>');  
          });
         $(document).on('click', '.btn_remove', function(){  
            $(this).closest("tr").remove();  
         });
         $(document).on('click', '.btn_remove1', function(){  
            $(this).closest("tr").remove();  
         });
         $(document).on('click', '.btn_remove2', function(){  
            $(this).closest("tr").remove();  
         });

         // Append new row In Add Form
         $('#AddImageTable').on('click', "#add", function(e) {
            $('#field_name').append('<tr class="add_row"><td><input  name="product_images[]" type="text" multiple /></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
            e.preventDefault();
         });
         // Delete row In Add Form
         $('#AddImageTable').on('click', "#delete", function(e) {
            if (!confirm("Are you sure you want to remove this file?"))
               return false;
            $(this).closest('tr').remove();
            e.preventDefault();
         });

         $(document).on('click','.RemovequotationItem',function(){
            $(this).closest("tr").remove();
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