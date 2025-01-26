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
        Schema::create('srt_magang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_surat', 50)->nullable();
            $table->string('nama_mhw', 50)->nullable();
            $table->string('almt_smg', 80);
            $table->tinyInteger('semester');
            $table->integer('sksk');
            $table->decimal('ipk', 3, 2);
            $table->string('nama_lmbg', 60);
            $table->string('jbt_lmbg', 50);
            $table->string('kota_lmbg', 40);
            $table->string('almt_lmbg', 80);
            $table->date('tanggal_surat');
            $table->string('catatan_surat', 280)->nullable()->default('-');
            // $table->string('file_pdf')->nullable();
            $table->enum('role_surat', ['tolak' ,'mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer', 'wd1'])->default('admin');
            $table->unsignedBigInteger('users_id')->nullable();
            $table->unsignedBigInteger('prd_id')->nullable();
            $table->foreign('prd_id')->references('id')->on('prodi')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade')->nullable();
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
