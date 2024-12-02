<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modul extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'moduls';

    protected $fillable = [
        'pertemuan_id',
        'file',
        'link',
        'type',
        'keterangan'
    ];

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id', 'id');
    }
}
