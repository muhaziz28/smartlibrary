<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataKuliahDiambil extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mata_kuliah_diambil';

    protected $fillable = [
        'nim',
        'sesi_mata_kuliah_id',
    ];

    public function sesiMataKuliah()
    {
        return $this->belongsTo(SesiMataKuliah::class, 'sesi_mata_kuliah_id', 'id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
