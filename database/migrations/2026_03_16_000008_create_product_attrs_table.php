<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_attrs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_attr_group_id')->constrained('product_attr_groups');
            $table->string('name');
            $table->text('detail')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attrs');
    }
};
