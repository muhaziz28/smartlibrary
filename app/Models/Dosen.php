<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dosen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dosen';

    protected $primaryKey = 'kode_dosen';
    protected $keyType = 'string';

    protected $fillable = [
        'kode_dosen',
        'nama_dosen',
        'fakultas_id',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'username', 'kode_dosen');
    }
}
