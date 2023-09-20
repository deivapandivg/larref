<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Task Update
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <p>Task Id :{{ $task_details->task_id }}</p>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <form class="form form-horizontal" method="post" action="{{ route('task_update_submit') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                           <input type="hidden" name="task_id" value="{{ $task_details->task_id }}">
                        <h4 class="form-section"></h4>
                        <div class="row">
                           <div class="col-lg-6">
                              <div class="form-group row">
                                 <label class="col-lg-3 label-control" for="userinput1">Client :</label>
                                 <div class="col-lg-9">
                                    <p>{{ $clients->client_name }}</p>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group row">
                                 <label class="col-lg-3 label-control" for="userinput1">Project :</label>
                                 <div class="col-lg-9">
                                    <p>{{ $projects->project_name }}</p>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group row">
                                 <label class="col-lg-3 label-control" for="userinput1">Task :</label>
                                 <div class="col-lg-9">
                                    <p>{{ $task_details->task_name }}</p>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group row">
                                 <label class="col-lg-3 label-control" for="userinput1">Description :</label>
                                 <div class="col-lg-9">
                                    <p>{{ $task_details->status_description }}</p>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group row">
                                 <label class="col-lg-3 label-control" for="userinput1">Created By :</label>
                                 <div class="col-lg-9">
                                    <p>{{ $createdby->first_name }}</p>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group row">
                                 <label class="col-lg-3 label-control" for="userinput1">Created At :</label>
                                 <div class="col-lg-9">
                                    <p>{{ $task_details->created_at }}</p>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <h4 class="form-section"></h4>
                        <div class="row">
                           <div class="col-lg-6">
                              <div class="form-group row">
                                 <label class="col-lg-3 label-control" for="userinput1">Assigned To :</label>
                                 <div class="col-lg-9">
                                    <p>{{ $users_list->first_name }}</p>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group row">
                                 <label class="col-lg-3 label-control" for="userinput1">Priority Level :</label>
                                 <div class="col-lg-9">
                                    <p>{{ $priority_lists->priority_name }}</p>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group row">
                                 <label class="col-lg-3 label-control" for="userinput1">Attachment :</label>
                                 <div class="col-lg-9">
                                 @foreach($task_attachments as $task_attachment)
                                    @if($task_attachment->attachment=='')
                                    <p>{{ $task_attachment->attachment }}</p>
                                    @else
                                    <p><a href="{{ asset('public/task_uploads/'. $task_attachment->attachment) }}" target="_blank"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;{{ $task_attachment->attachment }}</p>
                                    @endif
                                 @endforeach
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group row">
                                 <label class="col-lg-3 label-control" for="userinput1"><b>Status<sup class="text-danger" style="font-size: 13px;">*</sup> :</b></label>
                                 <div class="col-lg-9">
                                    <select class="form-control border-primary select2 form-select" name="status_id" data-placeholder="Choose one" style="width:100%;">
                                       <option disabled selected>Select</option>
                                       @foreach ($status_lists as $status_list)
                                          <option value="{{  $status_list->status_id }}" {{ $task_details->task_status==$status_list->status_id ? 'selected' : '' }}>{{ $status_list->status_name }}</option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Description : </b>
                                    <textarea class="form-control" rows="5" name="description" placeholder="Description">{{ $task_details->description }}</textarea>   
                                 </fieldset>   
                              </div>
                           </div>
                        </div> 
                        <div class="form-actions right">
                           <a href="{{ route('tasks') }}">
                              <button type="button" class="btn btn-danger btn-md">
                                 <i class="fa fa-times"></i> Close
                              </button>
                           </a>
                           <button type="submit" class="btn btn-primary btn-md">
                              <i class="fa fa-check"></i> Update
                           </button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <x-slot name="page_level_scripts">
      <script type="text/javascript">

         
      </script>
   </x-slot>
   <style type="text/css">
         form .form-section
      {
         line-height: 3rem;
         margin-bottom: 20px;
         color: #82a3de;
         border-bottom: 1px solid #9cb6e5;
      }
    </style>
</x-app-layout>