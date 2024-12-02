<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoConf extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'video_confs';

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
