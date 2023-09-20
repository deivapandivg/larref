<x-app-layout>
   @php
   $user_id=Auth::user()->id;
   Session::put('user_id', $user_id);
   if(session()->has('from_date')){ $from_date=Session::get('from_date'); }else{$from_date=date("Y-m-d");}
   if(session()->has('to_date')){ $to_date=Session::get('to_date'); }else{$to_date=date("Y-m-d");}
   if(session()->has('all_date')){ $all_date=Session::get('all_date'); }else{$all_date="No";}
   if(session()->has('lead_stage_id')){ $lead_stage_id=Session::get('lead_stage_id'); }else{$lead_stage_id='1';}
   @endphp


   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Leads 
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                     <div class="heading-elements d-flex">
                        <a href="{{ route('leads_import')}}">
                           <button class="btn btn-info">
                              <i class="fa fa-upload"></i> Upload
                           </button>
                        </a>
                        <a href="{{ route('lead_add') }}">
                           <button class="btn btn-primary">
                              <i class="fa fa-plus"></i> Lead
                           </button>
                        </a>
                        <a>
                           <button class="btn btn-danger" id="AddModal">
                              <i class="fa fa-plus"></i> Quick Add
                           </button>
                        </a>
                     </div> 
               </div>
               @php $today_date=date('Y-m-d');@endphp
               <div class="row mr-1 ml-1">
                  <div class="col-md-3">
                     <div class="form-group">
                        <label class="label-control" for="department_id">Users :</label>
                        <select class="form-control border-primary select2 form-select" name="user_id" data-placeholder="Choose one" id="user_id" style="width:100%;">
                           <option selected value="All">All Users</option>
                           @foreach ($users_list as $user_list)
                           <option value="{{ $user_list->id }}">{{ $user_list->first_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-1">
                     <div class="form-group">
                        <label class="label-control" for="userinput4">All&nbsp;Dates:</label><br>
                       <input type="checkbox" value="{{ $all_date }}"  class="border-primary form-control AllDate" name="AllDate" id="AllDate" <?php if(Session::get('all_date')=="All"){echo "checked";} ?>> 
                     </div>
                  </div>
                  <div class="col-md-3 <?php if($all_date=='All'){echo 'hidden'; } ?> DateFilters">
                     <div class="form-group">
                        <label class="label-control" for="userinput4">From&nbsp;Date :</label>
                        <input type="date" value="{{ $from_date }}" class="form-control border-primary" name="from_date" id="from_date"> 
                     </div>
                  </div>
                  <div class="col-md-3 <?php if($all_date=='All'){echo 'hidden'; } ?> DateFilters">
                     <div class="form-group">
                        <label class="label-control" for="userinput4">To&nbsp;Date :</label>
                        <input type="date" value="{{ $to_date }}" class="form-control border-primary" name="to_date" id="to_date"> 
                     </div>
                  </div>
                  <div class="col-md-1">
                     <div class="form-group">
                        <label class="label-control" for="search">Search&nbsp;:</label><br>
                        <i class="fa fa-search fa-2x text-primary" style="margin-top:5px;margin-left: 9px; font-size:33px;" aria-hidden="true" id="search"></i> 
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
   <!-- <div class="modal fade" id="TimelineModal"  role="dialog" aria-labelledby="TimelineModal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
         <div id="TimelineModalData">
         </div>
      </div>
   </div>
</div> -->
   <div class="modal fade" id="TimelineModal"  role="dialog" aria-labelledby="TimelineModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">TimeLine Add</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('timeline_submit')}}" enctype="multipart/form-data">
                  @csrf
                  <div id="TimelineModalData"></div>
               </form>
            </section>
         </div>
      </div>
   </div>
   <div class="modal modal_outer right_modal fade" id="lead_view_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
      <div class="modal-dialog" role="document">
         <div class="modal-content ">
            <div class="modal-header bg-primary white">
               <h2 class="modal-title white">Lead Quick View :</h2>
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
   <div class="modal modal_outer right_modal fade" id="quick_add_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header bg-primary white">
               <h2 class="modal-title white">Lead Quick Add :</h2>
               <button type="button" class="btn btn-lg close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body get_quote_view_modal_body">
               <form method="post" action="{{ route('leads_quick_add_submit') }}" enctype="multipart/form-data">
                  @csrf
               <div id="quick_add_html"></div>
               </form>
            </div>
         </div>
      </div>
   </div>
   <section>
      <div class="modal fade" id="sms_option"  role="dialog" aria-labelledby="sms_option" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
            <div class="modal-content">
               <section class="contact-form">
                  <div class="modal-header bg-primary white">
                     <h5 class="modal-title white">Send Sms</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <form method="post" action="{{ route('sms_option_submit')}}" enctype="multipart/form-data">
                     @csrf
                     <div id="sms_option_data"></div>
                  </form>
               </section>
            </div>
         </div>
      </div>
   </section>
   <section>
      <div class="modal fade" id="mail_option"  role="dialog" aria-labelledby="mail_option" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
            <div class="modal-content">
               <section class="contact-form">
                  <div class="modal-header bg-primary white">
                     <h5 class="modal-title white">Send Mail</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <form method="post" action="{{ route('mail_option_submit')}}" enctype="multipart/form-data">
                     @csrf
                     <div id="mail_option_data"></div>
                  </form>
               </section>
            </div>
         </div>
      </div>
   </section>
   <section>
      <div class="modal fade" id="whatsapp_option"  role="dialog" aria-labelledby="whatsapp_option" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
            <div class="modal-content">
               <section class="contact-form">
                  <div class="modal-header bg-primary white">
                     <h5 class="modal-title white">Send Whatsapp</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <form method="post" action="{{ route('whatsapp_option_submit')}}" enctype="multipart/form-data">
                     @csrf
                     <div id="whatsapp_option_data"></div>
                  </form>
               </section>
            </div>
         </div>
      </div>
   </section>
   @php
   $DeleteTableName="leads";
   $DeleteColumnName="lead_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(document).ready(function(){
            var LeadStatusId="{{ $lead_stage_id }}";
            if(LeadStatusId==undefined)
            {
               LeadStatusId=1;
               $("#LeadStatusTabs li a:first").addClass("active");
            }
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

            LoadTableData(LeadStatusId,ToDate,FromDate,AllDate,user_id);
         });

         $(document).on('click','#LeadStatusTabs li a', function(){
            // alert();
            var LeadStatusId=$(this).attr('href');
            var ToDate = $("#to_date").val();
            var FromDate = $('#from_date').val();
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

            LoadTableData(LeadStatusId,ToDate,FromDate,AllDate,user_id);
         });

          $(document).on('click','#search', function(){
            var LeadStatusId=$("#LeadStatusTabs li a.active").attr('href');
            var ToDate = $("#to_date").val();
            var FromDate = $('#from_date').val();
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

            LoadTableData(LeadStatusId,ToDate,FromDate,AllDate,user_id);
         });
        
         $(document).on('click', '.AllDate', function(){
            var True=$('#AllDate').prop('checked');
            var False=$('#AllDate').is(':checked');
            if(True==true)
            {
               $('.DateFilters').addClass('hidden');
            }
            if(False==false)
            {
               $('.DateFilters').removeClass('hidden');
            }
         });

         function LoadTableData(LeadStatusId,ToDate,FromDate,AllDate,user_id)
         {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.post("{{ route('leads_ajax') }}",{_token:CSRF_TOKEN,LeadStatusId:LeadStatusId,ToDate:ToDate,FromDate:FromDate,AllDate:AllDate,user_id:user_id},function(data){
               $("#AjaxData").html(data);
               
                $(function () {
                var table = $(".LeadsTable").DataTable({
                     destroy: true,
                    processing: true,
                    order: [[ 0, "desc" ]],
                    serverSide: true,
                    ajax:{
                        url: "{{ route('leads') }}",
                        type: "POST",
                        data:{ _token: "{{csrf_token()}}"}
                    },
                    columns: [
                    {data: "lead_id", name: "a.lead_id"},
                    {data: "action", name: "action", orderable: false, searchable: false},
                    {data: "lead_name", name: "a.lead_name"},
                    {data: "mobile_number", name: "a.mobile_number"},
                    {data: "alter_mobile_number", name: "a.alter_mobile_number"},
                    {data: "email_id", name: "a.email_id"},
                    {data: "alter_email_id", name: "a.alter_email_id"},
                    {data: "age", name: "a.age"},
                    {data: "medium_name", name: "d.medium_name"},
                    {data: "lead_source_name", name: "e.lead_source_name"},
                    {data: "lead_sub_source_name", name: "f.lead_sub_source_name"},
                    {data: "campaign_name", name: "g.campaign_name"},
                    {data: "lead_owner", name: "h.first_name"},
                    {data: "ad_name", name: "i.ad_name"},
                    {data: "product_category_name", name: "j.product_category_name"},
                    {data: "product_name", name: "k.product_name"},
                    {data: "country_name", name: "l.country_name"},
                    {data: "state_name", name: "m.state_name"},
                    {data: "city_name", name: "n.city_name"},
                    {data: "pincode", name: "a.pincode"},
                    {data: "address", name: "a.address"},
                    {data: "created_by", name: "b.first_name"},
                    {data: "created_at", name: "a.created_at"},
                    {data: "updated_by", name: "c.first_name"},
                    {data: "updated_at", name: "a.updated_at"},
                    ]
                  });
               });
                
            });
         }
         $(document).on('change', '#source_id', function(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var source_id=$(this).val();
            $.post("{{route('source_ajax')}}",{_token:CSRF_TOKEN,source_id:source_id},function(data){
               $("#sub_source_id").html(data);
               
            });
         });

         $(document).on('click', '.TimelineModal', function(){
            var lead_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'timeline_add/'+lead_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#TimelineModalData").html(data);
                  $("#TimelineModal").modal('show');
                  $("#TimelineModal .select2").select2();
               }
            });
         });

         $(document).on('click', '#AddModal', function(){
            $.ajax({
               url: "{{ route('leads_quick_add') }}",
               type: "post",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#quick_add_html").html(data);
                  $("#quick_add_modal").modal('show');
                  $(".select2_values").select2({
                     dropdownParent: $("#quick_add_modal")
                  });  
               }
            });
         });

         $(document).on('click', '.Checkbutton', function(){
            // var lead_id=$(this).closest("tr").find("td:eq(0)").text();
            var RadioButton=$(this).val();
            $.ajax({
               url: 'remainder_task_add/'+RadioButton,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#GetTaskAjaxYes").html(data);
                  // $("#GetTaskAjaxYes").modal('show');
                  $(".select2").select2();
               }
            });
         });
         // $(document).on('click', '#lead_stages_id', function(){
         //    $( "select option:selected" ).each(function() {
         //      $('#lead_sub_stage_id').val($( this ).text());
         //    });
         // });
         $(document).on('change', '#communication_mediumid', function(){
            var communication_id=$(this).val();
            $.post("{{route('communication_type_ajax')}}",{_token :"{{ csrf_token() }}",communication_id:communication_id},function(data){
               $("#communication_type_id").html(data);
            });
         });

          $(document).on('change', '#communication_medium', function(){
            var communication_medium=$(this).val();
            $.post(" {{route('communication_type_ajax_task')}}",{_token :"{{ csrf_token() }}",communication_medium:communication_medium},function(data){
               $("#communication_type").html(data);
            });
         });
         $(document).on('change', '#lead_stages_id', function(){
            var lead_stage_id=$(this).val();
            $.post(" {{route('lead_substage_ajax')}}",{"_token":"{{ csrf_token() }}","lead_stage_id":lead_stage_id},function(data){
               $("#lead_sub_stages_id").html(data);
            });
         });

         // Append new row In Add Form
         $(document).on('click', '#add', function(e) {
            $('#ImageTBodyAdd').append('<tr class="add_row"><td><input  name="Image[]" type="file" multiple /></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
            e.preventDefault();
         });
         // Delete row In Add Form
         $(document).on('click', "#delete", function(e) {
            if (!confirm("Are you sure you want to remove this file?"))
               return false;
            $(this).closest('tr').remove();
            e.preventDefault();
         });

         $(document).on('click', '.DeleteDataModal', function(){
            var DeleteColumnValue=$(this).closest("tr").find("td:eq(0)").text();
            $("#DeleteColumnValue").val(DeleteColumnValue);
            $("#DeleteDataModal").modal('show');
         });

         $(document).on('click', '.LeadViewModal', function(){
            var lead_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'leads_modal_view/'+lead_id,
               type: "POST",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#get_quote_frm").html(data);
                  $("#lead_view_modal").modal('show');
               }
            });
         });

         $(document).on('click', '.sms_option', function(){
            var lead_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'sms_option/'+lead_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#sms_option_data").html(data);
                  $("#sms_option").modal('show');
                  $(".select2").select2();
               }
            });
         });

         $(document).on('click', '.whatsapp_option', function(){
            var lead_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'whatsapp_option/'+lead_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#whatsapp_option_data").html(data);
                  $("#whatsapp_option").modal('show');
                   $(".select2").select2();
               }
            });
         });

         $(document).on('click', '.mail_option', function(){
            var lead_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'mail_option/'+lead_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#mail_option_data").html(data);
                  $("#mail_option").modal('show');
                  $(".select2").select2();
               }
            });
         });
         // $(document).on('click', '#success', function(e) {
         //    swal(
         //       'Success',
         //       'You clicked the <b style="color:green;">Success</b> button!',
         //       'success'
         //    )
         // });
      </script>
      <style type="text/css">
         .nav.nav-tabs .nav-item .nav-link.active {
          border-radius: 20px;
          margin-bottom: 10px;
         }
         .nav-tabs {
          border-bottom: 3px solid #6967ce;
         }

      </style>
   </x-slot>
</x-app-layout>