<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_transaction_id')->constrained('product_transactions');
            $table->foreignId('product_id')->constrained('products');
            $table->string('serial_no');
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('sold_price', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_transaction_details');
    }
};
