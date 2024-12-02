<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // 1
    }

    public function index()
    {
        $username = Auth::user()->username;

        $mahasiswa = Mahasiswa::where('nim', $username)->first();
        $dosen = Dosen::where('kode_dosen', $username)->first();

        if ($mahasiswa) {
            return view('profile.index', compact('mahasiswa'));
        } else if ($dosen) {
            return view('profile.index', compact('dosen'));
        } else {
            return view('profile.index');
        }
    }

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:10048',
        ]);

        $file = $request->file('file');
        $filename = time() . '.' . $file->guessExtension() ?: 'png';

        $file->move(public_path("/media"), $filename);

        // update user profile_pict
        $user = Auth::user();
        $oldPict = $user->profile_pict;
        if ($oldPict != null) {
            if (file_exists(public_path("/media/" . $oldPict))) {
                // delete file
                unlink(public_path("/media/" . $oldPict));
            }
        }
        $user->profile_pict = $filename;
        $user->save();


        return redirect()->back()->with('success', 'Foto profil berhasil diubah');
    }

    public function deleteProfilePicture($id)
    {
        $user = User::find($id);
        $oldPict = $user->profile_pict;
        if ($oldPict != null) {
            if (file_exists(public_path("/media/" . $oldPict))) {
                // delete file
                unlink(public_path("/media/" . $oldPict));
            }
        }
        $user->profile_pict = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully!'
        ]);
    }

    public function addEmail(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $user = User::find($id);
            $validated = $request->validate([
                'email' => 'required|email|unique:users,email',
            ]);

            $user->email = $validated['email'];
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $user = User::find($id);
            $validated = $request->validate([
                'password-lama' => 'required',
                'password-baru' => 'required|min:8',
                'konfirmasi-password-baru' => 'required|same:password-baru',
            ]);

            if (!password_verify($validated['password-lama'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama tidak sesuai!'
                ]);
            }

            $user->password = bcrypt($validated['password-baru']);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
