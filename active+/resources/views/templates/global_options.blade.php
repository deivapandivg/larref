<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Global Options
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="card-content">
                     <div class="card-body">
                        <form class="form form-horizontal" method="post" enctype="multipart/form-data" action="{{route('global_options_submit')}}"> 
                           @csrf
                           <div class="form-body">
                              <div class="row container">
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label class="userinput4"><b>Sms Option <sup class="text-danger" style="font-size: 13px;">*</sup> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b></label>
                                       <div class="d-inline-block custom-control custom-radio">
                                          <input type="radio" name="sms_option" id="sms_enable" value="Yes" {{$options->sms_option == 'Yes' ? 'checked':''}}> <label for="sms_enable">Enable</label>
                                       </div>&nbsp;&nbsp;
                                       <div class="d-inline-block custom-control custom-radio">
                                          <input type="radio" name="sms_option" id="sms_disable" value="No" {{$options->sms_option == 'No' ? 'checked':''}}> <label for="sms_disable">Disable</label>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label class="userinput4"><b>Mail Option <sup class="text-danger" style="font-size: 13px;">*</sup> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b></label>
                                       <div class="d-inline-block custom-control custom-radio">
                                          <input type="radio" name="mail_option" id="mail_enable" value="Yes" {{$options->mail_option == 'Yes' ? 'checked':''}}> <label for="mail_enable">Enable</label><br>
                                       </div>&nbsp;&nbsp;
                                       <div class="d-inline-block custom-control custom-radio">
                                          <input type="radio" name="mail_option" id="mail_disable" value="No" {{$options->mail_option == 'No' ? 'checked':''}}> <label for="mail_disable">Disable</label>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label class="userinput4"><b>Call Option <sup class="text-danger" style="font-size: 13px;">*</sup> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b></label>
                                       <div class="d-inline-block custom-control custom-radio">
                                          <input type="radio" name="call_option" id="call_enable" value="Yes" {{$options->call_option == 'Yes' ? 'checked':''}}> <label for="call_enable">Enable</label><br>
                                       </div>&nbsp;&nbsp;
                                       <div class="d-inline-block custom-control custom-radio">
                                          <input type="radio" name="call_option" id="call_disable" value="No" {{$options->call_option == 'No' ? 'checked':''}}> <label for="call_disable">Disable</label>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label class="userinput4"><b>Whatsapp Option <sup class="text-danger" style="font-size: 13px;">*</sup> &nbsp;&nbsp;:</b></label>
                                       <div class="d-inline-block custom-control custom-radio">
                                          <input type="radio" name="whatsapp_option" id="whatsapp_enable" value="Yes" {{$options->whatsapp_option == 'Yes' ? 'checked':''}}> <label for="whatsapp_enable">Enable</label><br>
                                       </div>&nbsp;&nbsp;
                                       <div class="d-inline-block custom-control custom-radio">
                                          <input type="radio" name="whatsapp_option" id="whatsapp_disable" value="No" {{$options->whatsapp_option == 'No' ? 'checked':''}}> <label for="whatsapp_disable">Disable</label>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="form-actions right">
                                 <button type="submit" name="submit" id="button" class="btn btn-primary">
                                    <i class="fa fa-check"></i> Update
                                 </button>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <x-slot name="page_level_scripts">
      <script type="text/javascript">

      </script>
      <style type="text/css">
         #search{
            margin-top: 5px;
            margin-left: 9px;
            font-size: 33px;
         }
         #search:hover{
            cursor: pointer;
         }
      </style>
   </x-slot>
</x-app-layout>