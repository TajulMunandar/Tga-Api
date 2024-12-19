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
    Schema::create('stocks', function (Blueprint $table) {
        $table->id();
        $table->string('item_name');
        $table->integer('quantity');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('stocks');
}

};