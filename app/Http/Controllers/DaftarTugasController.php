<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MataKuliahDiambil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarTugasController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->role_id == 2) {
            $nim = AUth::user()->username;

            $mataKuliahDiambil = MataKuliahDiambil::with('sesiMataKuliah.pertemuan.tugas')->where('nim', $nim)->get();

            return response()->json($mataKuliahDiambil);
        } else {
            return redirect()->back();
        }
    }
}
