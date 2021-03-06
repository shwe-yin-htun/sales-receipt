@extends('master')
@section('content')
<style>
.select2-container--default .select2-selection--single {
       background-color: #fff;
       border: 1px solid #aaa;
       border-radius: 4px;
       height: 36px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
       height: 26px;
       position: absolute;
       top: 5px !important;
       right: 1px;
       width: 20px;
}
</style>
    <div class="container" style="margin-top:20px;">
          <div class="row">
               <div class="col-md-10" >
                 <h3 style="text-align:center">Point Of Sales System</h3>
               </div>
          </div><br/>
          <div class="row">
              <div class="col-md-10" id="content">
                <form id="receipt-form">
                    <div class="row">
                        <div class="col-md-4">
                                <input type="text" class="form-control" name="receipt_no" placeholder="Receipt Number">
                                <span style="color:red" id="receipt_err"></span><br/>
                        </div>
                        <div class="col-md-4 ">
                                <select class="js-data-example-ajax form-control sel_item">
                                </select>
                        </div>
                        <div class="col-md-4">
                                <a href="{{'itemset'}}" class="btn btn-success float-right">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Create Set
                                </a>
                                <a href="{{'item'}}" class="btn btn-success float-right" style="margin-right:5%">
                                   <i class="fa fa-plus" aria-hidden="true"></i> Create Item
                                </a>
                        </div>
                    </div><br/>
                  <table class="table  table-hover table-bordered" id="receipt">
                      <thead>
                          <tr>
                              <th>No.</th>
                              <th>Item Name</th>
                              <th style="width:24%;">Price</th>
                              <th style="width:12%;">Qty</th>
                              <th style="width:24%;">Amount</th>
                              <th style="width:4%;">-</th>
                          </tr>
                      </thead>
                      <tbody>
                          
                      </tbody>
                      <tfoot>
                            <tr>
                                <td colspan="3"></td>
                                <td>Sub Total</td>
                                <td><input type="text" class="form-control" name="sub_total" id="sub-total" value="00.00"  readonly></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td>Discount</td>
                                <td><input type="text" class="form-control" value="0" name="disc" id="disc"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td>Grand Total</td>
                                <td><input type="text" class="form-control" value="00.00" name="g_total" id="g-total" readonly></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td>Cash</td>
                                <td><input type="text" class="form-control" value="00.00" name="cash" id="cash" readonly></td>
                                <td></td>
                            </tr>
                      </tfoot>
                  </table>

                  <button type="submit" id="checkout" class="btn btn-success float-right">
                      <i class="fa fa-money" aria-hidden="true"></i> CheckOut
                  </button>
             </form>
              </div>
          </div><br/>
    </div>

    <script>
       
       $(document).ready(function(){
           var sub_total=0;
           checkItem();
           $('.sel_item').select2({
                placeholder: 'Select Item',
                allowClear: true,
                ajax: {
                    url: 'api/autocomplete',
                    processResults: function (response) {
                        var itemArr=[]
                        var data= response.data;
                        var item={};
                        data.forEach(e => {
                            item={
                                'id' : e.id,
                                'text' : e.name
                            }
                            itemArr.push(item);
                        });
        
                    // Tranforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: itemArr
                    };
                    }
                }
            });

             $('.sel_item').change(function(){
                 var id= $(this).val();
                 $('#checkout').show();
                 var len =$('table > tbody > tr').length +1;
                 $.ajax({
                        type: 'get',
                        url: 'api/autocomplete/'+id ,
                        dataType: 'json',
                        success: function( response ){
                               var existed_item=$("tr[key='"+response.data.id+"']");

                               if(existed_item.length==0){// haven't appended item
                                        var tr="<tr key='"+response.data.id+"'><td>"+len+"</td>"+
                                            "<td>"+response.data.name+"</td>"+
                                            "<td><input type='text' id='price'class='form-control' value='"+response.data.price+"' readonly></td>"+
                                            "<td><input type='text' id='qty' name='qty[]' value='1' class='form-control'></td>"+
                                            "<td><input type='text' id='amount' value='"+response.data.price+"' class='form-control' readonly></td>"+
                                            "<td><button id='remove' class='btn btn-link btn-sm' >X</button>"+
                                            "</td><input type='hidden' name='id[]' value='"+response.data.id+"'></tr>";
                                    $('table#receipt > tbody').append(tr);
                                    calculation();
                                    fetchItem();
                               }else{// have appended item
                                      var q=existed_item.find('td input#qty').val();
                                      var am=existed_item.find('td input#amount').val();
                                      existed_item.find('td input#qty').val( parseInt(q)+1 );
                                      existed_item.find('td input#amount').val( parseFloat(am) + parseFloat(response.data.price));

                                      calculation();
                               }
                              
                        }
                 });
             });

             $('#disc').keyup(function(){
                 var dis=$(this).val();
                     dis=(dis==0) ? 0: dis;
                 var subTotal=$('#sub-total').val();
                 var grandTotal=parseFloat(subTotal)-(parseFloat(subTotal)*parseInt(dis)/100);
                 $('#g-total').val(parseFloat(grandTotal).toFixed(2));
                 $('#cash').val(parseFloat(grandTotal).toFixed(2));
             });

             $('#receipt-form').submit(function(e){
                e.preventDefault();
                $.ajax({
                        type: 'post',
                        url: 'api/receipt',
                        data: $(this).serialize(),
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function (response) {
                            if(response.status){
                                window.location='api/receipt/'+response.id;
                            }
                        },
                        error : function(error){
                            if(error.responseJSON.errors.receipt_no==undefined){
                                $('#receipt_err').html('');
                            }else{
                                $('#receipt_err').html('*'+error.responseJSON.errors.receipt_no);
                            }
                        }
                    });

             })
        });

        function fetchItem(){
            $( "table#receipt > tbody > tr " ).each( function() {
                    var tr = $(this);
                    var price=tr.find('td input#price').val();
                    var key = tr.attr('key');
                    tr.find('td input#qty').keyup(function(){   // for edit button
                        var qty= $(this).val() ;
                        var total=(qty=='') ? 0 : parseFloat(price)* parseInt(qty);
                        tr.find('td input#amount').val(parseFloat(total).toFixed(2));

                         calculation();
                   });

                   tr.find('td button#remove').click(function(){
                         $(this).closest('tr').remove();
                         calculation();
                         checkItem();
                   })
            })
        }

        function calculation(){
            var sub_total=0;
            $( "table#receipt > tbody > tr " ).each( function(){
                var amount=$(this).find('td input#amount').val();
                var disc=$('#disc').val();
                    disc=(disc=='') ? 0 : disc;
                var grand_total=$('#g-total').val();
                    sub_total +=parseFloat(amount);
                    grand_total=parseFloat(sub_total) - ( parseFloat(sub_total) * parseInt(disc)/100)
                $('#sub-total').val(parseFloat(sub_total).toFixed(2));
                $('#g-total').val(parseFloat(grand_total).toFixed(2));
                $('#cash').val(parseFloat(grand_total).toFixed(2));
            })
        }

        function checkItem(){
            console.log();
             if( $('table > tbody > tr').length == 0){
                 $('#sub-total').val('00.00');
                 $('#g-total').val('00.00');
                 $('#disc').val('0');
                 $('#cash').val('00.00');
                 $('#checkout').hide();

             }
        }

    </script>
@endsection