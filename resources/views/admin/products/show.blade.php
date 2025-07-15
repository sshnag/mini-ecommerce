@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Product Details - {{ $product->name }}</h1>

    <p><strong>Category:</strong> {{ $product->category->name }}</p>
    <p><strong>Description:</strong> {{ $product->description }}</p>
    <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
    <p><strong>Stock:</strong> {{ $product->stock }}</p>

    @if($product->image)
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-width: 300px;">
    @endif

    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-3">Back to list</a>
</div>
@endsection
