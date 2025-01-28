<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class srt_izin_penelitian extends Model
{
    use HasFactory;
    protected $table = 'srt_izin_plt';
    protected $primaryKey = 'id';
    protected $fillable = [
        'no_surat',
        'id',
        'lampiran',
        'judul_data',
        'nama_lmbg',
        'jbt_lmbg',
        'semester',
        'jenis_surat',
        'almt_lmbg',
        'kota_lmbg',
        'catatan_surat',
        'role_surat',
        'prd_id',
        'tanggal_surat',
        'users_id',
        'file_pdf',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'no_surat',
        'id',
        'judul_data',
        'nama_lmbg',
        'jbt_lmbg',
        'almt_lmbg',
        'kota_lmbg',
        'catatan_surat',
        'role_surat',
        'prd_id',
        'tanggal_surat',
        'users_id',
        'file_pdf',
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

    public function jenjang()
    {
        return $this->belongsTo(jenjang_pendidikan::class, 'jnjg_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
