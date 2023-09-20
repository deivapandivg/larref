<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Candidate View
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a>
                        <button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                            Click Schedule
                        </button>
                        <!-- <button class="btn btn-primary btn-sm pb-0" data-toggle='tooltip' data-placement='top' title='Add' data-original-title='Add'>
                           <h6 class="text-white">
                              click Schedule
                               <i class="fas fa-user-plus"></i> 
                           </h6>
                        </button> -->
                     </a>
                  </div>
               </div>   
               <div class="card-body">
                  <form class="form" method="POST" enctype="multipart/form-data" action="">
                     @csrf
                     <div class="form-body">
                       <div class="row">
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>First Name :</b>
                                       <p>{{ $candidate_details->first_name }}</p>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Last Name :</b>
                                       <p>{{ $candidate_details->last_name }}</p>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Mail Id :</b>
                                       <p>{{ $candidate_details->mail_id }}</p>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Mobile Number  :</b>
                                       <p>{{ $candidate_details->mobile_number }}</p>
                                    </fieldset>
                                 </div>
                              </div>
                               <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Date Of Birth :</b>
                                       <p>{{ $candidate_details->dob }}</p>

                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Gender :</b>
                                       <p>
                                          @if ($candidate_details->gender==1)
                                              Male
                                          @elseif($candidate_details->gender==2)
                                              Female
                                          @else
                                              Others
                                          @endif
                                       </p>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Address :</b>
                                       <p>{{ $candidate_details->address }}</p>
                                       
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Country  :</b>
                                       <p>{{ $country->country_name }}</p>
        
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>State :</b>
                                       <p>{{ $state->state_name }}</p>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>City :</b>
                                       <p>{{ $city->city_name }}</p>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Pincode :</b>
                                       <p>{{ $candidate_details->pincode }}</p>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Job Role :</b>
                                       <p>{{ $candidate_details->job_role }}</p>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Educational Qualification :</b>
                                       <p>{{ $candidate_details->educational_qualification }}</p>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Year Of Passing :</b>
                                       <p>{{ $candidate_details->year_of_passing }}</p>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Year Of Experience :</b>
                                       <p>{{ $candidate_details->year_of_exprience }}</p>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Description :</b>
                                      <p>{{ $candidate_details->description }}</p>
                                    </fieldset>
                                 </div>
                              </div>
                              @foreach ($more_details_list as $more_details_lists)
                               <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>{{ $more_details_lists->heading }} :</b>
                                      <p>{{ $more_details_lists->detail }}</p>
                                    </fieldset>
                                 </div>
                              </div>
                              @endforeach
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
                                                @foreach($candidate_experience_details as $candidate_experience_detail)
                                                <tr class="add_row">
                                                   <td>{{ $candidate_experience_detail->company_name }}</td>
                                                   <td>{{ $candidate_experience_detail->from_year }}</td>
                                                   <td>{{ $candidate_experience_detail->to_year }}</td>
                                                   <td>{{ $candidate_experience_detail->year_of_exp }}</td>
                                                </tr>
                                                @endforeach
                                             </tbody>
                                          </table>
                                       </center>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Attach Documents :</b>
                                       <center>
                                          <table class="table table-bordered responsive" id="AddImageTable">
                                             <thead>
                                                <tr>
                                                   <th width="50%">Attach Document Name</th>
                                                   <th width="50%">Upload File</th>
                                                </tr>
                                             </thead>
                                             <tbody id="ImageTBodyAdd">
                                                @foreach($candidate_attachments as $candidate_attachment)
                                                <tr class="add_row">
                                                   <td>{{ $candidate_attachment->attachment_name }}</td>
                                                   <td>
                                                      <a href="{{ asset('public/recruitmentattachment/'.$candidate_attachment->attachment) }}" target="_blank">
                                                        <img src="{{ asset('public/recruitmentattachment/'.$candidate_attachment->attachment) }}" style="width: 50px;">
                                                   </td>
                                                </tr>
                                                @endforeach
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
                              <i class="fa fa-arrow-left"></i> Back
                           </button>
                        </a>
                     </div>
                  </form>
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
                  <h5 class="modal-title white">Schedule</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form method="post" action="{{route('recruitment_process_submit')}}">
                  @csrf
                  <div class="modal-body">
                     <div class="row">
                        <input type="hidden" name="candidate_id" value="{{ $candidate_details->candidate_id }}">
                        <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Recruitment Stage<sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                 <select class="form-control border-primary select2 form-select" id="" name="recruit_stage_id" data-placeholder="Choose one" required style="width:100%;">
                                    @foreach ($recruitment_stage_lists as $recruitment_stage_list)
                                    @if($candidate_details->recruitment_stage_id!=$recruitment_stage_list->recruitment_stage_id)
                                    <option value="{{  $recruitment_stage_list->recruitment_stage_id }}">{{ $recruitment_stage_list->recruitment_stage }}</option>
                                    @else
                                    <option value="{{ $candidate_details->recruitment_stage_id }}" selected>{{ $recruitment_stage_list->recruitment_stage }}</option> 
                                    @endif
                                    @endforeach
                                 </select>
                              </fieldset>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><b>Rating :<sup class="text-danger" style="font-size: 13px;">*</sup></b>
                                 <input type="number" min="0" max="10" id="" name="rating" class="name form-control">
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
      
         
   </script>
   </x-slot>
</x-app-layout>