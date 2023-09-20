<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Candidate Add
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
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>First Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="text" id="" required name="first_name" class="name form-control" placeholder="First Name">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Last Name :</b>
                                       <input type="text" id=""  name="last_name" class="name form-control" placeholder="Last Name">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Mail Id <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="email" id=""  name="mail_id" class="name form-control" placeholder="Mail Id" required>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Mobile Number <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="text" id=""  name="mobile_number" class="name form-control" placeholder="Mobile Number" required>
                                    </fieldset>
                                 </div>
                              </div>
                               <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Date Of Birth <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="date" id=""  name="dob" class="name form-control" placeholder="" required>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label class="label-control"><b>Gender :</b></label><br>
                                    <div class="d-inline-block custom-control custom-radio">
                                       <input type="radio" required checked name="gender" id="Men" value="1">
                                       <label for="Men">Male</label><br>
                                    </div>&nbsp;&nbsp;
                                    <div class="d-inline-block custom-control custom-radio">
                                       <input type="radio" name="gender" id="Women" value="2">
                                       <label  for="Women">Female</label>
                                    </div>
                                     <div class="d-inline-block custom-control custom-radio">
                                       <input type="radio" name="gender" id="others" value="3">
                                       <label  for="others">Others</label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Address :</b>
                                       <textarea type="text" id=""  name="address" class="name form-control" placeholder="Building No, Street, Landmark "></textarea>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Country <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" id="country_id" name="country_id" data-placeholder="Choose one" style="width:100%;">
                                          <option selected>Select</option>
                                          @foreach ($country_lists as $country_list)
                                          <option value="{{  $country_list->country_id }}">{{ $country_list->country_name }}</option>
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
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>City <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <select class="form-control border-primary select2 form-select" id="city_id" name="city_id" data-placeholder="Choose one" style="width:100%;">
                                       <option selected="selected" disabled="disabled">Select City</option>
                                       </select>
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Pincode <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                       <input type="text" class="form-control" id="pincode" name="pincode" placeholder="622201">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Job Role :</b>
                                       <input type="text" id=""  name="job_role" class="name form-control" placeholder="Job Role">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Educational Qualification :</b>
                                       <input type="text" id=""  name="educational_qualification" class="name form-control" placeholder="Bsc Computer Science">
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Year Of Passing :</b>
                                       <input type="number" id=""  name="year_of_passing" class="name form-control" placeholder="2015">
                                    </fieldset>
                                 </div>
                              </div>
                            <!--   <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Year Of Experience :</b>
                                       <input type="number" id=""  name="year_of_exprience" class="name form-control" placeholder="3">
                                    </fieldset>
                                 </div>
                              </div> -->
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Description :</b>
                                       <textarea id=""  name="description" class="name form-control">
                                       </textarea>  
                                    </fieldset>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <fieldset class="form-group floating-label-form-group"><b>Add More Details :</b>
                                       <center>
                                          <table class="table table-bordered responsive" id="AddMoreDetails">
                                             <thead>
                                                <tr>
                                                   <th width="50%">Heading</th>
                                                   <th width="40%">Detail</th>
                                                   <th width="10%"></th>
                                                </tr>
                                             </thead>
                                             <tbody id="AddMoreDetail">
                                                <tr class="add_row">
                                                   <td><input class="form-control" class="heading" type="text"  placeholder="Father Name" name="heading[]"/></td>
                                                   <td><input class="form-control" class="" type="text"   name="detail[]"  placeholder="John" /></td>
                                                   <td><button class="btn btn-success btn-sm" type="button" id="add_details" title='Add new'><i class="fa fa-plus"></i></button></td>
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
                                                <tr class="add_row">
                                                   <td><input class="form-control" class="filename" type="text"   name="company_name[]"/></td>
                                                   <td><input class="form-control" class="" type="text"   name="from_year[]"  placeholder="2012" /></td>
                                                   <td><input class="form-control" class="" type="text"   name="to_year[]"  placeholder="2015" /></td>
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
                                    <fieldset class="form-group floating-label-form-group"><b>Attach Documents :</b>
                                       <center>
                                          <table class="table table-bordered responsive" id="AddImageTable">
                                             <thead>
                                                <tr>
                                                   <th width="50%">Attach Document Name</th>
                                                   <th width="40%">Upload File</th>
                                                   <th width="10%"></th>
                                                </tr>
                                             </thead>
                                             <tbody id="ImageTBodyAdd">
                                                <tr class="add_row">
                                                   <td><input class="form-control" class="filename" type="text"   name="attachment_name[]"/></td>
                                                   <td><input id="upload" name='attachment[]' type='file' multiple ></td>
                                                   <td><button class="btn btn-success btn-sm" type="button" id="add" title='Add new file'><i class="fa fa-plus"></i></button></td>
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
      // $( document ).ready(function() {
             //Append new row In Add Form
             $('#AddImageTable').on('click', "#add", function(e) {
                $('#ImageTBodyAdd').append('<tr class="add_row"><td><input class="form-control filename" name="attachment_name[]" type="text"  multiple /></td><td><input id="upload"  name="attachment[]" type="file"  multiple /></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
                e.preventDefault();
            });
            // Delete row In Add Form
            $('#AddImageTable').on('click', "#delete", function(e) {
                if (!confirm("Are you sure you want to remove this file?"))
                    return false;
                $(this).closest('tr').remove();
                e.preventDefault();
            });

             $('#Expreience').on('click', "#add_experience", function(e) {
                $('#ImageTBodyAdds').append('<tr class="add_row"><td><input class="form-control filename" name="company_name[]" type="text" multiple /></td><td><input  class="form-control id=""  name="from_year[]" type="text"  placeholder="2012" multiple/></td> <td><input  class="form-control id=""  name="to_year[]" type="text"  placeholder="2015" multiple/></td>    <td><input  class="form-control id=""  name="experience_years[]" type="text"  placeholder="3" multiple/></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="deletes" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
                e.preventDefault();
            });
            // Delete row In Add Form
            $('#Expreience').on('click', "#deletes", function(e) {
                if (!confirm("Are you sure you want to remove this file?"))
                    return false;
                $(this).closest('tr').remove();
                e.preventDefault();
            });

            $('#AddMoreDetails').on('click', "#add_details", function(e) {
                $('#AddMoreDetail').append('<tr class="add_row"><td><input class="form-control" name="heading[]" type="text" placeholder="Father Name" multiple /></td><td><input class="form-control id=""  name="detail[]" type="text"  placeholder="John" multiple/></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="details_delete" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
                e.preventDefault();
            });
            // Delete row In Add Form
            $('#AddMoreDetails').on('click', "#details_delete", function(e) {
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