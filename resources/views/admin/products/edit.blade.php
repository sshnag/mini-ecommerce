@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>Edit Product</h2>
<form action="{{ route('admin.products.update', $product->custom_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                id="name"
                name="name"
                value="{{ old('name', $product->name) }}"
            >
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select
                name="category_id"
                id="category_id"
                class="form-select @error('category_id') is-invalid @enderror"
            >
                <option value="">-- Select Category --</option>
                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea
                name="description"
                class="form-control @error('description') is-invalid @enderror"
                rows="4"
            >{{ old('description', $product->description) }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input
                type="number"
                step="100"
                name="price"
                class="form-control @error('price') is-invalid @enderror"
                value="{{ old('price', $product->price) }}"
            >
            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input
                type="number"
                name="stock"
                class="form-control @error('stock') is-invalid @enderror"
                value="{{ old('stock', $product->stock) }}"
            >
            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input
                type="file"
                name="image"
                class="form-control @error('image') is-invalid @enderror"
            >
            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror

            @if ($product->image)
                <div class="mt-2">
                    <img
                        src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        style="max-width: 200px;"
                    >
                </div>
            @endif
        </div>

        <input type="hidden" name="user_id" value="{{ old('user_id', $product->user_id) }}">

        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>
@endsection
