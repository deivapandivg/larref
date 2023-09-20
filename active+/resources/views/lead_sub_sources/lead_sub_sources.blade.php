<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Lead Sub Sources
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                           <i class="fa fa-plus"></i> Lead Sub Source
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered lead_sub_source" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Lead Sub Source Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Lead Source Name</th>
                                 <th>Lead Sub Source Name</th>
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
                                 <th>Lead Sub Source Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Lead Source Name</th>
                                 <th>Lead Sub Source Name</th>
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
                  <h5 class="modal-title white">Lead Sub Source</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('lead_sub_sources_submit') }}">
                  @csrf
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Lead Source Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <select class="form-control border-primary select2 form-select" name="lead_source_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($lead_source_lists as $lead_source_list)
                                    <option value="{{  $lead_source_list->lead_source_id }}">{{ $lead_source_list->lead_source_name }}</option>
                                    @endforeach
                                 </select>
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Lead Sub Source Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <input type="text" id="" required name="lead_sub_source_name" class="name form-control" placeholder="Lead Sub Source Name">
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
   <div class="modal fade" id="edit_modal_lead_sub_sources"  role="dialog" aria-labelledby="edit_modal_lead_sub_sources" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Lead Sub Source</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('lead_sub_sources_submit') }}">
                  @csrf
               <div id="menu_group_edit_modal_form"></div>
               </form>
            </section>
         </div>
      </div>
   </div>
   @php
   $DeleteTableName="lead_sub_sources";
   $DeleteColumnName="lead_sub_source_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function () {
            var table = $('.lead_sub_source').DataTable({
               processing: true,
               serverSide: true,
               order: [[ 0, "desc" ]],
               ajax: "{{ route('lead_sub_sources') }}",
               columns: [
               {data: 'lead_sub_source_id', name: 'a.lead_sub_source_id'},
               {data: 'action', name: 'a.action', orderable: false, searchable: false},
               {data: 'lead_source_name', name: 'lead_sources.lead_source_name'},
               {data: 'lead_sub_source_name', name: 'a.lead_sub_source_name'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'created_at', name: 'a.created_at'},
               {data: 'updated_by', name: 'c.first_name'},
               {data: 'updated_at', name: 'a.updated_at'},
               ]
            });
         });

         $(document).on('click', '.edit_model_btn', function(){
            var lead_sub_source_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'lead_sub_sources_edit/'+lead_sub_source_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#menu_group_edit_modal_form").html(data);
                  $("#edit_modal_lead_sub_sources").modal('show');
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