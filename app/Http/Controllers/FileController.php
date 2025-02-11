<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $query = $request->input('query');
        $file = File::where('name', $query)->first();

        if (!$file) {
            return back()->with('error', 'Файл не найден');
        }
        
        return response()->download(storage_path("app\\vault\\{$file->path}"));
    }
}
