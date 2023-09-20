<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Ticket Status
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                           <i class="fa fa-plus"></i> Ticket Status
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered ticket_status" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;</th>
                                 <th>Name</th>
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
                                 <th>&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;</th>
                                 <th>Name</th>
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
                  <h5 class="modal-title white">Ticket Status</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('ticket_status_submit') }}">
                  @csrf
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Status Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <input type="text" id="" required name="ticket_status_name" class="name form-control" placeholder="Ticket Status Name">
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
   <div class="modal fade" id="edit_modal_status"  role="dialog" aria-labelledby="edit_modal_status" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Ticket Status</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('ticket_status_submit') }}">
                  @csrf
               <div id="status_edit_modal_form"></div>
               </form>
            </section>
         </div>
      </div>
   </div>
   @php
   $DeleteTableName="ticket_status";
   $DeleteColumnName="ticket_status_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function () {
            var table = $('.ticket_status').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('ticket_status') }}",
               columns: [
               {data: 'ticket_status_id', name: 'a.ticket_status_id'},
               {data: 'action', name: 'a.action', orderable: false, searchable: false},
               {data: 'ticket_status_name', name: 'a.ticket_status_name'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'created_at', name: 'a.created_at'},
               {data: 'updated_by', name: 'c.first_name'},
               {data: 'updated_at', name: 'a.updated_at'},
               ]
            });
         });

         $(document).on('click', '.edit_model_btn', function(){
            var ticket_status_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'ticket_status_edit/'+ticket_status_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#status_edit_modal_form").html(data);
                  $("#edit_modal_status").modal('show');
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