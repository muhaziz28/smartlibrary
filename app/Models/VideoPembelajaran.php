<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoPembelajaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'video_pembelajarans';

    protected $fillable = [
        'pertemuan_id',
        'link',
        'keterangan',
    ];

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }
}
