<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemSet;
use App\Models\ReceiptDetail;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->item= new Item();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=$this->item->paginate(5);
        return view('templates.items',['items'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'price'=>'required|numeric'
        ]);

        $result=$this->item->create([
                        'name'=>$request->name,
                        'price'=>$request->price,
                        'is_set'=>0
                ]);

        return response()->json(['method'=>'create','data'=>$result]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return response()->json(['data'=>$item]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name'=>'required',
            'price'=>'required|numeric'
        ]);
        
        $result=$item->update([
                    'name'=>$request->name,
                    'price'=>$request->price,
                ]);
      //  $this->item->where('id',$item->id)->update();
        return response()->json(['status'=> $result,'method'=>'update','data'=>$request->all(),'id'=>$item->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        ReceiptDetail::where('item_id',$item->id)->delete();
        $result=$item->delete();

        return response()->json(['data'=>$result]);
    }

    public function create_set(){
        $items=$this->item->where('is_set',0)->get();
        return view('templates.create_set',['items'=>$items]);
    }

    public function itemset_store(Request $request){
        $request->validate([
            'name'=>'required',
            'price'=>'required|numeric'
        ]);
        
        $set=[];

        $set_id=$this->item->insertGetId([
                                'name'=>$request->name,
                                'price'=>$request->price,
                                'is_set'=>1
                            ]);
                
        foreach ($request->item_id as $id) {
             $item=[
                 'item_id'=>$id,
                 'set_id'=>$set_id
             ];

             array_push($set,$item);
        }
        $result=ItemSet::insert($set);
        return response()->json(['status'=>$result]);
    }
}
