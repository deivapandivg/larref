<x-app-layout>
   @php
      $user_id=Auth::user()->id;
   @endphp
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Leads To Do
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                     <div class="heading-elements">
                        <a href="{{ route('leads_import')}}">
                           <button class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                              <i class="fa fa-upload"></i> Upload
                           </button>
                        </a>
                        <a href="{{ route('lead_add') }}">
                           <button class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                              <i class="fa fa-plus"></i> Lead
                           </button>
                        </a>
                     </div> 
               </div>
               <div class="card-content">
                  <div class="card-body">
                    <div class="table-responsive">
                       <table class="table table-striped table-bordered LeadsToDoTable" style="width:100%;">
                           <thead>
                               <tr>
                                   <th>Id</th>
                                   <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                   <th>Lead Name</th>
                                   <th>Mobile Number</th>
                                   <th>Alter MobileNumber</th>
                                   <th>Email</th>
                                   <th>Alter EmailId</th>
                                   <th>Age</th>
                                   <th>Medium Id</th>
                                   <th>Source Id</th>
                                   <th>Sub SourceId</th>
                                   <th>CampaignId</th>
                                   <th>Lead Owner</th>
                                   <th>Ad Name</th>
                                   <th>Course Categoty</th>
                                   <th>Course</th>
                                   <th>Country</th>
                                   <th>State</th>
                                   <th>City</th>
                                   <th>Pincode</th>
                                   <th>Address</th>
                                   <th>Created By</th>
                                   <th>Created At</th>
                                   <th>Updated By</th>
                                   <th>Updated At</th>
                               </tr>
                           </thead>
                           <tfoot>
                              <tr>
                                   <th>Id</th>
                                   <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                   <th>Lead Name</th>
                                   <th>Mobile Number</th>
                                   <th>Alter MobileNumber</th>
                                   <th>Email</th>
                                   <th>Alter EmailId</th>
                                   <th>Age</th>
                                   <th>Medium Id</th>
                                   <th>Source Id</th>
                                   <th>Sub SourceId</th>
                                   <th>CampaignId</th>
                                   <th>Lead Owner</th>
                                   <th>Ad Name</th>
                                   <th>Course Categoty</th>
                                   <th>Course</th>
                                   <th>Country</th>
                                   <th>State</th>
                                   <th>City</th>
                                   <th>Pincode</th>
                                   <th>Address</th>
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
   <div class="modal fade" id="Task_modal"  role="dialog" aria-labelledby="Task_modal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <!-- <section class="contact-form">
               <form method="post" action="{{ route('remainder_task_submit')}}" enctype="multipart/form-data">
                  @csrf
                  <div id="TaskModalData"></div>
               </form>
            </section> -->
         </div>
      </div>
   </div>
   @php
   $DeleteTableName="leads";
   $DeleteColumnName="lead_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function () {
           var table = $(".LeadsToDoTable").DataTable({
           processing: true,
           order: [[ 0, "desc" ]],
           serverSide: true,
           ajax: "{{ route('to_do') }}",
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
            $.post(" {{route('communication_type_ajax')}}",{_token :"{{ csrf_token() }}",communication_id:communication_id},function(data){
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