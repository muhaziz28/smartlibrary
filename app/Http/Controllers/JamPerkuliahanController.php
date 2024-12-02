<?php

namespace App\Http\Controllers;

use App\Models\JamPerkuliahan;
use Exception;
use Illuminate\Http\Request;

class JamPerkuliahanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = JamPerkuliahan::get();
        return view('jam-perkuliahan.index', compact('data'));
    }

    public function store(Request $request)
    {
        try {
            $jamPerkuliahan = new JamPerkuliahan();
            $jamPerkuliahan->start = $request->start;
            $jamPerkuliahan->end = $request->end;


            $jamPerkuliahan->save();
            return response()->json([
                'success' => true,
                'message' => 'jam perkuliahan created successfully!'
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
            $data = JamPerkuliahan::find($id);

            return response()->json([
                'success' => true,
                'data' => $data
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
        $data = JamPerkuliahan::where('id', $request->id)->first();

        try {
            $data->start = $request->start;
            $data->end = $request->end;
            $data->save();

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
            $data = JamPerkuliahan::find($id);

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not found'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
