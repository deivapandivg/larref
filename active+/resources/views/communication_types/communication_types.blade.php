<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Communication Types
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                           <i class="fa fa-plus"></i> Communication Type
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered CommunicationTypeTable" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Communication Medium</th>
                                 <th>Communication Type</th>
                                 <th>Duration</th>
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
                                 <th>Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Communication Medium</th>
                                 <th>Communication Type</th>
                                 <th>Duration</th>
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
         </div>
      </div>
   </section>
   <div class="modal fade" id="AddModal"  role="dialog" aria-labelledby="AddModals" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Communication Type</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('communication_type_submit') }}">
                  @csrf
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Communication Medium <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <select class="form-control border-primary select2" name="communication_medium_id" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($communication_medium_lists as $communication_medium_list)
                                    <option value="{{  $communication_medium_list->communication_medium_id }}">{{ $communication_medium_list->communication_medium }}</option>
                                    @endforeach
                                 </select>
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Communication Type <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <input type="text" required name="communication_type" class="name form-control" placeholder="Communication Type">
                              </fieldset>
                           </div>
                        </div>
                         <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Duration 
                                 <input type="number"  min="0" name="duration" class="name form-control" placeholder="0">
                              </fieldset>
                           </div>
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
   </div>
   <div class="modal fade" id="communication_edit_model"  role="dialog" aria-labelledby="edit" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Communication Type</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('communication_type_submit') }}">
                  @csrf
                  <div id="communication_type_modal_form"></div>
               </form>
            </section>
         </div>
      </div>
   </div>
   @php
   $DeleteTableName="communication_types";
   $DeleteColumnName="communication_type_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function () {
            var table = $('.CommunicationTypeTable').DataTable({
               processing: true,
               serverSide: true,
               order: [[ 0, "desc" ]],
               ajax: "{{ route('communication_types') }}",
               columns: [
               {data: 'communication_type_id', name: 'communication_types.communication_type_id'},
               {data: 'action', name: 'communication_types.action', orderable: false, searchable: false},
               {data: 'communication_medium', name: 'd.communication_medium'},
               {data: 'communication_type', name: 'communication_types.communication_type'},
               {data: 'duration', name: 'communication_types.duration'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'created_at', name: 'communication_types.created_at'},
               {data: 'updated_by', name: 'c.first_name'},
               {data: 'updated_at', name: 'communication_types.updated_at'},
               ]
            });
         });

         $(document).on('click', '.edit_model_btn', function(){
            var communication_type_id=$(this).closest("tr").find("td:eq(0)").text();
            // alert(communication_type_id);
            $.ajax({
               url: 'communication_type_edit/'+communication_type_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#communication_type_modal_form").html(data);
                  $("#communication_edit_model").modal('show');
                  $(".select2").select2();

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