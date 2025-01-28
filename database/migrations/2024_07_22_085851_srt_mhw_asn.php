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
        Schema::create('srt_mhw_asn', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_surat', 50)->nullable();
            // $table->string('nama_mhw', 50)->nullable();
            $table->year('thn_awl');
            $table->year('thn_akh');
            $table->tinyInteger('semester');
            $table->string('nama_ortu', 50);
            $table->string('nip_ortu', 20);
            $table->string('ins_ortu', 60);
            $table->date('tanggal_surat');
            $table->string('catatan_surat', 280)->nullable()->default('-');
            $table->enum('role_surat', ['tolak' ,'mahasiswa', 'admin', 'supervisor_akd', 'manajer'])->default('admin');
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
