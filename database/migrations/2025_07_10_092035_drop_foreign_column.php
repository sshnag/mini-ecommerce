<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
                        $table->dropColumn('product_id');

        });
          Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
                                    $table->dropColumn('product_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
