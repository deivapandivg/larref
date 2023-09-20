<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Custom Fields
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a href="{{ route('custom_fields_add') }}">
                        <button  type="submit" class="btn btn-primary">
                           <i class="fa fa-plus"></i> Custom Field
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered custom_field" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Custom Field Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;</th>
                                 <th>Menu Name</th>
                                 <th>Custom Field Name</th>
                                 <th>Custom Field Type</th>
                                 <th>Created By</th>
                                 <th>Created At</th>
                                 <th>Updated By</th>
                                 <th>Updated At</th>
                              </tr>
                           </thead>
                           <tbody>

                           </tbody>
                           <tfoot>
                              <tr>
                                 <th>Custom Field Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;</th>
                                 <th>Menu Name</th>
                                 <th>Custom Field Name</th>
                                 <th>Custom Field Type</th>
                                 <th>Created By</th>
                                 <th>Created At</th>
                                 <th>Updated By</th>
                                 <th>Updated At</th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>
               </div>
            </div>

         </section>
         <!-- <div class="modal fade" id="AddModal"  role="dialog" aria-labelledby="AddModals" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
               <div class="modal-content">
                  <section class="contact-form">
                     <div class="modal-header bg-primary white">
                        <h5 class="modal-title white">Custom Field</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <form method="post" action="{{ route('custom_field_submit') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                           <div class="row">
                              <div class="col-lg-12">
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
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Field Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="text" id="" required name="field_name" class="name form-control" placeholder="Field Name">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Custom Field Type <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" name="field_type_id" id="field_type_id" data-placeholder="Choose one" style="width:100%;">
                                          <option selected>Select</option>
                                          @foreach ($field_types as $field_type)
                                          <option value="{{  $field_type->field_type_id }}">{{ $field_type->field_type }}</option>
                                          @endforeach
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Required <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       &nbsp;&nbsp;<input type="radio" id="no" name="RequiredField" value="No" checked> <label for="no">No</label>&nbsp;&nbsp;
                                       <input type="radio" name="RequiredField" id="yes" value="Yes"> <label for="yes">Yes</label>
                                    </fieldset>
                                 </div>
                              </div>
                              <div id="ValuesGet">

                              </div>
                           </div> 
                        </div>
                        <div class="modal-footer">
                           <button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
                              <i class="fa fa-times"></i> Close
                           </button>
                           <button type="submit" class="btn btn-primary btn-md">
                              <i class="fa fa-check"></i> Add
                           </button>
                        </div>
                     </form>
                  </section>
               </div>
            </div>
         </div> -->
         <div class="modal fade" id="edit_modal_teams"  role="dialog" aria-labelledby="edit_modal_teams" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
               <div class="modal-content">
                  <section class="contact-form">
                     <div class="modal-header bg-primary white">
                        <h5 class="modal-title white">Custom Field</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <form method="post" action="{{ route('custom_field_submit') }}" enctype="multipart/form-data">
                        @csrf
                     <div id="menu_group_edit_modal_form"></div>
                     </form>
                  </section>
               </div>
            </div>
         </div>
      </div>
   </section>
   @php
   $DeleteTableName="custom_fields";
   $DeleteColumnName="custom_field_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function () {
            var table = $('.custom_field').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('custom_fields') }}",
               columns: [
               {data: 'custom_field_id', name: 'a.custom_field_id'},
               {data: 'action', name: 'a.action', orderable: false, searchable: false},
               {data: 'field_page', name: 'e.menu_name'},
               {data: 'field_name', name: 'a.field_name'},
               {data: 'field_type', name: 'd.field_type'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'created_at', name: 'a.created_at'},
               {data: 'updated_by', name: 'c.first_name'},
               {data: 'updated_at', name: 'a.updated_at'},
               ]
            });
         });

         // $("#field_type_id").change(function(){
         //    var field_type_id=$(this).val();
         //    if (field_type_id==3) {
         //    $.post("{{ route('custom_fields_dropdown') }}",{"_token":"{{ csrf_token() }}","field_type_id":field_type_id},
         //       function(data){
         //          $("#ValuesGet").html(data);
         //       });
         //    }
         //    else if(field_type_id==7)
         //    {
         //    $.post("{{ route('custom_fields_radio') }}",{"_token":"{{ csrf_token() }}","field_type_id":field_type_id},
         //       function(data){
         //          $("#ValuesGet").html(data);
         //       });
         //    }
         //     else if(field_type_id==9)
         //    {
         //    $.post("{{ route('custom_fields_checkbox') }}",{"_token":"{{ csrf_token() }}","field_type_id":field_type_id},
         //       function(data){
         //          $("#ValuesGet").html(data);
         //       });
         //    }
         //    else
         //    {
         //       $("#ValuesGet").html("");
         //    }
         // });
         
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

         $(document).on('click', '.edit_model_btn', function(){
            var custom_field_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'custom_fields_edit/'+custom_field_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#menu_group_edit_modal_form").html(data);
                  $("#edit_modal_teams").modal('show');
               }
            });
         });

         $(document).on('click', '.DeleteDataModal', function(){
            var DeleteColumnValue=$(this).closest("tr").find("td:eq(0)").text();
            $("#DeleteColumnValue").val(DeleteColumnValue);
            $("#DeleteDataModal").modal('show');
         });
      </script>
   </x-slot>
</x-app-layout>