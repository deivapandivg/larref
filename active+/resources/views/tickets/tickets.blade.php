
<x-app-layout>
   @php
   $user_id=Auth::user()->id;
   Session::put('user_id', $user_id);
   if(session()->has('from_date')){ $from_date=Session::get('from_date'); }else{$from_date=date("Y-m-d");}
   if(session()->has('to_date')){ $to_date=Session::get('to_date'); }else{$to_date=date("Y-m-d");}
   if(session()->has('all_date')){ $all_date=Session::get('all_date'); }else{$all_date="No";}
   @endphp
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Tickets
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a href="{{ route('ticket_add') }}">
                        <button  type="submit" class="btn btn-primary">
                           <i class="fa fa-plus"></i> Ticket
                        </button>
                     </a>
                  </div>
               </div>
               <div class="row mr-1 ml-1">
                  <div class="col-lg-3">
                     <div class="form-group">
                        <label class="label-control" for="department_id">Assigned To :</label>
                        <select class="form-control border-primary select2 form-select" name="user_id" data-placeholder="Choose one" id="user_id" style="width:100%;">
                           <option selected value="All">All Users</option>
                           @foreach ($users_list as $user_list)
                           <option value="{{  $user_list->id }}">{{ $user_list->first_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-1">
                     <div class="form-group">
                        <label class="label-control" for="all_date">All&nbsp;Dates:</label><br>
                        <input type="checkbox" value="{{ $all_date }}"  class="border-primary form-control AllDate" name="all_date" id="AllDate" <?php if(Session::get('all_date')=="All"){echo "checked";} ?>> 
                     </div>
                  </div>
                  @php $today_date=date('Y-m-d'); @endphp
                  <div class="col-md-3 <?php if($all_date=='All'){echo 'hidden'; } ?> DateFilters">
                     <div class="form-group">
                        <label class="label-control" for="from_date">From&nbsp;Date :</label>
                        <input type="date" value="{{ $from_date }}" class="form-control" name="from_date" id="from_date" > 
                     </div>
                  </div>
                  <div class="col-md-3 <?php if($all_date=='All'){echo 'hidden'; } ?> DateFilters">
                     <div class="form-group">
                        <label class="label-control" for="to_date">To&nbsp;Date :</label>
                        <input type="date" value="{{ $to_date }}" class="form-control" name="to_date" id="to_date"  > 
                     </div>
                  </div>
                  <div class="col-md-1">
                     <div class="form-group">
                        <label class="label-control" for="search">Search :</label>
                        <i class="fa fa-search fa-2x text-primary" style="margin-top:5px;margin-left: 9px; font-size:33px;" aria-hidden="true" id="search"></i>
                     </div>
                  </div>
               </div>
               <div class="card-body">
                  <div id="AjaxData"></div>
               </div>
            </div>
         </section>
      </div>
   </section>
   <div class="modal modal_outer right_modal fade" id="ticket_view_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
      <div class="modal-dialog" role="document">
         <div class="modal-content ">
            <div class="modal-header bg-primary white">
               <h2 class="modal-title white">Ticket Quick View :</h2>
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
   @php
   $DeleteTableName="tickets";
   $DeleteColumnName="ticket_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">

         $(document).ready(function(){

            var ToDate = $("#to_date").val();
            var FromDate = $('#from_date').val();
            var user_id = $('#user_id').val();
            var True = $('#AllDate').prop('checked');
            if(True==true)
            {
               var AllDate='All';
            }
            else
            {
               var AllDate='No';
            }

            LoadTableData(ToDate,FromDate,AllDate,user_id);
         });

         $(document).on('click', '#search', function(){
            var ToDate = $("#to_date").val();
            var FromDate = $('#from_date').val();
            var user_id = $('#user_id').val();
            var True = $('#AllDate').prop('checked');
            if(True==true)
            {
               var AllDate='All';
            }
            else
            {
               var AllDate='No';
            }

            LoadTableData(ToDate,FromDate,AllDate,user_id);
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
       
         function LoadTableData(ToDate,FromDate,AllDate,user_id)
         {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.post("{{ route('tickets_ajax') }}",{_token:CSRF_TOKEN,ToDate:ToDate,FromDate:FromDate,AllDate:AllDate,user_id:user_id},function(data){
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

               var table = $('.tickets').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                order: [[ 0, "desc" ]],
                ajax: {
                        url: "{{ route('tickets') }}",
                        data: function(data) { data.all_date = all_date; data.from_date=from_date;data.to_date=to_date; data.user_id=user_id; },
                    },
               columns: [
               {data: 'ticket_id', name: 'a.ticket_id'},
               {data: 'action', name: 'a.action', orderable: false, searchable: false},
               {data: 'client_id', name: 'd.client_name'},
               {data: 'customer_contact_id', name: 'e.first_name'},
               {data: 'subject', name: 'a.subject'},
               {data: 'description', name: 'a.description'},
               {data: 'ticket_type_id', name: 'f.ticket_type'},
               {data: 'priority_id', name: 'g.priority_name'},
               {data: 'source_id', name: 'h.ticket_source_name'},
               {data: 'assign_to', name: 'i.first_name'},
               {data: 'status_id', name: 'j.status_name'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'created_at', name: 'a.created_at'},
               {data: 'updated_by', name: 'c.first_name'},
               {data: 'updated_at', name: 'a.updated_at'},
                ]
               });
               });
                
            });
         }

         $(document).on('click', '.DeleteDataModal', function(){
            var DeleteColumnValue=$(this).closest("tr").find("td:eq(0)").text();
            $("#DeleteColumnValue").val(DeleteColumnValue);
            $("#DeleteDataModal").modal('show');
         });

         $(document).on('click', '.TicketViewModal', function(){
            var ticket_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: "{{ route('ticket_modal_view',"") }}/"+ticket_id,
               type: "post",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#get_quote_frm").html(data);
                  $("#ticket_view_modal").modal('show');
               }
            });
         });
      </script>
      <style type="text/css">
         #search:hover{
            cursor:pointer;
         }
      </style>
   </x-slot>
</x-app-layout>