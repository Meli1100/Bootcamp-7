@extends('template.layouts')
@section('title', 'Home Page')
@section('content')
    <div class="container my-5">
        <div class="row g-4">
            <div class="col-12">
                <h1 class="text-center">Welcome to Our Store</h1>
                <p class="text-center text-muted">Find the best products at the best prices</p>
            </div>
            @forelse($products as $product)
                <div class="col-md-4">
                    <x-product-card 
                        :name="$product['name']" 
                        :price="$product['price']" 
                        :image="$product['image']"
                        :slug="$product['slug']" 
                    />
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center">No products found.</p>
                </div>
            @endforelse
            <div class="d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

