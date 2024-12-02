<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModulPengantar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'modul_pengantar';

    protected $fillable = [
        'sesi_mata_kuliah_id',
        'deskripsi',
        'file',
        'link',
    ];

    public function sesiMataKuliah()
    {
        return $this->belongsTo(SesiMataKuliah::class, 'sesi_mata_kuliah_id', 'id');
    }
}
