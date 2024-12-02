<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\Providers\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data()
    {
        try {
            $token = JWTAuth::getToken();
            $userData = JWTAuth::toUser($token);

            $user = getUser($userData->username);

            $user->profile_pict = $user->profile_pict ? url('media/' . $user->profile_pict) : url('logo.png');

            $isMahasiswa = $user->role->role_name === 'mahasiswa';
            $isDosen = $user->role->role_name === 'dosen';

            $user->mahasiswa = null;
            $user->dosen = null;

            if ($isMahasiswa) {
                $mahasiswa = Mahasiswa::with('fakultas', 'prodi')->where('nim', $user->username)->first();
                $user->mahasiswa = $mahasiswa;
            }

            if ($isDosen) {
                $dosen = Dosen::with('fakultas')->where('kode_dosen', $user->username)->first();
                $user->dosen = $dosen;
            }

            return response()->json([
                'success'   => true,
                'message'   => 'Profile data retrieved successfully!',
                'data'      => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage(),
                'data'      => null
            ], 500);
        }
    }

    public function uploadProfilePicture(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:10048',
            ]);

            $file = $request->file('file');
            $filename = time() . '.' . $file->guessExtension() ?: 'png';

            $file->move(public_path("/media"), $filename);

            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $oldPict = $user->profile_pict;
            if ($oldPict != null) {
                if (file_exists(public_path("/media/" . $oldPict))) {
                    unlink(public_path("/media/" . $oldPict));
                }
            }
            $user->profile_pict = $filename;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile picture uploaded successfully!',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                "data" => null
            ], 500);
        }
    }

    public function deleteProfilePicture()
    {
        try {
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);

            $user = User::find($user->id);
            $oldPict = $user->profile_pict;
            if ($oldPict != null) {
                if (file_exists(public_path("/media/" . $oldPict))) {
                    unlink(public_path("/media/" . $oldPict));
                }
            }
            $user->profile_pict = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile picture deleted successfully!',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                "data" => null
            ], 500);
        }
    }

    public function addEmail(Request $request)
    {
        try {
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $validate = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first(),
                    'data' => null
                ], 400);
            }

            $user->email = $request->email;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil ditambahkan!',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                "data" => null
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $validated = $request->validate([
                'password-lama' => 'required',
                'password-baru' => 'required|min:8',
                'konfirmasi-password-baru' => 'required|same:password-baru',
            ]);

            if (!password_verify($validated['password-lama'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama tidak sesuai!',
                    'data' => null
                ], 400);
            }

            $user->password = bcrypt($validated['password-baru']);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah!',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                "data" => null
            ], 500);
        }
    }
}
