@extends('master')
@section('content')
     <div class="container" style="margin-top:20px;">
            <div class="row">
               <div class="col-md-3">
                    <input type="text" id="set-name" class="form-control" placeholder="Set-Name">
                    <span style="color:red" id="name_err"></span>
               </div>
            </div><br/>
            <div class="row">
               <div  class="col-md-3">
                    @foreach($items as $item)
                        <input type="checkbox" id="{{$item->id}}" price="{{$item->price}}"> {{$item->name}} <br/>
                    @endforeach
                    <br/><input type="text" class="form-control" value="00.00" id="price" plcaeholder="Set's Price">
                    <span style="color:red" id="price_err"></span><br/>
                    <br/><input type="button" class="btn btn-success" id="create-set" value="Create">
               </div>
               
            </div><br/>
     </div>
    <script>
          $(document).ready(function(){
                var ids=[];
                var total_price=0;
                $('input[type="checkbox"]').click(function(){
                    var price=$(this).attr('price');
                    var id=$(this).attr('id');

                    if($(this).prop("checked") == true){
                        ids.push(id);
                        total_price +=parseFloat(price);
                    }else{
                        var index = ids.indexOf(id);
                            ids.splice( index, 1 );
                        total_price -=parseFloat(price);
                       
                    }
                    (total_price==0) ? $('#price').val('00.00') : $('#price').val(total_price);
                });

              $('#create-set').click(function(){
                    var set={
                        'name' : $('#set-name').val(),
                        'price': $('#price').val(),
                        'item_id' :  ids
                    }
                    console.log(set);
                    $.ajax({
                            type: 'post',
                            url: 'api/itemset_store' ,
                            dataType: 'json',
                            data: set,
                            success: function( response ){
                                if(response.status){
                                    window.location = "item";
                                }
                            },
                            error : function(error){
                                console.log(error);
                                if(error.responseJSON.errors.name==undefined){
                                    $('#name_err').html('');
                                }else{
                                    $('#name_err').html('*'+error.responseJSON.errors.name);
                                }
                                if(error.responseJSON.errors.price==undefined){
                                    $('#price_err').html('');
                                }else{
                                    $('#price_err').html('*'+error.responseJSON.errors.price);
                                }
                            }
                     })
               })
          })
    </script>
        
@endsection