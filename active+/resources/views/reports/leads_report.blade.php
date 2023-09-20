<x-app-layout>
   <style type="text/css">

   </style>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <form class="form form-horizontal" method="post" enctype="multipart/form-data" action="leads_operation_action.php">
                  <div class="card-header">
                     <h4 class="card-title">Leads Report
                        <ol class="breadcrumb mt-0">
                           <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                           </li>
                        </ol>

                     </h4>
                  </div>
                  <div class="row mr-1 ml-1">
                     <div class="selectclass col-lg-4">
                        <div class="form-group">
                           <label class="label-control" for="user">Employee :</label>
                           <select class="form-control select2 form-select" name="user" data-placeholder="Choose one" id="user_id" style="width:100%;" required>
                              <option selected>All Employees</option>
                              @foreach ($user_lists as $user_list)
                              <option value="{{  $user_list->id }}">{{ $user_list->first_name }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <!-- <div class="selectclass col-lg-4">
                        <div class="form-group">
                           <label class="label-control" for="campaign_id">Campaign :</label>
                           <select class="form-control select2 form-select" name="campaign_id" data-placeholder="Choose one" id="campaign_id" style="width:100%;" required>
                              <option selected>All Campaigns</option>
                              @foreach ($campaigns as $campaign)
                              <option value="{{  $campaign->campaign_id }}">{{ $campaign->campaign_name }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div> -->
                     <div class="selectclass col-lg-4">
                        <div class="form-group">
                           <label class="label-control" for="status_id">Lead Status :</label>
                           <select class="form-control select2 form-select" name="lead_stage_id" data-placeholder="Choose one" id="lead_stage" style="width:100%;" required>
                              <option selected>All Status</option>
                              @foreach ($lead_stages as $lead_stage)
                              <option value="{{  $lead_stage->lead_stage_id }}">{{ $lead_stage->lead_stage_name }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     
                     @php $today_date=date('Y-m-d'); @endphp
                     
                     <div class="col-md-4 DateFilters">
                        <div class="form-group">
                           <label class="label-control" for="from_date">From&nbsp;Date :</label>
                           <input type="date" value="{{ $today_date }}" class="form-control" name="from_date" id="from_date"> 
                        </div>
                     </div>
                     <div class="col-md-4 DateFilters">
                        <div class="form-group">
                           <label class="label-control" for="to_date">To&nbsp;Date :</label>
                           <input type="date" value="{{ $today_date }}" class="form-control" name="to_date" id="to_date" > 
                        </div>
                     </div>
                     <div class="col-md-1">
                        <div class="form-group">
                           <label class="label-control" for="all_date">All&nbsp;Dates:</label><br>
                           <input type="checkbox" value="All"  class="border-primary form-control AllDate" name="AllDate" id="AllDate"> 
                        </div>
                     </div>
                     <div class="col-md-1">
                        <div class="form-group">
                           <label class="label-control" for="search">Search&nbsp;:</label><br>
                           <i class="fa fa-search fa-2x text-primary" style="margin-top:5px;margin-left: 9px; font-size:33px;" aria-hidden="true" id="Search"></i> 
                        </div>
                     </div>
                  </div>
                  <div id="AjaxData">
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>

   <x-slot name="page_level_scripts">
      
      <script type="text/javascript">
         
         $(document).on('change', '.AllDate', function(){
            
           var True=$('#AllDate').prop('checked');
           if(True==true)
           {
                $('.DateFilters').addClass('hidden');
                $('.selectclass').removeClass("col-lg-4").addClass("col-lg-3");
           }
           else
           {
               $('.DateFilters').removeClass('hidden');
               $('.selectclass').removeClass("col-lg-3").addClass("col-lg-4");
           }
         });


         $(document).ready(function(){
            $("#AjaxData").html("<center><img src='public/loading.gif'></center>");
            var user_id = $('#user_id').val();
            var lead_stage = $('#lead_stage').val();
            // var campaign_id = $('#campaign_id').val();
            if(user_id!=''  && lead_stage!="")
            {
               var to_date = $("#to_date").val();
               var from_date = $('#from_date').val();
               var True=$('#AllDate').prop('checked');
               var False=$('#AllDate').is(':checked');
               if(True==true)
               {
                  var AllDate='All';
               }
               if(False==false)
               {
                  var AllDate='No';
               }
               LoadTableData(to_date,from_date,user_id,lead_stage,AllDate);
            }
            else
            {
               var to_date = $("#to_date").val();
               var from_date = $('#from_date').val();
               var True=$('#AllDate').prop('checked');
               var False=$('#AllDate').is(':checked');
               var user_id=new Array("All");
               var lead_stage=new Array("All");

               if(True==true)
               {
                  var AllDate='All';
               }
               if(False==false)
               {
                  var AllDate='No';
               }
               LoadTableData(to_date,from_date,user_id,lead_stage,AllDate);
            }
         });

         $(document).on('click', '#Search', function(){
            // alert()
            $("#AjaxData").html("<center><img src='public/loading.gif'></center>");
            var lead_stage = $('#lead_stage').val();
            var user_id = $('#user_id').val();
            // var campaign_id = $('#campaign_id').val();
         if(user_id!='' && lead_stage!="")
         {
            var to_date = $("#to_date").val();
            var from_date = $('#from_date').val();
            
            var True=$('#AllDate').prop('checked');
            var False=$('#AllDate').is(':checked');
            if(True==true)
            {
               var AllDate='All';
            }
            if(False==false)
            {
               var AllDate='No';
            }
            
               LoadTableData(to_date,from_date,user_id,lead_stage,AllDate);
         }
            else
            {
               toastr.warning('<p>Alert <br>Select AnyOne</p>');
            }
         });

         function LoadTableData(to_date,from_date,user_id,lead_stage,AllDate)
         {
            $("#AjaxData").html("<center><img src='public/loading.gif'></center>");
            $.post("{{ route('leads_report_ajax') }}",{_token :"{{ csrf_token() }}",to_date:to_date,from_date:from_date,user_id:user_id,lead_stage:lead_stage,AllDate:AllDate},function(data){
               $("#AjaxData").html(data);
            });
         }

         // $(document).on('click', '.UsersTable td', function(){
         //    var UserId = $(this).attr("UserId");
         //    var Stageid = $(this).attr("Stageid");
         //    var to_date = $("#to_date").val();
         //    var from_date = $('#from_date').val();
         //    var user_id = $('#user_id').val();
            
         //    var campaign_id = $('#campaign_id').val();
         //    var lead_stage = $('#lead_stage').val();
         //    var True = $('#AllDate').prop('checked');
         //    var False = $('#AllDate').is(':checked');
         //    if(True==true)
         //       {
         //          var AllDate='All';
         //       }
         //       if(False==false)
         //       {
         //          var AllDate='No';
         //       }
         // window.open("lead_distribution_substage_click_data.php?UserId="+btoa(UserId)+"&Stageid="+btoa(Stageid)+"&to_date="+btoa(to_date)+"&from_date="+btoa(from_date)+"&user_id="+btoa(user_id)+"&="+btoa()+"&LeadSources="+btoa(LeadSources)+"&campaign_id="+btoa(campaign_id)+"&lead_stage="+btoa(lead_stage)+"&AllDate="+btoa(AllDate));
         // });
      </script>

   </x-slot>
</x-app-layout>