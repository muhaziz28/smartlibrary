<?php

namespace App\Http\Controllers;

use App\Models\AbsensiType;
use Exception;
use Illuminate\Http\Request;

class AbsensiTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $daring = 'daring';
        $luring = 'luring';
        try {
            $data = AbsensiType::create([
                'pertemuan_id' => $request->pertemuan_id,
                'type'         => $request->type === $daring ? $daring : $luring,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
