<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // SQLite: recreate table to update CHECK constraint on payment_method
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement('CREATE TABLE payments_new (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            compound_id INTEGER NOT NULL,
            transaction_id VARCHAR NOT NULL UNIQUE,
            amount DECIMAL(10,2) NOT NULL,
            payment_method VARCHAR CHECK(payment_method IN (\'online\', \'cash\', \'bank_transfer\', \'jompay\')) NOT NULL DEFAULT \'jompay\',
            status VARCHAR CHECK(status IN (\'pending\', \'completed\', \'failed\')) NOT NULL DEFAULT \'pending\',
            paid_at DATETIME NULL,
            notes TEXT NULL,
            receipt_image VARCHAR NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (compound_id) REFERENCES compounds(id)
        )');

        DB::statement("INSERT INTO payments_new SELECT * FROM payments");

        DB::statement('DROP TABLE payments');
        DB::statement('ALTER TABLE payments_new RENAME TO payments');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        // No rollback needed
    }
};
