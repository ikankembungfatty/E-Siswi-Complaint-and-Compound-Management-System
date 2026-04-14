<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support ALTER column or DROP constraint easily.
        // We need to recreate the table with the new schema.
        // Step 1: Disable foreign keys
        DB::statement('PRAGMA foreign_keys = OFF');

        // Step 2: Create new table with updated schema
        DB::statement('CREATE TABLE complaints_new (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            category_id INTEGER NOT NULL,
            assigned_to INTEGER NULL,
            title VARCHAR NOT NULL,
            description TEXT NOT NULL,
            location VARCHAR NULL,
            image VARCHAR NULL,
            status VARCHAR CHECK(status IN (\'processing\', \'in_progress\', \'completed\')) NOT NULL DEFAULT \'processing\',
            priority VARCHAR CHECK(priority IN (\'low\', \'medium\', \'high\')) NOT NULL DEFAULT \'medium\',
            resolution_notes TEXT NULL,
            completed_at DATETIME NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (category_id) REFERENCES complaint_categories(id),
            FOREIGN KEY (assigned_to) REFERENCES users(id)
        )');

        // Step 3: Copy data with status mapping
        DB::statement("INSERT INTO complaints_new (id, user_id, category_id, assigned_to, title, description, location, image, status, priority, resolution_notes, completed_at, created_at, updated_at)
            SELECT id, user_id, category_id, assigned_to, title, description, location, image,
                CASE status
                    WHEN 'submitted' THEN 'processing'
                    WHEN 'resolved' THEN 'completed'
                    WHEN 'rejected' THEN 'completed'
                    ELSE status
                END,
                priority, resolution_notes, resolved_at, created_at, updated_at
            FROM complaints");

        // Step 4: Drop old table and rename new one
        DB::statement('DROP TABLE complaints');
        DB::statement('ALTER TABLE complaints_new RENAME TO complaints');

        // Step 5: Re-enable foreign keys
        DB::statement('PRAGMA foreign_keys = ON');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement('CREATE TABLE complaints_old (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            category_id INTEGER NOT NULL,
            assigned_to INTEGER NULL,
            title VARCHAR NOT NULL,
            description TEXT NOT NULL,
            location VARCHAR NULL,
            image VARCHAR NULL,
            status VARCHAR CHECK(status IN (\'submitted\', \'in_progress\', \'resolved\', \'rejected\')) NOT NULL DEFAULT \'submitted\',
            priority VARCHAR CHECK(priority IN (\'low\', \'medium\', \'high\')) NOT NULL DEFAULT \'medium\',
            resolution_notes TEXT NULL,
            resolved_at DATETIME NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (category_id) REFERENCES complaint_categories(id),
            FOREIGN KEY (assigned_to) REFERENCES users(id)
        )');

        DB::statement("INSERT INTO complaints_old (id, user_id, category_id, assigned_to, title, description, location, image, status, priority, resolution_notes, resolved_at, created_at, updated_at)
            SELECT id, user_id, category_id, assigned_to, title, description, location, image,
                CASE status
                    WHEN 'processing' THEN 'submitted'
                    WHEN 'completed' THEN 'resolved'
                    ELSE status
                END,
                priority, resolution_notes, completed_at, created_at, updated_at
            FROM complaints");

        DB::statement('DROP TABLE complaints');
        DB::statement('ALTER TABLE complaints_old RENAME TO complaints');

        DB::statement('PRAGMA foreign_keys = ON');
    }
};
