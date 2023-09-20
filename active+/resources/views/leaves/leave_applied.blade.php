<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Leaves Applied
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                           <i class="fa fa-plus"></i> Leave Apply
                        </button>
                     </a>
                  </div>
               </div>
               <div class="row mr-1 ml-1">
                  <div class="col-lg-3">
                     <div class="form-group">
                        <label class="label-control" for="department_id">User :</label>
                        <select class="form-control border-primary select2 form-select" name="user_id" data-placeholder="Choose one" id="user_id" style="width:100%;">
                           <option selected value="All">All Users</option>
                           @foreach ($user_lists as $user_list)
                           <option value="{{  $user_list->id }}">{{ $user_list->first_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-1">
                     <div class="form-group">
                        <label class="label-control" for="all_date">All&nbsp;Dates:</label><br>
                        <input type="checkbox" value="All"  class="border-primary form-control AllDate" name="all_date" id="AllDate"> 
                     </div>
                  </div>
                  @php $today_date=date('Y-m-d'); @endphp
                  <div class="col-md-3 DateFilters">
                     <div class="form-group">
                        <label class="label-control" for="from_date">From&nbsp;Date :</label>
                        <input type="date" value="{{ $today_date }}" class="form-control" name="from_date" id="from_date" > 
                     </div>
                  </div>
                  <div class="col-md-3 DateFilters">
                     <div class="form-group">
                        <label class="label-control" for="to_date">To&nbsp;Date :</label>
                        <input type="date" value="{{ $today_date }}" class="form-control" name="to_date" id="to_date"  > 
                     </div>
                  </div>
                  <div class="col-md-1">
                     <div class="form-group">
                        <label class="label-control" for="search">Search :</label>
                        <i class="fa fa-search fa-2x text-primary" aria-hidden="true" id="search"></i>
                     </div>
                  </div>
               </div>
               <div class="card-body">
                  <div id="AjaxData"></div>
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
                  <h5 class="modal-title white">Leave Apply</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('leave_applied_submit') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="modal-body">
                     <div class="row">
                        @php $date=date("Y-m-d"); @endphp
                        <div class="col-md-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>From Date <sup class="text-danger" style="font-size: 13px;">*</sup> : </b>
                                 <input type="date" name="from_date" required class="pick-a-date bg-white form-control" 
                                 value="{{ $date }}">
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>To Date <sup class="text-danger" style="font-size: 13px;">*</sup> : </b>
                                 <input type="date" name="to_date" id="to_date" required  class="pick-a-date bg-white form-control" 
                                 value="{{ $date }}">
                                 <span id="Company" class="text-center" style="text-align: center;"></span>
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Leave Type <sup class="text-danger" style="font-size: 13px;">*</sup> : </b>
                                 <select class="select2 form-control Customer" required name="leave_type"  style="width:100%;">
                                    <option value="" disabled="disabled" selected>Select Leave Type</option>
                                    @foreach($leave_types as $leave_type)
                                       <option  value="{{$leave_type->leave_type_id}}">{{$leave_type->leave_type_name}}</option>
                                    @endforeach
                                 </select>
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group">Attachments :
                                 <input type="file" id="Attachment" name="attachment" class="form-control" >
                              </fieldset>
                           </div>
                        </div>
                     </div>   
                     <div class="row">
                        <div class="col-md 12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group">Description :
                                 <textarea class="form-control" name="description"></textarea>
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
   <div class="modal fade" id="edit_modal_leave_types"  role="dialog" aria-labelledby="edit_modal_leave_types" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Leave Update</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('leave_applied_submit') }}" enctype="multipart/form-data">
                  @csrf
               <div id="menu_group_edit_modal_form"></div>
               </form>
            </section>
         </div>
      </div>
   </div>
   <div class="modal fade" id="view_modal_leave_types"  role="dialog" aria-labelledby="view_modal_leave_types" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Leave Applied Status</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="" action="">
                  @csrf
               <div id="menu_group_view_modal_form"></div>
               </form>
            </section>
         </div>
      </div>
   </div>
   @php
   $DeleteTableName="leave_approvals";
   $DeleteColumnName="leave_approval_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">

         $(document).on('click', '#search', function(){
            var FromDate = $('#from_date').val();
            var ToDate = $('#to_date').val();
            var user_id = $('#user_id').val();
            var True=$('#AllDate').prop('checked');
            if(True==true)
            {
               var AllDate='All';
            }
            else
            {
               var AllDate='No';
            }
           LoadTableData(FromDate,ToDate,user_id,AllDate);
         });

         $(document).on('change', '.AllDate', function(){
           var True=$('#AllDate').prop('checked');
           if(True==true)
           {
                $('.DateFilters').addClass('hidden');
           }
           else
           {
               $('.DateFilters').removeClass('hidden');
           }
       });

           $(document).ready(function(){
                LoadTableData();
            });
       
         function LoadTableData(FromDate,ToDate,user_id,AllDate) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.post("{{ route('leave_applied_ajax') }}",{_token:CSRF_TOKEN,ToDate:ToDate,FromDate:FromDate,AllDate:AllDate,user_id:user_id},function(data){
               $("#AjaxData").html(data);
               $(function () {
                var True=$('#AllDate').prop('checked');
                   if(True==true)
                   {
                       var all_date="yes";
                       var from_date=null;
                       var to_date=null;
                       var user_id=$("#user_id").val();
                   }
                   else
                   {
                       var all_date="no";
                       var from_date=$("#from_date").val();
                       var to_date=$("#to_date").val();
                       var user_id=$("#user_id").val();
                   }

               var table = $('.LeavesTable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                order: [[ 0, "desc" ]],
                ajax: {
                        url: "{{ route('leave_applied') }}",
                        data: function(data) { data.all_date = all_date; data.from_date=from_date;data.to_date=to_date; data.user_id=user_id; },
                    },
                columns: [
                {data: 'leave_approval_id', name: 'a.leave_approval_id'},
                {data: 'action', name: 'a.action', orderable: false, searchable: false},
                {data: 'approval', name: 'a.approval', orderable: false, searchable: false},
                {data: 'approval_status', name: 'a.approval_status'},
                {data: 'created_at', name: 'a.created_at'},
                {data: 'leave_type', name: 'd.leave_type_name'},
                {data: 'approval_person', name: 'c.first_name'},
                {data: 'approval_comments', name: 'a.approval_comments'},
                {data: 'num_of_days', name: 'a.num_of_days'},
                {data: 'approved_at', name: 'a.approved_at'},
                ]
               });
            });
            });
         }


         $(document).on('click', '.edit_model_btn', function(){
            var leave_applied_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'leave_applied_edit/'+leave_applied_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#menu_group_edit_modal_form").html(data);
                  $("#edit_modal_leave_types").modal('show');
               }
            });
         });

         $(document).on('click', '.view_model_btn', function(){
            var leave_applied_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'leave_applied_status_view/'+leave_applied_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#menu_group_view_modal_form").html(data);
                  $("#view_modal_leave_types").modal('show');
               }
            });
         });

         $(document).on('click', '.DeleteDataModal', function(){
            var DeleteColumnValue=$(this).closest("tr").find("td:eq(0)").text();
            $("#DeleteColumnValue").val(DeleteColumnValue);
            $("#DeleteDataModal").modal('show');
         });
      </script>
      <style type="text/css">
         #search{
            margin-top: 5px;
            margin-left: 9px;
            font-size: 33px;
         }
         #search:hover{
            cursor: pointer;
         }
      </style>
   </x-slot>
</x-app-layout>