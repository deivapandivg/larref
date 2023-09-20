<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Leaves Approvals
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
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
   <div class="modal fade" id="view_modal_leave_types"  role="dialog" aria-labelledby="view_modal_leave_types" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Leave Approval</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('leave_approval_submit') }}" enctype="multipart/form-data">
                  @csrf
               <div id="menu_group_view_modal_form"></div>
               </form>
            </section>
         </div>
      </div>
   </div>
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
            $.post("{{ route('leave_approval_ajax') }}",{_token:CSRF_TOKEN,ToDate:ToDate,FromDate:FromDate,AllDate:AllDate,user_id:user_id},function(data){
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
                     url: "{{ route('leave_approvals') }}",
                     data: function(data) { data.all_date = all_date; data.from_date=from_date;data.to_date=to_date; data.user_id=user_id; },
                 },
             columns: [
             {data: 'leave_approval_id', name: 'a.leave_approval_id'},
             {data: 'approval', name: 'a.approval', orderable: false, searchable: false},
             {data: 'approval_status', name: 'a.approval_status'},
             {data: 'user_id', name: 'b.first_name'},
             {data: 'leave_type', name: 'c.leave_type_name'},
             {data: 'from_date', name: 'a.from_date'},
             {data: 'to_date', name: 'a.to_date'},
             {data: 'num_of_days', name: 'a.num_of_days'},
             {data: 'created_at', name: 'a.created_at'},
             ]
            });
            });
               });
         }

         $(document).on('click', '.view_model_btn', function(){
            var leave_approval_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'leave_approval_model/'+leave_approval_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#menu_group_view_modal_form").html(data);
                  $("#view_modal_leave_types").modal('show');
               }
            });
         });

      </script>
      <style type="text/css">
         #search:hover{
            cursor: pointer;
         }
         #search{
            margin-top: 5px;
            margin-left: 9px;
            font-size: 33px;
         }
      </style>
   </x-slot>
</x-app-layout>