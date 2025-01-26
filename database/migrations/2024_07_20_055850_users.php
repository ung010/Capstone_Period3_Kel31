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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama', 50);
            $table->string('nmr_unik', 50)->unique();
            $table->string('nowa', 15)->nullable();
            $table->string('email', 80);
            $table->string('kota', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('almt_asl', 80)->nullable();
            $table->string('foto', 50)->nullable();
            $table->string('nama_ibu', length: 25)->nullable();
            $table->string('password', 65);
            $table->enum('status', ['mahasiswa', 'alumni']);
            $table->enum('role', [
                'non_mahasiswa',
                'del_mahasiswa',
                'mahasiswa',
                'admin',
                'supervisor_akd',
                'supervisor_sd',
                'manajer',
                'wd1',
                'wd2'
            ])->default('non_mahasiswa');
            $table->string('catatan_user')->nullable()->default('-');
            $table->unsignedBigInteger('prd_id')->nullable();
            $table->foreign('prd_id')->references('id')->on('prodi')->onDelete('cascade')->onUpdate('cascade')->nullable();
            // $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
