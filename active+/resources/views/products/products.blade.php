<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Products
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
                  <div class="heading-elements">
                     <a href="{{ route('products_add') }}">
                        <button type="submit" class="btn btn-primary">
                           <i class="fa fa-plus"></i> Product
                        </button>
                     </a>
                  </div>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered product" style="width:100%;">
                           <thead>
                              <tr>
                                 <th>Product Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Product Category Name</th>
                                 <th>Product Name</th>
                                 <th>Created By</th>
                                 <th>Created At</th>
                                 <th>Updated By</th>
                                 <th>Updated At</th>
                                 <th>Quantity</th>
                              </tr>
                           </thead>
                           <tbody>

                           </tbody>
                           <tfoot>
                              <tr>
                                 <th>Product Id</th>
                                 <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                 <th>Product Category Name</th>
                                 <th>Product Name</th>
                                 <th>Created By</th>
                                 <th>Created At</th>
                                 <th>Updated By</th>
                                 <th>Updated At</th>
                                 <th>Quantity</th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
   </section>
   </div>
   </section>
   <div class="modal modal_outer right_modal fade" id="product_modal_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header bg-primary white">
               <h2 class="modal-title white">Product Quick View :</h2>
               <button type="button" class="btn btn-lg close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body get_quote_view_modal_body">
               <form method="post" id="get_form_data">
               </form>
            </div>
         </div>
      </div>
   </div>
   @php
   $DeleteTableName="products";
   $DeleteColumnName="product_id";
   @endphp
   @include('delete')
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         $(function() {
            var table = $('.product').DataTable({
               processing: true,
               order: [
                  [0, "desc"]
               ],
               serverSide: true,
               ajax: "{{ route('products') }}",
               columns: [{
                     data: 'product_id',
                     name: 'a.product_id'
                  },
                  {
                     data: 'action',
                     name: 'action',
                     orderable: false,
                     searchable: false
                  },
                  {
                     data: 'product_category_id',
                     name: 'd.product_category_name'
                  },
                  {
                     data: 'product_name',
                     name: 'a.product_name'
                  },
                  {
                     data: 'created_by',
                     name: 'b.first_name'
                  },
                  {
                     data: 'created_at',
                     name: 'a.created_at'
                  },
                  {
                     data: 'updated_by',
                     name: 'c.first_name'
                  },
                  {
                     data: 'updated_at',
                     name: 'a.updated_at'
                  },
                  {
                     data: 'quantity',
                     name: 'a.quantity'
                  },
               ]
            });
         });

         $(document).on('click', '.DeleteDataModal', function() {
            var DeleteColumnValue = $(this).closest("tr").find("td:eq(0)").text();
            $("#DeleteColumnValue").val(DeleteColumnValue);
            $("#DeleteDataModal").modal('show');
         });

         $(document).on('click', '.ProductModalView', function() {
            var product_id = $(this).closest("tr").find("td:eq(0)").text();
            $.ajax({
               url: 'products_modal_view/'+product_id,
               type: "post",
               data: {
                  "_token": "{{ csrf_token() }}"
               },
               success: function(data) {
                  $("#get_form_data").html(data);
                  $("#product_modal_view").modal('show');
               }
            })
         });
      </script>
   </x-slot>
</x-app-layout>