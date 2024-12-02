<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TugasMahasiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tugas_id',
        'nim',
        'file',
        'link',
        'status',
        'komentar',
        'nilai',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id', 'id');
    }
}
