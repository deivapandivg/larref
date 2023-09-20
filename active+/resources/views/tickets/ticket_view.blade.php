<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Tickets View
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <ul class="list-inline mb-0">
                        <li>
                           <div class="btn-group">
                              <a>
                                 <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#add_modal_timeline" title="Timeline Add">
                                    <i class="fa fa-clock text-white"></i>
                                 </button>
                              </a>
                              <a href="{{ route('ticket_edit',encrypt($tickets_list->ticket_id)) }}">
                                 <button type="button" class="btn  btn-primary"  title="Ticket Edit">
                                    <i class="fas fa-edit text-white"></i>
                                 </button>
                              </a>
                           </div>
                           <a data-action="collapse">
                              <b>Ticket Id : {{ $tickets_list->ticket_id }}</b>
                           </a>
                        </li>
                     </ul>
                  </div>
               </div>   
               <div class="card-body">
                  <ul class="nav nav-tabs">
                     <li class="nav-item">
                        <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" aria-expanded="true"><i class="fas fa-ticket-alt"></i>TicketView</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link active" id="about-tab" data-toggle="tab" href="#about" aria-controls="about" aria-expanded="false"><i class="fa fa-clock-o"></i>TimeLine</a>
                     </li>
                      <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile" aria-expanded="false"><i class="fa fa-comments"></i>Chat</a>
                     </li>
                  </ul>
                  <hr class="new">
                  <div class="tab-content px-1 pt-1">
                     <div role="tabpanel active" class="tab-pane" id="home" aria-labelledby="home-tab" aria-expanded="true">
                        <form class="form form-horizontal mt-0" method="post" enctype="multipart/form-data">
                           <div class="form-body">
                              <h4 class="form-section"><i class="fas fa-ticket-alt"></i> Ticket Details
                              </h4>
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Customer :</b></label>
                                       <p>{{ $customers_lists->client_name }}</p>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Customer Contact :</b></label>
                                       <p>{{ $customers_contact_list->first_name }}</p>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Subject :</b></label>
                                       <p>{{ $tickets_list->subject }}</p>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Description :</b></label>
                                       <p>{{ $tickets_list->description }}</p>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Ticket Type :</b></label>
                                       <p>{{ $ticket_type_lists->ticket_type_name }}</p>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Ticket Priority :</b></label>
                                       <p>{{ $ticket_priority_lists->priority_name }}</p>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Ticket Source :</b></label>
                                       <p>{{ $ticket_source_lists->ticket_source_name }}</p>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Ticket Status :</b></label>
                                       <p>{{ $ticket_status_lists->ticket_status_name }}</p>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Assign To :</b></label>
                                       <p>{{ $ticket_assign_lists->first_name }}</p>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Ticket Created Type :</b></label>
                                       <p>{{ $ticket_created_type_lists->ticket_created_type_name }}</p>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Created By :</b></label>
                                       <p>{{ $created_by_list->first_name }}</p>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Created At :</b></label>
                                       <p>{{ $tickets_list->created_at }}</p>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Updated By :</b></label>
                                       @if($tickets_list->updated_by!='')
                                       <p>{{ $updated_by_list->first_name }}</p>
                                       @endif
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="label-control" for="userinput2"><b>Updated At :</b></label>
                                       <p>{{ $tickets_list->updated_at }}</p>
                                    </div>
                                 </div>
                              </div>
                              <h4 class="form-section"><i class="fas fa-ticket-alt"></i><b>Ticket Attachement</b></h4>
                              <div class="row">
                                 <div class="col-lg-6">
                                    <div class="form-group">
                                       <fieldset class="form-group floating-label-form-group"><b>Attachments :</b><br>
                                       @foreach($ticket_attachments as $ticket_attachment)
                                           <a href="{{ asset('public/ticket_uploads/'.$ticket_attachment->attachment)}}" target="_blank"><button type="button" class="btn btn-sm btn-primary"  title="View Attachment">
                                           <i class="fa fa-eye"></i></button></a>{{ $ticket_attachment->attachment }}<br><br>
                                           @endforeach
                                       </fieldset>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                     <div class="tab-pane active" id="about" role="tabpanel" aria-labelledby="about-tab" aria-expanded="false">
                        <ul class="nav nav-tabs">
                           <li class="nav-item">
                              <a class="nav-link" id="home1-tab" data-toggle="tab" href="#home1" aria-controls="home1" aria-expanded="true"><i class="fa fa-list"></i>ListView</a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link active" id="profile1-tab" data-toggle="tab" href="#profile1" aria-controls="profile1" aria-expanded="false"><i class="fa fa-table"></i>TableView</a>
                           </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                           <div role="tabpanel" class="tab-pane" id="home1" aria-labelledby="home1-tab" aria-expanded="true">
                              <div class="timeline" >
                                 <ul class="list-unstyled base-timeline activity-timeline mt-1">
                                    @foreach($timeline_lists_get as $timeline_list_get)
                                    <li>
                                       
                                       <div class="timeline-icon bg-danger">
                                          <i class="fa fa-clock font-medium-1"></i>
                                       </div>
                                       <div class="act-time">
                                          <?=$timeline_list_get->created_date?>
                                       </div>
                                       <div class="base-timeline-info">
                                          <a href="#" class="text-danger text-uppercase line-height-2"><?=$timeline_list_get->operation_comments?></a>
                                          <span class="d-block"><i class="fa fa-clock-o font-medium-1"></i>&nbsp;<?=$timeline_list_get->description?></span><br>
                                       </div>
                                       <ul class="base-timeline-sub list-unstyled users-list m-0">
                                          @foreach($user_lists as $user_list)
                                          <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="<?php echo $user_list->first_name; ?>" class="avatar avatar-sm pull-up AddModel">
                                             @if($timeline_list_get->created_by==$user_list->id)
                                             <img class="media-object rounded-circle" src="{{ asset('public/profile_uploads/'.$user_list->profile_upload) }}" alt="Avatar">
                                             <a class="line-height-2"><?='CreatedBy : '.$user_list->first_name. '  | CreatedAt : '.$timeline_list_get->created_at.' | '?>
                                             </a>
                                             @endif
                                          </li>
                                          @endforeach
                                       </ul>
                                       
                                    </li>
                                    @endforeach
                                 </ul>
                              </div>
                           </div>
                           <div class="tab-pane active" id="profile1" role="tabpanel" aria-labelledby="profile1-tab" aria-expanded="false">
                              <div style="overflow-x:auto;">
                                 <table class="table table-striped table-bordered ticket_timeline_table" style="width: 100%;">
                                    <thead>
                                       <tr>
                                          <th>TimeLine Id</th>
                                          <th>Customer</th>
                                          <th>Customer Contact</th>
                                          <th>Status</th>
                                          <th>OPCommand</th>
                                          <th>Description </th>
                                          <th>Assign To</th>
                                          <th>Created By</th>
                                          <th>Created At</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       
                                    </tbody>
                                    <tfoot>
                                       <tr>
                                          <th>TimeLine Id</th>
                                          <th>Customer</th>
                                          <th>Customer Contact</th>
                                          <th>Status</th>
                                          <th>OPCommand</th>
                                          <th>Description </th>
                                          <th>Assign To</th>
                                          <th>Created By</th>
                                          <th>Created At</th>
                                       </tr>
                                    </tfoot>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane " id="profile" role="tabpanel" aria-labelledby="profile-tab" aria-expanded="true">
                        <div class="chat-application">
                           <div class="content-wrapper">
                              <div class="content-body">
                                 <section class="chat-app-window">
                                    <div class="mb-1 secondary text-bold-700">Chat History</div>
                                    <div class="chats">
                                       <div class="chats">
                                          @foreach($chat_messages as $chat_message)
                                             @if($chat_message->ticket_created_type_id==2)
                                                <div class="chat chat-left">
                                                   <div class="chat-avatar">
                                                      <a class="avatar" data-toggle="tooltip" href="#" data-placement="left" title="" data-original-title="">
                                                         <img src="{{ asset('public/profile_uploads/'.$chat_message->profile) }}" class="box-shadow-4" alt="avatar" />
                                                      </a>
                                                   </div>
                                                   <div class="chat-body">
                                                      <div class="chat-content">
                                                         <p>{{ $chat_message->chat_message }}</p>
                                                      </div>
                                                   </div>
                                                </div>
                                             @else
                                                @if($tickets_list->created_by==Auth::id() OR $tickets_list->assign_to==Auth::id())
                                                <div class="chat">
                                                   <div class="chat-avatar">
                                                      <a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">
                                                         <img src="{{ asset('public/profile_uploads/'.$chat_message->profile_upload) }}" class="box-shadow-4" alt="avatar" />
                                                      </a>
                                                   </div>
                                                   <div class="chat-body">
                                                      <div class="chat-content">
                                                         <p>{{ $chat_message->chat_message }}</p>
                                                      </div>
                                                   </div>
                                                </div>
                                                @endif
                                             @endif
                                          @endforeach 
                                       </div>
                                    </div>
                                    <div class="chat-app-input mt-1 row">
                                       <div class="col-12">
                                          <div class="input-group position-relative has-icon-left">
                                             <div class="form-control-position">
                                                <span id="basic-addon3"><i class="ft-image"></i></span>
                                             </div>
                                               <input type="text" class="form-control Text " name="Chat" placeholder="Send message" id="Chat">
                                               <input type="hidden" class="form-control" name="TicketId" id="TicketId" value="{{$tickets_list->ticket_id}}">
                                             <div class="input-group-append">
                                                <button class="btn btn-primary SaveChatBtn" type="button">Send</button>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </section>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <div class="modal fade" id="add_modal_timeline"  role="dialog" aria-labelledby="add_modal_timeline" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Timeline Add</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('timelines_ticket_submit') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="modal-body">
                     <div class="row">
                        <input type="hidden" name="ticket_id" value="{{ $timeline_lists->ticket_id }}">
                        <input type="hidden" name="client_id" value="{{ $timeline_lists->client_id }}">
                        <input type="hidden" name="customer_contact_id" value="{{ $timeline_lists->customer_contact_id }}">
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Ticket Status <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <select class="form-control border-primary select2 form-select" name="status_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected disabled>Select</option>
                                    @foreach ($status_lists as $status_list)
                                    <option value="{{  $status_list->ticket_status_id }}" {{ $timeline_lists->status_id==$status_list->ticket_status_id ? 'selected' : '' }}>{{ $status_list->ticket_status_name }}</option>
                                    @endforeach
                                 </select>
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Description <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <textarea id="" name="description" rows="5" class="name form-control" placeholder="Description" required></textarea>
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="form-group">
                              <label class="label-control">Replacement Attachment :</label>
                              <table id="AddImageTable" width="50%">
                                 <tbody id="ImageTBodyAdd">
                                    <tr class="add_row">
                                       <td width="100%"><input name="replacement_attachments[]" type="file" multiple></td>
                                       <td width="20%"><button class="btn btn-success btn-sm" type="button" id="add" title='Add new file'><i class="fa fa-plus"></i></button></td>
                                    </tr>
                                 </tbody>
                              </table>
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
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(document).on('click','.SaveChatBtn', function(){
            var Chat=$("#Chat").val();
            var TicketId=$("#TicketId").val();
            $.ajax({
               url:"{{ route('chat_submit') }}",
               method:"post",
               data:{"_token":"{{ csrf_token() }}",Chat:Chat,TicketId:TicketId},
               success:function(data)
               {
                window.location.reload(); 
               }
            })
         });

         $(document).ready(function() { 
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
               localStorage.setItem('lastTab', $(this).attr('href'));
            });
            var lastTab = localStorage.getItem('lastTab');
            if (lastTab) {
               $('[href="' + lastTab + '"]').tab('show');
            }
         });
         $(function () {
            var table = $('.ticket_timeline_table').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('ticket_view',$timeline_lists->ticket_id) }}",
               columns: [
               {data: 'ticket_timeline_id', name: 'a.ticket_timeline_id'},
               {data: 'client_id', name: 'b.client_name'},
               {data: 'customer_contact_id', name: 'c.first_name'},
               {data: 'status_id', name: 'd.ticket_status_name'},
               {data: 'operation_comments', name: 'a.operation_comments'},
               {data: 'description', name: 'a.description'},
               {data: 'assign_to', name: 'e.first_name'},
               {data: 'created_by', name: 'f.first_name'},
               {data: 'created_at', name: 'a.created_at'},
               ]
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
      </script>
   </x-slot>
   <style type="text/css">
   form .form-section {
   line-height: 3rem;
   margin-bottom: 20px;
   color: solid blue;
   border-bottom: 2px solid blue;
   }

   hr.new {
     border-top: 2px solid #6967CE;
     margin-top: -5px !important;
   }
   nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
      background-color:#6967CE;
      padding: 10px !important;
      border-radius: 15px !important;
   }
    </style>
</x-app-layout>