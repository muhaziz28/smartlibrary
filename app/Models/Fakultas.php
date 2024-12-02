<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fakultas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fakultas';

    protected $fillable = [
        'nama_fakultas',
        'kode_fakultas',
    ];

    public function prodi()
    {
        return $this->hasMany(Prodi::class);
    }
}
