@extends('template.layouts')
@section('title', $product->name) {{-- Menggunakan nama produk sebagai judul halaman --}}
@section('content')
    <div class="container my-5">
        <div class="row g-4">
            <div class="col-md-6">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid">
            </div>
            <div class="col-md-6">
                <h1>{{ $product->name }}</h1>
                <p class="text-muted">Category: {{ $product->product_category->name }}</p>
                <h3>Rp{{ number_format($product->price, 0, ',', '.') }}</h3>
                <p>{{ $product->description }}</p>
                <a href="#" class="btn btn-primary">Add to Cart</a>
            </div>
            <div class="col-12">
                <h4>Recommended Products</h4>
                <div class="row g-4">
                    @foreach($product_recommendations as $recommendation)
                        <div class="col-md-3" d-flex justify-content-center>
                            <x-product-card 
                                :name="$recommendation['name']" 
                                :price="$recommendation['price']" 
                                :image="$recommendation['image']"
                                :slug="$recommendation['slug']" 
                            />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection