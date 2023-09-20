<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Employees Shifts
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                           <i class="fa fa-plus"></i> Employee Shift
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered shift" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Shift Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Employee Name</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Shift Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Shift Description</th>
                                 <th>&nbsp;&nbsp;From Date&nbsp;&nbsp;</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;To Date&nbsp;&nbsp;&nbsp;&nbsp;</th>
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
                                 <th>Shift Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Employee Name</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Shift Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Shift Description</th>
                                 <th>&nbsp;&nbsp;From Date&nbsp;&nbsp;</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;To Date&nbsp;&nbsp;&nbsp;&nbsp;</th>
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
                        <h5 class="modal-title white">Shift</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <form method="post" action="{{ route('shift_submit') }}">
                        @csrf
                        <div class="modal-body">
                           <div class="row">
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Employee Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" name="user_id" data-placeholder="Choose one" style="width:100%;" required>
                                          <option selected disabled>Select Employee</option>
                                          @foreach ($users_lists as $users_list)
                                          <option value="{{  $users_list->id }}">{{ $users_list->first_name }}</option>
                                          @endforeach
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Shift Timing <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" name="shift_id" data-placeholder="Choose one" style="width:100%;" required>
                                          <option selected disabled>Select Shift</option>
                                          @foreach ($shift_lists as $shift_list)
                                          <option value="{{  $shift_list->shift_id }}">{{ $shift_list->shift_name }}</option>
                                          @endforeach
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>From Date :</b>
                                       <input type="date" id="" name="from_date" class="name form-control">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>To Date  :</b>
                                       <input type="date" id="" name="to_date" class="name form-control">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Shift Description :</b>
                                       <textarea class="form-control" rows="2" name="shift_description" placeholder="Shift Description"></textarea>
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
         <div class="modal fade" id="edit_modal_teams"  role="dialog" aria-labelledby="edit_modal_teams" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
               <div class="modal-content">
                  <section class="contact-form">
                     <div class="modal-header bg-primary white">
                        <h5 class="modal-title white">Employee Shift Edit </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <form method="post" action="{{ route('shift_submit') }}">
                        @csrf
                     <div id="menu_group_edit_modal_form"></div>
                     </form>
                  </section>
               </div>
            </div>
         </div>
         <div class="modal fade" id="view_modal_teams"  role="dialog" aria-labelledby="view_modal_teams" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
               <div class="modal-content">
                  <section class="contact-form">
                     <div class="modal-header bg-primary white">
                        <h5 class="modal-title white">Employee Shift View </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <form method="post" action="">
                        @csrf
                     <div id="menu_group_view_modal_form"></div>
                     </form>
                  </section>
               </div>
            </div>
         </div>
      </div>
   </section>
   @php
   $DeleteTableName="users_shifts";
   $DeleteColumnName="users_shift_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function () {
            var table = $('.shift').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('users_shifts') }}",
               columns: [
               {data: 'users_shift_id', name: 'a.users_shift_id'},
               {data: 'action', name: 'a.action', orderable: false, searchable: false},
               {data: 'user_id', name: 'd.first_name'},
               {data: 'shift_id', name: 'e.shift_name'},
               {data: 'shift_description', name: 'a.shift_description'},
               {data: 'from_date', name: 'a.from_date'},
               {data: 'to_date', name: 'a.to_date'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'created_at', name: 'a.created_at'},
               {data: 'updated_by', name: 'c.first_name'},
               {data: 'updated_at', name: 'a.updated_at'},
               ]
            });
         });

         $(document).on('click', '.edit_model_btn', function(){
            var users_shift_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'shift_edit/'+users_shift_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#menu_group_edit_modal_form").html(data);
                  $("#edit_modal_teams").modal('show');
                  $(".select2-show-search").select2();
               }
            });
         });

         $(document).on('click', '.view_model_btn', function(){
            var users_shift_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'shift_view/'+users_shift_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#menu_group_view_modal_form").html(data);
                  $("#view_modal_teams").modal('show');
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