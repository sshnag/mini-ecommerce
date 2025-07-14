<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
{
    $tables = ['order_items', 'reviews', 'carts'];

    foreach ($tables as $tableName) {
        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            // First add the column if it doesn't exist
            if (!Schema::hasColumn($tableName, 'product_id')) {
                $table->unsignedBigInteger('product_id')->after('id');
            }

            // Then add the foreign key
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }
}


    public function down()
    {
        $tables = ['order_items', 'reviews', 'carts'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['product_id']);
            });
        }
    }
};
