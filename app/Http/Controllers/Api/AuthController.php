<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Cast\Object_;

use function PHPSTORM_META\map;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(['username', 'password']);

        $validator = Validator::make($credentials, [
            'username'  => 'required',
            'password'  => 'required|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'message'   => $validator->errors(),
                'data'      => null
            ], 400);
        }

        try {
            $accessToken = JWTAuth::attempt($credentials);
            if (!$accessToken) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Invalid credentials',
                    'data'      => null
                ], 400);
            }

            $user = getUser($request->username);

            $refreshToken = Str::random(60);
            $token = [
                'token' => $accessToken,
                'token_expires_in' => auth()->factory()->getTTL(),
                'token_type' => 'bearer',
                'refresh_token' => hash('sha256', $refreshToken),
            ];

            $user->profile_pict = $user->profile_pict ? url('media/' . $user->profile_pict) : url('logo.png');


            $isMahasiswa = $user->role->role_name === 'mahasiswa';
            $isDosen = $user->role->role_name === 'dosen';

            $user->mahasiswa = null;
            $user->dosen = null;

            if ($isMahasiswa) {
                $mahasiswa = Mahasiswa::with('fakultas', 'prodi')->where('nim', $request->username)->first();
                $user->mahasiswa = $mahasiswa;
            }

            if ($isDosen) {
                $dosen = Dosen::with('fakultas')->where('kode_dosen', $request->username)->first();
                $user->dosen = $dosen;
            }

            $data = [
                'user' => $user,
                'token' => $token,
            ];

            return response()->json([
                'success'   => true,
                'message'   => 'Login success',
                'data'      => $data
            ], 200);
        } catch (JWTException $th) {
            return response()->json([
                'success'   => false,
                'message'   => 'Could not create token',
                'data'      => null
            ], 500);
        }
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->input('refresh_token');

        $user = User::where('refresh_token', hash('sha256', $refreshToken))->first();

        if (!$user) {
            return response()->json([
                'success'   => false,
                'message'   => 'Invalid refresh token',
                'data'      => null
            ], 400);
        }

        // Menghapus refresh token lama
        $user->refresh_token = null;
        $user->save();

        // Menghasilkan token baru
        $token = JWTAuth::fromUser($user);

        // Generate refresh token
        $refreshToken = Str::random(60);
        $user->refresh_token = hash('sha256', $refreshToken);
        $user->save();

        return response()->json([
            'success'   => true,
            'message'   => 'Token refreshed successfully',
            'data'      => [
                'token' => $token,
                'refresh_token' => $refreshToken,
            ]
        ], 200);
    }


    public function checkNim(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nim' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success'   => false,
                'message'   => $validate->errors(),
                'data'      => null
            ], 400);
        }

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();
        if ($mahasiswa) {
            $user = User::where('username', $mahasiswa->nim)->first();
            if ($user) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'NIM sudah terdaftar',
                    'data'      => null
                ], 400);
            }
            return response()->json([
                'success'   => true,
                'message'   => 'NIM ditemukan',
                'data'      => $mahasiswa
            ], 200);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => 'NIM tidak ditemukan',
                'data'      => null
            ], 400);
        }
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

    public function register()
    {
        $data = request()->validate([
            'username'      => 'required',
            'fakultas_id'   => 'required',
            'prodi_id'      => 'required',
            'password'      => 'required',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $data['username'])->first();
        if (!$mahasiswa) {
            return response()->json([
                'success'   => false,
                'message'   => 'NIM tidak ditemukan',
                'data'      => null
            ], 400);
        }

        if (User::where('username', $data['username'])->first()) {
            return response()->json([
                'success'   => false,
                'message'   => 'NIM sudah terdaftar',
                'data'      => null
            ], 400);
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

            return response()->json([
                'success'   => true,
                'message'   => 'Berhasil mendaftar',
                'data'      => $user
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success'   => false,
                'message'   => $th->getMessage(),
                'data'      => null
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken();

            // Periksa apakah token diberikan
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token not provided',
                    'data' => null
                ], 401);
            }

            // Invalidasi token
            JWTAuth::invalidate($token);

            // Berhasil logout
            return response()->json([
                'success' => true,
                'message' => 'Logout successfully',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            // Tangani kesalahan yang mungkin terjadi saat logout
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first(),
                'data' => null
            ], 400);
        }

        // cek email di database
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan',
                'data' => null
            ], 400);
        }

        $credentials = $request->only('email');

        try {
            $response = Password::sendResetLink($credentials);

            return $response == Password::RESET_LINK_SENT
                ? $this->sendResetLinkResponse($request, $response)
                : $this->sendResetLinkFailedResponse($request, $response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json([
            'success' => true,
            'message' => trans($response),
            'data' => null
        ]);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json([
            'success' => false,
            'message' => trans($response),
            'data' => null
        ], 400);
    }
}
