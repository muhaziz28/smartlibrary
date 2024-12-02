<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $mahasiswa = Mahasiswa::where('nim', $data['username'])->first();
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'NIM tidak ditemukan');
        }


        $mahasiswaUser = User::where('username', $data['username'])->first();
        if ($mahasiswaUser) {
            return redirect()->back()->with('error', 'NIM tidak dapat digunakan');
        }

        return User::create([
            'username'  => $data['username'],
            'password'  => Hash::make($data['password']),
            'role_id'   => 2,
        ]);
    }

    public function getfakultas(Request $request)
    {
        $fakultas = Fakultas::when($request->search, function (
            $fakultas,
            $search
        ) {
            return $fakultas->where('nama_fakultas', 'LIKE', '%' . $search . '%')->orWhere('kode_fakultas', 'LIKE', '%' . $search . '%');
        })->get();

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $fakultas
        ]);
    }

    public function getProdi(Request $request)
    {
        $search = $request->query('search');
        $fakultas = $request->query('fakultas');

        if ($fakultas == null) {
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => []
            ]);
        }
        $prodi = Prodi::when($request->search, function ($query) use ($search) {
            $query->where(
                'nama_prodi',
                'like',
                '%' . $search . '%'
            )->orWhere('kode_prodi', 'like', '%' . $search . '%');
        })->when($request->fakultas, function ($query) use ($fakultas) {
            $query->where('fakultas_id', $fakultas);
        })->get();

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $prodi
        ]);
    }

    public function checkNim(Request $request)
    {
        $nim = $request->nim;
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'NIM tidak ditemukan',
            ]);
        }

        if (User::where('username', $nim)->first()) {
            return response()->json([
                'success' => false,
                'message' => 'NIM tidak dapat digunakan',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $mahasiswa,
        ]);
    }

    public function registerm()
    {
        $data = request()->validate([
            'username'      => 'required',
            'fakultas_id'   => 'required',
            'prodi_id'      => 'required',
            'password'      => 'required',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $data['username'])->first();
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'NIM tidak ditemukan');
        }

        if (User::where('username', $data['username'])->first()) {
            return redirect()->back()->with('error', 'NIM tidak dapat digunakan');
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'username'  => $data['username'],
                'password'  => Hash::make($data['password']),
                'role_id'   => 2,
            ]);

            // update mahasiswa
            $mahasiswa->fakultas_id = $data['fakultas_id'];
            $mahasiswa->prodi_id = $data['prodi_id'];
            $mahasiswa->save();


            DB::commit();

            return redirect()->route('login')->with('success', 'Berhasil mendaftar');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal mendaftar');
        }
    }
}
