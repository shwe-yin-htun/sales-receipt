@extends('master')
@section('content')
<style>
.container{
    margin-top:20px
}
#receipt{
    margin-left: 15%;
    background: #8b86860f;
}
#qty{
    width:40px;
    border-top:dotted; 
    border-bottom:dotted;
}
#item{
    border-top:dotted; 
    border-bottom:dotted;
}
p{
    text-align:center;
    margin-top: 20px;
    margin-left: -15%;
}
</style>
     <div class="container">
          <div class="row">
              <div class="col-md-8" id="receipt">
                  
                    <p>
                        LIBRASUN SNACKS<br/>
                        Mld Valley City,<br/>
                        Lingkaran Syed Putra,<br/>
                        59200 Kuala Lumpur<br/>
                    </p>
                       <table align="center" style="margin-right:20%; width:400px;"> 
                         <thead>
                            <tr>
                                 <td colspan="2">Receipt No.{{$receipt->receipt_no}}</td>
                                 <td>Temp:Temp_01</td>
                            </tr>
                            <tr>
                                 <td colspan="2">Shift No.1</td>
                                 <td>{{$receipt->created_at->format('d/m/Y')}}</td>
                            </tr>
                            <tr>
                                 <td colspan="2">Cashier : SUPPORT </td>
                                 <td>{{$receipt->created_at->format('d/m/Y h:i:s')}}</td>
                            </tr>
                            <tr>
                                 <td colspan="2">DINE-IN</td>
                                 <td></td>
                            </tr>
                            <tr>
                                 <td id="qty">QTY</td>
                                 <td id="item">ITEM</td>
                                 <td>AMOUNT</td>
                            </tr>
                       </thead>
                       <tbody> 
                           <?php $subtotal=0 ?>
                           @foreach($items as $item)
                               <?php $amount=$item['items']->price * $item['items']->qty; ?>
                               <tr>
                                   <td>{{$item['items']->qty}}</td>
                                   <td>{{$item['items']->name}}</td>
                                   <td>{{number_format($amount,2)}}</td>
                               </tr>
                               @if($item['item_set']!=0)
                                  @foreach($item['item_set'] as $set)
                                      <tr style="color:blue">
                                          <td style="padding-left:13px;">1</td>
                                          <td>{{$set->name}}</td>
                                          <td>00.00</td>
                                      </tr>
                                  @endforeach
                               @endif
                               <?php $subtotal += $item['items']->price * $item['items']->qty;?>
                           @endforeach
                       </tbody>
                       <tfoot>
                          <tr >
                              <td style="border-top:dotted; text-align:center;" colspan="2">Sub Total</td>
                              <td>{{number_format($subtotal, 2)}}</td>
                          </tr>
                          @if($receipt->discount !='' and $receipt->discount!=0)
                            <tr>
                                <td colspan="2" align="center">Discount %</td>
                                <td>{{$receipt->discount}}</td>
                            </tr>
                          @endif
                          <tr>
                              <td colspan="2" align="center">Grand Total</td>
                              <td>{{$receipt->grand_total}}</td>
                          </tr>
                          <tr>
                              <td colspan="2" align="center">Cash</td>
                              <td>{{$receipt->cash}}</td>
                          </tr>
                     </tfoot>
                 </table>
                
                 <p>
                    CUSTOMER HOTLINE<br/>
                    (060)3 2298 7229<br/>
                    *** Thank You ***<br/>
                 </p>     
              </div>
          </div>
     </div>
@endsection