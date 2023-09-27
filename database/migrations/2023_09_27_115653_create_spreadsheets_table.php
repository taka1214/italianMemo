<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('spreadsheets', function (Blueprint $table) {
        $table->id();
        $table->string('japanese');
        $table->string('italian');
        $table->string('answer');
        $table->text('memo')->nullable(); // メモは大きなテキストとして保存し、nullを許可する場合
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spreadsheets');
    }
};
