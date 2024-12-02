<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FakultasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(Request $request)
    {
        $perPage = 10;
        $currentPage = request('page', 1);

        $data = Fakultas::with('prodi')
            ->when($request->keyword, function ($query) use ($request) {
                $query->where(function ($subquery) use ($request) {
                    $subquery->where('kode_fakultas', 'like', "%{$request->keyword}%")->orWhere('nama_fakultas', 'like', "%{$request->keyword}%");
                });
            })
            ->paginate($perPage);

        return view('fakultas.index', compact('data', 'request'));
    }

    public function getAllFakultas(Request $request)
    {
        $fakultas = Fakultas::when($request->search, function ($fakultas, $search) {
            return $fakultas->where('nama_fakultas', 'LIKE', '%' . $search . '%')->orWhere('kode_fakultas', 'LIKE', '%' . $search . '%');
        })->get();

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $fakultas
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_fakultas' => 'required',
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        try {
            $fakultas = new Fakultas();
            $fakultas->nama_fakultas = $request->nama_fakultas;
            $fakultas->kode_fakultas = $request->kode_fakultas;
            $fakultas->save();

            return response()->json([
                'success' => true,
                'message' => 'Fakultas created successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        try {
            $fakultas = Fakultas::find($id);

            return response()->json([
                'success' => true,
                'data' => $fakultas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $fakultas = Fakultas::where('id', $request->id)->first();

        try {
            $fakultas->nama_fakultas = $request->nama_fakultas;
            $fakultas->kode_fakultas = $request->kode_fakultas;
            $fakultas->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diubah.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $fakultas = Fakultas::find($id);

            $prodi = $fakultas->prodi;
            foreach ($prodi as $p) {
                $p->delete();
            }

            $fakultas->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fakultas deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
