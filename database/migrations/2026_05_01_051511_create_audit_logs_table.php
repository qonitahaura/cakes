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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->string('table_name');
            $table->unsignedBigInteger('record_id');

            $table->enum('action', ['insert', 'update', 'delete']);

            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamp('changed_at')->useCurrent();

            $table->index(['table_name', 'record_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
