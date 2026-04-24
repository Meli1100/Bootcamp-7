<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'user_id' => auth()->id(),
            'status' => 'pending',
            'shipping_address' => $request->address,
            'total_amount' => Cart::where('user_id', Auth::id())->with('product')->get()->sum(function($cartItem) {
                return $cartItem->quantity * $cartItem->product->price;
            }),
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        foreach ($cartItems as $cartItem) {
            $order->orderItems()->create([
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
            ]);
        }

        // Clear the cart after creating the order
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('orders.show', $order->order_number)->with('success', 'Order placed successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $order_number)
    {
        $order = Order::where('order_number', $order_number)
                        ->with('orderItems.product')
                        ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
