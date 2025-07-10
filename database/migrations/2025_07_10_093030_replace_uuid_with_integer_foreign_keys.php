<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        $tables = ['order_items', 'reviews', 'carts'];

        foreach ($tables as $table) {
            // 1. Drop existing UUID column and foreign key
            if (Schema::hasColumn($table, 'product_id')) {
                // Disable foreign key checks temporarily
                DB::statement('SET FOREIGN_KEY_CHECKS=0');

                // Drop foreign key constraint if exists
                $this->dropForeignKeyIfExists($table, 'product_id');

                // Drop the UUID column
                Schema::table($table, function ($table) {
                    $table->dropColumn('product_id');
                });

                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }

            // 2. Add new unsigned big integer column
            if (!Schema::hasColumn($table, 'product_id')) {
                Schema::table($table, function ($table) {
                    $table->unsignedBigInteger('product_id')->after('id');
                });
            }

            // 3. Set default value (first product ID)
            $defaultProductId = DB::table('products')->value('id') ?? 1;
            DB::table($table)->update(['product_id' => $defaultProductId]);

            // 4. Add foreign key constraint (will be done in next migration)
        }
    }

    protected function dropForeignKeyIfExists($tableName, $columnName)
    {
        $constraint = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '$tableName'
            AND COLUMN_NAME = '$columnName'
            AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");

        if ($constraint) {
            DB::statement("ALTER TABLE $tableName DROP FOREIGN KEY `{$constraint->CONSTRAINT_NAME}`");
        }
    }

    public function down()
    {
        // Not recommended to rollback this destructive operation
    }
};
