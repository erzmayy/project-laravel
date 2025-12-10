<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('bank_name'); // Bank tujuan transfer (BCA, Mandiri, BNI)
            $table->string('account_name'); // Nama pengirim
            $table->string('account_number'); // Nomor rekening pengirim
            $table->decimal('amount', 10, 2); // Jumlah transfer
            $table->string('proof_image'); // Path foto bukti transfer
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable(); // Catatan dari admin
            $table->timestamp('confirmed_at')->nullable(); // Waktu customer upload
            $table->timestamp('verified_at')->nullable(); // Waktu admin verify
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_confirmations');
    }
};