<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('code')->unique();
            $table->timestamp('order_date')->useCurrent();

            $table->decimal('total_price', 12, 2)->default(0);

            $table->enum('status', [
                'pending',
                'waiting_payment',
                'paid',
                'processing',
                'shipped',
                'completed',
                'cancelled',
                'refunded'
            ])->default('pending');

            // fulfillment
            $table->enum('fulfillment_type', ['pickup', 'delivery']);

            // pickup
            $table->date('pickup_date')->nullable();
            $table->time('pickup_time')->nullable();

            // delivery
            $table->date('delivery_date')->nullable();
            $table->time('delivery_time')->nullable();
            $table->json('delivery_address')->nullable();
            $table->decimal('delivery_fee', 12, 2)->default(0);

            $table->text('note')->nullable();

            $table->timestamps();
        });

        DB::statement("
        ALTER TABLE orders ADD CONSTRAINT check_fulfillment
        CHECK (
            (fulfillment_type = 'pickup' AND delivery_date IS NULL AND delivery_time IS NULL AND delivery_address IS NULL)
            OR
            (fulfillment_type = 'delivery' AND pickup_date IS NULL AND pickup_time IS NULL)
        )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE orders DROP CONSTRAINT check_fulfillment");
        Schema::dropIfExists('orders');
    }
};
