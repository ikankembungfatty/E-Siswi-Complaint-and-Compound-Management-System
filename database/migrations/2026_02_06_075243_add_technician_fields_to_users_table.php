<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('specialization')->nullable()->after('room');
            $table->boolean('is_available')->default(true)->after('specialization');
        });

        // Update the role enum to include technician
        // SQLite doesn't support altering enums, so we handle this in the model
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['specialization', 'is_available']);
        });
    }
};
