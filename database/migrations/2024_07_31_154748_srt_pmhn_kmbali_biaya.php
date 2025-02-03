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
        Schema::create('srt_pmhn_kmbali_biaya', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_surat', 50)->nullable();
            // $table->string('nama_mhw', 50)->nullable();
            // $table->string('skl', 50);
            // $table->string('buku_tabung', 50);
            // $table->string('bukti_bayar', 50);
            $table->string('skl', 50)->nullable();
            $table->string('buku_tabung', 50)->nullable();
            $table->string('bukti_bayar', 50)->nullable();
            // $table->string('file_pdf')->nullable();
            $table->date('tanggal_surat');
            $table->string('catatan_surat', 280)->nullable();
            $table->enum('role_surat', ['tolak' ,'mahasiswa', 'admin', 'supervisor_sd', 'manajer', 'wd2'])->default('admin');
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('prd_id');
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
