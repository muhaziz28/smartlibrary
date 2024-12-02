<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeriodeMataKuliah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'periode_mata_kuliah';

    protected $fillable = [
        'periode_id',
        'mata_kuliah_id',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id', 'id');
    }

    public function mata_kuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id', 'id');
    }

    public function sesi_mata_kuliah()
    {
        return $this->hasMany(SesiMataKuliah::class, 'periode_mata_kuliah_id', 'id');
    }
}
