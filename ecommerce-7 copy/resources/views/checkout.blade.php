@extends('template.layouts')
@section('title', 'Checkout Page')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 pt-2">
            <h1>Checkout Page</h1>
            <p>Welcome to the checkout page.</p>
        </div>
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($cartItems) && $cartItems->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Total Amount --}}
                        <h4>Total: Rp {{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity), 0, ',', '.') }}</h4>

                        {{-- Checkout Button --}}
                        <form method="POST" action="{{ route('make.order') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="address" class="form-label">Shipping Address</label>
                                <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Confirm Checkout</button>
                        </form>
                    @else
                        <p>Your cart is empty. Please add some products to proceed to checkout.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection