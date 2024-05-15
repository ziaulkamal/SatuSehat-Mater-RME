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
        Schema::create('user_client_credential', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fasyankes')->nullable();
            $table->string('organisasi_id')->unique();
            $table->string('client_id')->unique();
            $table->string('secret_id')->unique();
            $table->string('const_users');
            $table->string('validasi')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_client_credential');
    }
};
