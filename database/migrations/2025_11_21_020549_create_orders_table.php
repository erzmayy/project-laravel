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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('order_number')->unique();
        $table->decimal('total_amount', 10, 2);
        $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
        $table->string('shipping_name');
        $table->string('shipping_phone');
        $table->text('shipping_address');
        $table->string('payment_method')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
        $table->string('shipping_courier')->nullable()->after('payment_method');
        $table->string('shipping_service')->nullable()->after('shipping_courier');
        $table->decimal('shipping_cost', 10, 2)->default(0)->after('shipping_service');
        $table->string('city')->nullable()->after('shipping_address');
        $table->string('province')->nullable()->after('city');
        $table->string('postal_code')->nullable()->after('province');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
