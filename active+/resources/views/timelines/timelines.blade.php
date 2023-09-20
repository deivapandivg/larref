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
                  <h4 class="card-title">Time Lines
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>
               <div class="row mr-1 ml-1">
                  <div class="col-lg-4">
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
                  <div class="col-lg-4">
                     <div class="form-group">
                        <label class="label-control" for="communication_medium_id">Communication Medium :</label>
                        <select class="form-control border-primary select2 form-select" name="communication_medium_id" data-placeholder="Choose one" id="communication_medium_id" style="width:100%;">
                           <option selected value="All">All Communication Mediums</option>
                           @foreach ($communication_medium_lists as $communication_medium_list)
                           <option value="{{  $communication_medium_list->communication_medium_id }}">{{ $communication_medium_list->communication_medium }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="form-group">
                        <label class="label-control" for="userinput4">Communication Type :</label>
                        <select class="form-control border-primary select2"  name="communication_type_id" id="communication_type_id"  style="width: 100%">
                           <option selected value="All">All Communication Types</option>
                           <option value=""></option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-1">
                     <div class="form-group">
                        <label class="label-control" for="all_date">All&nbsp;Dates:</label><br>
                        <input type="checkbox" value="All"  class="border-primary form-control AllDate" name="AllDate" id="AllDate"> 
                     </div>
                  </div>
                  @php $today_date=date('Y-m-d'); @endphp
                  
                  <div class="col-md-3 DateFilters">
                     <div class="form-group">
                        <label class="label-control" for="from_date">From&nbsp;Date :</label>
                        <input type="date" value="{{ $today_date }}" class="form-control" name="from_date" id="from_date"> 
                     </div>
                  </div>
                  <div class="col-md-3 DateFilters">
                     <div class="form-group">
                        <label class="label-control" for="to_date">To&nbsp;Date :</label>
                        <input type="date" value="{{ $today_date }}" class="form-control" name="to_date" id="to_date" > 
                     </div>
                  </div>
                  <div class="col-md-1">
                     <div class="form-group">
                        <label class="label-control" for="search">Search&nbsp;:</label><br>
                        <i class="fa fa-search fa-2x text-primary" style="margin-top:5px;margin-left: 9px; font-size:33px;" aria-hidden="true" id="search"></i> 
                     </div>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped  table-bordered TimeLineTable" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>TimeLine Id</th>
                                 <th>LeadName</th>
                                 <th>MobileNumber</th>
                                 <th>Communicate Medium</th>
                                 <th>Communicate Type</th>
                                 <th>Lead Stage</th>
                                 <th>Lead SubStage</th>
                                 <th>Comments</th>
                                 <th>Created At</th>
                                 <th>Created By</th>
                              </tr>
                           </thead>
                           <tbody>
                           </tbody>
                           <tfoot>
                              <tr>
                                 <th>TimeLine Id</th>
                                 <th>LeadName</th>
                                 <th>MobileNumber</th>
                                 <th>Communicate Medium</th>
                                 <th>Communicate Type</th>
                                 <th>Lead Stage</th>
                                 <th>Lead SubStage</th>
                                 <th>Comments</th>
                                 <th>Created At</th>
                                 <th>Created By</th>
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
   <x-slot name="page_level_scripts">

      <script type="text/javascript">


         $(document).on('click', '#search', function(){
           var True=$('#AllDate').prop('checked');
           if(True==true)
           {
                $('.DateFilters').addClass('hidden');
           }
           else
           {
               $('.DateFilters').removeClass('hidden');
           }
           LoadTableData(True);
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
           // LoadTableData(True);
       });

           $(document).ready(function(){
                LoadTableData();
            });
       
          function LoadTableData() {
             var True=$('#AllDate').prop('checked');
                if(True==true)
                {
                    var all_date="yes";
                    var from_date=null;
                    var to_date=null;
                    var communication_medium_id=$("#communication_medium_id").val();
                    var communication_type_id=$("#communication_type_id").val();
                    var user_id=$("#user_id").val();
                }
                else
                {
                    var all_date="no";
                    var from_date=$("#from_date").val();
                    var to_date=$("#to_date").val();
                    var communication_medium_id=$("#communication_medium_id").val();
                    var communication_type_id=$("#communication_type_id").val();
                    var user_id=$("#user_id").val();
                }

            var table = $('.TimeLineTable').DataTable({
             destroy: true,
             processing: true,
             serverSide: true,
             order: [[ 0, "desc" ]],
             ajax: {
                     url: "{{ route('timeline_list') }}",
                     data: function(data) { data.all_date = all_date; data.from_date=from_date;data.to_date=to_date; data.communication_medium_id=communication_medium_id; data.communication_type_id=communication_type_id;data.user_id=user_id; },
                 },
             columns: [
             {data: 'timeline_id', name: 'a.timeline_id'},
             {data: 'lead_name', name: 'c.lead_name'},
             {data: 'mobile_number', name: 'c.mobile_number'},
             {data: 'communication_medium', name: 'd.communication_medium'},
             {data: 'communication_type', name: 'e.communication_type'},
             {data: 'lead_stage_name', name: 'f.lead_stage_name'},
             {data: 'lead_sub_stage', name: 'g.lead_sub_stage'},
             {data: 'description', name: 'a.description'},
             {data: 'first_name', name: 'b.first_name'},
             {data: 'created_at', name: 'a.created_at'},
             ]
            });
         }


            $(document).on('change', '#communication_medium_id', function(){
            var communication_id=$(this).val();
            $.post(" {{route('communication_type_ajax')}}",{_token :"{{ csrf_token() }}",communication_id:communication_id},function(data){
               $("#communication_type_id").html(data);
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