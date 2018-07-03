@extends('master')
@section('content')
<style>
#item-model{
    margin-left:40%;
}
</style>
</head>
<body>
    <div class="container" style="margin-top:20px;">
       <div class="row">
           <div class="col-md-10">
                <h4 style="text-align:center;">Item Lists</h4>
           </div>
       </div><br/>
      <div class="row">
        <div class="col-md-12">
            <a href="{{url('itemset')}}" class="btn btn-success float-right" id="create-set">
              <i class="fa fa-plus" aria-hidden="true"></i> Create Set
            </a>
            <button class="btn btn-success float-right" id="new-item" style="margin-right:20px;">
               <i class="fa fa-plus" aria-hidden="true"></i> New Item
            </button>
           
        </div>
        </div><br/>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover" id="item-lists">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Is_Set</th>
                            <th style="width:20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; ?>
                        @foreach($items as $item)
                            <tr key="{{$item->id}}">
                                <td>{{$i++}}</td>
                                <td id="td_name">{{$item->name}}</td>
                                <td id="td_price">{{$item->price}}</td>
                                <td>{{$item->is_set}}</td>
                                <td>
                                    <button class="btn btn-success" id="edit" data-id="{{$item->id}}">
                                        <i class="fa fa-edit" aria-hidden="true"></i> 
                                    </button>
                                    <button class="btn btn-danger" id="delete" data-id="{{$item->id}}">
                                        <i class="fa fa-trash" aria-hidden="true"></i> 
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $items->links() }}
            </div>
        </div>

        <!-- The Modal -->
        <div class="modal fade modal-sm " id="item-model">
            <div class="modal-dialog">
              <div class="modal-content">
            
                <!-- Modal Header -->
                <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="pwd">Item Name:</label>
                        <input type="text" class="form-control" id="name" >
                        <span style="color:red" id="name_err"></span>
                    </div>
                    <div class="form-group">
                        <label for="pwd">Price:</label>
                        <input type="text" class="form-control" id="price" >
                        <span style="color:red" id="price_err"></span>
                    </div>
                    <button class="btn btn-primary" id="save" data-id="">
                       <i class="fa fa-floppy-o" aria-hidden="true"></i> Save
                    </button>
                </div>
                
              </div>
            </div>
        </div>
  
    </div>

<script>  
     $(document).ready(function(){
       fetchItem();
       $('#new-item').click(function(){
            clear();
            $('#save').attr('data-id','');
            $("#item-model").modal();
            $('.modal-title').text('Create Item');
       });

       $('#save').click(function(){
            var id= $(this).attr('data-id');
            var url = (id!="" && id!=null) ? 'api/item/'+id : 'api/item';
            var type = (id!="" && id!=null) ? 'put' : 'post';

            var item={
                    'name' : $('#name').val(),
                    'price' :  $('#price').val()
                };
            
            $.ajax({
                type: type,
                url: url ,
                dataType: 'json',
                data: item,
                success: function( response ){
                    if(response.method=='create'){   // for creating new item
                        if(response.data.id!=undefined){
                            var count=$('table#item-lists > tbody > tr').length + 1;
                   
                            var tr = "<tr key='"+response.data.id+"'><td>"+ count +"</td>"+
                                    "<td>"+response.data.name+"</td>"+
                                    "<td>"+response.data.price+"</td>"+
                                    "<td>"+response.data.is_set+"</td>"+
                                    "<td><button class='btn btn-success' id='edit' data-id='"+response.data.id+"'>Edit</button>&nbsp;&nbsp;"+
                                    "<button class='btn btn-danger' id='delete' data-id='"+response.data.id+"'>Delete</button></td></tr>";

                           // clear();
                            $("#item-model").modal('hide');
                            $('table#item-lists > tbody').append(tr);
                            fetchItem();
                            
                        }
                    } // end of creating new item
                    else{ // for updating existed item
                        var tr = $("tr[key='"+response.id+"']"); 
                            tr.find('#td_name').text(response.data.name);
                            tr.find('#td_price').text(response.data.price);
                            $("#item-model").modal('hide');
                    }// end of updating existed item
                },
                error: function( error ){
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
       });
    }) ;

    function clear(){  // clear data 
           $('#name').val('');
           $('#price').val('');
           $('#name_err').html('');
           $('#price_err').html('');
         
    }

    function fetchItem(){
        $( "table#item-lists > tbody > tr " ).each( function() {
            $(this).find('td button#edit').click(function(){   // for edit button
                clear();
                var id= $(this).attr('data-id');
                $('#save').attr('data-id',id);
                $.ajax({
                    type: 'get',
                    url: 'api/item/'+id,
                    dataType: 'json',
                    success: function( response ){
                        console.log(response);
                        if(response.data.id!=undefined){
                            $("#item-model").modal('show');
                            $('.modal-title').text('Edit Item');
                            $('#name').val(response.data.name);
                            $('#price').val(response.data.price);
                        }
                    }
                });          
            });

            $(this).find('td button#delete').click(function(){  // for delete button
                if(confirm("Are you sure to delete ?")){
                    var id= $(this).attr('data-id');
                    var tr=$(this).closest('tr');
                    $.ajax({
                            type: 'delete',
                            url: 'api/item/'+id,
                            dataType: 'json',
                            success: function( response ){
                                if(response.data){
                                    tr.remove();
                                }else{
                                    alert('Something went wrong!');
                                } 
                            },
                            error : function(error){
                                console.log(error);
                            }
                    });         
                }
            })
       });    
    }
</script>

@endsection
