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

        Schema::create('prodi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_prd', 30);
            $table->unsignedBigInteger('dpt_id');
            $table->foreign('dpt_id')->references('id')->on('prodi')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
