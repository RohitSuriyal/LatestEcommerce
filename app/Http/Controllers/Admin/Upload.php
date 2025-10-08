<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Upload extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('product_images')[0] ?? $request->file('filepond') ?? $request->allFiles()[0] ?? null;

        if ($file) {
            $path = $file->store('products', 'public');

            return response()->json([
                'path' => $path
            ]);
        }
        
        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
