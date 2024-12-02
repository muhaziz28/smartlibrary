<?php

namespace App\Http\Controllers;

use App\Imports\DosenImport;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class DosenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(Request $request)
    {
        $perPage = 10;
        $currentPage = request('page', 1);

        $data = Dosen::with('user', 'fakultas')
            ->when($request->keyword, function ($query) use ($request) {
                $query->where(function ($subquery) use ($request) {
                    $subquery->where('kode_dosen', 'like', "%{$request->keyword}%")->orWhere('nama_dosen', 'like', "%{$request->keyword}%");
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

        $data->each(function ($dosen) {
            $user = User::where('username', $dosen->kode_dosen)->first();
            $dosen->is_user = $user ? true : false;
        });

        $fakultas = Fakultas::all();

        return view('dosen.index', compact('data', 'request', 'fakultas'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $data = Excel::toArray(new DosenImport(), $request->file('file'));
        $count = count($data[0]);

        $skipValue = [];

        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $dosen = new Dosen();
                $dosen->nama_dosen = $data[0][$i]['nama_dosen'];
                $dosen->kode_dosen = $data[0][$i]['kode_dosen'];

                // jika data sudah ada, maka jangan ikut diimport
                $check = Dosen::where('kode_dosen', $data[0][$i]['kode_dosen'])->first();
                if (!$check) {
                    $dosen->save();
                } else {
                    array_push($skipValue, $data[0][$i]['kode_dosen']);
                }
            }
        }

        if (count($skipValue) > 0) {
            $skipValue = implode(", ", $skipValue);


            return response()->json([
                'success' => true,
                'haveSkip' => true,
                'message' => 'Dosen imported successfully!',
                'skipValue' => $skipValue
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Dosen imported successfully!'
        ]);
    }

    public function store(Request $request)
    {
        // validate request

        $validator = Validator::make($request->all(), [
            'kode_dosen' => 'required',
            'nama_dosen' => 'required',
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        try {
            $cekDosen = Dosen::where('kode_dosen', $request->kode_dosen)->first();
            if ($cekDosen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode dosen sudah ada.'
                ]);
            }

            Dosen::create($request->all());

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

        // jika validasi sukses
    }

    public function show($kode_dosen)
    {
        try {
            $dosen = Dosen::where('kode_dosen', $kode_dosen)->first();

            return response()->json([
                'success' => true,
                'data' => $dosen
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
        try {
            $dosen = Dosen::where('kode_dosen', $request->kode_dosen)->first();

            $dosen->nama_dosen = $request->nama_dosen;
            $dosen->fakultas_id = $request->fakultas_id;
            $dosen->save();

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

    public function destroy(Request $request, $kode_dosen)
    {
        try {
            $dosen = Dosen::where('kode_dosen', $kode_dosen)->first();
            $dosen->delete();

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

    public function make_user(Request $request, $kode_dosen)
    {
        try {
            $dosen = Dosen::where('kode_dosen', $kode_dosen)->first();

            $user = new User();
            $user->username = $dosen->kode_dosen;
            $user->password = bcrypt($dosen->kode_dosen);
            $user->role_id = 3;

            $dosen->fakultas_id = $request->fakultas_id;
            $dosen->save();

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dibuat.'
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
            $dosen = Dosen::all();

            foreach ($dosen as $key => $value) {
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
