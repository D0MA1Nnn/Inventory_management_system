<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('supplier_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('supplier_price', 10, 2)->nullable(); // Optional: supplier-specific pricing
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_product');
    }
};