<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Angket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nim',
        'periode_id',
        'sesi_mata_kuliah_id',
    ];

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }
}
