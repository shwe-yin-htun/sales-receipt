<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\ReceiptDetail;
use Illuminate\Http\Request;
use DB;

class ReceiptController extends Controller
{
    public function __construct()
    {
        $this->receipt= new Receipt();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('templates.sales');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $details=[];
        $request->validate([
            'receipt_no'=>'required|unique:receipts'
        ]);
      
        $receipt_id=$this->receipt->insertGetId([
                                  'receipt_no'=>$request->receipt_no,
                                  'discount'=>$request->disc,
                                  'grand_total'=>$request->g_total,
                                  'cash'=>$request->cash,
                           ]);
        
        for ($i=0; $i < count($request->id) ; $i++) { 
            $detail=[
                'receipt_id'=>$receipt_id,
                'item_id'=>$request->id[$i],
                'qty'=>$request->qty[$i],

            ];

            array_push($details,$detail);
        }

        $result=ReceiptDetail::insert($details);
        return response()->json(['status'=>$result,'id'=>$receipt_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function show(Receipt $receipt)
    {
        $sets=[];
        $items=DB::table('items')->leftJoin('receipt_details','items.id','=','receipt_details.item_id')
                                 ->where('receipt_details.receipt_id','=',$receipt->id)
                                 ->get();
        
        foreach($items as $item){
            if($item->is_set==1){
                $item_set=DB::table('items')->leftJoin('item_set','items.id','=','item_set.item_id')
                                           ->where('item_set.set_id','=',$item->item_id)
                                           ->get();
                    $set=[
                        'items'=>$item,
                        'item_set'=>$item_set->all()
                    ];
               
            }else{
                 $set=[
                     'items'=>$item,
                     'item_set'=>0
                 ];
            }
            array_push($sets,$set);
        }
        return view('templates.receipt',['items'=>$sets,'receipt'=>$receipt]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function edit(Receipt $receipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $receipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receipt $receipt)
    {
        //
    }
}
