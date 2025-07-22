<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Creates a new product with validation and image handling
     * @param array $data Validated product data
     * @param UploadedFile|null $imageFile Uploaded image file
     * @return Product
     */
    public function createProduct(array $data, ?UploadedFile $imageFile = null): Product
    {
        // Handle image upload
        if ($imageFile) {
            $data['image'] = $this->storeImage($imageFile);
            $data['available_sizes'] = $data['available_sizes'] ?? null;

        }

        return Product::create($data + [
            'custom_id' => $this->generateProductId(),
        ]);
    }

    /**
     * Updates an existing product with image handling
     *
     * @param Product $product
     * @param array $data Validated product data
     * @param UploadedFile|null $imageFile New image file
     * @return Product
     */
    public function updateProduct(Product $product, array $data, ?UploadedFile $imageFile = null): Product
    {
        // Handle image update
        if ($imageFile) {
            $this->deleteImage($product->image);
            $data['image'] = $this->storeImage($imageFile);
            $data['available_sizes'] = $data['available_sizes'] ?? null;

        }

        $product->update($data);
        return $product;
    }

    /**
     * Deletes a product and its associated image
     *
     * @param Product $product
     * @return void
     */
    public function deleteProduct(Product $product): void
    {
        $this->deleteImage($product->image);
        $product->delete();
    }

    /**
     * Stores product image to disk
     *
     * @param UploadedFile $imageFile
     * @return string Stored image path
     */
    protected function storeImage(UploadedFile $imageFile): string
    {
        return $imageFile->store('products', 'public');
    }

    /**
     * Deletes product image from disk
     *
     * @param string|null $imagePath
     * @return void
     */
    protected function deleteImage(?string $imagePath): void
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    /**
     * Generates custom product ID
     *
     * @return string
     */
    protected function generateProductId(): string
    {
        $nextNumber = Product::withTrashed()->count() + 1;
        return 'PROD-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
