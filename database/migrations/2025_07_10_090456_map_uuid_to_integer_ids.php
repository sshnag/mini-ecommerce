<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        // Tables to convert
        $tables = ['order_items', 'reviews', 'carts'];

        foreach ($tables as $table) {
            // 1. First remove foreign key constraint if exists
            $this->dropForeignKeyIfExists($table, 'product_id');

            // 2. Add temporary integer column
            if (!Schema::hasColumn($table, 'new_product_id')) {
                DB::statement("ALTER TABLE $table ADD COLUMN new_product_id BIGINT UNSIGNED NULL");
            }

            // 3. Get first product ID as fallback (since we can't map UUIDs anymore)
            $defaultProductId = DB::table('products')->value('id') ?? 1;

            // 4. Set all records to default product ID
            DB::table($table)->update(['new_product_id' => $defaultProductId]);

            // 5. Drop old UUID column
            DB::statement("ALTER TABLE $table DROP COLUMN product_id");

            // 6. Rename new column
            DB::statement("ALTER TABLE $table CHANGE new_product_id product_id BIGINT UNSIGNED NOT NULL");

            // 7. Add index (foreign key will be added in next migration)
            DB::statement("ALTER TABLE $table ADD INDEX (product_id)");
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

        if ($constraint && $constraint->CONSTRAINT_NAME) {
            DB::statement("ALTER TABLE $tableName DROP FOREIGN KEY `{$constraint->CONSTRAINT_NAME}`");
        }
    }

    public function down()
    {
        // Not implementing rollback as this is a destructive operation
    }
};
