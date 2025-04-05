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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique ()->index ();
            $table->longText('description');
            $table->decimal('price');
            $table->decimal('sale_price')->nullable ();
            $table->integer('quantity')->default(0);
            $table->decimal('discount')->default(0);
            $table->string ('image')->nullable();
            $table->foreignId('product_category_id')->constrained()->onDelete('cascade');
            $table->foreignId ('vendor_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->index('product_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
