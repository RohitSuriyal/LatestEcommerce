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
            $table->string("name");
            $table->string("price");
            $table->integer('rating');
            $table->integer('discount');
            $table->foreignId("category")->constrained('product_categories')->onDelete('cascade');
            $table->foreignId("subcategory")->constrained('subcategories')->onDelete('cascade');
            $table->foreignId('brand')->constrained('brands')->onDelete('cascade');
            $table->longText("description");
            $table->integer("sale_price");
            $table->integer('stock');
            $table->string("main_image");
            $table->longText("product_images");



            $table->timestamps();
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
