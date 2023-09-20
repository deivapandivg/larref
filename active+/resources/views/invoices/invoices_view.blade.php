<x-app-layout>
   <section class="tabs-with-icons">
      <div class="row">
         <div class="col-xl-10 col-md-8 col-12 printable-content" id="print_invoice">
            <div class="card">
               <div class="card-body p-2">
                  <div class="card-header px-0">
                     <div class="row">
                        <div class="col-md-12 col-lg-7 col-xl-4 mb-50">
                           <!-- <span class="invoice-id font-weight-bold">invoice# </span> -->
                           <img src="{{ asset('public/accsource/mainlogo.png') }}" alt="company-logo" height="80" width="100" style="margin-left:5px ;">
                        </div>
                        <div class="col-md-12 col-lg-5 col-xl-8">
                           <div class="d-flex align-items-center justify-content-end justify-content-xs-start">
                              <div class="issue-date pr-2">
                                 <div class="due-date">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <br>
                  <div class="row col-12 invoice-adress-info py-0">
                     @foreach($clients as $client)
                     <div class="col-6 mb-1 to-info pl-10" >
                        <div class="info-title mb-1">
                           <span>Invoice To :</span>
                        </div>
                        <div class="company-name">
                           <span class="text-muted">{{ $client->client_name }}
                        </span>
                     </div>
                     <div class="company-address">
                        <span class="text-muted">{{ $client->address }}</span>
                     </div>
                     <div class="company-email">
                        <span class="text-muted">{{ $client->email_id }}</span>
                     </div>
                     <div class="company-phone">
                        <span class="text-muted">{{ $client->mobile_number }}</span>
                     </div>
                     @endforeach
                  </div>
                  <div class="col-6"  align="right">
                     <div class="info-title mb-1">
                        <h3 class="text-primary">INVOICE</h3>
                     </div>
                     
                     <div class="company-address mb-1">
                        <span align="center">Invoice #:  {{ $invoice_view->invoiceId }}</span>
                     </div>
                     <div class="company-address mb-1">
                        <span align="center">Invoice Date :  {{ $invoices->date_issue }}</span>
                     </div>
                  </div>
               </div>
               <hr>
               <div class="row ml-1 mr-1">
               @if($invoice_details->custom_fields!='')
               @foreach($GetCustomFields as $GetCustomField)
                  <div class="col-lg-4">
                     <div class="form-group">
                        <label class="label-control"><?= $GetCustomField->field_name ?> :</label>
                        @if($GetCustomField->field_type==1 OR $GetCustomField->field_type==2 OR $GetCustomField->field_type==4 OR $GetCustomField->field_type==10 OR $GetCustomField->field_type==8 OR $GetCustomField->field_type==5)
                        @php 
                           $invoice_value=$invoice_details->custom_fields;
                           $CustomFieldsValueArr=json_decode($invoice_value, true);
                           $custom_field_id=$GetCustomField->custom_field_id; 
                           @endphp 
                           <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr)) <?= $CustomFieldsValueArr[$custom_field_id] ?> @endif </p>
                           @if($GetCustomField->field_type==6)
                              <a href="<?= $CustomFieldsValueArr[$custom_field_id] ?>">Click to View</a>
                           @endif
                        @elseif($GetCustomField->field_type==6)
                           @php
                                 $invoice_value=$invoice_details->custom_fields;
                                 $CustomFieldsValueArr=json_decode($invoice_value, true);
                                 $custom_field_id=$GetCustomField->custom_field_id;
                              @endphp
                              @foreach($GetCustomFieldTypes as $GetCustomFieldType)
                                 @if($GetCustomFieldType->field_type_id==$GetCustomField->field_type)
                                    @if($CustomFieldsValueArr!='')
                                       @if(array_key_exists($custom_field_id,$CustomFieldsValueArr)) 
                                          <p><?= $CustomFieldsValueArr[$custom_field_id] ?><a href="public/uploads/invoices/<?= $CustomFieldsValueArr[$custom_field_id] ?>">Click to View</a></p>
                                       @else
                                          <p><?= $CustomFieldsValueArr[$custom_field_id] ?></p>
                                       @endif
                                    @else
                                       <p><?= $CustomFieldsValueArr[$custom_field_id] ?></p>
                                    @endif
                                 @endif
                              @endforeach
                        @elseif($GetCustomField->field_type==7)
                           @php 
                           $invoice_value=$invoice_details->custom_fields;
                           $CustomFieldsValueArr=json_decode($invoice_value, true);
                           $custom_field_id=$GetCustomField->custom_field_id; 
                           @endphp
                           <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif</p>
                           
                        @elseif($GetCustomField->field_type==9)
                           @php 
                           $invoice_value=$invoice_details->custom_fields;
                           $CustomFieldsValueArr=json_decode($invoice_value, true);
                           $custom_field_id=$GetCustomField->custom_field_id; 
                           @endphp
                           <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))&nbsp;&nbsp;<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif </p>
                           
                        @elseif($GetCustomField->field_type==3)
                        
                           @php
                           $field_value=$GetCustomField->field_value;
                           $OptionsArr=json_decode($field_value, true);
                           $invoice_value=$invoice_details->custom_fields;
                           $CustomFieldsValueArr=json_decode($invoice_value, true);
                           $custom_field_id=$GetCustomField->custom_field_id; 
                           @endphp
                           <p> @if(array_key_exists($custom_field_id,$CustomFieldsValueArr))<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif</p>
                           
                        @elseif($GetCustomField->field_type==11)
                           @php
                           $invoice_value=$invoice_details->custom_fields;
                           $CustomFieldsValueArr=json_decode($invoice_value, true);
                           $custom_field_id=$GetCustomField->custom_field_id; 
                           @endphp
                           <p>@if(array_key_exists($custom_field_id,$CustomFieldsValueArr))<?= $CustomFieldsValueArr[$custom_field_id] ?>@endif</p>
                        @endif
                     </div>
                  </div>
               @endforeach
               @endif
               </div>
               <hr>
               <div class="product-details-table py-2 table-responsive">
                  <table class="table table-borderless">
                     <thead class="bg-primary text-white">
                        <tr>
                           <th scope="col">#</th>
                           <th scope="col">ITEM & DESCRIPTION</th>
                           <th scope="col">RATE</th>
                           <th scope="col">QTY</th>
                           <th scope="col">AMOUNT</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        $gsttype=$invoices->igst_type;
                        $i=1;
                        @endphp
                        @foreach($invoice_items as $invoice_item)
                        <tr>
                           <td>{{ $i }}</td>
                           @if($invoice_item->Product!='')
                           @foreach($products as $product)
                           @if($invoice_item->Product==$product->product_id)
                           <td>
                           {{ $product->product_name }}<br>
                           <span style="color:gray; font-style: italic; font-size: 13px;">
                              {{ $invoice_item->ProductDescription }}</span>
                           </td>@endif
                           @endforeach
                           @endif
                           @if($invoice_item->Service!='')
                           @foreach($services as $service)
                           @if($invoice_item->Service==$service->service_id)
                           <td>
                           {{ $service->service_name }}<br>
                           <span style="color:gray; font-style: italic; font-size: 13px;">
                              {{ $invoice_item->ServiceDescription }}</span>
                           </td>@endif
                           @endforeach
                           @endif
                           <td>{{ $invoice_item->Cost }}</td>
                           <td>{{ $invoice_item->Quantity }}</td>
                           <td class="font-weight-bold"><i class="fa fa-inr text-light"></i>{{ $invoice_item->Amount }}</td>
                        </tr>
                        @php $i++; @endphp
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <hr>
               <p>( <b>Note : <span class="<?php if($gsttype==2){echo"hidden";} ?>">IGST - Integrated Goods and Service Tax. </span></b><span class="<?php if($gsttype==1){echo"hidden";} ?>"><b>CGST</b> - Central Goods and Service Tax. <b>SGST</b> - State Goods and Services Tax. </span>)</p>
               <div class="invoice-total py-2">
                  <div class="row">
                     <div class="col-4 col-sm-6 mt-75">
                        <div class="hidd">
                           <p>Terms & Conditions</p>
                        </div>
                     </div>
                     <div class="col-8 col-sm-6 d-flex justify-content-end mt-75">
                        <ul class="list-group cost-list">
                           <li class="list-group-item each-cost border-0 p-50 d-flex justify-content-between">
                              <span class="cost-title mr-2">Subtotal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-</span>
                              <span class="cost-value"><i class="fa fa-inr text-light"></i> {{ $invoices->total_amount }}.00</span>
                           </li>
                           <div class="<?php if($gsttype==1){echo"hidden";} ?>">
                              <li class="list-group-item each-cost border-0 p-50 d-flex justify-content-between">
                                 <span class="cost-title mr-2">CGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-</span>
                                 <span class="cost-value"><i class="fa fa-inr text-light"></i> {{ $invoices->total_cgst }}.00</span>
                              </li>
                           </div>
                           <div class="<?php if($gsttype==1){echo"hidden";} ?>">
                              <li class="list-group-item each-cost border-0 p-50 d-flex justify-content-between">
                                 <span class="cost-title mr-2">SGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-</span>
                                 <span class="cost-value"><i class="fa fa-inr text-light"></i> {{ $invoices->total_sgst }}.00</span>
                              </li>
                           </div>
                           <div class="<?php if($gsttype!=1){echo"hidden";} ?>">
                              <li class="list-group-item each-cost border-0 p-50 d-flex justify-content-between">
                                 <span class="cost-title mr-2">IGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-</span>
                                 <span class="cost-value"><i class="fa fa-inr text-light"></i> {{ $invoices->total_igst }}.00</span>
                              </li>
                           </div>
                           <li class="dropdown-divider"></li>
                           <li class="list-group-item each-cost border-0 p-50 d-flex justify-content-between">
                              <span class="cost-title mr-2">invoice Total -</span>
                              <span class="cost-value"><i class="fa fa-inr text-light"></i> {{ $invoices->grand_total }}.00</span>
                           </li>
                           <li class="dropdown-divider"></li>
                        </ul>
                     </div>
                  </div>
                  <p class="text-center">Thanks for your business.</p>
               </div>
            </div>
         </div>
      </div>
      <div class="col-xl-2 col-md-4 col-12 action-btns">
         <div class="card">
            <div class="card-body p-2">
               <!-- <a href="#" class="btn btn-primary btn-block mb-1"> <i class="feather icon-check mr-25 common-size"></i> Send invoice</a> -->
               <a href="#" class="btn btn-warning btn-block btn-sm mb-1 print-invoice" onclick="codespeedy()"> <i class="feather icon-printer mr-25 common-size"> Print</i></a>
               <a href="{{ route('invoices_edit',base64_encode($invoice_view->invoiceId)) }}" class="btn btn-sm btn-primary btn-block mb-1"><i class="fa fa-edit mr-25 common-size"> Edit</i> </a>
               <a target="_blank" href="{{ route('invoices_pdf', base64_encode($invoice_view->invoiceId)) }}" class="btn btn-sm btn-info btn-block mb-1"><i class="fa fa-download mr-25 common-size"> PDF</i> </a>
               <a href="{{ route('invoice_send_mail', base64_encode($invoice_view->invoiceId)) }}" class="btn btn-sm btn-success btn-block mail mb-1"><i class="fa fa-paper-plane  mr-25 common-size"> Mail</i> </a>
            </div>
         </div>
      </div>
   </section>

   <div class="modal fade" id="EditSubMailModal" role="dialog" aria-labelledby="EditSubMailModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-" role="document">
         <div class="modal-content">
            <div id="EditMaildata">
            </div>
         </div>
      </div>
   </div>
   <x-slot name="page_level_scripts">
      <script type="text/javascript">
         function codespeedy(){
            var printable_content = document.getElementById("print_invoice");
            var print_area = window.open('', '', 'height=1000,width=1800');
            print_area.document.write(printable_content.innerHTML);
            print_area.document.close();
            print_area.focus();
            print_area.print();
            print_area.close();
         }


      </script>
   </x-slot>
</x-app-layout>