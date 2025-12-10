<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah ENUM role untuk tambah super_admin
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'admin', 'super_admin') DEFAULT 'customer'");
    }

    public function down(): void
    {
        // Rollback ke ENUM awal
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'admin') DEFAULT 'customer'");
    }
};