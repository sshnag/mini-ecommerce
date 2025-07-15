@extends('adminlte::page')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <h1>Edit Product</h1>

    <form method="POST" action="{{ route('supplier.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Name</label>
            <input name="name" type="text" class="form-control" value="{{ old('name', $product->name) }}" required>
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Price</label>
            <input name="price" type="number" step="0.01" class="form-control" value="{{ old('price', $product->price) }}" required>
            @error('price')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Stock</label>
            <input name="stock" type="number" class="form-control" value="{{ old('stock', $product->stock) }}" required>
            @error('stock')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Current Image</label><br>
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" width="100" alt="{{ $product->name }}">
            @else
                <p>No image uploaded.</p>
            @endif
        </div>

        <div class="form-group">
            <label>Change Image</label>
            <input name="image" type="file" class="form-control">
            @error('image')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <button class="btn btn-primary" type="submit">Update</button>
    </form>
</div>
@endsection
