<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SesiMataKuliah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sesi_mata_kuliah';

    protected $fillable = [
        'periode_mata_kuliah_id',
        'kode_sesi',
        'kode_dosen',
        'jadwal_teori',
        'jadwal_praktikum',
        'chat_id',
        'radius',
    ];

    public function periode_mata_kuliah()
    {
        return $this->belongsTo(PeriodeMataKuliah::class, 'periode_mata_kuliah_id', 'id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'kode_dosen', 'kode_dosen');
    }

    public function mata_kuliah_diambil()
    {
        return $this->hasMany(MataKuliahDiambil::class, 'sesi_mata_kuliah_id', 'id');
    }

    public function mata_kuliah_diajukan()
    {
        return $this->hasMany(MataKuliahDiajukan::class, 'sesi_mata_kuliah_id', 'id');
    }

    public function pertemuan()
    {
        return $this->hasMany(Pertemuan::class, 'sesi_mata_kuliah_id', 'id');
    }

    public function pengantar()
    {
        return $this->hasOne(Pengantar::class, 'sesi_mata_kuliah_id', 'id');
    }

    public function rps()
    {
        return $this->hasMany(Rps::class, 'sesi_mata_kuliah_id', 'id');
    }

    public function modul_pengantar()
    {
        return $this->hasMany(ModulPengantar::class, 'sesi_mata_kuliah_id', 'id');
    }

    public function jadwalTeori()
    {
        return $this->belongsTo(JamPerkuliahan::class, 'jadwal_teori', 'id');
    }

    public function jadwalPraktikum()
    {
        return $this->belongsTo(JamPerkuliahan::class, 'jadwal_praktikum', 'id');
    }
}
