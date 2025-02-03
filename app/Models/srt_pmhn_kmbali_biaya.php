<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class srt_pmhn_kmbali_biaya extends Model
{
    use HasFactory;
    protected $table = 'srt_pmhn_kmbali_biaya';
    protected $primaryKey = 'id';
    protected $fillable = [
        'no_surat',
        'id',
        'skl',
        'bukti_bayar',
        'buku_tabung',
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
        'nama_mhw',
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

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
