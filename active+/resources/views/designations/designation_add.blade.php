<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Designation Add
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div><hr> 
               <div class="card-body">
                  <form method="post" action="{{ route('designation_submit') }}">
                     @csrf
                     <center>
                        <div class="row">
                           <div class="col-lg-2">
                           </div>
                           <div class="col-lg-8">
                              <div class="form-group row">
                                 <label class="col-lg-3 label-control" for="designation_name">
                                    <b>
                                       Designation Name<sup class="text-danger" style="font-size: 13px;">*</sup>:
                                    </b>
                                 </label>
                                 <div class="col-lg-9">
                                    <input type="text" name="designation_name" class="form-control border-primary" placeholder="Designation Name">
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-2">
                           </div>
                           <div class="col-lg-2">
                           </div>
                           <div class="col-lg-8">
                              <div class="form-group row">
                                 <label class="col-lg-3 label-control" for="">
                                    <b>
                                       Description :
                                    </b>
                                 </label>
                                 <div class="col-lg-9">
                                    <textarea class="form-control border-primary" placeholder="Description" name="description">
                                    </textarea>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-2">
                           </div>
                        </div>
                     </center>
                     <h4 class="text-center"><hr>Permission<hr></h4>
                     <ul class="sortable-row">
                        @if ($access_menus!="") 
                        @foreach($get_menus as $get_menu)
                           @if($get_menu->menu_group_id==1)
                              <label class="form-control bg-info text-white" for="{{ $get_menu->menu_id }}">
                                 <ul class="SortableLi">
                                    <input id="{{ $get_menu->menu_id }}" type="checkbox" class="listdataid" name="list_data_array[]" value="{{ $get_menu->menu_id }}"><b> {{ $get_menu->menu_name }}</b>
                                 </ul>
                              </label>
                           @else
                              @if(!in_array($get_menu->menu_group_id, $CompletedMenuGroupsArr))
                                 @php
                                 array_push($CompletedMenuGroupsArr, $get_menu->menu_group_id);@endphp
                                 <li class="SortableLi list-unstyled bg-info text-white menu_group"><b>{{ $get_menu->menu_group_name }}</b>
                                    <ul class="sortable-row">
                                       @foreach($child_menus as $child_menu)
                                          @if($get_menu->menu_group_id==$child_menu->menu_group_id)
                                          <label class="form-control bg-info text-white" for="{{ $child_menu->menu_id }}">
                                             <li class="SortableLi list-unstyled">
                                                <input type="checkbox" id="{{ $child_menu->menu_id }}" class="listdataid bg-info borderless" name="list_data_array[]" value="{{ $child_menu->menu_id }}"><b> {{ $child_menu->menu_name }}</b>
                                             </li>
                                          </label>
                                          @endif
                                       @endforeach
                                    </ul>
                                 </li>
                              @endif   
                           @endif
                        @endforeach
                        @endif
                     </ul>
                     <div class="form-actions right">
                        <a href="dashboard.php">
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
         $(function() {
            $( ".sortable-row" ).sortable({
               placeholder: "ui-state-highlight"
            });
         });
      </script>
   </x-slot>
   <style type="text/css">
      .menu_group{
         border-radius: 5px;
         padding:0px 5px 5px 5px;
         font-size: 20px;
         margin-bottom: 10px;
      }
   </style>
</x-app-layout>