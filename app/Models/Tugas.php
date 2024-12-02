<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tugas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tugas';

    protected $fillable = [
        'pertemuan_id',
        'file',
        'link',
        'keterangan',
        'deadline',
    ];

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id', 'id');
    }

    public function tugas_mahasiswa()
    {
        return $this->hasMany(TugasMahasiswa::class, 'tugas_id', 'id');
    }
}
