<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // First check if the column exists
            if (!Schema::hasColumn('products', 'id')) {
                // Add the column and make it primary in one operation
                $table->bigIncrements('id')->first();
            } else {
                // If column exists but isn't primary, make it primary
                DB::statement('ALTER TABLE products MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Only attempt to drop if the column exists
            if (Schema::hasColumn('products', 'id')) {
                // For MySQL, you need to remove auto_increment first
                DB::statement('ALTER TABLE products MODIFY id BIGINT UNSIGNED');
                $table->dropPrimary(['id']);
                $table->dropColumn('id');
            }
        });
    }
};
