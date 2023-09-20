<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Clients
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a href="{{ route('client_add') }}">
                        <button  type="submit" class="btn btn-primary">
                           <i class="fa fa-plus"></i> Client
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered client" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Client Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Client Name</th>
                                 <th>Company Name</th>
                                 <th>Mobile Number</th>
                                 <th>Alter Number</th>
                                 <th>Email Id</th>
                                 <th>Alter Email</th>
                                 <th>Gst</th>
                                 <th>Address</th>
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
                                 <th>Client Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Client Name</th>
                                 <th>Company Name</th>
                                 <th>Mobile Number</th>
                                 <th>Alter Number</th>
                                 <th>Email Id</th>
                                 <th>Alter Email</th>
                                 <th>Gst</th>
                                 <th>Address</th>
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
      </div>
      <div class="modal modal_outer right_modal fade" id="client_view_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
         <div class="modal-dialog" role="document">
            <div class="modal-content ">
               <div class="modal-header bg-primary white">
                  <h2 class="modal-title white">Client Quick View :</h2>
                  <button type="button" class="btn btn-lg close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body get_quote_view_modal_body">
                  <form method="post" id="get_quote_frm">
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
   @php
   $DeleteTableName="clients";
   $DeleteColumnName="client_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function () {
            var table = $('.client').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('clients') }}",
               columns: [
               {data: 'client_id', name: 'a.client_id'},
               {data: 'action', name: 'action', orderable: false, searchable: false},
               {data: 'client_name', name: 'a.client_name'},
               {data: 'company_name', name: 'a.company_name'},
               {data: 'mobile_number', name: 'a.mobile_number'},
               {data: 'alter_mobile_number', name: 'a.alter_mobile_number'},
               {data: 'email_id', name: 'a.email_id'},
               {data: 'alter_email_id', name: 'a.alter_email_id'},
               {data: 'gst_number', name: 'a.gst_number'},
               {data: 'address', name: 'a.address'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'created_at', name: 'a.created_at'},
               {data: 'updated_by', name: 'c.first_name'},
               {data: 'updated_at', name: 'a.updated_at'},
               ]
            });
         });

         // $(document).on('click', '.edit_model_btn', function(){
         //    var client_id=$(this).closest("tr").find("td:eq(0)").text();
         //    $.ajax({
         //       url: 'clients_edit/'+client_id,
         //       type: "GET",
         //       data : {"_token":"{{ csrf_token() }}"},
         //       success:function(data) {
         //          $("#client_edit_form").html(data);
         //          $("#edit_modal_clients").modal('show');
         //          $(".select2").select2();
         //       }
         //    });
         // });

         $(document).on('click', '.ClientViewModal', function(){
            var client_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: "{{ route('clients_modal_view',"") }}/"+client_id,
               type: "post",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#get_quote_frm").html(data);
                  $("#client_view_modal").modal('show');
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