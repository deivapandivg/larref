<x-app-layout>

   <section id="horizontal-form-layouts">
      <div class="row">
         <div class="col-md-12">
            <div class="card PageBrandingBacground">
               <div class="card-content collpase show">
                  <div class="card-body">
                     <div class="card-text">
                        <div class="row">
                           <div class="col-lg-6" >
                              <h3 class="card-title">Leads Upload<br> 
                                 <span class="btn btn-sm text-primary ml-0" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back
                                 </span>
                              </h3>                                            
                           </div>
                           <div class="col-lg-6" >
                              <a href="{{ asset('public/app-assets/Download/leadupload.csv') }}" download class="float-right">
                                 <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-download"></i> Download Sample File
                                 </button>
                              </a>
                           </div>
                        </div>
                        <div class="card-block hidden" id="loadCont">
                           <center><img src="{{ asset('public/app-assets/images/svg/gif1.gif') }}" style="width: 260px;"></center>
                           <center><h3>Uploading ...</h3></center>
                        </div>
                        <div class="card-body" id="transCont">
                           <form method="post" class="form form-horizontal mt-0" id="forms" action="{{ route('import_submit') }}" enctype="multipart/form-data">
                              @csrf
                              <h6 class="form-section mt-0"><i class="fa fa-file"></i> File Details</h6>
                              <div class="">
                                 <div class="row">
                                    <div class="col-md-4">
                                       <div class="form-group row">
                                          <label class="ml-1 label-control" for="userinput1"><b>Select CSV File <span class="text-danger">*</span></b>:</label> 
                                          <div class="col-md-12">
                                             <div class="form-group">
                                                <input type="file" id="clientCodeFile" name="image" class="image form-control" accept=".csv" required>
                                             </div>
                                          </div>
                                       </div>
                                    </div> 
                                    <div class="col-md-6">
                                       <div class="form-group row">
                                          <label class="ml-1 label-control" for="userinput1"><b>Lead Owner <span class="text-danger">*</span> :</b> </label>
                                          <div class="col-md-12">
                                             <div class="form-group">
                                                <select class="select2 form-control UserIdArry" multiple="multiple" id="UserIdArry" style="width: 100%;" name="UserIdArry[]" required="required">
                                                   <option value="0">AnyOne</option>
                                                   @foreach($get_users as $get_user)
                                                   <option value="{{ $get_user->id}}">{{ $get_user->first_name}}</option>
                                                   @endforeach
                                                </select>
                                             </div>
                                          </div>
                                       </div>
                                    </div>    
                                    <div class="col-md-2">
                                       <div class="form-group row">
                                          <label class="ml-1 label-control" for="userinput1"><b>Create Todo <span class="text-danger">*</span> :</b> </label>
                                          <div class="col-md-12">
                                             <div class="form-group">
                                                <input type="radio" name="create_todo" value="No" checked> No
                                                &nbsp;<input type="radio" name="create_todo" value="Yes"> Yes
                                             </div>
                                          </div>
                                       </div>
                                    </div>                                                
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <div class="form-group row">
                                          <div class="col-md-12">
                                             <label class="label-control" for="userinput4"><b>Medium <sup class="text-danger" style="font-size: 13px;">*</sup> :</b></label>
                                             <select class="form-control border-primary select2" required name="MediumId" id="MediumId" style="width: 100%" >
                                                <option selected="selected" value="">Select</option>
                                                @foreach($get_mediums as $get_medium)
                                                <option value="{{ $get_medium->medium_id}}">{{ $get_medium->medium_name}}</option>
                                                @endforeach
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group row">
                                          <div class="col-md-12">
                                             <label class="label-control" for="userinput4"><b>Lead Source <sup class="text-danger" style="font-size: 13px;">*</sup> :</b></label>
                                             <select class="form-control border-primary select2" required name="SourceId" id="source_id" style="width: 100%" >
                                                <option selected="selected" value="">Select</option>
                                                @foreach($get_sources as $get_source)
                                                <option value="{{ $get_source->lead_source_id}}">{{ $get_source->lead_source_name}}</option>
                                                @endforeach
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group row">
                                          <div class="col-md-12">
                                             <label class="label-control" for="userinput4"><b>Lead Sub Source <sup class="text-danger" style="font-size: 13px;">*</sup> :</b></label>
                                             <select class="form-control border-primary select2" required id="sub_source_id" name="SubSourceid" style="width: 100%">
                                                <option selected="selected" value="">Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group row">
                                          <div class="col-md-12">
                                             <label class="label-control" for="userinput4"><b>Campaigns <sup class="text-danger" style="font-size: 13px;">*</sup> :</b></label>
                                             <select class="form-control border-primary select2" required name="CampaignId" id="CampaignId" style="width: 100%"   >
                                                <option selected="selected" value="">Select</option>
                                                @foreach($get_campaigns as $get_campaign)
                                                <option value="{{ $get_campaign->campaign_id}}">{{ $get_campaign->campaign_name}}</option>
                                                @endforeach
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group row">
                                          <div class="col-md-12">
                                             <label class="label-control" for="userinput4"><b>Lead Stage <sup class="text-danger" style="font-size: 13px;">*</sup> :</b></label>
                                             <select class="form-control border-primary select2" required name="LeadStage" id="lead_stages_id" style="width: 100%"   >
                                                <option selected="selected" value="">Select</option>
                                                @foreach($get_lead_stages as $get_lead_stage)
                                                <option value="{{ $get_lead_stage->lead_stage_id}}">{{ $get_lead_stage->lead_stage_name}}</option>
                                                @endforeach
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group row">
                                          <div class="col-md-12">
                                             <label class="label-control" for="userinput4"><b>Lead Sub Stage <sup class="text-danger" style="font-size: 13px;">*</sup> :</b></label>
                                             <select class="form-control border-primary select2" required name="LeadSubStage" id="lead_sub_stages_id" style="width: 100%"   >
                                                <option selected="selected" value="">Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <h6 class="form-section"><i class="fa fa-users  fa-3x"></i> Users Details</h6>
                              <div class="row">
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b> First Name :</b></label>
                                          <div class="form-group">
                                             <select class="select2 form-control FirstName " id="FirstName" style="width: 100%;" name="FirstName" required>
                                                <option selected="selected" value="">Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b> Last Name :</b></label>
                                          <div class="form-group">
                                             <select class="select2 form-control LastName " id="LastName" style="width: 100%;" name="LastName" required>
                                                <option selected="selected" value="">Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b> Mobile Number :</b></label>
                                          <div class="form-group">
                                             <select class="select2 form-control MobileNumber" id="MobileNumber" style="width: 100%;" name="MobileNumber" required>
                                                <option selected="selected" value="">Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b>Phone Number :</b></label>
                                          <div class="form-group">
                                             <select class="select2 form-control AlterMobileNumber" id="AlterMobileNumber" style="width: 100%;" name="AlterMobileNumber" required>
                                                <option selected="selected" value="">Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b> Email :</b></label>
                                          <div class="form-group">
                                             <select class="select2 form-control EmailId" id="EmailId" style="width: 100%;" name="EmailId" required>
                                                <option selected="selected" value="">Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b> Alter Email :</b></label>
                                          <div class="form-group">
                                             <select class="select2 form-control AlterEmailId" id="AlterEmailId" style="width: 100%;" name="AlterEmailId" required>
                                                <option selected="selected" value="">Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b> Age :</b></label>
                                          <div class="form-group">
                                             <select class="select2 form-control Age" id="Age" style="width: 100%;" name="Age" required>
                                                <option selected="selected" value="">Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b> Product :</b></label>
                                          <div class="form-group">
                                             <select class="select2 form-control Product" id="Product" style="width: 100%;" name="Product" required>
                                                <option selected="selected" value="">Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b> Language :</b></label>
                                          <div class="form-group">
                                             <select class="select2 form-control Language" id="Language" style="width: 100%;" name="Language" required>
                                                <option selected="selected" value="">Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                              </div>
                              <h6 class="form-section"><i class="fa fa-address-card"></i> Address Details</h6>
                              <div class="row">
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b> Country :</b> </label>
                                          <div class="form-group">
                                             <select class="select2 form-control CountryName" id="CountryName" style="width: 100%;" name="CountryName" required>
                                                <option value="" selected>Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b> State :</b> </label>
                                          <div class="form-group">
                                             <select class="select2 form-control StateName" id="StateName" style="width: 100%;" name="StateName" required>
                                                <option value="" selected>Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b> City :</b></label>
                                          <div class="form-group">
                                             <select class="select2 form-control CityName" id="CityName" style="width: 100%;" name="CityName" required>
                                                <option value="" selected>Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b> Address :</b> </label>
                                          <div class="form-group">
                                             <select class="select2 form-control Address" id="Address" style="width: 100%;" name="Address" required>
                                                <option value="" selected>Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group row">
                                       <div class="col-md-12">
                                          <label class="label-control" for="userinput1"><b> Description :</b> </label>
                                          <div class="form-group">
                                             <select class="select2 form-control Description" id="Description" style="width: 100%;" name="Description" required>
                                                <option value="" selected>Select</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="form-actions right">
                                 <a href="{{ route('leads') }}">
                                    <button type="button" class="btn btn-danger mr-1">
                                       <i class="fa fa-times"></i> Close
                                    </button>
                                 </a>
                                 <button type="submit" id="submit" name="submit"  class="btn btn-primary">
                                    <i class="fa fa-check-square"></i> Save
                                 </button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(document).on('change', '#source_id', function(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var source_id=$(this).val();
            $.post("source_ajax",{_token:CSRF_TOKEN,source_id:source_id},function(data){
               $("#sub_source_id").html(data);
            });
         });

         $(document).on('change', '#lead_stages_id', function(){
            var lead_stage_id=$(this).val();
            $.post("{{ route('lead_substage_ajax') }}",{"_token":"{{ csrf_token() }}",lead_stage_id:lead_stage_id},function(data){
               $("#lead_sub_stages_id").html(data);
            });
         });

         $(document).on("click","#submit",function(e){  
            e.preventDefault();
            var UserIdArry=$("#UserIdArry").val();
            var FirstName=$("#FirstName").val();
            var LastName=$("#LastName").val();
            var AlterMobileNumber=$("#AlterMobileNumber").val();
            var MobileNumber=$("#MobileNumber").val();
            var EmailId=$("#EmailId").val();
            var AlterEmailId=$("#AlterEmailId").val();
            var MediumId=$("#MediumId").val();
            var SourceId=$("#source_id").val();
            var SubSourceid=$("#sub_source_id").val();
            var CampaignId=$("#CampaignId").val();
            var LeadStage=$("#LeadStage").val();
            var LeadSubStage=$("#LeadSubStage").val();
            var Age=$("#Age").val();
            var Product=$("#Product").val();
            var CountryName=$("#CountryName").val();
            var StateName=$("#StateName").val();
            var CityName=$("#CityName").val();
            var Address=$("#Address").val();
            var Description=$("#Description").val();
            var Language=$("#Language").val();
            if(UserIdArry!='' && FirstName!='' && LastName!='' && MobileNumber!='' && AlterMobileNumber!='' && EmailId!='' && AlterEmailId!='' && MediumId!='' && SourceId!='' && CampaignId!='' && SubSourceid!='' &&  Age!='' && Product!='' && CountryName!='' && StateName!='' && CityName!='' && CityName!='' && Address!='' && Description!='' && LeadStage!='' && LeadSubStage!='' && Language!='')
            {
               var formData =$("#forms").submit(function(e){
                  return ;
               });
               $("#transCont").addClass("hidden");
               $("#loadCont").removeClass("hidden");
               var formData = new FormData(formData[0]); 
               $.ajax({
                  url: $('#forms').attr('action'),
                  type: 'POST',
                  data: formData,
                  success: function(data) {
                     $("#loadCont").html(data);
                     Swal.fire("Leads Been Uploaded");
                     setTimeout(function(){
                        window.location.assign("{{ route('leads') }}");
                     }, 1000); 
                  },
                  contentType: false,
                  processData: false,
                  cache: false
               });
            }
            else
            {
               Swal.fire("Kindly Fill All Required Fields");
            }
            return false;
         });

         $(function() {
            $('.image').on('change', function() {
               var file_data = $('.image').prop('files')[0];
               if(file_data != undefined) {
                  var form_data = new FormData();    
                  form_data.append('_token',"{{ csrf_token() }}");              
                  form_data.append('file', file_data);
                  $.ajax({
                     type: 'POST',
                     url: 'leads_import_file_fetch/get_fields',
                     contentType: false,
                     processData: false,
                     data: form_data,
                     success:function(response) {
                        var column_names_arr=response;
                        var FirstName =getCSVFields(column_names_arr, 'FirstName', 'FirstName');
                        var LastName =getCSVFields(column_names_arr, 'LastName', 'LastName');
                        var MobileNumber=getCSVFields(column_names_arr, 'MobileNumber', 'MobileNumber');
                        var AlterMobileNumber=getCSVFields(column_names_arr, 'AlterMobileNumber', 'AlterMobileNumber');
                        var EmailId=getCSVFields(column_names_arr, 'EmailId', 'EmailId');
                        var AlterEmailId=getCSVFields(column_names_arr, 'AlterEmailId', 'AlterEmailId');
                        var LastCommand=getCSVFields(column_names_arr, 'Age', 'Age');
                        var Product=getCSVFields(column_names_arr, 'Product', 'Product');
                        var Language=getCSVFields(column_names_arr, 'Language', 'Language');
                        var CountryName=getCSVFields(column_names_arr, 'CountryName', 'CountryName');
                        var StateName=getCSVFields(column_names_arr, 'StateName', 'StateName');
                        var CityName=getCSVFields(column_names_arr, 'CityName', 'CityName');
                        var Address=getCSVFields(column_names_arr, 'Address', 'Address');
                        var Description=getCSVFields(column_names_arr, 'Description', 'Description');
                     }
                  });                        

                  function getCSVFields(column_names_arr, className, selectData)
                  {
                     var select_options='';
                     var selected='';
                     for (let i = 0; i < column_names_arr.length; ++i) {
                        if(column_names_arr[i]==selectData)
                           selected='selected';
                        else
                           selected='';
                        select_options+='<option value="'+i+'" '+selected+'>'+column_names_arr[i]+'</option>';
                     }
                     $("."+className).html(select_options);
                  }
               }
               return false;
            });
         });
      </script>
   </x-slot>
</x-app-layout>