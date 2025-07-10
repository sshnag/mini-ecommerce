<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Safely drop foreign keys
        try {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
            });
        } catch (\Throwable $e) {
            // foreign key doesn't exist, ignore
        }

        try {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
            });
        } catch (\Throwable $e) {}

        try {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
            });
        } catch (\Throwable $e) {}

        // Drop UUID column
        Schema::table('products', function (Blueprint $table) {
    // Safely drop primary key if it exists
    try {
        DB::statement('ALTER TABLE products DROP PRIMARY KEY');
    } catch (\Throwable $e) {
        // Ignore if it doesn't exist
    }

    // Drop UUID column
    if (Schema::hasColumn('products', 'id')) {
        $table->dropColumn('id');
    }
});

// Add auto-incrementing numeric ID again
Schema::table('products', function (Blueprint $table) {
    $table->id()->first();});
        // Recreate foreign keys with new bigint id
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Add reverse logic if needed
    }
};
