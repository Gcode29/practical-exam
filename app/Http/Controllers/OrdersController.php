<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function store(Request $request) 
    {   
        $product = Products::where('id', $request->product_id)->first();
        
        if ($request->quantity > $product->available_stock){
            return response()->json([
                'message' => 'Failed to order this product due to unavailability of the stock'
            ], 400);
        }

        Orders::create([
            'product_id' => $request['product_id'],
            'quantity' => $request['quantity'],
        ]);

        $product->decrement('available_stock', $request->quantity);

        return response()->json([
            'message' => 'You have successfully ordered this product'
        ],201);
    }
}
