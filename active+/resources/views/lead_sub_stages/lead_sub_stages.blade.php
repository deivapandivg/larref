<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Lead Sub Stages
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                           <i class="fa fa-plus"></i> Lead Sub Stage
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered LeadSubStagesTable" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Lead Sub Stage Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Lead Stage Name</th>
                                 <th>Lead Sub Stage</th>
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
                                 <th>Lead Sub Stage Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Lead Stage Name</th>
                                 <th>Lead Sub Stage</th>
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
                  <h5 class="modal-title white">Lead Sub Stage</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('lead_sub_stages_submit') }}">
                  @csrf
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Lead Stage <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <select class="form-control border-primary select2" name="lead_stage_id" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($lead_stages_lists as $lead_stages_list)
                                    <option value="{{  $lead_stages_list->lead_stage_id }}">{{ $lead_stages_list->lead_stage_name }}</option>
                                    @endforeach
                                 </select>
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Lead Sub Stage <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <input type="text" required name="lead_sub_stage" class="name form-control" placeholder="Lead Status Name">
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

   <div class="modal fade" id="edit_modal"  role="dialog" aria-labelledby="edit_modal_menus" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Lead Sub Stage</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('lead_sub_stages_submit') }}">
                  @csrf
                  <div id="lead_sub_stage_modal_form"></div>
               </form>
            </section>
         </div>
      </div>
   </div>
   @php
   $DeleteTableName="lead_sub_stage";
   $DeleteColumnName="lead_sub_stage_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function () {
            var table = $('.LeadSubStagesTable').DataTable({
               processing: true,
               serverSide: true,
               order: [[ 0, "desc" ]],
               ajax: "{{ route('lead_sub_stages') }}",
               columns: [
               {data: 'lead_sub_stage_id', name: 'lead_sub_stage.lead_stage_id'},
               {data: 'action', name: 'lead_sub_stage.action', orderable: false, searchable: false},
               {data: 'lead_stage_name', name: 'd.lead_stage_name'},
               {data: 'lead_sub_stage', name: 'lead_sub_stage.lead_sub_stage'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'created_at', name: 'lead_sub_stage.created_at'},
               {data: 'updated_by', name: 'c.first_name'},
               {data: 'updated_at', name: 'lead_sub_stage.updated_at'},
               ]
            });
         });

         $(document).on('click', '.edit_model_btn', function(){
            var lead_sub_stage_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'lead_sub_stage_edit/'+lead_sub_stage_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#lead_sub_stage_modal_form").html(data);
                  $("#edit_modal").modal('show');
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