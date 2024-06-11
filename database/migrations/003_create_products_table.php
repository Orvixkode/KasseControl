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
            $table->string('name');
            $table->string('code', 50)->unique(); // Different from 'sku'
            $table->string('barcode', 100)->nullable()->unique();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2)->default(0); // Purchase/cost price
            $table->decimal('price', 10, 2)->default(0); // Selling price
            $table->decimal('stock', 10, 2)->default(0); // Current stock
            $table->integer('min_stock')->default(5); // Alert threshold
            $table->string('unit', 20)->default('pcs'); // unit of measure
            $table->string('image')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('code');
            $table->index('barcode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
