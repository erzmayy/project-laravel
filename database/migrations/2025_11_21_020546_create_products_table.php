<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // seller
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description');
        $table->decimal('price', 10, 2);
        $table->integer('stock')->default(1);
        $table->enum('condition', ['like_new', 'good', 'fair', 'worn'])->default('good');
        $table->enum('status', ['available', 'sold', 'reserved'])->default('available');
        $table->json('images'); // multiple images
        $table->string('size')->nullable();
        $table->string('brand')->nullable();
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
