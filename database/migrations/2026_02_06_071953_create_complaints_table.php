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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Student who submitted
            $table->foreignId('category_id')->constrained('complaint_categories')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // Warden assigned
            $table->string('title');
            $table->text('description');
            $table->string('location')->nullable(); // Room/Block number
            $table->string('image')->nullable(); // Photo attachment
            $table->enum('status', ['submitted', 'in_progress', 'resolved', 'rejected'])->default('submitted');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
