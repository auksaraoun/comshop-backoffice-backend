<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('lot');
            $table->enum('type', ['Import', 'Export']);
            $table->date('transaction_date');
            $table->foreignId('admin_id')->constrained('admin_users');
            $table->decimal('total_cost_price', 15, 2)->default(0);
            $table->decimal('total_sold_price', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_transactions');
    }
};
