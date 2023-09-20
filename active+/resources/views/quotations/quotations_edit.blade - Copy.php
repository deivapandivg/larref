<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Quotation Edit
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>
               <div class="card-body">
                  <form method="post" action="{{ route('quotations_submit') }}" id="form" enctype="multipart/form-data">
                     @csrf
                     <div class="row ml-2 mr-2">
                        <div class="col-xl-4">
                           <div class="form-group">
                              @foreach($quotation_edit as $quotation_edits)
                              <input type="hidden" name="quotation_id" value="{{ $quotation_edits->QuotationId }}">
                              <label class="label-control">Client Name</label>
                              <select class="form-control border-primary select2 form-select" name="client_id" data-placeholder="Choose one" style="width:100%;">
                                 <option selected>Select</option>
                                 @foreach($clients as $client)
                                 <option value="{{ $client->client_id }}"{{ ($quotations->client_id==$client->client_id) ? 'selected' : '' }}>{{ $client->client_name }}</option>
                                 @endforeach
                              </select>
                           </div>@endforeach
                        </div>
                        @php $today_date=date('Y-m-d'); @endphp
                        <div class="col-xl-4 DateFilters">
                           <div class="form-group">
                              <label class="label-control">Quotation Date</label>
                              <input type="date" name="qualification_date"  value="{{ $quotations->date_issue }}"  class="form-control" id="qualification_date">
                           </div>
                        </div>
                        <div class="col-xl-4 DateFilters">
                           <div class="form-group">
                              <label class="label-control">Valid Date</label>
                              <input type="date" name="valid_date" id="valid_date" value="{{ $quotations->date_due }}" class="form-control">
                           </div>
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-xl-4 col-lg-4">
                           <input type="radio" name="GSTType" id="IGST" class="GSTTypeIn" value="1"{{ ($quotations->igst_type==1) ? 'checked' : '' }}>&nbsp;&nbsp;<label class="label-control" for="IGST">IGST</label>&nbsp;&nbsp;
                           <input type="radio" name="GSTType" id="CGSTType" class="GSTTypeIn" value="2"{{ ($quotations->igst_type!=1) ? 'checked' : '' }}>&nbsp;&nbsp;<label class="label-control" for="CGSTType">CGST & SGST</label>
                        </div>
                     </div>
                     <hr>
                     <table class="table-striped table-responsive" border="0" id="mytable">
                        <thead class="bg-primary text-white">
                           <tr>
                              <th>Item</th>
                              <th>Rate</th>
                              <th>Qty</th>
                              <th>GST (%)</th>
                              <th class="IGSThide">IGST ( <i class="fa fa-inr"></i> )</th>
                              <th class="CGSThide">CGST ( <i class="fa fa-inr"></i> )</th>
                              <th class="SGSThide">SGST ( <i class="fa fa-inr"></i> )</th>
                              <th>Amount ( <i class="fa fa-inr"></i> )</th>
                              <td></td>
                           </tr>
                        </thead>
                        <tbody id="QuotationItems">  
                           <tr>
                              @php
                               $gsttype=$quotations->igst_type;
                              @endphp

                              @foreach($quotation_edit as $quotation_edits)
                              <td>
                                 <select class="select2 form-control Product"  name="Product[]" style="width: 250px;">
                                    <option value="">Select Product</option>
                                    @foreach($products as $product){
                                     <option  value="{{ $product->product_id }}"{{ (($quotation_edits->Product==$product->product_id)? 'selected':'') }}>{{ $product->product_name }}</option>
                                    @endforeach
                                 </select>
                                 <textarea class="form-control" style="padding-top:5px;" name="ProductDescription[]" placeholder="Product Description" style="width: 250px;">{{ $quotation_edits->ProductDescription }}</textarea>
                              </td>
                              <td><input type="number" class="form-control Cost" name="Cost[]" value="{{ $quotation_edits->Cost }}" style="width: 110px;">
                              </td>
                              <td><input type="number" class="form-control Quantity" name="Quantity[]" value="{{ $quotation_edits->Quantity }}" placeholder="Quantity" style="width: 110px;"></td>
                              <td>
                                 <input type="number" class="form-control GST" name="GST[]" placeholder="Total" value="{{ $quotation_edits->GST }}" style="width: 110px;">
                              </td>
                              <td class="IGSThide {{ (($quotations->igst_type!='1')?'hidden':'') }}"><input type="number" class="form-control IGST" name="IGST[]" value="{{ $quotation_edits->IGST }}" readonly style="width: 110px;"></td>
                              <td class="CGSThide {{ (($quotations->igst_type=='1')?'hidden':'') }}"><input type="number" class="form-control CGST" name="CGST[]" value="{{ $quotation_edits->CGST }}" readonly style="width: 110px;"></td>
                              <td class="SGSThide {{ (($quotations->igst_type=='1')?'hidden':'') }}"><input type="number" class="form-control SGST" name="SGST[]" value="{{ $quotation_edits->SGST }}" readonly style="width: 110px;"></td>
                              <td><input type="number" name="Amount[]" class="form-control Amount" readonly placeholder="Total" value="{{ $quotation_edits->Amount }}" style="width: 110px;"></td>
                              <td>
                                 <span type="button" class="btn btn-danger btn-sm RemoveInvoiceItem text-white">
                                     <i class="fa fa-trash"></i> 
                                 </span>
                              </td>
                              @endforeach
                           </tr>
                        </tbody>
                        <tfoot>
                           <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td class="IGSThide"></td>
                              <td class="CGSThide"></td>
                              <td class="SGSThide"></td>
                              <td>Total Amount</td>
                              <td><input class="form-control TotalAmount" type="number" name="TotalAmount" readonly value="{{ $quotations->total_amount }}"  style="width: 110px;"></td>
                              <td><span class="btn btn-primary btn-sm AddInvoiceItem"><i class="fa fa-plus"></i></span></td>
                           </tr>
                           <tr class="IGSThide">
                              <td></td>
                              <td></td>
                              <td></td>
                              <td class="IGSThide"></td>
                              <td class="CGSThide"></td>
                              <td class="SGSThide"></td>
                              <td>Total IGST</td>
                              <td><input class="TotalIGST form-control" type="number" name="TotalIGST" readonly value="{{ $quotations->total_igst }}"  style="width: 110px;"></td>
                              <td></td>
                           </tr>
                           <tr class="CGSThide">
                              <td></td>
                              <td></td>
                              <td></td>
                              <td class="IGSThide"></td>
                              <td class="CGSThide"></td>
                              <td class="SGSThide"></td>
                              <td>Total CGST</td>
                              <td><input class="TotalCGST form-control" type="number" name="TotalCGST" readonly value="{{ $quotations->total_cgst }}"  style="width: 110px;"></td>
                              <td></td>
                           </tr>
                           <tr class="SGSThide">
                              <td></td>
                              <td></td>
                              <td></td>
                              <td class="IGSThide"></td>
                              <td class="CGSThide"></td>
                              <td class="SGSThide"></td>
                              <td>Total SGST</td>
                              <td><input class="TotalSGST form-control" type="number" name="TotalSGST" readonly value="{{ $quotations->total_sgst }}"  style="width: 110px;"></td>
                              <td></td>
                           </tr>
                           <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td class="IGSThide"></td>
                              <td class="CGSThide"></td>
                              <td class="SGSThide"></td>
                              <td>Grand Total</td>
                              <td><input class="form-control GrandTotal" type="number" name="GrandTotal" readonly value="{{ $quotations->grand_total }}"  style="width: 110px;"></td>
                              <td></td>
                           </tr>
                        </tfoot>
                     </table>
                     <hr>
                     <div class="modal-footer">
                        <a href="{{ route('quotations') }}">
                        <button type="button" data-dismiss="modal" class="btn btn-danger">
                           <i class="fa fa-arrow-left"></i> Back
                        </button>
                        </a>
                        <button type="submit" name="submit"  class="btn btn-success">
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
         $(document).on('change','.Product',function(){
            var CurTr=$(this).closest("tr");
            var GST=0;
            var Cost=0;
            var Quantity=CurTr.find(".Quantity").val();
            var Product=CurTr.find(".Product").val();
            $.post("{{ route('quotation_new_rate') }}",{"_token": "{{ csrf_token() }}","Product":Product},function(data){
               CurTr.find(".Cost").val(data);
               Cost=data;
               $.post("{{ route('quotation_new_gst') }}",{"_token": "{{ csrf_token() }}","Product":Product},function(data){
                  CurTr.find(".GST").val(data);
                  GST=data;
                  $("tr .select2").select2();
                  var TotalProduct=(Cost*Quantity);
                  var GetGST=(TotalProduct*GST/100);
                  var GetGSTTwo=(GetGST/2);
                  var TotalGST=TotalProduct+GetGST;
                  CurTr.find('.Amount').val(TotalProduct);
                  CurTr.find('.IGST').val(GetGST);
                  CurTr.find('.CGST').val(GetGSTTwo);
                  CurTr.find('.SGST').val(GetGSTTwo);
                  CalculateTotal();
               });
               $("tr .select2").select2();
            });
         });
         function ValidateTax(TaxType)
         {
            if (TaxType!=1) 
            {
               $('.IGSThide').addClass('hidden');
               $('.CGSThide').removeClass('hidden');
               $('.SGSThide').removeClass('hidden');
            }
            else
            {
               $('.CGSThide').addClass('hidden');
               $('.SGSThide').addClass('hidden');
               $('.IGSThide').removeClass('hidden');
            }
         }
         $(".GSTTypeIn").click(function(){
            var GSTTypeIn=$("input[name=GSTType]:checked").val();
            ValidateTax(GSTTypeIn);
            CalculateTotal();
         });
         $(document).ready(function(){
            var GSTTypeIn=$("input[name=GSTType]:checked").val();
            ValidateTax(GSTTypeIn);
            $.post("quotation_new_product",{"_token": "{{ csrf_token() }}","GSTTypeIn":GSTTypeIn},function(data){
               $("#QuotationItems").append(data);
               $("tr .select2").select2();
            });
         });
         $(".AddInvoiceItem").click(function(){
            var GSTTypeIn=$("input[name=GSTType]:checked").val();
            ValidateTax(GSTTypeIn);
            $.post("{{ route('quotation_new_product') }}",{"_token":"{{ csrf_token() }}","GSTTypeIn":GSTTypeIn},function(data){
               $("#QuotationItems").append(data);
               $("tr .select2").select2();
            });
         });
         $(document).on('click','.RemoveInvoiceItem',function(){
            $(this).closest("tr").remove();
            CalculateTotal();
         });
         $(document).on('change','.GST,.Cost,.Quantity', function(){
            var CurTr=$(this).closest("tr");
            var Product=CurTr.find(".Product").val();
            var Cost=CurTr.find(".Cost").val();
            var Quantity=CurTr.find(".Quantity").val();
            var GST=CurTr.find(".GST").val();
            var TotalProduct=(Cost*Quantity);
            var GetGST=(TotalProduct*GST/100);
            var GetGSTTwo=(GetGST/2);
            var TotalGST=TotalProduct+GetGST;
            CurTr.find('.Amount').val(TotalProduct);
            CurTr.find('.IGST').val(GetGST);
            CurTr.find('.CGST').val(GetGSTTwo);
            CurTr.find('.SGST').val(GetGSTTwo);
            CalculateTotal();
         });
         function CalculateTotal()
         {
            var TotalAmount = 0;
            $('.Amount').each(function() {
               var Amount=$(this).val();
               TotalAmount=TotalAmount+parseInt(Amount);
            });
            $(".TotalAmount").val(TotalAmount);
            var GSTTypeIn=$("input[name=GSTType]:checked").val();
            var TotalGST=0;
            if(GSTTypeIn==1)
            {
               var TotalIGST = 0;
               $('.IGST').each(function() {
                  var IGST=$(this).val();
                  TotalIGST=TotalIGST+parseInt(IGST);
               });
               TotalGST=TotalGST+TotalIGST;
               $(".TotalIGST").val(TotalIGST);
            }
            else
            {
               var TotalCGST = 0;
               $('.CGST').each(function() {
                  var CGST=$(this).val();
                  TotalCGST=TotalCGST+parseInt(CGST);
               });
               TotalGST=TotalGST+TotalCGST;
               $(".TotalCGST").val(TotalCGST);
               var TotalSGST = 0;
               $('.SGST').each(function() {
                  var SGST=$(this).val();
                  TotalSGST=TotalSGST+parseInt(SGST);
               });
               TotalGST=TotalGST+TotalSGST;
               $(".TotalSGST").val(TotalSGST);
            }
            var GrandTotal=TotalGST+TotalAmount;
            $(".GrandTotal").val(GrandTotal);
         }  
      </script>
   </x-slot>
   <style type="text/css">
      #mytable td{
         padding: 2px;
      }
      #mytable th{
         text-align: center;
         background-color: 
         color: #fff;
      }
      #mytable td{
         vertical-align:top;
      }
   </style>
</x-app-layout>