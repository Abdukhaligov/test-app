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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('customer_uuid')->nullable()->constrained('customers', 'uuid')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('order_products', function (Blueprint $table) {
            $table->foreignUuid('order_uuid')->constrained('orders', 'uuid')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products', 'id')->nullOnDelete();
            $table->integer('quantity');
            $table->decimal('unit_price', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
