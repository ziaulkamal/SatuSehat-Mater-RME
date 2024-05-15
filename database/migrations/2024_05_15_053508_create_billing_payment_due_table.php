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
        Schema::create('billing_payment_due', function (Blueprint $table) {
            $table->id();
            $table->string('const_users');
            $table->date('tanggal_mulai');
            $table->unsignedTinyInteger('jenis_fasyankes');
            $table->double('harga_awal', 15, 2)->nullable();
            $table->double('harga_langganan', 15, 2)->nullable();
            $table->integer('total_bayar')->default(0);
            $table->string('status');
            $table->date('jatuh_tempo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_payment_due');
    }
};
