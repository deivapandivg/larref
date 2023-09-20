<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Projects
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                           <i class="fa fa-plus"></i> Project
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered projects" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Project Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Client Name</th>
                                 <th>Project Name</th>
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
                                 <th>Project Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Client Name</th>
                                 <th>Project Name</th>
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
         <div class="modal fade" id="AddModal"  role="dialog" aria-labelledby="AddModals" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
               <div class="modal-content">
                  <section class="contact-form">
                     <div class="modal-header bg-primary white">
                        <h5 class="modal-title white">Project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <form method="post" action="{{ route('project_submit') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                           <div class="row">
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Client Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" name="client_id" data-placeholder="Choose one" style="width:100%;">
                                          <option selected>Select</option>
                                          @foreach ($client_lists as $client_list)
                                          <option value="{{  $client_list->client_id }}">{{ $client_list->client_name }}</option>
                                          @endforeach
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Project Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="text" id="" required name="project_name" class="name form-control" placeholder="Project Name">
                                    </fieldset>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              @foreach($GetCustomFields as $GetCustomField)
                              <div class="col-lg-12">
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
                                                      &nbsp;&nbsp;<input checked type="{{ $GetCustomFieldType->field_type }}" placeholder="{{  $GetCustomField->field_name }}" name="Custom-{{ $GetCustomField->custom_field_id }}" value="<?= $data['FieldValue'] ?>" style="width:20px;height:20px;">&nbsp;&nbsp;<?= $data['FieldValue'] ?>&nbsp;&nbsp;
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
                                                      &nbsp;&nbsp;<input type="{{ $GetCustomFieldType->field_type }}" name="Custom-{{ $GetCustomField->custom_field_id }}[]" value="<?= $Checkbox['FieldValue'] ?>" style="width:20px;height:20px;">&nbsp;&nbsp;<?= $Checkbox['FieldValue'] ?>&nbsp;&nbsp;
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
         </div>
         <div class="modal fade" id="edit_modal_projects"  role="dialog" aria-labelledby="edit_modal_projects" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
               <div class="modal-content">
                  <section class="contact-form">
                     <div class="modal-header bg-primary white">
                        <h5 class="modal-title white">Project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <form method="post" action="{{ route('project_submit') }}" enctype="multipart/form-data">
                        @csrf
                     <div id="project_edit_modal_form"></div>
                     </form>
                  </section>
               </div>
            </div>
         </div>
      </div>
   </section>
   @php
   $DeleteTableName="projects";
   $DeleteColumnName="project_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function () {
            var table = $('.projects').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('projects') }}",
               columns: [
               {data: 'project_id', name: 'a.project_id'},
               {data: 'action', name: 'a.action', orderable: false, searchable: false},
               {data: 'client_id', name: 'd.client_name'},
               {data: 'project_name', name: 'a.project_name'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'created_at', name: 'a.created_at'},
               {data: 'updated_by', name: 'c.first_name'},
               {data: 'updated_at', name: 'a.updated_at'},
               ]
            });
         });

         $(document).on('click', '.edit_model_btn', function(){
            var project_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'project_edit/'+project_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#project_edit_modal_form").html(data);
                  $("#edit_modal_projects").modal('show');
                  $("#edit_modal_projects .select2-show-search").select2();
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