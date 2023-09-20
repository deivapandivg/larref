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
                  <h4 class="card-title">Tasks
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                           <i class="fa fa-plus"></i> Task
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
                        <input type="checkbox" value="{{ $all_date }}"  class="border-primary form-control AllDate" name="all_date" id="AllDate"<?php if(Session::get('all_date')=="All"){echo "checked";} ?>> 
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
                        <label class="label-control" for="search">Search :
                           <i class="fa fa-search fa-2x text-primary" style="margin-top:5px;margin-left: 9px; font-size:33px;" aria-hidden="true" id="search"></i>
                        </label>
                     </div>
                  </div>
               </div>
               <div class="card-body">
                  <div id="AjaxData"></div>
               </div>
            </div>

         </section>
         <div class="modal fade" id="AddModal"  role="dialog" aria-labelledby="AddModals" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
               <div class="modal-content">
                  <section class="contact-form">
                     <div class="modal-header bg-primary white">
                        <h5 class="modal-title white">Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <form method="post" action="{{ route('tasks_submit') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                           <div class="row">
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Client Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" name="client_id" id="client_id" data-placeholder="Choose one" style="width:100%;">
                                          <option selected>Select</option>
                                          @foreach ($clients as $client)
                                          <option value="{{  $client->client_id }}">{{ $client->client_name }}</option>
                                          @endforeach
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Task Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="text" id="" required name="task_name" class="name form-control" placeholder="Task Name">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Project Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" name="project_id" id="project_id" data-placeholder="Choose one" style="width:100%;">
                                          <option selected>Select</option>
                                          @foreach ($projects as $project)
                                          <option value="{{  $project->project_id }}">{{ $project->project_name }}</option>
                                          @endforeach
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Assigned To <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" name="assign_to" data-placeholder="Choose one" style="width:100%;">
                                          <option selected>Select</option>
                                          @foreach ($users_list as $user_list)
                                          <option value="{{  $user_list->id }}">{{ $user_list->first_name }}</option>
                                          @endforeach
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Status<sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" name="status_id" data-placeholder="Choose one" style="width:100%;">
                                          <option disabled>Select</option>
                                          @foreach ($status_lists as $status_list)
                                          <option value="{{  $status_list->status_id }}">{{ $status_list->status_name }}</option>
                                          @endforeach
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Priority Level<sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" name="priority_id" data-placeholder="Choose one" style="width:100%;">
                                          <option selected>Select</option>
                                          @foreach ($priority_lists as $priority_list)
                                          <option value="{{  $priority_list->priority_id }}">{{ $priority_list->priority_name }}</option>
                                          @endforeach
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                 <label class="label-control">Attachment :</label>
                                 <table id="AddImageTable" width="50%">
                                    <tbody id="ImageTBodyAdd">
                                       <tr class="add_row">
                                          <td width="100%"><input name="attachment[]" type="file" multiple></td>
                                          <td width="20%"><button class="btn btn-success btn-sm" type="button" id="add" title='Add new file'><i class="fa fa-plus"></i></button></td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Description : </b>
                                       <textarea class="form-control" name="description" placeholder="Description"></textarea>   
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
         <div class="modal fade" id="edit_modal_tasks"  role="dialog" aria-labelledby="edit_modal_tasks" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
               <div class="modal-content">
                  <section class="contact-form">
                     <div class="modal-header bg-primary white">
                        <h5 class="modal-title white">Task Edit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <form method="post" action="{{ route('tasks_submit') }}" enctype="multipart/form-data">
                        @csrf
                     <div id="menu_group_edit_modal_form"></div>
                     </form>
                  </section>
               </div>
            </div>
         </div>
         <div class="modal modal_outer right_modal fade" id="task_view_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog" role="document">
               <div class="modal-content ">
                  <div class="modal-header bg-primary white">
                     <h2 class="modal-title white">Task Quick View :</h2>
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
      </div>
   </section>
   @php
   $DeleteTableName="tasks";
   $DeleteColumnName="task_id";
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

            LoadTableData(ToDate,FromDate,AllDate,user_id);
         });
       
         function LoadTableData(FromDate,ToDate,user_id,AllDate) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.post("{{ route('tasks_ajax') }}",{_token:CSRF_TOKEN,ToDate:ToDate,FromDate:FromDate,AllDate:AllDate,user_id:user_id},function(data){
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

               var table = $('.tasks').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                order: [[ 0, "desc" ]],
                ajax: {
                        url: "{{ route('tasks') }}",
                        data: function(data) { data.all_date = all_date; data.from_date=from_date;data.to_date=to_date; data.user_id=user_id; },
                    },
                columns: [
                {data: 'task_id', name: 'a.task_id'},
                  {data: 'action', name: 'a.action', orderable: false, searchable: false},
                  {data: 'client_id', name: 'd.client_name'},
                  {data: 'project_id', name: 'e.project_name'},
                  {data: 'task_name', name: 'a.task_name'},
                  {data: 'task_status', name: 'f.status_name'},
                  {data: 'status_description', name: 'a.status_description'},
                  {data: 'created_by', name: 'b.first_name'},
                  {data: 'assign_to', name: 'g.first_name'},
                  {data: 'created_at', name: 'a.created_at'},
                ]
               });
            });
            });
         }

         $(document).on('click', '.edit_model_btn', function(){
            var task_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'task_edit/'+task_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#menu_group_edit_modal_form").html(data);
                  $("#edit_modal_tasks").modal('show');
                  $("#edit_modal_tasks .select2").select2();
               }
            });
         });

         $(document).on('click', '.TaskViewModal', function(){
            var task_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: "{{ route('task_modal_view',"") }}/"+task_id,
               type: "post",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#get_quote_frm").html(data);
                  $("#task_view_modal").modal('show');
               }
            });
         });

         $(document).on('click', '.DeleteDataModal', function(){
            var DeleteColumnValue=$(this).closest("tr").find("td:eq(0)").text();
            $("#DeleteColumnValue").val(DeleteColumnValue);
            $("#DeleteDataModal").modal('show');
         });

         $(document).on('change', '#client_id', function(){
            var client_id=$(this).val();
            $.post("{{ route('client_change_ajax') }}",{"_token":"{{ csrf_token() }}",client_id:client_id},function(data){
               $("#project_id").html(data);
            });
         });

         $(document).on('change', '#edit_client_id', function(){
            var client_id=$(this).val();
            $.post("{{ route('client_change_ajax') }}",{"_token":"{{ csrf_token() }}",client_id:client_id},function(data){
               $("#edit_project_id").html(data);
            });
         });

         // Append new row In Add Form
         $('#AddImageTable').on('click', "#add", function(e) {
            $('#ImageTBodyAdd').append('<tr class="add_row"><td><input  name="attachment[]" type="file" multiple /></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
            e.preventDefault();
         });
         // Delete row In Add Form
         $('#AddImageTable').on('click', "#delete", function(e) {
            if (!confirm("Are you sure you want to remove this file?"))
               return false;
            $(this).closest('tr').remove();
            e.preventDefault();
         });

         // Append new row In Edit Form
         $(document).on('click', "#EditImageTable #add", function(e) {
            $('#ImageTBodyEdit').append('<tr class="add_row"><td><input  name="attachment[]" type="file" multiple /></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
            e.preventDefault();
         });
         // Delete row In Edit Form
         $(document).on('click', "#EditImageTable #delete", function(e) {
            if (!confirm("Are you sure you want to remove this file?")){
               return false;
            }
            else{
            $(this).closest('tr').remove();
            }
            e.preventDefault();
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