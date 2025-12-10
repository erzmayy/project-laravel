<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('courier'); // JNE, J&T, SiCepat, dll
            $table->string('service'); // REG, YES, OKE, dll
            $table->decimal('cost', 10, 2);
            $table->string('tracking_number')->nullable();
            $table->integer('estimated_days')->nullable();
            $table->enum('status', ['pending', 'picked_up', 'in_transit', 'delivered', 'returned'])->default('pending');
            $table->text('delivery_notes')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};