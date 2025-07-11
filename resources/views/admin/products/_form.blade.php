<div class="card-body">
    <div class="form-group">
        <label for="name">Product Name</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name ?? '') }}">
    </div>

    <div class="form-group">
        <label for="price">Price</label>
        <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price', $product->price ?? '') }}">
    </div>

    <div class="form-group">
        <label for="image">Product Image</label>
        <input type="file" name="image" id="image" class="form-control-file">
        @if(isset($product) && $product->image)
            <img src="{{ asset('storage/' . $product->image) }}" width="100" class="mt-2">
        @endif
    </div>
</div>
