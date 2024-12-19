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
    Schema::create('laundry_rates', function (Blueprint $table) {
        $table->id();
        $table->string('service_name');  // Nama layanan laundry
        $table->decimal('rate', 8, 2);  // Tarif laundry
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('laundry_rates');
}

};