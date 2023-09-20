<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Task View
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>
               <div class="card-body">
                  <h4 class="form-section"><i class="fa fa-book"></i> All Details</h4>
                  <div class="row">
                     <div class="col-lg-4">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>TaskId </b>
                              <p>{{ $tasklogs->task_id }}</p>
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Client Name </b>
                              @foreach($client_details as $client_detail)
                                 @if($client_detail->client_id==$tasklogs->client_id)
                                    <p value="{{$tasklogs->client_id}}">{{ $client_detail->client_name }}</p>
                                 @endif
                              @endforeach
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Project Name </b>
                              @foreach($projects_details as $project_detail)
                                 @if($project_detail->project_id==$tasklogs->project_id)
                                    <p value="{{$tasklogs->project_id}}">{{ $project_detail->project_name }}</p>
                                 @endif
                              @endforeach
                           </fieldset>
                        </div>
                     </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Task Name</b>
                              <p>{{ $tasklogs->task_name }}</p>
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Description</b>
                              <p>{{ $tasklogs->description }}</p>
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Assign To</b>
                              @foreach($users_details as $user_detail)
                                 @if($user_detail->id==$tasklogs->assign_to)
                                    <p value="{{$tasklogs->assign_to}}">{{ $user_detail->first_name }}</p>
                                 @endif
                              @endforeach
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Status Description</b>
                              <p>{{ $tasklogs->status_description }}</p>
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Task Status</b>
                              @foreach($task_status_details as $task_status_detail)
                                 @if($task_status_detail->status_id==$tasklogs->task_status)
                                    <p value="{{$tasklogs->task_status}}">{{ $task_status_detail->status_name }}</p>
                                 @endif
                              @endforeach
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Created By</b>
                              @foreach($users_details as $users_detail)
                                 @if($users_detail->id==$tasklogs->created_by)
                                    <p value="{{$tasklogs->created_by}}">{{ $users_detail->first_name }}</p>
                                 @endif
                              @endforeach
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Created At</b>
                              <p>{{ $tasklogs->created_at }}</p>
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Updated By</b>
                              @foreach($users_details as $users_detail)
                                 @if($users_detail->id==$tasklogs->updated_by)
                                    <p value="{{$tasklogs->created_by}}">{{ $users_detail->first_name }}</p>
                                 @endif
                              @endforeach
                           </fieldset>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="form-group">
                           <fieldset class="form-group floating-label-form-group"><b>Updated At</b>
                              <p>{{ $tasklogs->updated_at }}</p>
                           </fieldset>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="card-content">
                  <div class="card-body">
                     <h4 class="form-section"><i class="fa fa-book"></i> Log Details</h4>
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered tasksview" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Id</th>
                                 <th>Status</th>
                                 <th>Created By</th>
                                 <th>Task Owner</th>
                                 <th>Created At</th>
                                 <th>Updated By</th>
                                 <th>Updated At</th>
                                 <th>Task Comment</th>
                                 <th>Priority Status</th>
                              </tr>
                           </thead>
                           <tbody>

                           </tbody>
                           <tfoot>
                              <tr>
                                 <th>Id</th>
                                 <th>Status</th>
                                 <th>Created By</th>
                                 <th>Task Owner</th>
                                 <th>Created At</th>
                                 <th>Updated By</th>
                                 <th>Updated At</th>
                                 <th>Task Comment</th>
                                 <th>Priority Status</th>
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
         $(function () {
            var table = $('.tasksview').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('task_view',$tasklogs->task_id) }}",
               columns: [
               {data: 'task_log_id', name: 'a.task_log_id'},
               {data: 'task_status', name: 'f.status_name'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'assign_to', name: 'g.first_name'},
               {data: 'created_at', name: 'a.created_at'},
               {data: 'updated_by', name: 'h.first_name'},
               {data: 'updated_at', name: 'a.updated_at'},
               {data: 'description', name: 'a.description'},
               {data: 'priority_id', name: 'i.priority_name'},
               ]
            });
         });
         
         </script>
   </x-slot>
</x-app-layout>