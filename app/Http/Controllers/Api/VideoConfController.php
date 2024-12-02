<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VideoConf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoConfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data($pertemuan_id)
    {
        try {
            $data = VideoConf::where('pertemuan_id', $pertemuan_id)->get();

            return response()->json([
                'success' => true,
                'message' => 'Data video conference berhasil diambil',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function store(Request $request, $pertemuan_id)
    {
        $validate = Validator::make($request->all(), [
            'link' => 'required|url',
            'keterangan' => 'nullable'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Link harus berupa url',
                'data' => null
            ], 400);
        }

        try {
            $store = VideoConf::create([
                'pertemuan_id' => (int) $pertemuan_id,
                'link' => $request->link,
                'keterangan' => $request->keterangan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan video conference',
                'data' => $store
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $video_conf = VideoConf::find($id);

            if ($video_conf == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                    'data' => null
                ], 404);
            }

            $video_conf->link = $request->link;
            $video_conf->keterangan = $request->keterangan;
            $video_conf->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diubah.',
                'data' => $video_conf
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {

        try {
            $video_conf = VideoConf::find($id);
            if ($video_conf == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                    'data' => null
                ], 404);
            }
            $video_conf->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.',
                'data' => $video_conf
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
