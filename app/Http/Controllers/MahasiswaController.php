<?php

namespace App\Http\Controllers;

use App\Imports\MahasiswaImport;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(Request $request)
    {
        $perPage = 10;
        $currentPage = request('page', 1);

        $data = Mahasiswa::with('user')
            ->when($request->keyword, function ($query) use ($request) {
                $query->where(function ($subquery) use ($request) {
                    $subquery->where('nim', 'like', "%{$request->keyword}%")
                        ->orWhere('nama_mahasiswa', 'like', "%{$request->keyword}%");
                });
            })
            ->when($request->fakultas_id, function ($query) use ($request) {
                $query->where('fakultas_id', $request->fakultas_id);
            })
            ->when($request->is_user == 'true', function ($query) {
                $query->has('user');
            })
            ->when($request->is_user == 'false', function ($query) {
                $query->doesntHave('user');
            })
            ->paginate($perPage);

        $data->each(function ($mahasiswa) {
            $user = User::where('username', $mahasiswa->nim)->first();
            $mahasiswa->is_user = $user ? true : false;
        });

        $fakultas = Fakultas::all();

        return view('mahasiswa.index', compact('data', 'request', 'fakultas'));
    }


    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xls,xlsx'
            ]);

            $data = Excel::toArray(new MahasiswaImport(), $request->file('file'));
            $count = count($data[0]);

            $skipValue = [];

            if ($count > 0) {
                for ($i = 0; $i < $count; $i++) {
                    $mahasiswa = new Mahasiswa();
                    $mahasiswa->nim = $data[0][$i]['nim'];
                    $mahasiswa->nama_mahasiswa = $data[0][$i]['nama_mahasiswa'];

                    // jika data sudah ada, maka jangan ikut diimport
                    $check = Mahasiswa::where('nim', $data[0][$i]['nim'])->first();
                    if (!$check) {
                        $mahasiswa->save();
                    } else {
                        array_push($skipValue, $data[0][$i]['nim']);
                    }
                }
            }

            if (count($skipValue) > 0) {
                $skipValue = implode(", ", $skipValue);


                return response()->json([
                    'success' => true,
                    'haveSkip' => true,
                    'message' => 'Mahasiswa imported successfully!',
                    'skipValue' => $skipValue
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dosen imported successfully!'
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
        $validator = Validator::make($request->all(), [
            'nim'            => 'required',
            'nama_mahasiswa' => 'required',
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        try {
            $cekMahasiswa = Mahasiswa::where('nim', $request->nim)->first();
            if ($cekMahasiswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIM sudah ada.'
                ]);
            }

            Mahasiswa::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        return response()->json([
            'success' => true,
            'data' => $mahasiswa
        ]);
    }

    public function update(Request $request)
    {
        try {
            $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

            $mahasiswa->nama_mahasiswa = $request->nama_mahasiswa;
            $mahasiswa->save();

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

    public function destroy(Request $request, $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $mahasiswa->delete();

        try {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function truncate()
    {
        try {
            $mahasiswa = Mahasiswa::all();

            foreach ($mahasiswa as $key => $value) {
                $value->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus semua.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
