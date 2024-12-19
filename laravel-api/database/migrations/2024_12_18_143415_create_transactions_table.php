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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained('customers');  // Relasi dengan tabel customers
        $table->foreignId('laundry_rate_id')->constrained('laundry_rates');  // Relasi dengan tabel laundry_rates
        $table->decimal('amount_paid', 8, 2);  // Jumlah yang dibayar
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('transactions');
}
};
