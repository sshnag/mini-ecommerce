<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $tables = [
            'order_items',
            'reviews',
            'carts'
        ];

        foreach ($tables as $tableName) {
            // Disable foreign key checks temporarily
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // Try to drop foreign key (will fail silently if not exists)
                try {
                    $table->dropForeign(['product_id']);
                } catch (\Exception $e) {
                    // Foreign key didn't exist, continue
                }

                // Change column type to UUID
                $table->uuid('product_id')->change();

                // Add new foreign key constraint
                $table->foreign('product_id')
                      ->references('id')
                      ->on('products')
                      ->onDelete('cascade');
            });

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down()
    {
        // Optional rollback logic
    }
};
