@extends('adminlte::page')

@section('title', 'Product Details')

@section('content_header')
    <h1 class="m-0 text-dark">Product Details</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">{{ $product->name }}</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Category:</strong> {{ $product->category->name }}</p>
                    <p><strong>Description:</strong> {{ $product->description }}</p>
                    <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                    <p><strong>Stock:</strong> {{ $product->stock }}</p>
                </div>
                <div class="col-md-6 text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="img-fluid img-thumbnail"
                             style="max-height: 300px;">
                    @else
                        <p class="text-muted">No image available.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>
@endsection
