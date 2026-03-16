<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->string('serial_no');
            $table->foreignId('product_transaction_detail_id')->nullable()->constrained('product_transaction_details');
            $table->enum('status', ['available', 'sold', 'defective', 'returned']);
            $table->date('warranty_expires_at')->nullable();
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('sale_price', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
