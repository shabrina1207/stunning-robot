<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->nullable();
            $table->string('transaction_code')->nullable();
            $table->string('nama_pemesan')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('nomor_meja')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->decimal('total_harga', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};