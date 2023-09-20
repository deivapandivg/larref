<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Profile Edit
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary"  onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i>    Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>
               <div class="card-content">
                  <div class="card-body pt-1">
                     <ul class="nav nav-tabs active">
                        <li class="nav-item active">
                           <a class="nav-link active" id="homeIcon-tab active" data-toggle="tab" href="#homeIcon" aria-controls="homeIcon" aria-expanded="true" ><i class="feather ft-globe"></i> General</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" id="profileIcon-tab" data-toggle="tab" href="#profileIcon" aria-controls="profileIcon" aria-expanded="false"><i class="feather ft-lock"></i>Reset Password</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" id="Grafix-tab new" data-toggle="tab" href="#Grafix" aria-controls="profileIcon" aria-expanded="false"><i class="feather ft-codepen"></i>Themes</a>
                        </li>
                     </ul>
                     <hr>
                     <div class="tab-content px-1 pt-1">
                        <div role="tabpanel" class="tab-pane show active" id="homeIcon" aria-labelledby="homeIcon-tab" aria-expanded="true">
                           @if(Auth::user()->profile_upload!='')
                           <div class="media">
                              <a href="{{ asset('public/profile_uploads/' . Auth::user()->profile_upload) }}" target="target">
                                 <img src="{{ asset('public/profile_uploads/'.Auth::user()->profile_upload) }}" class="avatar-100 rounded" style="max-width:100%;" alt="avatar" id="HideImage">
                                 <div id="uploaded_image"></div>
                              </a>
                           </div>
                           @else
                           @php $firstStringCharacter = substr(Auth::user()->first_name, 0, 1);
                           $profilePic = array(
                          'a.png' => 'a',
                          'b.png' => 'b',
                          'c.png' => 'c',
                          'd.png' => 'd',
                          'e.png' => 'e',
                          'f.png' => 'f',
                          'g.png' => 'g',
                          'h.png' => 'h',
                          'i.png' => 'i',
                          'j.png' => 'j',
                          'k.png' => 'k',
                          'l.png' => 'l',
                          'm.png' => 'm',
                          'n.png' => 'n',
                          'o.png' => 'o',
                          'p.png' => 'p',
                          'q.png' => 'q',
                          'r.png' => 'r',
                          's.png' => 's',
                          't.png' => 't',
                          'u.png' => 'u',
                          'v.png' => 'v',
                          'w.png' => 'w',
                          'x.png' => 'x',
                          'y.png' => 'y',
                          'z.png' => 'z',
                          );
                          $searchkey = strtolower($firstStringCharacter);
                          $value = array_search($searchkey,$profilePic);
                        @endphp
                           <div class="media">
                              <a href="{{ asset('public/default_profile_images/' .$value) }}" target="target">
                                 <img src="{{ asset('public/default_profile_images/'.$value) }}" class="avatar-100 rounded" style="max-width:100%;" alt="avatar" id="HideImage">
                                 <div id="uploaded_image"></div>
                              </a>
                           </div>
                           @endif
                           <form method="post" enctype="multipart/form-data" action="">
                              <!-- <div class="media-body">
                                 <div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
                                      <label class="btn btn-sm btn-primary mt-1 ml-0 pl-1 cursor-pointer" for="account-upload"><i class="fa fa-camera"></i>Upload new photo</label>
                                       <input type="file" name="upload_image"  id="account-upload" hidden>
                                    <input type="hidden" id="UserId" value="" name="UserId">
                                 </div>
                                 <p class="text-muted ml-75 mt-50"><small> Allowed JPG, GIF or PNG. Maxsize of 800 kB.</small></p>
                              </div> -->
                              <hr>
                              <div class="row">
                                 <div class="col-6">
                                    <div class="form-group">
                                       <div class="controls">
                                          <label for="account-username"><b>First Name <sup class="text-danger" style="font-size: 13px;">*</sup>:</b></label>
                                          <p>{{ Auth::user()->first_name }}</p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-6">
                                    <div class="form-group">
                                       <div class="controls">
                                          <label for="account-name"><b>Last Name :</b></label>
                                           <p>{{ Auth::user()->last_name }}</p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-6">
                                    <div class="form-group">
                                       <div class="controls">
                                          <label for="account-name">Email :</label>
                                           <p>{{ Auth::user()->email }}</p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-6">
                                    <div class="form-group">
                                       <div class="controls">
                                          <label for="account-name">Personal Email :</label>
                                           <p>{{ Auth::user()->personal_mail_id }}</p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-6">
                                    <div class="form-group">
                                       <div class="controls">
                                          <label for="account-name">Mobile Number :</label>
                                           <p>{{ Auth::user()->personal_mobile_number }}</p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </form>
                        </div>
                        <div class="tab-pane" id="profileIcon" role="tabpanel" aria-labelledby="profileIcon-tab" aria-expanded="false">
                           <form method="post" action="{{ route('password_chage_submit') }}">
                              @csrf 
                              @foreach ($errors->all() as $error)
                                 <p class="text-danger">{{ $error }}</p>
                              @endforeach 
                              <div class="row">
                                 <div class="col-3">
                                    <div class="form-group">
                                       <div class="controls">
                                          <label for="account-new-password"><b>Old Password  <sup class="text-danger" style="font-size: 13px;">*</sup>:</b></label>
                                          <input type="Password" name="current_password" autocomplete="current-password" id="OldPassword" required class="form-control" placeholder="Old password" >
                                          <span id="OldPasswordMessage" class="text-center " style="text-align: center;"></span>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-3">
                                    <div class="form-group">
                                       <div class="controls">
                                          <label for="account-new-password"><b>New Password  <sup class="text-danger" style="font-size: 13px;">*</sup>:</b></label>
                                          <input type="hidden" id="UserId" value="" name="UserId">
                                          <input type="password"  name="new_password" autocomplete="current-password" id="NewPassword" class="form-control password" placeholder="New Password" required data-validation-required-message="The password field is required" minlength="6">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-3">
                                    <div class="form-group">
                                       <div class="controls">
                                          <label for="account-retype-new-password"><b>Retype
                                          Password <sup class="text-danger" style="font-size: 13px;">*</sup> :</b></label>
                                          <input type="password" name="new_confirm_password" autocomplete="current-password" class="form-control confirm_password" required id="ConfirmPassword" data-validation-match-match="password" placeholder="Retype Password"required data-validation-required-message="The Confirm password field is required" minlength="6">
                                          <span id='ConfirmPasswordMessage'></span>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-6">
                                    <div class="form-group">
                                       <div class="controls">
                                          <label for="account-e-mail">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                          <input type="hidden" class="form-control" id="" placeholder="Mobile No" required data-validation-required-message="This field is required"  name="">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-6">
                                    <div class="form-group">
                                       <div class="controls">
                                          <label for="account-e-mail">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                          <input type="hidden" class="form-control" id="" placeholder="Mobile No" required data-validation-required-message="This field is required"  name="">
                                       </div>
                                    </div>
                                 </div>
                                  <div class="col-6">
                                    <div class="form-group">
                                       <div class="controls">
                                          <label for="account-e-mail">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                          <input type="hidden" class="form-control" id="" placeholder="Mobile No" required data-validation-required-message="This field is required"  name="">
                                       </div>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                    <button type="submit" name="submit1" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0" id="PasswordSaveButton">Save
                                    </button>
                                 </div>
                              </div>
                           </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="Grafix" aria-labelledby="Grafix-tab new" aria-expanded="true">
                           <form method="post" enctype="multipart/form-data" action="{{ route('user_theme_submit') }}">
                              @csrf
                              <input type="hidden" id="" value="{{ Auth::user()->id }}" name="user_id">
                              <!-- BEGIN: Customizer-->
                              <h5 class="mt-1 mb-1 text-bold-500">Navbar Color Options</h5>
                              <div class="navbar-color-options clearfix">
                                 <div class="gradient-colors mb-1 clearfix">
                                    <label for="colo1">
                                       <div class="bg-gradient-x-purple-blue navbar-color-option" data-bg="bg-gradient-x-purple-blue" title="bg-gradient-x-purple-blue">
                                          <input type="radio" name="navbar_color" value="bg-gradient-x-purple-blue" class="hidden" id="colo1" 
                                           {{ Auth::user()->navbar_color == 'bg-gradient-x-purple-blue' ? 'checked' : '' }}>
                                       </div>
                                    </label>
                                    <label for="colo2">
                                       <div class="bg-gradient-x-purple-red navbar-color-option" data-bg="bg-gradient-x-purple-red" title="bg-gradient-x-purple-red">
                                          <input type="radio" name="navbar_color" value="bg-gradient-x-purple-red" class="hidden" id="colo2"
                                           {{ Auth::user()->navbar_color == 'bg-gradient-x-purple-red' ? 'checked' : '' }}>
                                       </div>
                                    </label>
                                    <label for="colo3">
                                       <div class="bg-gradient-x-blue-green navbar-color-option" data-bg="bg-gradient-x-blue-green" title="bg-gradient-x-blue-green">
                                          <input type="radio" name="navbar_color" value="bg-gradient-x-blue-green" class="hidden" id="colo3"
                                           {{ Auth::user()->navbar_color == 'bg-gradient-x-blue-green' ? 'checked' : '' }}>
                                       </div>
                                    </label>
                                    <label for="colo4">
                                       <div class="bg-gradient-x-orange-yellow navbar-color-option" data-bg="bg-gradient-x-orange-yellow" title="bg-gradient-x-orange-yellow">
                                          <input type="radio" name="navbar_color" value="bg-gradient-x-orange-yellow" class="hidden" id="colo4"  {{ Auth::user()->navbar_color == 'bg-gradient-x-orange-yellow' ? 'checked' : '' }}>
                                       </div>
                                    </label>
                                    <label for="colo5">
                                       <div class="bg-gradient-x-blue-cyan navbar-color-option" data-bg="bg-gradient-x-blue-cyan" title="bg-gradient-x-blue-cyan">
                                          <input type="radio" name="navbar_color" value="bg-gradient-x-blue-cyan" class="hidden" id="colo5"
                                           {{ Auth::user()->navbar_color == 'bg-gradient-x-blue-cyan' ? 'checked' : '' }}>
                                       </div>
                                    </label>
                                    <label for="colo6">
                                       <div class="bg-gradient-x-red-pink navbar-color-option" data-bg="bg-gradient-x-red-pink" title="bg-gradient-x-red-pink">
                                          <input type="radio" name="navbar_color" value="bg-gradient-x-red-pink" class="hidden" id="colo6"
                                           {{ Auth::user()->navbar_color == 'bg-gradient-x-red-pink' ? 'checked' : '' }}>
                                       </div>
                                    </label>
                                 </div>
                                 <!-- <div class="solid-colors clearfix">
                                    <div class="bg-primary navbar-color-option" data-bg="bg-primary" title="primary"></div>
                                    <div class="bg-success navbar-color-option" data-bg="bg-success" title="success"></div>
                                    <div class="bg-info navbar-color-option" data-bg="bg-info" title="info"></div>
                                    <div class="bg-warning navbar-color-option" data-bg="bg-warning" title="warning"></div>
                                    <div class="bg-danger navbar-color-option" data-bg="bg-danger" title="danger"></div>
                                    <div class="bg-blue navbar-color-option" data-bg="bg-blue" title="blue"></div>
                                 </div> -->
                              </div>
                              <hr>
                              <!--Sidebar Background Image Starts-->
                              <h5 class="mt-1 mb-1 text-bold-500">Sidebar Background Image </h5>
                              <div class="cz-bg-image row">
                                 <div class="col mb-3">
                                    <label for="img4">
                                       <img src="{{ asset('public/app-assets/images/backgrounds/04.jpg') }}" class="rounded sidiebar-bg-img" width="50" height="100" alt="background image">
                                       <input type="radio" name="background_image"  value="public/app-assets/images/backgrounds/04.jpg" id="img4" class="Checkbutton hidden" data-size="xs" {{ Auth::user()->background_image == 'public/app-assets/images/backgrounds/04.jpg' ? 'checked' : '' }} /> 
                                    </label>
                                 </div>
                                 <div class="col mb-3"> 
                                    <label for="img1">
                                       <img src="{{ asset('public/app-assets/images/backgrounds/01.jpg') }}" class="rounded sidiebar-bg-img" width="50" height="100" alt="background image"> 
                                       <input type="radio" name="background_image"  value="public/app-assets/images/backgrounds/01.jpg" id="img1" class="Checkbutton hidden" data-size="xs" {{ Auth::user()->background_image == 'public/app-assets/images/backgrounds/01.jpg' ? 'checked' : '' }} /> 
                                    </label>
                                 </div>
                                 <div class="col mb-3"> 
                                    <label for="img2">
                                       <img src="{{ asset('public/app-assets/images/backgrounds/02.jpg') }}" class="rounded sidiebar-bg-img" width="50" height="100" alt="background image"> 
                                       <input type="radio" name="background_image"  value="public/app-assets/images/backgrounds/02.jpg" id="img2" class="Checkbutton hidden" data-size="xs" {{ Auth::user()->background_image == 'public/app-assets/images/backgrounds/02.jpg' ? 'checked' : '' }} /> 
                                    </label>
                                 </div>
                                 <div class="col mb-3"> 
                                    <label for="img5">
                                       <img src="{{ asset('public/app-assets/images/backgrounds/05.jpg') }}" class="rounded sidiebar-bg-img" width="50" height="100" alt="background image"> 
                                        <input type="radio" name="background_image"  value="public/app-assets/images/backgrounds/05.jpg" id="img5" class="Checkbutton hidden" data-size="xs" {{ Auth::user()->background_image == 'public/app-assets/images/backgrounds/05.jpg' ? 'checked' : '' }} /> 
                                    </label>
                                 </div>
                              </div>
                              <!--Sidebar Background Image Ends-->
                              <!--Sidebar BG Image Toggle Starts-->
                              <!-- <div class="sidebar-image-visibility">
                                 <div class="row ml-0">
                                    <label for="toggle-sidebar-bg-img" class="card-title font-medium-2 mr-2">Hide Image</label>
                                    <div class="text-center mb-1">
                                       <input type="checkbox" value="block" id="toggle-sidebar-bg-img" name="background_view" class="switchery" data-size="xs"/> 
                                    </div>
                                    <label for="toggle-sidebar-bg-img" class="card-title font-medium-2 ml-2">Show Image</label>
                                 </div>
                              </div> -->
                              <!--Sidebar BG Image Toggle Ends-->
                              <hr>
                              <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                 <button type="submit"  class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save
                                 changes</button>
                              </div>
                              <!-- End: Customizer-->
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

         // $('#OldPassword, #NewPassword, #ConfirmPassword').change(function(){
         //    var OldPassword=$("#OldPassword").val();
         //    var ExistingPassword="";
         //    if(OldPassword==ExistingPassword)
         //    {
         //       var NewPassword=$("#NewPassword").val();
         //       var ConfirmPassword=$("#ConfirmPassword").val();
         //       if(NewPassword==ConfirmPassword)
         //       {
         //          $("#OldPasswordMessage").html("");
         //          $("#ConfirmPasswordMessage").html("");
         //          $("#PasswordSaveButton").removeClass("hidden");
         //       }
         //       else
         //       {
         //          $("#ConfirmPasswordMessage").html("Password not Matching.");
         //          $("#PasswordSaveButton").addClass("hidden");
         //       }
         //    }
         //    else
         //    {
         //       $("#OldPasswordMessage").html("Kindly Enter the Corect Password.");
         //       $("#PasswordSaveButton").addClass("hidden");
         //    }
         // });
      </script>
   </x-slot>
</x-app-layout>