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
        Schema::create('srt_bbs_pnjm', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_surat', 50)->nullable();
            // $table->string('nama_mhw', 50)->nullable();
            $table->string('almt_smg', 80);
            $table->string('dosen_wali', 50);
            $table->date('tanggal_surat');
            $table->string('catatan_surat', 280)->nullable();
            $table->enum('role_surat', ['tolak' ,'mahasiswa', 'admin', 'supervisor_sd', 'manajer'])->default('admin');
            $table->unsignedBigInteger('users_id')->nullable();
            $table->unsignedBigInteger('prd_id')->nullable();
            $table->foreign('prd_id')->references('id')->on('prodi')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
