<?php

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;

function getUser($param)
{
    $user = User::with('role')->where('username', $param)->orWhere('id', $param)
        ->first();

    $nama = '';
    $dosen = Dosen::where('kode_dosen', $param)->first();
    if ($dosen) {
        $nama = $dosen->nama_dosen;
    }

    $mahasiswa = Mahasiswa::where('nim', $param)->first();
    if ($mahasiswa) {
        $nama = $mahasiswa->nama_mahasiswa;
    }

    // $user->profile_picture = $user->profile_picture ? asset('storage/' . $user->profile_picture) : "";

    $user->nama = $nama == null ? 'ADMINISTRATOR' : $nama;

    return $user;
}
