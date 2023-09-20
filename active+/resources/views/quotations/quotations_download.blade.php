<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title></title>
</head>
   <body>
      <section>
         <table style="width:100%">
            <tr>
               <th style="width:30%" align="left">
                  <img src="{{ $pic }}" class="ml-2" alt="" width="60%" >
               </th>
               <th style="width:40%">
                  <h3 class="text-primary">Quotation Invoice</h3>
               </th>
               <th style="width:30%" align="right">
                  <p>Quotation #:  {{ $quotation_view->QuotationId }}</p>
                 
                  <p>Quotation Date : {{ $quotations->date_issue }}</p>
               </th>
            </tr>
         </table>
      </section>
      <br>
      <section>
         <table style="width:100%">
            <tr>
               <th style="width:50%;" align="left">Quotation Invoice From :
                  <p style="font-weight:normal;">Vingreen Technologies</p>
                  <p style="font-weight:normal;">Vadapalani,chennai-26</p>
                  <p style="font-weight:normal;">vingreentechnologies@gmail.com</p>
                  <p style="font-weight:normal;">8608841146</p>
               </th>
               <th style="width:50%;" align="right">Quotation Invoice To :
                  <p style="font-weight:normal;">{{ $clients->client_name }}</p>
                  <p style="font-weight:normal;">{{ $clients->address }}</p>
                  <p style="font-weight:normal;">{{ $clients->email_id }}</p>
                  <p style="font-weight:normal;">{{ $clients->mobile_number }}</p>
               </th>
            </tr>
         </table>
      </section>
      <br>
      <hr>
      <section>
         <table style="width:100%;">
            <thead align="left">
               <tr>
                  <th>#</th>
                  <th>ITEM & DESCRIPTION</th>
                  <th style="text-align: center;">RATE</th>
                  <th style="text-align: center;">QTY</th>
                  <th style="text-align: center;">AMOUNT</th>
               </tr>
            </thead>
            <tbody>
               @php
               $gsttype=$quotations->igst_type;
               $i=1;
               @endphp
               @foreach($quotation_items as $quotation_item)
               <br>
               <tr>
                  @if($quotation_item->Product!='')
                     <td style="width:5%;">{{ $i }}</td>
                     @foreach($products as $product)
                        @if($quotation_item->Product==$product->product_id)
                           <td style="width:59%;">
                           {{ $product->product_name }}<span style="color:gray; font-style: italic; font-size: 13px;">{{ $quotation_item->ProductDescription }}</span><br>
                           </td>
                           <td style="width:12%;text-align: center;">{{ $quotation_item->Cost }}</td>
                           <td style="width:12%;text-align: center;">{{ $quotation_item->Quantity }}</td>
                           <td style="width:12%;text-align: center;">{{ $quotation_item->Amount }}</td>
                        @endif
                     @endforeach
                  @endif
                  @if($quotation_item->Service!='')
                     <td style="width:5%;">{{ $i }}</td>
                     @foreach($services as $service)
                        @if($quotation_item->Service==$service->service_id)
                        <td style="width:59%;">
                        {{ $service->service_name }}<span style="color:gray; font-style: italic; font-size: 13px;">{{ $quotation_item->ServiceDescription }}</span><br>
                        </td>
                        <td style="width:12%;text-align: center;">{{ $quotation_item->Cost }}</td>
                        <td style="width:12%;text-align: center;">{{ $quotation_item->Quantity }}</td>
                        <td style="width:12%;text-align: center;">{{ $quotation_item->Amount }}</td>
                        @endif
                     @endforeach
                  @endif
               </tr>
               @php $i++; @endphp
               @endforeach
            </tbody>
         </table>
      </section>
      <hr>
      <br>
      <section>
         <?php
         if($gsttype==2){
            echo "<p>( <b>Note : <span>CGST</b> - Central Goods and Service Tax. <b>SGST</b> - State Goods and Services Tax. </span>)</p>";
         }
         else{
            echo "<p>( <b>Note : <span>IGST</b> - Integrated Goods and Service Tax. </span>)</p>";
         } ?>
         <p>Terms & Conditions</p>
      </section>
      <br>
      <section>
         <table style="width:100%;">
            <tr>        
               <th style="text-align: right;">Subtotal :  
               <span style="font-weight:normal;">{{ $quotations->total_amount }}.00</span></th>
            </tr>
            <br>
            <?php if($gsttype==2){
            echo '<tr>
               <th style="text-align: right;">CGST :
               <span style="font-weight:normal;">'.$quotations->total_cgst.'.00</span></th>
           
            </tr>
            <br>';
            }
            ?>
            <?php if($gsttype==2){
            echo '<tr>
               <th style="text-align: right;">SGST :
               <span style="font-weight:normal;">'.$quotations->total_sgst.'.00</span></th>
           
            </tr>
            <br>';
            }
            ?>
            <?php if($gsttype==1){
            echo '<tr>
               <th style="text-align: right;">IGST :
               <span style="font-weight:normal;">'.$quotations->total_igst.'.00</span></th>
            </tr>
            <br>';
            }
            ?>
            <tr>
               <hr style="width:30%;margin-left: 500px;">
            </tr>
            <tr>
               <th style="text-align: right;">Quotation Total :
               <span style="font-weight:normal;"> {{ $quotations->grand_total }}.00</span></th>
                   
            </tr>
            <tr>
               <hr style="width:30%;margin-left: 500px;">
            </tr>
            <br>
            <tr>              
               <p style="text-align: center;">Thanks for your business.</p>
            </tr>
         </table>      
      </section>
      <br>
   </body>
</html>  
