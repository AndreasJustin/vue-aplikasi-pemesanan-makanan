<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::select('id','customer_name','table_no','order_date','status','total_price')->get();
        
        return response(['data' => $orders], 200);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);

        return $order->loadMissing('orderDetail:order_id,price,item_id', 'orderDetail.item:id,name,price', 'waitress:id,name', 'cashier:id,name');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'table_no' => 'required|min:1|max:5',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['customer_name', 'table_no']);
            $data['order_date'] = date('Y-m-d H:i:s');
            $data['status'] = 'ordered';
            $data['total_price'] = 10000;
            $data["waitress_id"] = Auth::user()->id;
            $data['items'] = $request->items;

            $order = Order::create($data);

            collect($data['items'])->each(function($itemId) use ($order) {
                $item = Item::findOrFail($itemId);
                OrderDetail::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'price' => $item->price,
                ]);
            });
            // edit total dari order
            $order->total_price = $order->sumOrderPrice();
            $order->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response($th);
        }
        return response(['data' => $order], 201);
    }
}
