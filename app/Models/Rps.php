<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rps extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rps';

    protected $fillable = [
        'sesi_mata_kuliah_id',
        'deskripsi',
        'file',
        'link',
    ];

    public function sesi_mata_kuliah()
    {
        return $this->belongsTo(SesiMataKuliah::class, 'sesi_mata_kuliah_id', 'id');
    }
}
