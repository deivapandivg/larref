<x-app-layout>
   <section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Proforma Invoice Add
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>
               <div class="card-body">
                  <form method="post" action="{{ route('proforma_invoices_submit') }}" id="form" enctype="multipart/form-data">
                     @csrf
                     <div class="row ml-2 mr-2">
                        <div class="col-xl-4">
                           <div class="form-group">
                              <label class="label-control">Client Name</label>
                              <select class="form-control border-primary select2 form-select" name="client_id" data-placeholder="Choose one" style="width:100%;" required>
                                 <option value="">Select Client</option>
                                 @foreach($clients as $client)
                                 <option value="{{ $client->client_id }}">{{ $client->client_name }}</option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                        @php $today_date=date('Y-m-d'); @endphp
                        <div class="col-xl-4 DateFilters">
                           <div class="form-group">
                              <label class="label-control">Proforma Invoice Date</label>
                              <input type="date" name="qualification_date"  value="{{ $today_date }}"  class="form-control" id="qualification_date">
                           </div>
                        </div>
                        <div class="col-xl-4 DateFilters">
                           <div class="form-group">
                              <label class="label-control">Valid Date</label>
                              <input type="date" name="valid_date" id="valid_date" value="{{ $today_date }}" class="form-control">
                           </div>
                        </div>
                     </div>
                     <div class="row ml-2 mr-2">
                     @foreach($GetCustomFields as $GetCustomField)
                        <div class="col-lg-4">
                           <div class="form-group">
                              <fieldset class="form-group floating-label-form-group"><label>{{ $GetCustomField->field_name }} @if($GetCustomField->required_field=="Yes") <sup style="color:red;font-size:12px;">*</sup>@endif :</label><br>
                              
                                 @if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==10 OR $GetCustomField->field_type==6 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==5)
                                    @foreach($GetCustomFieldTypes as $GetCustomFieldType)
                                       @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type) 
                                          <input type="{{ $GetCustomFieldType->field_type }}" class="name form-control" value="@if ($GetCustomField->field_type==5) echo date('Y-m-d');@elseif($GetCustomField->field_type==8) echo date('h:i');@endif" placeholder="{{ $GetCustomField->field_name }}" name="Custom-{{ $GetCustomField->custom_field_id }}">
                                       @endif
                                    @endforeach
                                 @elseif($GetCustomField->field_type==7)
                                 <br>
                                    @php
                                    $field_value=$GetCustomField->field_value;
                                    $RadioArr=json_decode($field_value,true);
                                    @endphp
                                       @foreach($RadioArr as $data)
                                       @foreach ($GetCustomFieldTypes as $GetCustomFieldType)
                                          @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type) 
                                             &nbsp;&nbsp;<input checked type="{{ $GetCustomFieldType->field_type }}" placeholder="{{  $GetCustomField->field_name }}" name="Custom-{{ $GetCustomField->custom_field_id }}" value="<?= $data['FieldValue'] ?>" style="width:15px;height:15px;">&nbsp;&nbsp;<?= $data['FieldValue'] ?>&nbsp;&nbsp;
                                          @endif
                                       @endforeach
                                       @endforeach
                                       
                                 @elseif($GetCustomField->field_type==9)
                                 <br>
                                 @php
                                    $field_value=$GetCustomField->field_value;
                                    $CheckboxArr=json_decode($field_value, true);@endphp
                                    
                                    @foreach ($CheckboxArr as $Checkbox)
                                       @foreach ($GetCustomFieldTypes as $GetCustomFieldType)
                                          @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type) 
                                             &nbsp;&nbsp;<input type="{{ $GetCustomFieldType->field_type }}" name="Custom-{{ $GetCustomField->custom_field_id }}[]" value="<?= $Checkbox['FieldValue'] ?>" style="width:15px;height:15px;">&nbsp;&nbsp;<?= $Checkbox['FieldValue'] ?>&nbsp;&nbsp;
                                          @endif
                                       @endforeach
                                    @endforeach   
                                 @elseif($GetCustomField->field_type==3)
                                    <select class="select2 form-control" style="width: 100%;" name="Custom-{{ $GetCustomField->custom_field_id }}">
                                       <option value="">Select {{  $GetCustomField->field_name }}</option>
                                       @php
                                       $field_value=$GetCustomField->field_value;
                                       $OptionsArr=json_decode($field_value, true);
                                       @endphp
                                       <?php
                                 
                                 foreach ($OptionsArr as $Options)
                                 {
                                    echo "<option>".$Options['FieldValue']."</option>";
                                 }
                                 ?>
                                        
                                    </select>
                                 @elseif($GetCustomField->field_type==11)
                                    <textarea name="Custom-{{ $GetCustomField->custom_field_id }}" class="form-control" placeholder="{{  $GetCustomField->field_name }}"></textarea>
                                 @endif
                           </div>
                        </div>
                        @endforeach
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-xl-4 col-lg-4">
                           <input type="radio" name="GSTType" id="IGST" class="GSTTypeIn" value="1">&nbsp;&nbsp;<label class="label-control" for="IGST">IGST</label>&nbsp;&nbsp;
                           <input type="radio" checked name="GSTType" id="CGSTType" class="GSTTypeIn" value="2">&nbsp;&nbsp;<label class="label-control" for="CGSTType">CGST & SGST</label>
                        </div>
                        <div class="col-xl-4 col-lg-4">
                        </div>
                        <div class="col-xl-4 col-lg-4">
                           <button type="button" class="btn btn-primary AddProformaInvoiceItemProduct" name="input_type" id="product" class="input_type" value="1"><i class="fa fa-plus"></i>&nbsp;&nbsp;Product</button>&nbsp;&nbsp;
                           <button type="button" class="btn btn-success AddProformaInvoiceItemService" name="input_type" id="service" class="input_type" value="2"><i class="fa fa-plus"></i>&nbsp;&nbsp;Service</button>                        
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
                        <tbody id="proforma_InvoiceItems">
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
                              <td><input class="form-control TotalAmount" type="number" name="TotalAmount" readonly value="0"  style="width: 110px;"></td>
                           </tr>
                           <tr class="IGSThide">
                              <td></td>
                              <td></td>
                              <td></td>
                              <td class="IGSThide"></td>
                              <td class="CGSThide"></td>
                              <td class="SGSThide"></td>
                              <td>Total IGST</td>
                              <td><input class="TotalIGST form-control" type="number" name="TotalIGST" readonly value="0"  style="width: 110px;"></td>
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
                              <td><input class="TotalCGST form-control" type="number" name="TotalCGST" readonly value="0"  style="width: 110px;"></td>
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
                              <td><input class="TotalSGST form-control" type="number" name="TotalSGST" readonly value="0"  style="width: 110px;"></td>
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
                              <td><input class="form-control GrandTotal" type="number" name="GrandTotal" readonly value="0"  style="width: 110px;"></td>
                              <td></td>
                           </tr>
                        </tfoot>
                     </table>
                     <hr>
                     
                     <div class="modal-footer">
                        <a href="{{ route('proforma_invoices') }}">
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
            $.post("proforma_invoice_new_rate",{"_token":"{{ csrf_token() }}","Product":Product},function(data){
               CurTr.find(".Cost").val(data);
               Cost=data;
               $.post("proforma_invoice_new_gst",{"_token":"{{ csrf_token() }}","Product":Product},function(data){
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
         $(document).on('change','.Service',function(){
            var CurTr=$(this).closest("tr");
            var GST=0;
            var Cost=0;
            var Quantity=CurTr.find(".Quantity").val();
            var Service=CurTr.find(".Service").val();
            $.post("proforma_invoice_new_rate_service",{"_token":"{{ csrf_token() }}","Service":Service},function(data){
               CurTr.find(".Cost").val(data);
               Cost=data;
               $.post("proforma_invoice_new_gst_service",{"_token":"{{ csrf_token() }}","Service":Service},function(data){
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
            var input_type=$('#product').val();
            ValidateTax(GSTTypeIn);
            $.post("proforma_invoice_new_product",{"_token": "{{ csrf_token() }}","GSTTypeIn":GSTTypeIn,"input_type":input_type},function(data){
               $("#proforma_InvoiceItems").append(data);
               $("tr .select2").select2();
            });
         });
         $(".AddProformaInvoiceItemProduct").click(function(){
            var GSTTypeIn=$("input[name=GSTType]:checked").val();
            var input_type=$('#product').val();
            ValidateTax(GSTTypeIn);
            $.post("{{ route('proforma_invoice_new_product') }}",{"_token":"{{ csrf_token() }}","GSTTypeIn":GSTTypeIn,"input_type":input_type},function(data){
               $("#proforma_InvoiceItems").append(data);
               $("tr .select2").select2();
            });
         });
         $(".AddProformaInvoiceItemService").click(function(){
            var GSTTypeIn=$("input[name=GSTType]:checked").val();
            var input_type=$('#service').val();
            ValidateTax(GSTTypeIn);
            $.post("{{ route('proforma_invoice_new_product') }}",{"_token":"{{ csrf_token() }}","GSTTypeIn":GSTTypeIn,"input_type":input_type},function(data){
               $("#proforma_InvoiceItems").append(data);
               $("tr .select2").select2();
            })
         });
         $(document).on('click','.RemoveProformaInvoiceItem',function(){
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
      form .form-section
      {
         line-height: 3rem;
         margin-bottom: 20px;
         color: #82a3de;
         border-bottom: 1px solid #9cb6e5;
      }
   </style>
</x-app-layout>