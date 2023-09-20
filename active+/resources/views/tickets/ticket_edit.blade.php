<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Ticket Edit
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>   
               <div class="card-body">
                  <form class="form" method="POST" enctype="multipart/form-data" action="{{ route('ticket_submit') }}">
                     @csrf
                     <div class="form-body">
                        <div class="row">
                           <input type="hidden" name="ticket_id" value="{{ $tickets_list->ticket_id }}">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="label-control">Customer :</label>
                                 <select class="form-control border-primary select2 form-select" name="client_id" id="customer" data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($customers_lists as $customer_list)
                                    <option value="{{  $customer_list->client_id }}" {{ $customer_list->client_id==$tickets_list->client_id ? 'selected' : '' }}>{{ $customer_list->client_name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="label-control">Customer Contact :</label>
                                 <select class="form-control border-primary select2 form-select" name="customer_contact_id" id="customer_contact" data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($customers_contact_list as $customer_contact_list)
                                    <option value="{{  $customer_contact_list->customer_contact_id }}" {{ $customer_contact_list->customer_contact_id==$tickets_list->customer_contact_id ? 'selected' : '' }}>{{ $customer_contact_list->first_name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label class="label-control">Subject :</label>
                                 <input type="text" id="" class="form-control border-primary" placeholder="Subject" name="subject" value="{{ $tickets_list->subject }}">
                              </div>
                           </div>
                           
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label class="label-control">Description :</label>
                                 <textarea rows="5" id="" class="form-control border-primary" placeholder="Description" name="description">{{ $tickets_list->description }}</textarea>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="label-control">Ticket Types :</label>
                                 <select class="form-control border-primary select2 form-select" name="ticket_type_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($ticket_type_lists as $ticket_type_list)
                                    <option value="{{ $ticket_type_list->ticket_type_id }}" {{ $ticket_type_list->ticket_type_id==$tickets_list->ticket_type_id ? 'selected' : '' }}>{{ $ticket_type_list->ticket_type_name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="label-control">Ticket Priority :</label>
                                 <select class="form-control border-primary select2 form-select" name="ticket_priority_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($ticket_priority_lists as $ticket_priority_list)
                                    <option value="{{  $ticket_priority_list->priority_id }}" {{ $ticket_priority_list->priority_id==$tickets_list->priority_id ? 'selected' : '' }}>{{ $ticket_priority_list->priority_name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="label-control">Ticket Source :</label>
                                 <select class="form-control border-primary select2 form-select" name="ticket_source_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($ticket_source_lists as $ticket_source_list)
                                    <option value="{{  $ticket_source_list->ticket_source_id }}" {{ $ticket_source_list->ticket_source_id==$tickets_list->source_id ? 'selected' : '' }}>{{ $ticket_source_list->ticket_source_name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="label-control">Ticket Assigned To :</label>
                                 <select class="form-control border-primary select2 form-select" name="ticket_assign_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($ticket_assign_lists as $ticket_assign_list)
                                    <option value="{{  $ticket_assign_list->id }}" {{ $ticket_assign_list->id==$tickets_list->assign_to ? 'selected' : '' }}>{{ $ticket_assign_list->first_name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><b>Attachments :</b>
                                    <center>
                                       <table id="EditImageTable" width="50%">
                                          <tbody id="ImageTBodyEdit">
                                             @foreach($ticket_attachments as $ticket_attachment)
                                                <tr class="add_row">
                                                  <td width="100%"><input name="existing_attachments[]" type="file" multiple style="width:100px;">{{ $ticket_attachment->attachment }}</td>
                                                  <td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td>
                                                  <td><a href="{{ asset('public/ticket_uploads/'.$ticket_attachment->attachment) }}" target="_blank"><button type="button" class="btn btn-primary btn-sm" id="view" title="View file"><i class="fa fa-eye"></i></button></a></td>
                                                </tr>
                                             @endforeach
                                             <tr>
                                                <td width="100%"><input name="attachment[]" type="file" multiple></td>
                                                <td width="20%"><button class="btn btn-success btn-sm" type="button" id="add" title="Add new file"><i class="fa fa-plus"></i></button></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </center>
                                 </fieldset>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="form-actions right">
                        <a href="{{ route('tickets') }}">
                           <button type="button" class="btn btn-danger mr-1">
                              <i class="fa fa-times"></i> Close
                           </button>
                        </a>
                        <button type="submit" class="btn btn-primary">
                           <i class="fa fa-check"></i> Save
                        </button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(document).on('change', '#customer', function(){
            var client_id=$(this).val();
            $.post("{{ route('client_change_ajax') }}",{"_token":"{{ csrf_token() }}",client_id:client_id},function(data){
               $("#customer_contact").html(data);
            });
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
   </x-slot>
</x-app-layout>