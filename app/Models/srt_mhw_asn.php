<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class srt_mhw_asn extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'srt_mhw_asn';
    protected $primaryKey = 'id';
    protected $fillable = [
        'no_surat',
        'id',
        'nim_mhw',
        'nowa_mhw',
        'nama_ortu',
        'nip_ortu',
        'ins_ortu',
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
        'no_surat',
        'id',
        'nim_mhw',
        'nowa_mhw',
        'nama_ortu',
        'nip_ortu',
        'ins_ortu',
        'catatan_surat',
        'role_surat',
        'prd_id',
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
