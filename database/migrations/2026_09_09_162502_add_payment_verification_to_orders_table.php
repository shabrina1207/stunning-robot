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
    Schema::table('orders', function (Blueprint $table) {
        $table->string('status_verifikasi')->default('pending'); // pending, verified, rejected
        $table->string('bukti_pembayaran')->nullable(); // Untuk QRIS
        $table->decimal('jumlah_bayar', 12, 2)->default(0); // Untuk uang tunai
        $table->decimal('kembalian', 12, 2)->default(0);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
