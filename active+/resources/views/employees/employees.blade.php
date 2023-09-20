<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Employees
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4><br>
                  <div class="heading-elements">
                     <a href="{{ route('employees_add') }}">
                        <button class="btn btn-primary">
                           <i class="fa fa-plus"></i> Employee
                        </button>
                     </a>
                  </div>
               </div>
               <div class="modal fade login-modal" id="EditemployeeModal" role="dialog" aria-labelledby="editemployeeLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered " role="document">
                     <div class="modal-content">
                        <div class="modal-header" id="editemployeeLabel">
                           <h4 class="modal-title">Edit Employee</h4>
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                        </div>
                        <div class="modal-body">
                           <form class="mt-0 needs-validation" novalidate method="post" action="{{ url('employees_submit/{id}') }}"  enctype="multipart/form-data">
                              @csrf
                              <div id="edit_employee_form">
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered GetEmployeeTable" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Employee Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Employee Name</th>
                                 <th>Employee Code</th>
                                 <th>Shift Name</th>
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
                                 <th>Employee Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Employee Name</th>
                                 <th>Employee Code</th>
                                 <th>Shift Name</th>
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
   @php
   $DeleteTableName="users";
   $DeleteColumnName="id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function(){
            var table=$('.GetEmployeeTable').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('employees') }}",
               columns: [
               {data:'id', name:'a.id'},
               {data:'action', name:'a.action', orderable: false, searchable: false},
               {data:'employee_name', name:'a.employee_name'},
               {data:'employee_code', name:'a.employee_code'},
               {data:'shift_id', name:'d.shift_name'},
               {data:'created_by', name:'b.first_name'},
               {data:'created_at', name:'a.created_at'},
               {data:'updated_by', name:'c.first_name'},
               {data:'updated_at', name:'a.updated_at'},

               ]
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