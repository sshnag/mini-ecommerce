<?php
namespace App\Services;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
class ProductService{
   /**
     * Creates a new product with a generated custom_id and optional image upload.
     *
     * @param array $data
     * @param Request|null $request
     * @return Product
     */
    public function createProduct(array $data, ?Request $request = null): Product
    {
        if ($request && $request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request);
        }

        return Product::create($data + [
            'custom_id' => $this->generateProductId(),
        ]);
    }

    /**
     * Updates an existing product and handles image replacement.
     *
     * @param Product $product
     * @param array $data
     * @param Request|null $request
     * @return Product
     */
    public function updateProduct(Product $product, array $data, ?Request $request = null): Product
    {
        if ($request && $request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request, $product->image);
        }

        $product->update($data);
        return $product;
    }

    /**
     * Uploads image to storage and deletes old one if exists.
     *
     * @param Request $request
     * @param string|null $existingImage
     * @return string
     */
    public function uploadImage(Request $request, ?string $existingImage = null): string
    {
        if ($existingImage && Storage::exists('public/' . $existingImage)) {
            Storage::delete('public/' . $existingImage);
        }

        return $request->file('image')->store('products', 'public');
    }

    /**
     * Generates a  custom product ID.
     *
     * @return string
     */
    private function generateProductId(): string
    {
        $lastId = Product::max('id') ?? 0;
        return 'PROD-' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
    }
}
