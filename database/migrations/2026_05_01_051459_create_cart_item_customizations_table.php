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
        Schema::create('cart_item_customizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_item_id')->constrained()->cascadeOnDelete();

            $table->foreignId('customization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customization_option_id')->nullable()->constrained()->nullOnDelete();

            $table->json('custom_values')->nullable();
            $table->decimal('additional_price', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_item_customizations');
    }
};
