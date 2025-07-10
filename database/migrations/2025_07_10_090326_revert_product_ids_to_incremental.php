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
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // 1. Drop foreign key constraint if exists
            $this->dropForeignKeyIfExists($tableName, 'product_id');

            // 2. Change UUID column to unsigned big integer
            DB::statement("ALTER TABLE $tableName MODIFY product_id BIGINT UNSIGNED NOT NULL");

            // 3. Add new foreign key constraint
            DB::statement("
                ALTER TABLE $tableName
                ADD CONSTRAINT {$tableName}_product_id_foreign
                FOREIGN KEY (product_id) REFERENCES products(id)
                ON DELETE CASCADE
            ");

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // 4. Convert products table primary key
        Schema::table('products', function (Blueprint $table) {
            $table->dropPrimary('id');
            $table->unsignedBigInteger('id')->change();
            $table->bigIncrements('id')->change();
        });
    }

    public function down()
    {
        // Optional: Implement rollback if needed
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

        if ($constraint && $constraint->CONSTRAINT_NAME) {
            DB::statement("ALTER TABLE $tableName DROP FOREIGN KEY `{$constraint->CONSTRAINT_NAME}`");
        }
    }
};
