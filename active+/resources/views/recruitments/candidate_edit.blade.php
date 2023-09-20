<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Candidate Edit
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>   
               <div class="card-body">
                  <form class="form" method="POST" enctype="multipart/form-data" action="{{ route('recruitment_submit') }}">
                     @csrf
                     <div class="form-body">
                       <div class="row">
                        <input type="hidden" name="candidate_id" value="{{ $candidate_details->candidate_id }}">
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>First Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="text" id="" required name="first_name" class="name form-control" placeholder="First Name" value="{{ $candidate_details->first_name }}">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Last Name :</b>
                                       <input type="text" id=""  name="last_name" class="name form-control" placeholder="Last Name"  value="{{ $candidate_details->last_name }}">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Mail Id <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="email" id=""  name="mail_id" class="name form-control" placeholder="Mail Id" value="{{ $candidate_details->mail_id }}" required>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Mobile Number <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="text" id=""  name="mobile_number" class="name form-control" placeholder="Mobile Number" value="{{ $candidate_details->mobile_number }}" required>
                                    </fieldset>
                                 </div>
                              </div>
                               <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Date Of Birth <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="date" id=""  name="dob" class="name form-control" placeholder=""value="{{ $candidate_details->dob }}" required>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Address :</b>
                                       <textarea type="text" id=""  name="address" class="name form-control" placeholder="Building No, Street, Landmark ">{{ $candidate_details->address }}</textarea>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Country <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" id="country_id" name="country_id" data-placeholder="Choose one" style="width:100%;">
                                          @foreach ($country_lists as $country_list)
                                             @if($candidate_details->country_id!=$country_list->country_id)
                                                <option value="{{  $country_list->country_id }}">{{ $country_list->country_name }}</option>
                                             @else
                                                <option value="{{ $candidate_details->country_id }}" selected>{{ $country_list->country_name }}</option> 
                                             @endif
                                          @endforeach
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>State <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" id="state_id" name="state_id" data-placeholder="Choose one" style="width:100%;">
                                       <option selected="selected" disabled="disabled">Select State</option>
                                       @foreach ($state_lists as $state_list)
                                          @if($candidate_details->state_id!=$state_list->state_id)
                                             <option value="{{  $state_list->state_id }}">{{ $state_list->state_name }}</option>
                                          @else
                                             <option value="{{ $candidate_details->state_id}}" selected>{{ $state_list->state_name }}</option> 
                                          @endif
                                       @endforeach
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>City <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" id="city_id" name="city_id" data-placeholder="Choose one" style="width:100%;">
                                       <option selected="selected" disabled="disabled">Select City</option>
                                       @foreach ($city_lists as $city_list)
                                          @if($candidate_details->city_id!=$city_list->city_id)
                                             <option value="{{  $city_list->city_id }}">{{ $city_list->city_name }}</option>
                                          @else
                                             <option value="{{ $candidate_details->city_id }}" selected>{{ $city_list->city_name }}</option>
                                          @endif
                                       @endforeach
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Pincode <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="text" class="form-control" id="pincode" name="pincode" placeholder="622201" value="{{ $candidate_details->pincode }}">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Job Role :</b>
                                       <input type="text" id=""  name="job_role" class="name form-control" placeholder="Job Role" value="{{ $candidate_details->job_role }}">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Educational Qualification :</b>
                                       <input type="text" id=""  name="educational_qualification" class="name form-control" placeholder="Bsc Computer Science" value="{{ $candidate_details->educational_qualification }}">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Year Of Passing :</b>
                                       <input type="number" id=""  name="year_of_passing" class="name form-control" placeholder="2015" value="{{ $candidate_details->year_of_passing }}">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Year Of Experience :</b>
                                       <input type="number" id=""  name="year_of_exprience" class="name form-control" placeholder="3" value="{{ $candidate_details->year_of_exprience }}">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Description :</b>
                                       <textarea id=""  name="description" class="name form-control">{{ $candidate_details->description }}
                                       </textarea>  
                                    </fieldset>
                                 </div>
                              </div>
                               <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>More Details :</b>
                                       <center>
                                          <table class="table table-bordered responsive" id="More_details">
                                             <thead>
                                                <tr>
                                                   <th width="50%">Heading</th>
                                                   <th width="40%">Detail</th>
                                                   <th width="10%"></th>
                                                </tr>
                                             </thead>
                                             <tbody id="More_details_edit">
                                                @foreach ($add_more_details as $add_more_detail)
                                                <tr >
                                                   <td><input class="form-control" class="" type="text"   name="exisiting_heading[]" value="{{ $add_more_detail->heading }}" /></td>
                                                   <td><input class="form-control" class="" type="text"   name="exisiting_detail[]"  placeholder="" value="{{ $add_more_detail->detail }}"/></td>
                                                   <td>
                                                      <input type="hidden" name="exisiting_candidate_additional_detail_id[]" value="{{ $add_more_detail->candidate_additional_detail_id }}">
                                                      <button class="btn btn-danger btn-sm deletes" type="button" title='Delete' id="add_more_delete">
                                                           <i class="fa fa-trash"></i>
                                                      </button>
                                                   </td>
                                                </tr>
                                                @endforeach
                                                <tr class="Edit_row">
                                                   <td><input class="form-control" class="filename" type="text"  placeholder="Father Name" name="heading[]"/></td>
                                                   <td><input class="form-control" class="" type="text"   name="detail[]"  placeholder="John" /></td>
                                                   <td><button class="btn btn-success btn-sm" type="button" id="add_more_details" title='Add new'><i class="fa fa-plus"></i></button></td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </center>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Experience Details :</b>
                                       <center>
                                          <table class="table table-bordered responsive" id="Expreience">
                                             <thead>
                                                <tr>
                                                   <th width="40%">Company Name</th>
                                                   <th width="20%">FromYear</th>
                                                   <th width="20%">ToYear</th>
                                                   <th width="20%">Year Of Experience</th>
                                                </tr>
                                             </thead>
                                             <tbody id="ImageTBodyAdds">
                                                @foreach ($candidate_experience_details as $candidate_experience_detail)
                                                <tr >
                                                   <td><input class="form-control" class="filename" type="text"   name="exisiting_company_name[]" value="{{ $candidate_experience_detail->company_name }}" /></td>
                                                   <td><input class="form-control" class="" type="text"   name="exisiting_from_year[]"  placeholder="2012-2015" value="{{ $candidate_experience_detail->from_year }}"/></td>
                                                   <td><input class="form-control" class="" type="text"   name="exisiting_to_year[]"  placeholder="2012-2015" value="{{ $candidate_experience_detail->to_year }}"/></td>
                                                   <td><input  id="" name='exisiting_experience_years[]' type='number' class="form-control" placeholder="3" value="{{ $candidate_experience_detail->year_of_exp }}"></td>
                                                   <td>
                                                      <input type="hidden" name="exisiting_exp_id[]" value="{{ $candidate_experience_detail->experience_detail_id }}">
                                                      <button class="btn btn-danger btn-sm deletes" type="button" title='Delete' id="deletes">
                                                           <i class="fa fa-trash"></i>
                                                      </button>
                                                   </td>
                                                </tr>
                                                @endforeach
                                                <tr class="Edit_row">
                                                   <td><input class="form-control" class="filename" type="text"   name="company_name[]"/></td>
                                                   <td><input class="form-control" class="" type="text"   name="from_year[]"  placeholder="2012-2015" /></td>
                                                   <td><input class="form-control" class="" type="text"   name="to_year[]"  placeholder="2012-2015" /></td>
                                                   <td><input  id="" name='experience_years[]' type='number' class="form-control" placeholder="3" multiple ></td>
                                                   <td><button class="btn btn-success btn-sm" type="button" id="add_experience" title='Add new file'><i class="fa fa-plus"></i></button></td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </center>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Attachment :</b>
                                       <center>
                                          <table class="table table-bordered responsive" id="EditImageTable">
                                             <thead>
                                                <tr>
                                                   <th width="50%">Attachment Name</th>
                                                   <th width="40%">Upload File</th>
                                                   <th width="10%"></th>
                                                </tr>
                                             </thead>
                                             <tbody id="ImageTBodyEdit">
                                                @foreach ($attachments as $attachment)
                                                <tr class="">
                                                   <td>
                                                      <input class="form-control" class="filename" type="text"   name="exisiting_attachment_name[]" value="{{$attachment->attachment_name }}" />
                                                   </td>
                                                   <td>
                                                      <a href="{{ asset('public/recruitmentattachment/'.$attachment->attachment) }}" target="_blank">
                                                        <img src="{{ asset('public/recruitmentattachment/'.$attachment->attachment) }}" style="width: 50px;">
                                                      </a>
                                                   </td>
                                                   <td>
                                                      <input type="hidden" name="exisiting_images_id[]" value="{{ $attachment->candidate_attachment_id }}">
                                                      <button class="btn btn-danger btn-sm delete" type="button" title='Delete'>
                                                           <i class="fa fa-trash"></i>
                                                      </button>
                                                   </td>
                                                </tr>
                                                @endforeach
                                                <tr class="Edit_row">
                                                   <td><input class="form-control" class="filename" type="text"   name="attachment_name[]" value="" /></td>
                                                   <td><input id="upload" name='attachment[]' type='file' multiple ></td>
                                                   <td><button class="btn btn-success btn-sm" type="button" id="EditAdd" title='Add new file'><i class="fa fa-plus"></i></button></td>
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
                        <a href="{{ route('recruitments') }}">
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
             //Append new row In Add Form
            //  $('#AddImageTable').on('click', "#add", function(e) {
            //     $('#ImageTBodyAdd').append('<tr class="add_row"><td><input class="form-control filename" name="attachment_name[]" type="text"  multiple /></td><td><input id="upload"  name="attachment[]" type="file"  multiple /></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
            //     e.preventDefault();
            // });

            $('#EditImageTable').on('click', "#EditAdd", function(e) {
                $('#ImageTBodyEdit').append('<tr class="add_row"><td><input class="form-control filename" name="attachment_name[]" type="text"  multiple /></td><td><input id="upload"  name="attachment[]" type="file"  multiple /></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm delete" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
                e.preventDefault();
            });

            $(document).on('click', '.delete', function(e) {
                if (!confirm("Are you sure you want to remove this file?"))
                    return false;
                $(this).closest('tr').remove();
                e.preventDefault();
            });


            $('#Expreience').on('click', "#add_experience", function(e) {
                $('#ImageTBodyAdds').append('<tr class="add_row"><td><input class="form-control filename" name="company_name[]" type="text" multiple /></td><td><input  class="form-control id=""  name="from_year[]" type="text"  placeholder="2012-2015" multiple/></td> <td><input  class="form-control id=""  name="to_year[]" type="text"  placeholder="2012-2015" multiple/></td>    <td><input  class="form-control id=""  name="experience_years[]" type="text"  placeholder="3" multiple/></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm deletes" id="deletes" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
                e.preventDefault();
            });
            // Delete row In Add Form
            $(document).on('click', '.deletes', function(e) {
                if (!confirm("Are you sure you want to remove this file?"))
                    return false;
                $(this).closest('tr').remove();
                e.preventDefault();
            });

               $('#More_details').on('click', "#add_more_details", function(e) {
                $('#More_details_edit').append('<tr class="add_row"><td><input class="form-control" name="heading[]" placeholder="Father Name" type="text" multiple /></td><td><input  class="form-control id=""  name="detail[]" type="text"  placeholder="John" multiple/></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm add_more_delete" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
                e.preventDefault();
            });
            // Delete row In Add Form
            $(document).on('click', '.add_more_delete', function(e) {
                if (!confirm("Are you sure you want to remove this file?"))
                    return false;
                $(this).closest('tr').remove();
                e.preventDefault();
            });
            



         $('#country_id').on('change',function(){
            var country_id=$(this).val();
            $.ajax({
               url: 'country_id/'+country_id,
               type: "GET",
               data : {country_id:country_id},
               success:function(data) {
                  $("#state_id").html(data);
               }
            });
         });

         $('#state_id').on('change',function(){
            var state_id=$(this).val();
            $.ajax({
               url: 'state_id/'+state_id,
               type: "GET",
               data : {state_id:state_id},
               success:function(data) {
                  $("#city_id").html(data);
               }
            });
         });
   </script>
   </x-slot>
</x-app-layout>