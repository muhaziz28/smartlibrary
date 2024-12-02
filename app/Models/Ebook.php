<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ebook extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ebooks';

    protected $fillable = [
        'pertemuan_id',
        'judul',
        'file',
        'link',
        'keterangan',
    ];

    public function ebook()
    {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id', 'id');
    }
}
