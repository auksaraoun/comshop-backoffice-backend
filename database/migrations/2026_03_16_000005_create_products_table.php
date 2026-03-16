<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands');
            $table->string('image_url')->nullable();
            $table->string('name');
            $table->text('detail')->nullable();
            $table->foreignId('product_type_id')->constrained('product_types');
            $table->string('status');
            $table->unsignedInteger('stock_qty')->default(0);
            $table->string('warranty')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
