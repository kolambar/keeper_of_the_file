<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;


Route::get('/', function (Request $request) {
    $currentPath = $request->query('path', '');
    $files = Storage::disk('vault')->files($currentPath);
    $folders = Storage::disk('vault')->directories($currentPath);

    return view('file_manager', compact('files', 'folders', 'currentPath'));
});

Route::post('/upload', function (Request $request) {
    $request->validate([
        'file' => 'required|file|max:10240',
    ]);

    $currentPath = $request->input('path', '');
    $originalName = $request->file('file')->getClientOriginalName();

    $path = $request->file('file')->storeAs($currentPath, $originalName, 'vault');

    return back()->with('success', "Файл загружен: $path");
});

Route::get('/download', function (request $request) {
    $file = $request->query('file');
    // if (!Storage::disk('valut')->exists($file)) {
    //     abort(404, 'Файл не найден');
    // }

    $path = storage_path(("app/vault/{$file}"));

    return response()->download($path);
});
