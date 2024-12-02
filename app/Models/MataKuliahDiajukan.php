<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataKuliahDiajukan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "mata_kuliah_diajukan";

    protected $fillable = [
        'nim',
        'sesi_mata_kuliah_id',
        'disetujui',
    ];

    public function sesi_mata_kuliah()
    {
        return $this->belongsTo(SesiMataKuliah::class, 'sesi_mata_kuliah_id', 'id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
