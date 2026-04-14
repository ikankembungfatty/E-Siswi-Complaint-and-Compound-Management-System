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
            $table->enum('role', ['student', 'warden', 'hepa_staff', 'technician'])->default('student')->after('email');
            $table->string('student_id')->nullable()->after('role'); // Matric number
            $table->string('phone')->nullable()->after('student_id');
            $table->string('block')->nullable()->after('phone'); // Hostel block
            $table->string('room')->nullable()->after('block'); // Room number
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'student_id', 'phone', 'block', 'room']);
        });
    }
};
