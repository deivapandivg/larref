<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Whatsapp Template
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                       <a href="{{route('whatsapp_add')}}">
                           <button class="btn btn-primary">
                              <i class="fa fa-plus"></i> Whatsapp Template
                           </button>
                       </a>
                  </div>
                  <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered whatsapp " style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;</th>
                                 <th>Template Name </th>
                                 <th>Whatsapp Content</th>
                                 <th>Created By</th>
                                 <th>Created At</th>
                              </tr>
                           </thead>
                           <tbody>

                           </tbody>
                           <tfoot>
                              <tr>
                                 <th>Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;</th>
                                 <th>Template Name </th>
                                 <th>Whatsapp Content</th>
                                 <th>Created By</th>
                                 <th>Created At</th>
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
     @php
   $DeleteTableName="com_template_whatsapp";
   $DeleteColumnName="template_id";
   @endphp
   @include('delete') 
   <x-slot name="page_level_scripts">
      <script type="text/javascript">

          $(function(){
            var table=$('.whatsapp').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('whatsapp') }}",
               columns: [
               {data:'template_id', name:'a.template_id'},
               {data:'action', name:'a.action', orderable: false, searchable: false},
               {data:'template_name', name:'a.template_name'},
               {data:'content', name:'a.content'},
               {data:'created_by', name:'b.first_name'},
               {data:'created_at', name:'a.created_at'},

               ]
            });
         });

         $(document).on('click', '.DeleteDataModal', function(){
            var DeleteColumnValue=$(this).closest("tr").find("td:eq(0)").text();
            $("#DeleteColumnValue").val(DeleteColumnValue);
            $("#DeleteDataModal").modal('show');
         });

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