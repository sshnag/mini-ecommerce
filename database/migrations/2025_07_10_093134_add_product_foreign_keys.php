<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        $tables = ['order_items', 'reviews', 'carts'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
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
