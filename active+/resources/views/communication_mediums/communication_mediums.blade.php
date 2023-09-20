<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Communication Mediums
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                           <i class="fa fa-plus"></i> Communication Medium
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered CommunicationTable text-center" style="width:100%;">
                           <thead>
                              <tr>
                                 <th class="">Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;</th>
                                 <th class="">Communication Name</th>
                                 <th class="">Description</th>
                                 <th class="">created_by</th>
                                 <th class="">created_at</th>
                              </tr>
                           </thead>
                           <tbody>

                           </tbody>
                           <tfoot>
                              <tr>
                                 <th class="">Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;</th>
                                 <th class="">Communication Name</th>
                                 <th class="">Description</th>
                                 <th class="">created_by</th>
                                 <th class="">created_at</th>
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
                  <h5 class="modal-title white">Communication Medium</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('communication_mediums_submit') }}">
                  @csrf
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Communication Medium Name  <sup class="text-danger" style="font-size: 13px;">*</sup>:</b>
                                 <input type="text" id="Title" required name="communication_medium" class="name form-control" placeholder="Communication Medium">
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Description :</b>
                                 <textarea id="" name="description" class="name form-control" placeholder="Description"></textarea>
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
   <div class="modal fade" id="CommunicationMediumEdit"  role="dialog" aria-labelledby="edit_modal_menus" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Communication Medium</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('communication_mediums_submit') }}">
                  @csrf
               <div id="edit_communication_form"></div>
               <div class="modal-footer">
                     <button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
                        <i class="fa fa-times"></i> Close
                     </button>
                     <button type="submit" class="btn btn-primary btn-md">
                        <i class="fa fa-check"></i> Update
                     </button>
                  </div>
               </form>
            </section>
         </div>
      </div>
   </div>
   @php
   $DeleteTableName="communication_mediums";
   $DeleteColumnName="communication_medium_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function () {
            var table = $('.CommunicationTable').DataTable({
             processing: true,
             serverSide: true,
             order: [[ 0, "desc" ]],
             ajax: "{{ route('communication_mediums') }}",
             columns: [
             {data: 'communication_medium_id', name: 'communication_mediums.communication_medium_id'},
             {data: 'action', name: 'action', orderable: false, searchable: false},
             {data: 'communication_medium', name: 'communication_mediums.communication_medium'},
             {data: 'description', name: 'communication_mediums.description'},
             {data: 'first_name', name: 'users.first_name'},
             {data: 'created_at', name: 'communication_mediums.created_at'},
             ]
            });
         });

         $(document).on('click', '.edit_model_btn', function(){
             var communication_medium_id=$(this).closest("tr").find("td:eq(0)").text();
             $.ajax({
                url: 'communication_medium_edit/'+communication_medium_id,
                type: "GET",
                data : {"_token":"{{ csrf_token() }}"},
                success:function(data) {
                   $("#edit_communication_form").html(data);
                   $("#CommunicationMediumEdit").modal('show');
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