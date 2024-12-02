<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'latitude',
        'longitude',
        'attachment',
        'date_in',
        'hadir'
    ];

    protected $casts = [
        'hadir' => 'boolean',
    ];
}
