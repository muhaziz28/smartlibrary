<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Telegram\Bot\Objects\Video;

class Pertemuan extends Model
{
    use HasFactory;

    protected $table = 'pertemuan';

    protected $fillable = [
        'sesi_mata_kuliah_id',
        'pertemuan_ke',
        'tanggal',
        'is_praktikum'
    ];

    public function sesiMataKuliah()
    {
        return $this->belongsTo(SesiMataKuliah::class, 'sesi_mata_kuliah_id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'pertemuan_id');
    }

    public function ebook()
    {
        return $this->hasMany(Ebook::class, 'pertemuan_id');
    }

    public function modul()
    {
        return $this->hasMany(Modul::class, 'pertemuan_id');
    }

    public function video_conf()
    {
        return $this->hasMany(VideoConf::class, 'pertemuan_id');
    }

    public function video_pembelajaran()
    {
        return $this->hasMany(VideoPembelajaran::class, 'pertemuan_id');
    }

    public function evaluasi()
    {
        return $this->hasMany(Evaluasi::class, 'pertemuan_id');
    }

    public function absensi() {
        return $this->hasMany(Absensi::class, 'pertemuan_id');
    }
}
