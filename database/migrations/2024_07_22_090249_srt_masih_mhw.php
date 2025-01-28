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
        Schema::create('srt_masih_mhw', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_surat', 50)->nullable();
            // $table->string('nama_mhw', 50)->nullable();
            $table->tinyInteger('semester');
            $table->year('thn_awl');
            $table->year('thn_akh');
            $table->string('almt_smg', 80);
            $table->string('tujuan_buat_srt', 90);
            $table->date('tanggal_surat');
            $table->string('catatan_surat', 280)->nullable()->default('-');
            // $table->string('file_pdf')->nullable();
            $table->enum('tujuan_akhir', ['manajer' ,'wd']);
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
