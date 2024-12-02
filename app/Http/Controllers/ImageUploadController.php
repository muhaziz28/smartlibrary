<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function storeImage(Request $request)
    {
        $originName = $request->file('upload')->getClientOriginalName();
        $fileName = pathinfo($originName, PATHINFO_FILENAME);
        $extension = $request->file('upload')->getClientOriginalExtension();
        $fileName = $fileName . '_' . time() . '.' . $extension;

        $request->file('upload')->move(public_path('media'), $fileName);

        $url = asset('media/' . $fileName);
        // dd($url);
        return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
    }
}
