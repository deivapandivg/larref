<x-app-layout>
    <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Send Mail
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>   
               <div class="card-body">
                  <form class="form" method="POST" enctype="multipart/form-data" action="{{ route('send_mail') }}">
                     @csrf
                    <div class="form-body">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label class="label-control">Email <b><sup class="text-danger" style="font-size: 13px;">*</sup></b> :</label>
                                 <input type="text" id="" class="form-control border-primary" placeholder="Email" name="email" value="">
                                 @error('email') <span class="text-danger">{{ $message }}</span>@enderror
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label class="label-control">Subject <b><sup class="text-danger" style="font-size: 13px;">*</sup></b> :</label>
                                 <input type="text" id="" class="form-control border-primary" placeholder="Subject" name="subject" value="">
                                 @error('subject') <span class="text-danger">{{ $message }}</span>@enderror
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label class="label-control" for="message">Message <b><sup class="text-danger" style="font-size: 13px;">*</sup></b> :</label>
                                 <textarea id="task-textarea" class="form-control border-primary" placeholder="Message" name="message"></textarea>
                                 @error('message') <span class="text-danger">{{ $message }}</span>@enderror
                              </div> 
                           </div>
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label class="label-control" for="attachment">Attachment :</label>
                                 <input type="file" id="attachment" class="form-control border-primary" name="attachment" value="">
                                 @error('attachment') <span class="text-danger">{{ $message }}</span>@enderror
                              </div>
                           </div>
                        </div>
                        <div class="form-actions right">
                            <a href="{{ route('quotations') }}">
                               <button type="button" class="btn btn-danger mr-1">
                                  <i class="fa fa-times"></i> Close
                               </button>
                            </a>
                            <button type="submit" class="btn btn-primary">
                               <i class="fa fa-check"></i> Send
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
   </section>
    <x-slot name="page_level_scripts">
        <script type="text/javascript">
            CKEDITOR.replace('message');
        </script>
    </x-slot>
</x-app-layout>