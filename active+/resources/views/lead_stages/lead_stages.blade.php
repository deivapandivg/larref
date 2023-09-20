<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Lead Stages
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                           <i class="fa fa-plus"></i> Lead Stage
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered LeadStagesTable" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>LeadStage Id </th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>LeadStage Name</th>
                                 <th>LeadStage Name Color</th>
                                 <th>LeadStage Color</th>
                                 <th>LeadStage</th>
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
                                 <th>LeadStage Id </th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>LeadStage Name</th>
                                 <th>LeadStage Name Color</th>
                                 <th>LeadStage Color</th>
                                 <th>LeadStage</th>
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
                  <h5 class="modal-title white">Lead Stage</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('lead_stages_submit') }}">
                  @csrf
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Lead Stage  Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <input type="text" required name="lead_stage_name" class="name form-control" placeholder="Lead Status Name">
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Lead Stage  Name Color <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <input type="color"  required name="lead_stage_name_color" class="name form-control">
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Lead Stage  Color <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <input type="color"  required name="lead_stage_color" class="name form-control">
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-12">
                           <div class="form-group">
                              <label class="label-control" ><b>Lead Status :</b></label>
                              <input required type="radio" checked name="lead_stage" id="Positive" value="Positive">
                              <label  for="Positive">Positive</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="radio" name="lead_stage"  id="Negative" value="Negative">
                              <label  for="No">Negative</label>
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
                  <h5 class="modal-title white">Lead Stage</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('lead_stages_submit') }}">
                  @csrf
                  <div id="lead_stage_modal_form"></div>
               </form>
            </section>
         </div>
      </div>
   </div>
   @php
   $DeleteTableName="lead_stages";
   $DeleteColumnName="lead_stage_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function () {
            var table = $('.LeadStagesTable').DataTable({
               processing: true,
               serverSide: true,
               order: [[ 0, "desc" ]],
               ajax: "{{ route('lead_stages') }}",
               columns: [
               {data: 'lead_stage_id', name: 'lead_stages.lead_stage_id'},
               {data: 'action', name: 'lead_stages.action', orderable: false, searchable: false},
               {data: 'lead_stage_name', name: 'lead_stages.lead_stage_name'},
               {data: 'lead_stage_name_color', name: 'lead_stages.lead_stage_name_color'},
               {data: 'lead_stage_color', name: 'lead_stages.lead_stage_color'},
               {data: 'lead_stage', name: 'lead_stages.lead_stage'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'created_at', name: 'lead_stages.created_at'},
               {data: 'updated_by', name: 'c.first_name'},
               {data: 'updated_at', name: 'lead_stages.updated_at'},
               ]
            });
         });

         $(document).on('click', '.edit_model_btn', function(){
            var lead_stage_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'lead_stage_edit/'+lead_stage_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#lead_stage_modal_form").html(data);
                  $("#edit_modal").modal('show');
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