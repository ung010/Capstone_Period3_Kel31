<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class legalisir extends Model
{
    use HasFactory;
    protected $table = 'legalisir';
    protected $primaryKey = 'id';
    protected $fillable = [
        'no_resi',
        'id',
        'jenis_lgl',
        'file_ijazah',
        'file_transkrip',
        'keperluan',
        'tgl_lulus',
        'almt_kirim',
        'kcmt_kirim',
        'kdps_kirim',
        'klh_kirim',
        'kota_kirim',
        'catatan_surat',
        'role_surat',
        'prd_id',
        'tanggal_surat',
        'users_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'no_resi',
        'id',
        'jenis_lgl',
        'file_ijazah',
        'file_transkrip',
        'keperluan',
        'tgl_lulus',
        'almt_kirim',
        'kcmt_kirim',
        'kdps_kirim',
        'klh_kirim',
        'kota_kirim',
        'catatan_surat',
        'role_surat',
        'prd_id',
        'tanggal_surat',
        'users_id',
        'remember_token',
        'create_at',
        'update_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getTempatTanggalLahirAttribute()
    {
        return $this->kota . ', ' . $this->tanggal_lahir;
    }

    public function departemen()
    {
        return $this->belongsTo(departemen::class, 'dpt_id', 'id');
    }

    public function prodi()
    {
        return $this->belongsTo(prodi::class, 'prd_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
