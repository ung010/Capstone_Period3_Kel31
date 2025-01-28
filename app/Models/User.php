<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama',
        'id',
        'nim_nip',
        'kota',
        'tanggal_lahir',
        'nowa',
        'almt_asl',
        'nama_ibu',
        'role',
        'prd_id',
        'foto',
        'role',
        'status',
        'email',
        'akses',
        'password',
        'catatan_user',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'nmr_unik',
        'id',
        'kota',
        'tanggal_lahir',
        'nowa',
        'almt_asl',
        'nama_ibu',
        'role',
        'prd_id',
        'foto',
        'role',
        'status',
        'email',
        'akses',
        'password',
        'catatan_user',
        'remember_token',
        'create_at',
        'update_at',
        'remember_token',
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

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prd_id');
    }
}
