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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->foreignId('id_pelanggan')->constrained('pelanggans')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_tarif')->constrained('tarifs')->onDelete('restrict')->onUpdate('cascade');
            $table->date('tanggal_masuk');
            $table->date('tanggal_selesai');
            $table->tinyInteger('berat');
            $table->boolean('status');
            $table->boolean('jenis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
