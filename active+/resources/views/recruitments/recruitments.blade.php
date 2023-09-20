<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Recruitments 
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a href="{{ route('candidate_add') }}">
                        <button class="btn btn-primary">
                           <i class="fa fa-plus"></i> Recruitment
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-body">
                  <div id="AjaxData"></div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <div class="modal fade" id="edit_modal_recruitments"  role="dialog" aria-labelledby="edit_modal_recruitments" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Department</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('recruitment_submit') }}">
                  @csrf
               <div id="recruitment_edit_modal_form"></div>
               </form>
            </section>
         </div>
      </div>
   </div>
   @php
   $DeleteTableName="candidates";
   $DeleteColumnName="candidate_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(document).on('click', '.edit_model_btn', function(){
            var candidate_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'recruitment_edit/'+candidate_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#recruitment_edit_modal_form").html(data);
                  $("#edit_modal_recruitments").modal('show');
               }
            });
         });
         $(document).ready(function(){
         var RecruitmentStageId="1";
            if(RecruitmentStageId==undefined)
            {
               RecruitmentStageId=1;
               $("#RecruitmentTabs li a:first").addClass("active");
            }
            LoadTableData(RecruitmentStageId);
         });

         $(document).on('click','#RecruitmentTabs li a', function(){

            var RecruitmentStageId=$(this).attr('href');

            LoadTableData(RecruitmentStageId);
         });

         function LoadTableData(RecruitmentStageId)
         {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.post("recruitment_ajax",{_token:CSRF_TOKEN,RecruitmentStageId:RecruitmentStageId},function(data){
               $("#AjaxData").html(data);
            });
         }
         $(document).on('click', '.DeleteDataModal', function(){
            var DeleteColumnValue=$(this).closest("tr").find("td:eq(0)").text();
            $("#DeleteColumnValue").val(DeleteColumnValue);
            $("#DeleteDataModal").modal('show');
         });
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