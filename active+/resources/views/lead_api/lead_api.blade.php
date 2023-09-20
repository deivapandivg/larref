<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Leads API
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                           <i class="fa fa-plus"></i> Leads API
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered lead_api" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Lead Api Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Api Name</th>
                                 <th>Api Key</th>
                                 <th>Active</th>
                                 <th>Medium</th>
                                 <th>Source</th>
                                 <th>SubSource</th>
                                 <th>Campaign</th>
                                 <th>AD Name</th>
                                 <th>Api Notes</th>
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
                                 <th>Lead Api Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Api Name</th>
                                 <th>Api Key</th>
                                 <th>Active</th>
                                 <th>Medium</th>
                                 <th>Source</th>
                                 <th>SubSource</th>
                                 <th>Campaign</th>
                                 <th>AD Name</th>
                                 <th>Api Notes</th>
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
      <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Lead API</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('lead_api_submit') }}">
                  @csrf
                  <div class="modal-body">
                     <div class="row">
                         <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Api Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                <input class="form-control" id="api_name" type="text"required name="api_name" >
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Mediums <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <select class="form-control border-primary select2 form-select" name="medium_id"  id="medium_id"data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($get_mediums as $get_medium)
                                    <option value="{{  $get_medium->medium_id }}">{{ $get_medium->medium_name }}</option>
                                    @endforeach
                                 </select>
                              </fieldset>
                           </div>
                        </div>
                         <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Lead Sources <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                  <select class="form-control border-primary select2 form-select" name="source_id"  id="source_id"data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($get_sources as $get_source)
                                    <option value="{{  $get_source->lead_source_id }}">{{ $get_source->lead_source_name }}</option>
                                    @endforeach
                                 </select>
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Lead Sub Sources <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                              <select class="form-control border-primary select2"  name="sub_source_id" id="sub_source_id" style="width: 100%">
                                 <option selected="selected" value="">Select Lead Sub Source</option>
                              </select>
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>AD Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <select class="form-control border-primary select2 form-select" name="ad_name_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($get_ad_names as $get_ad_name)
                                    <option value="{{  $get_ad_name->ad_name_id }}">{{ $get_ad_name->ad_name }}</option>
                                    @endforeach
                                 </select>
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Campaigns <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <select class="form-control border-primary select2 form-select" name="campaign_id" data-placeholder="Choose one" style="width:100%;">
                                    <option selected>Select</option>
                                    @foreach ($get_campaigns as $get_campaign)
                                    <option value="{{  $get_campaign->campaign_id }}">{{ $get_campaign->campaign_name }}</option>
                                    @endforeach
                                 </select>
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Active Status</b>
                                 <div class="d-inline-block custom-control custom-radio">
                                    <input type="radio" checked name="ActiveType" id="Active" value="Yes">
                                    <label for="Active"> Active</label>
                                 </div>
                                 <div class="d-inline-block custom-control custom-radio">
                                    <input type="radio" name="ActiveType" id="InActive" value="No">
                                    <label  for="InActive">InActive</label>
                                 </div>
                              </fieldset>
                           </div>
                        </div>
                         <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Description </b>
                               <textarea class="form-control border-primary" placeholder="API Notes" name="ApiNotes"></textarea>
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
   <div class="modal fade" id="edit_modal_lead_sub_sources"  role="dialog" aria-labelledby="edit_modal_lead_sub_sources" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
         <div class="modal-content">
            <section class="contact-form">
               <div class="modal-header bg-primary white">
                  <h5 class="modal-title white">Lead Api</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{ route('lead_api_submit') }}">
                  @csrf
               <div id="menu_group_edit_modal_form"></div>
               </form>
            </section>
         </div>
      </div>
   </div>
   @php
   $DeleteTableName="lead_sub_sources";
   $DeleteColumnName="lead_sub_source_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
       
         $(function () {
            var table = $('.lead_api').DataTable({
               processing: true,
               serverSide: true,
               order: [[ 0, "desc" ]],
               ajax: "{{ route('lead_api_list') }}",
               columns: [
               {data: 'lead_api_id', name: 'a.lead_api_id'},
               {data: 'action', name: 'a.action', orderable: false, searchable: false},
               {data: 'api_name', name: 'a.api_name'},
               {data: 'api_key', name: 'a.api_key'},
               {data: 'active', name: 'a.active'},
               {data: 'medium_name', name: 'd.medium_name'},
               {data: 'lead_source_name', name: 'e.lead_source_name'},
               {data: 'lead_sub_source_name', name: 'f.lead_sub_source_name'},
               {data: 'campaign_name', name: 'g.campaign_name'},
               {data: 'ad_name', name: 'h.ad_name'},
               {data: 'api_notes', name: 'a.api_notes'},
               {data: 'created_by', name: 'b.first_name'},
               {data: 'created_at', name: 'a.created_at'},
               {data: 'updated_by', name: 'c.first_name'},
               {data: 'updated_at', name: 'a.updated_at'},
               ]
            });
         });

         $(document).on('click', '.CopyToClipBorad', function(){
            var CopyVal=$(this).attr("value");
            var el = document.createElement('textarea');
            el.value = CopyVal;
            el.setAttribute('readonly', '');
            el.style.position = 'absolute';
            document.body.appendChild(el);
            el.select();
            document.execCommand("copy");
            document.body.removeChild(el);
            toastr.success('APIkey Copied to Clipboard !', '');
            });

           $(document).on('change', '#source_id', function(){
            var source_id=$(this).val();
            $.post("{{route ('source_ajax')}}",{_token:"{{ csrf_token() }}",source_id:source_id},function(data){
               $("#sub_source_id").html(data);
            });
         });
         $(document).on('click', '.edit_model_btn', function(){
            var lead_api_id=$(this).closest("tr").find("td:eq(0)").text();
            alert(lead_api_id);
            $.ajax({
               url: 'lead_api_edit/'+lead_api_id,
               type: "GET",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#menu_group_edit_modal_form").html(data);
                  $("#edit_modal_lead_sub_sources").modal('show');
               }
            });
         });

         // $(document).on('click', '.DeleteDataModal', function(){
         //    var DeleteColumnValue=$(this).closest("tr").find("td:eq(0)").text();
         //    $("#DeleteColumnValue").val(DeleteColumnValue);
         //    $("#DeleteDataModal").modal('show');
         // });
      </script>
   </x-slot>
      </x-app-layout>