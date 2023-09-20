<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Campaigns
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                           <i class="fa fa-plus"></i> Campaign
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered CampaignsTable" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Campaign Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Campaign Name</th>
                                 <th>Campaign Budget</th>
                                 <th>Campaign Date</th>
                                 <th>Lead Expecting</th>
                                 <th>Description</th>
                                 <th>Created By</th>
                                 <th>Created At</th>
                                 <th>Updated By</th>
                                 <th>Updated At</th>
                              </tr>
                           </thead>
                           <tbody>

                           </tbody>
                           <tfoot>
                              <tr>
                                 <th>Campaign Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Campaign Name</th>
                                 <th>Campaign Budget</th>
                                 <th>Campaign Date</th>
                                 <th>Lead Expecting</th>
                                 <th>Description</th>
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
   <div class="modal fade" id="AddModal"  role="dialog" aria-labelledby="AddModals" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Campaign</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('campaign_submit') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Campaign Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <input type="text" id="" required name="campaign_name" class="name form-control" placeholder="campaign Name">
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Campaign Budget <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <input type="number" id="" required name="campaign_budget" class="name form-control" placeholder="Campaign Budget">
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Campaign Date :</b>
                                 <input type="date" value="<?php echo date('Y-m-d'); ?>" id="" name="campaign_date" class="name form-control">
                              </fieldset>
                           </div>
                        </div>
                       <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Expecting Leads :</b>
                                 <input type="number" id=""  name="lead_expecting" class="name form-control" placeholder="Expecting Leads">
                              </fieldset>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Description :</b>
                                 <textarea name="description" class="name form-control"></textarea>
                              </fieldset>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        @foreach($GetCustomFields as $GetCustomField)
                        <div class="col-lg-6">
                           <div class="form-group">
                                 <fieldset class="form-group floating-label-form-group"><label>{{ $GetCustomField->field_name }} @if($GetCustomField->required_field=="Yes") <sup style="color:red;font-size:12px;">*</sup>@endif :</label><br>

                                    @if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==10 OR $GetCustomField->field_type==6 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==5)
                                       @foreach($GetCustomFieldTypes as $GetCustomFieldType)
                                          @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                             <input type="{{ $GetCustomFieldType->field_type }}" class="name form-control" value="@if ($GetCustomField->field_type==5) echo date('Y-m-d');@elseif($GetCustomField->field_type==8) echo date('h:i');@endif" placeholder="{{ $GetCustomField->field_name }}" name="Custom-{{ $GetCustomField->custom_field_id }}">
                                          @endif
                                       @endforeach
                                    @elseif($GetCustomField->field_type==7)
                                    <br>
                                       @php
                                       $field_value=$GetCustomField->field_value;
                                       $RadioArr=json_decode($field_value,true);
                                       @endphp
                                          @foreach($RadioArr as $data)
                                          @foreach ($GetCustomFieldTypes as $GetCustomFieldType)
                                             @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                                &nbsp;&nbsp;<input checked type="{{ $GetCustomFieldType->field_type }}" placeholder="{{  $GetCustomField->field_name }}" name="Custom-{{ $GetCustomField->custom_field_id }}" value="<?= $data['FieldValue'] ?>" style="width:20px;height:20px;">&nbsp;&nbsp;<?= $data['FieldValue'] ?>&nbsp;&nbsp;
                                             @endif
                                          @endforeach
                                          @endforeach

                                    @elseif($GetCustomField->field_type==9)
                                    <br>
                                    @php
                                       $field_value=$GetCustomField->field_value;
                                       $CheckboxArr=json_decode($field_value, true);@endphp

                                       @foreach ($CheckboxArr as $Checkbox)
                                          @foreach ($GetCustomFieldTypes as $GetCustomFieldType)
                                             @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                                &nbsp;&nbsp;<input type="{{ $GetCustomFieldType->field_type }}" name="Custom-{{ $GetCustomField->custom_field_id }}[]" value="<?= $Checkbox['FieldValue'] ?>" style="width:20px;height:20px;">&nbsp;&nbsp;<?= $Checkbox['FieldValue'] ?>&nbsp;&nbsp;
                                             @endif
                                          @endforeach
                                       @endforeach
                                    @elseif($GetCustomField->field_type==3)
                                       <select class="select2 form-control" style="width: 100%;" name="Custom-{{ $GetCustomField->custom_field_id }}">
                                          <option value="">Select {{  $GetCustomField->field_name }}</option>
                                          @php
                                          $field_value=$GetCustomField->field_value;
                                          $OptionsArr=json_decode($field_value, true);
                                          @endphp
                                          <?php

                                    foreach ($OptionsArr as $Options)
                                    {
                                       echo "<option>".$Options['FieldValue']."</option>";
                                    }
                                    ?>

                                       </select>
                                    @elseif($GetCustomField->field_type==11)
                                       <textarea name="Custom-{{ $GetCustomField->custom_field_id }}" class="form-control" placeholder="{{  $GetCustomField->field_name }}"></textarea>
                                    @endif
                              </div>
                        </div>
                        @endforeach
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
   <div class="modal fade" id="edit_modal"  role="dialog" aria-labelledby="edit_modal_menus" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Campaign</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('campaign_submit') }}" enctype="multipart/form-data">
                  @csrf
                  <div id="campaigns_edit_modal_form"></div>
               </form>
            </section>
         </div>
      </div>
   </div>
   @php
   $DeleteTableName="campaigns";
   $DeleteColumnName="campaign_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function () {
            var table = $('.CampaignsTable').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('campaigns') }}",
               columns: [
               {data: 'campaign_id', name: 'campaigns.campaign_id'},
               {data: 'action', name: 'campaigns.action', orderable: false, searchable: false},
               {data: 'campaign_name', name: 'campaigns.campaign_name'},
               {data: 'campaign_budget', name: 'campaigns.campaign_budget'},
               {data: 'campaign_date', name: 'campaigns.campaign_date'},
               {data: 'lead_expecting', name: 'campaigns.lead_expecting'},
               {data: 'description', name: 'campaigns.description'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'created_at', name: 'campaigns.created_at'},
               {data: 'updated_by', name: 'c.first_name'},
               {data: 'updated_at', name: 'campaigns.updated_at'},
               ]
            });
         });

         $(document).on('click', '.edit_model_btn', function(){
            var campaign_id=$(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'campaign_edit/'+campaign_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#campaigns_edit_modal_form").html(data);
                  $("#edit_modal").modal('show');
               }
            });
         });

         $(document).on('click', '.DeleteDataModal', function(){
            var DeleteColumnValue=$(this).closest("tr").find("td:eq(0)").text();
            $("#DeleteColumnValue").val(DeleteColumnValue);
            $("#DeleteDataModal").modal('show');
         });
      </script>
   </x-slot>
</x-app-layout>
