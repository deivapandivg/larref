<x-app-layout>
	<section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Menu Groups
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                           <i class="fa fa-plus"></i> Menu Group
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                    <div class="table-responsive">
                       <table class="table table-striped table-bordered  MenuGroupTable" style="width:100%;">
                           <thead>
                               <tr>
                                   <th>Id</th>
                                   <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                   <th>Menu Group Name</th>
                                   <th>Menu Group Icon</th>
                                   <th>Description</th>
                                   <th>Created By</th>
                                   <th>Created At</th>
                                   <th>Updated By</th>
                                   <th>Updated At</th>
                               </tr>
                           </thead>
                           <tfoot>
                              <tr>
                                 <th>Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Menu Group Name</th>
                                 <th>Menu Group Icon</th>
                                 <th>Description</th>
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
	               <h5 class="modal-title white">Menu Group</h5>
	               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                  <span aria-hidden="true">&times;</span>
	               </button>
	            </div>
	            <form method="post" action="{{ route('menu_groups_submit') }}">
	            	@csrf
	               <div class="modal-body">
	                  <div class="row">
	                     <div class="col-lg-12">
	                        <div class="form-group">
	                          <fieldset class="form-group floating-label-form-group"><b>Menu Group Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
	                          	<input type="text" id="" required name="menu_group_name" class="name form-control" placeholder="Menu Group Name">
	                          	</fieldset>
	                        </div>
	                     </div>
	                     <div class="col-lg-12">
	                        <div class="form-group">
	                          <fieldset class="form-group floating-label-form-group"><b>Menu Group Icon <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
	                          	<input type="text" id="" required name="menu_group_icon" class="name form-control" placeholder="Menu Group Icon">
	                          	</fieldset>
	                        </div>
	                     </div>
	                     <div class="col-lg-12">
	                        <div class="form-group">
	                         	<fieldset class="form-group floating-label-form-group"><b>Description <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <textarea  required name="description" class="name form-control" rows="4" placeholder="Description"></textarea> 
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
   <div class="modal fade" id="EditModal"  role="dialog" aria-labelledby="EditModals" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Menu Group</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('menu_groups_submit') }}">
                  @csrf
                  <div id="edit_model"></div>
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
   $DeleteTableName="menu_groups";
   $DeleteColumnName="menu_group_id";
   @endphp
   @include('delete')
	<x-slot name="page_level_scripts">
	   <script type="text/javascript">
   	   $(function () {
             var table = $('.MenuGroupTable').DataTable({
                 processing: true,
                 order: [[ 0, "desc" ]],
                 serverSide: true,
                 ajax: "{{ route('menu_groups') }}",
                 columns: [
                     {data: 'menu_group_id', name: 'a.menu_group_id'},
                     {data: 'action', name: 'action', orderable: false, searchable: false},
                     {data: 'menu_group_name', name: 'a.menu_group_name'},
                     {data: 'menu_group_icon', name: 'a.menu_group_icon'},
                     {data: 'description', name: 'a.description'},
                     {data: 'created_by', name: 'b.first_name'},
                     {data: 'created_at', name: 'a.created_at'},
                     {data: 'updated_by', name: 'c.first_name'},
                     {data: 'updated_at', name: 'a.updated_at'},
                 ]
             });
         });

         $(document).on('click', '.edit_model_btn', function(){
             var menu_group_id=$(this).closest("tr").find("td:eq(0)").text();
             $.ajax({
                url: 'menu_group_edit/'+menu_group_id,
                type: "GET",
                data : {"_token":"{{ csrf_token() }}"},
                success:function(data) {
                   $("#edit_model").html(data);
                   $("#EditModal").modal('show');
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