<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use App\Models\SesiMataKuliah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MataKulliahDosenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $user = Auth::user();
        $userRole = $user->role->role_name;
        if ($userRole != "dosen") {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorize to access this resource',
                'data' => null
            ]);
        }

        $sesiDosen = SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah')
            ->where('kode_dosen', $user->username)
            ->whereHas('periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })->get();

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data'    => $sesiDosen
        ], 200);
    }
}
