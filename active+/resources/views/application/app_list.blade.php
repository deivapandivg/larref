<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Accounts
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active"><span class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4><br>
                  <div class="heading-elements">
                     <a href="{{ route('app_account_add') }}">
                        <button class="btn btn-primary">
                           <i class="fa fa-plus"></i> Add Account
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <table class="table table-striped table-bordered GetAccountsTable" style="width:100%;">
                        <thead>
                           <tr>
                              <th>App Acc Id</th>
                              <th>Action</th>
                              <th>Account Name</th>
                              <th>Brand Name</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <th>App Acc Id</th>
                              <th>Action</th>
                              <th>Account Name</th>
                              <th>Brand Name</th>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   
   @php
   $DeleteTableName="application_accounts";
   $DeleteColumnName="app_account_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">

         $(function () {
            var table = $('.GetAccountsTable').DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "{{ route('application_list') }}",
               columns: [
               {data: 'app_account_id', name: 'app_account_id'},
               {data: 'action', name: 'action', orderable: false, searchable: false},
               {data: 'account_name', name: 'account_name'},
               {data: 'brand_name', name: 'brand_name'},
               ]
            });
         });

         $(document).on('click', '.DeleteDataModal', function(){
            var DeleteColumnValue=$(this).closest("tr").find("td:eq(0)").text();
            $("#DeleteColumnValue").val(DeleteColumnValue);
            $("#DeleteDataModal").modal('show');
         });

      </script>
   </x-slot>
</x-app-layout>