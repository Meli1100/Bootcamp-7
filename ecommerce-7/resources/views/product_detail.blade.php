@extends('template.layouts')

@section('title', 'Product Detail Page')

@section('content')
@push('js')
<script>
    alert('Welcome to the product detail page!');
</script>
@endpush
    <h1>Product Detail Page</h1>
    <p>Welcome to the product detail page.</p>

@push('css')
    <style>
        h1 {
            color: green;
        }
    </style>
@endpush
@endsection