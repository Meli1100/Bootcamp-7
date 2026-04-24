<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        // Logic for processing checkout and creating an order
        if(Auth::check()) {
            $user = Auth::user();
            $cartItems = Cart::with('product')
                            ->where('user_id', Auth::id())
                            ->get();
            $totalPrice = $cartItems->sum(function($item) {
                return $item->quantity * $item->product->price;
            });
            return view('checkout', compact('cartItems', 'totalPrice'));
        } else {
            return redirect()->route('login')->with('error', 'Please login to proceed to checkout.');
        }
    }
}
