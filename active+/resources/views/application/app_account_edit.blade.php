<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">
                  Accounts Add</h4>
                  <ol class="breadcrumb mt-0">
                     <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                     </li>
                  </ol>
               </div>
               <div class="card-body">
                  <form class="form form-horizontal" method="post" enctype="multipart/form-data" action="{{ route('app_account_submit') }}">
                     @csrf
                     <div class="form-body">
                        <h4 class="form-section"><i class="fa fa-book"></i> Account Details  </h4>
                        <input type="hidden" name="id" value="{{ $app_acc_details->app_account_id }}">
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group ">
                                 <label class="label-control" for="userinput1"><b>Account Name :</b></label>
                                 <input type="text" id="userinput1" class="form-control border-primary" placeholder="Account Name" name="account_name" value="{{ $app_acc_details->account_name }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control" for="userinput2"><b>Company Name :</b></label>
                                 <input type="text" id="userinput2" class="form-control border-primary" placeholder="Company Name" name="company_name" value="{{ $app_acc_details->brand_name }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control" for="userinput4"><b>Mail Id :</b></label>
                                 <input type="email" id="userinput4" class="form-control border-primary" placeholder="Mail Id" name="mail_id" value="{{ $app_acc_details->mail_id }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control" for="userinput3"><b>Website Name :</b></label>
                                 <input type="text" id="userinput3" class="form-control border-primary" 
                                 placeholder="www.website.com" name="web_site" value="{{ $app_acc_details->web_site }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control" for="userinput4"><b>MobileNumber :</b></label>
                                 <input type="number" id="userinput4" class="form-control border-primary" placeholder="9876543210" name="mobile_number" value="{{ $app_acc_details->mobile_number }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control" for="userinput4"><b>Alter MobileNumber :</b></label>
                                 <input type="number" id="userinput4" class="form-control border-primary" placeholder="9876543210" name="alter_mobile_number" value="{{ $app_acc_details->alter_mobile_number }}">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="label-control" for="userinput4"><b>GST Number :</b></label>
                                 <input type="text" id="userinput4" class="form-control border-primary" placeholder="Gst Number" name="gst_number" value="{{ $app_acc_details->gst_number }}">
                              </div>
                           </div>
                        </div>
                        <h4 class="form-section"><i class="fa fa-picture-o"></i><b> Branding Images :</b></h4>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group ">
                                 <label class="label-control" for="userinput4"><b>Brand Logo :</b></label>
                                 <div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
                                    <label class="btn btn-sm btn-primary mt-1 ml-0 pl-1 cursor-pointer" for="account-Logo"><i class="fa fa-camera"></i>Upload new photo</label>
                                    <input type="file" name="brand_logo"  id="account-Logo" hidden>
                                 </div>
                                 <p class="text-muted ml-75 mt-50"><small> Allowed JPG, GIF or PNG. Maxsize of 800 kB.</small></p>
                                 <br>
                                 <div id="uploaded_image"></div>
                                 <img style="width: 50%;" src="{{ asset('public/brand_logo/'. $app_acc_details->brand_logo) }}"  class="css-class" id="HideImageLogo">
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group ">
                                 <label class="label-control" for="userinput4"><b>Brand Icon :</b></label>
                                 <div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
                                    <label class="btn btn-sm btn-primary mt-1 ml-0 pl-1 cursor-pointer" for="account-Icon"><i class="fa fa-camera"></i>Upload new photo</label>
                                    <input type="file" name="brand_icon"  id="account-Icon" hidden>
                                 </div>
                                 <p class="text-muted ml-75 mt-50"><small> Allowed JPG, GIF or PNG. Maxsize of 800 kB.</small></p>
                                 <br>
                                 <div id="uploaded_image1"></div>
                                 <img style="width: 15%;" src="{{ asset('public/brand_logo_icon/'. $app_acc_details->branding_icon) }}"  class="css-class" id="HideImageIcon">
                              </div>
                           </div>
                        </div>
                        <h4 class="form-section"><i class="fa fa-address-card"></i><b>Address Details</b></h4>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group row">
                                 <div class="col-md-12">
                                    <label class="label-control" for="userinput3"><b> Address :</b></label>
                                    <textarea class="form-control border-primary" placeholder="address" name="address">{{$app_acc_details->address}}</textarea> 
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="form-actions right">
                        <a href="#">
                           <button type="button" class="btn btn-danger mr-1">
                              <i class="fa fa-times"></i> Cancel
                           </button>
                        </a>
                        <button type="submit" class="btn btn-primary">
                           <i class="fa fa-check-square"></i> Save
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
      </script>
   </x-slot>
</x-app-layout>