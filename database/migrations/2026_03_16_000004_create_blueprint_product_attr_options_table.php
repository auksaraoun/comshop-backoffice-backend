<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blueprint_product_attr_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('product_attr_id')->constrained('blueprint_product_attrs');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blueprint_product_attr_options');
    }
};
