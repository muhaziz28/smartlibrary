<?php

namespace App\Http\Controllers;

use App\Imports\ProdiImport;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProdiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index($id)
    {
        $fakultas = Fakultas::find($id);
        $perPage = 10;
        $currentPage = request('page', 1);

        $data = Prodi::with('mahasiswa.user')->where('fakultas_id', $id);

        $totalItems = $data->count();

        $totalPages = ceil($totalItems / $perPage);
        $prodi = $data->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        return view('prodi.index', compact('id', 'prodi', 'fakultas', 'totalItems', 'totalPages', 'currentPage', 'perPage'));
    }

    public function getAllProdi(Request $request)
    {
        $search = $request->query('search');
        $fakultas = $request->query('fakultas');

        $prodi = Prodi::when($request->search, function ($query) use ($search) {
            $query->where('nama_prodi', 'like', '%' . $search . '%')->orWhere('kode_prodi', 'like', '%' . $search . '%');
        })->when($request->fakultas, function ($query) use ($fakultas) {
            $query->where('fakultas_id', $fakultas);
        })->get();

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $prodi
        ]);
    }

    public function import(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        try {

            // Excel::import(new ProdiImport(), $request->file('file'));
            // cek panjang data
            $data = Excel::toArray(new ProdiImport(), $request->file('file'));
            $count = count($data[0]);

            $skipValue = [];

            if ($count > 0) {
                for ($i = 0; $i < $count; $i++) {
                    $prodi = new Prodi();
                    $prodi->nama_prodi = $data[0][$i]['nama_prodi'];
                    $prodi->kode_prodi = $data[0][$i]['kode_prodi'];
                    $prodi->fakultas_id = $request->fakultas_id;


                    // jika data sudah ada, maka jangan ikut diimport
                    $check = Prodi::where('nama_prodi', $data[0][$i]['nama_prodi'])->first();
                    if (!$check) {
                        $prodi->save();
                    } else {
                        array_push($skipValue, $data[0][$i]['nama_prodi']);
                    }
                }
            }

            if (count($skipValue) > 0) {
                $skipValue = implode(", ", $skipValue);


                return response()->json([
                    'success' => true,
                    'haveSkip' => true,
                    'message' => 'Prodi imported successfully!',
                    'skipValue' => $skipValue
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Prodi imported successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $prodi = new Prodi();
        $prodi->nama_prodi = $request->nama_prodi;
        $prodi->kode_prodi = $request->kode_prodi;
        $prodi->fakultas_id = $request->fakultas_id;
        $prodi->save();

        return response()->json([
            'success' => true,
            'message' => 'Prodi created successfully!'
        ]);
    }

    public function show($prodiId)
    {
        try {
            $prodi = Prodi::find($prodiId);

            return response()->json([
                'success' => true,
                'data' => $prodi
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
        $prodi = Prodi::find($request->id);
        $prodi->nama_prodi = $request->nama_prodi;
        $prodi->kode_prodi = $request->kode_prodi;
        $prodi->fakultas_id = $request->fakultas_id;
        $prodi->save();

        return response()->json([
            'success' => true,
            'message' => 'Prodi updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $prodi = Prodi::find($id);
        $prodi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prodi deleted successfully!'
        ]);
    }
}
