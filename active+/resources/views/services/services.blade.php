<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Services
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a href="{{ route('services_add') }}">
                        <button  type="submit" class="btn btn-primary">
                           <i class="fa fa-plus"></i> Service
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered service" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Service Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Service Category Name</th>
                                 <th>Service Name</th>
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
                                 <th>Service Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Service Category Name</th>
                                 <th>Service Name</th>
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
                        <h5 class="modal-title white">Service</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <form method="post" action="{{ route('services_submit') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            
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
         <div class="modal fade" id="edit_modal_services"  role="dialog" aria-labelledby="edit_modal_services" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
               <div class="modal-content">
                  <section class="contact-form">
                     <div class="modal-header bg-primary white">
                        <h5 class="modal-title white">Service</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <form method="post" action="{{ route('services_submit') }}" enctype="multipart/form-data">
                        @csrf
                     <div id="service_edit_form"></div>
                     </form>
                  </section>
               </div>
            </div>
         </div>
         <div class="modal modal_outer right_modal fade" id="service_modal_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header bg-primary white">
                     <h2 class="modal-title white">Service Quick View :</h2>
                     <button type="button" class="btn btn-lg close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body get_quote_view_modal_body">
                     <form method="post" id="get_form_data">
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   @php
   $DeleteTableName="services";
   $DeleteColumnName="service_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function () {
            var table = $('.service').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('services') }}",
               columns: [
               {data: 'service_id', name: 'a.service_id'},
               {data: 'action', name: 'action', orderable: false, searchable: false},
               {data: 'service_category_id', name: 'd.service_category_name'},
               {data: 'service_name', name: 'a.service_name'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'created_at', name: 'a.created_at'},
               {data: 'updated_by', name: 'c.first_name'},
               {data: 'updated_at', name: 'a.updated_at'},
               ]
            });
         });

         $(document).on('click', '.ServiceModalView', function(){
            var service_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'services_modal_view/'+service_id,
               type: "post",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#get_form_data").html(data);
                  $("#service_modal_view").modal('show');
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