@extends('template.layouts')
@section('title', 'Home Page')
@section('content')
    <div class="container my-5">
        <h1 class="mb-4">Products</h1>
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-md-4">
                    <x-product-card 
                        :name="$product['name']" 
                        :price="$product['price']" 
                        :image="$product['image']" 
                    />
                </div>
            @endforeach
        </div>
    </div>
@endsection

