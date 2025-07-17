@extends('adminlte::page')

@section('title', 'Product Details')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Product Details</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Products
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-secondary">
            <h3 class="card-title font-weight-bold">{{ $product->name }}</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Product Details Column -->
                <div class="col-md-6">
                    <div class="product-details">
                        <div class="detail-item mb-3">
                            <h5 class="detail-label text-muted">Category</h5>
                            <p class="detail-value">{{ $product->category->name }}</p>
                        </div>

                        <div class="detail-item mb-3">
                            <h5 class="detail-label text-muted">Description</h5>
                            <p class="detail-value">{{ $product->description ?: 'No description provided' }}</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <h5 class="detail-label text-muted">Price</h5>
                                    <p class="detail-value font-weight-bold text-white">
                                        ${{ number_format($product->price, 2) }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <h5 class="detail-label text-muted">Stock</h5>
                                    <p class="detail-value">
                                        <span class="badge {{ $product->stock > 0 ? 'badge-info' : 'badge-danger' }}">
                                            {{ $product->stock }} in stock
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Image Column -->
                <div class="col-md-6">
                    <div class="product-image-container text-center">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="img-fluid rounded shadow"
                                 style="max-height: 300px; object-fit: contain;">
                        @else
                            <div class="no-image-placeholder bg-light p-5 rounded">
                                <i class="fas fa-image fa-4x text-muted mb-3"></i>
                                <p class="text-muted">No image available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between">
                <div>
                    <small class="text-muted">
                        Last updated: {{ $product->updated_at->format('M d, Y h:i A') }}
                    </small>
                </div>
                <div>
                    <a href="{{ route('admin.products.edit', $product->custom_id) }}"
                       class="btn btn-primary btn-sm">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('css/admin/details.css') }}">
@endpush
