<?php

namespace App\Http\Controllers;

use App\Services\InstrumentService;
use Exception;
use Illuminate\Http\Request;

class InstrumentController extends Controller
{
    public function __construct(protected InstrumentService $instrumentService)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $instruments = $this->instrumentService->all();

        // return response()->json($instruments);
        return view('instrument.index', compact('instruments'));
    }

    public function store(Request $request)
    {
        $data = self::validateRequest($request);
        $data['item'] = $data['instrument'];
        unset($data['instrument']);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => $data
            ]);
        }

        try {
            $instruments = $this->instrumentService->create($data);

            return response()->json([
                'success' => true,
                'message' => $instruments
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $data = self::validateRequest($request);
        $data['item'] = $data['instrument'];
        unset($data['instrument']);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => $data
            ]);
        }

        try {
            $instruments = $this->instrumentService->update($data, $request->id);

            return response()->json([
                'success' => true,
                'message' => $instruments
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $instrument = $this->instrumentService->find($id);

        if (!$instrument) {
            return response()->json([
                'success' => false,
                'message' => 'Instrument tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $instrument
        ]);
    }

    public function destroy($id)
    {
        $instrument = $this->instrumentService->find($id);
        if (!$instrument) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);

        $this->instrumentService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Instrument deleted successfully'
        ]);
    }

    private static function validateRequest(Request $request)
    {
        return $request->validate([
            'instrument' => 'required'
        ], [
            'instrument.required' => 'Instrument harus diisi'
        ]);
    }
}
