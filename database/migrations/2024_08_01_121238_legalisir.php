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
        Schema::create('legalisir', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_resi', 40)->default('-');
            $table->enum('jenis_lgl', ['ijazah' ,'transkrip', 'ijazah_transkrip']);
            $table->enum('ambil', ['ditempat' ,'dikirim']);
            // $table->string('nama_mhw', 50)->nullable();
            $table->string('file_ijazah', 90)->nullable();
            $table->string('file_transkrip', 90)->nullable();
            $table->string('keperluan', 70);
            $table->date('tgl_lulus');
            $table->string('almt_kirim', 80)->nullable();
            $table->string('kcmt_kirim', 40)->nullable();
            $table->integer('kdps_kirim')->nullable();
            $table->string('klh_kirim', 40)->nullable();
            $table->string('kota_kirim', 40)->nullable();
            $table->date('tanggal_surat');
            $table->string('catatan_surat', 280)->nullable();
            $table->enum('role_surat', ['tolak' ,'mahasiswa', 'alumni', 'admin', 'supervisor_akd', 'dekan'])->default('admin');
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
