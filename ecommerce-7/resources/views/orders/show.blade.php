@extends('template.layouts')
@section('title', 'Order Details')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 pt-2">
            <h1>Order Details</h1>
            <p>Order Number: <b>{{ $order->order_number }}</b></p>
            <p>Order Date: <b>{{ $order->created_at->format('d M Y H:i') }}</b></p>
            <p>Shipping Address: <b>{{ $order->shipping_address }}</b></p>
        </div>
        <div class="col-12">
            <h3>Order Items</h3>
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
                    @foreach($order->orderItems as $item)
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
            <h4>Total: Rp {{ number_format($order->orderItems->sum(fn($item) => $item->product->price * $item->quantity), 0, ',', '.') }}</h4>

            @php
            $text = "Halo, saya ingin menanyakan tentang pesanan saya dengan nomor order " . $order->order_number . ". Apakah pesanan saya sudah diproses?";
            $whatsappLink = "https://wa.me/1234567890?text=" . urlencode($text);
            @endphp
            {{-- WhatsApp Support Button --}}
            <a href="{{ $whatsappLink }}" class="btn btn-success">
                Contact Support via WhatsApp
            </a>

            {{-- Back Button --}}
            <a href="{{ route('home') }}" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>  
</div>
@endsection