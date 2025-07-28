<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class StockValidationService
{
    /**
     * Check if cart items are still in stock
     * @return array
     */
    public function validateCartStock()
    {
        $cartItems = $this->getUserCart();
        $outOfStockItems = [];
        $lowStockItems = [];

        foreach ($cartItems as $item) {
            $product = $item->product;
            
            // Check if product is still available
            if ($product->stock <= 0) {
                $outOfStockItems[] = [
                    'product' => $product,
                    'cart_quantity' => $item->quantity,
                    'available_stock' => 0
                ];
            }
            // Check if requested quantity is available
            elseif ($item->quantity > $product->stock) {
                $lowStockItems[] = [
                    'product' => $product,
                    'cart_quantity' => $item->quantity,
                    'available_stock' => $product->stock
                ];
            }
        }

        return [
            'out_of_stock' => $outOfStockItems,
            'low_stock' => $lowStockItems,
            'has_issues' => !empty($outOfStockItems) || !empty($lowStockItems)
        ];
    }

    /**
     * Reserve stock for a user's cart (temporary hold)
     * @param int $userId
     * @return bool
     */
    public function reserveStock($userId)
    {
        return DB::transaction(function () use ($userId) {
            $cartItems = Cart::with('product')
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                return false;
            }

            foreach ($cartItems as $item) {
                $product = $item->product;
                
                if ($item->quantity > $product->stock) {
                    throw new Exception(
                        "Product {$product->name} is out of stock. Only {$product->stock} items available."
                    );
                }
            }

            // Create temporary stock reservations
            foreach ($cartItems as $item) {
                $item->product->decrement('stock', $item->quantity);
            }

            return true;
        });
    }

    /**
     * Release reserved stock (if order is cancelled)
     * @param int $userId
     * @return void
     */
    public function releaseStock($userId)
    {
        $cartItems = Cart::with('product')
            ->where('user_id', $userId)
            ->get();

        foreach ($cartItems as $item) {
            $item->product->increment('stock', $item->quantity);
        }
    }

    /**
     * Get user cart items
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getUserCart()
    {
        $query = Cart::with('product');

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', session()->getId());
        }

        return $query->get();
    }

    /**
     * Check if specific products are in stock
     * @param array $productIds
     * @return array
     */
    public function checkProductStock($productIds)
    {
        $products = Product::whereIn('id', $productIds)->get();
        $stockStatus = [];

        foreach ($products as $product) {
            $stockStatus[$product->id] = [
                'product' => $product,
                'in_stock' => $product->stock > 0,
                'available_stock' => $product->stock
            ];
        }

        return $stockStatus;
    }
} 