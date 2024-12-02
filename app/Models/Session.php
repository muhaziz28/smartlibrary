<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time_start',
        'time_end',
        'description',
        'type',
        'pertemuan_id',
    ];

    public function sessionData()
    {
        return $this->hasMany(SessionData::class);
    }

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }
}
