<?php

use App\Http\Controllers\FileController;
use App\Models\File;
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
        'file' => 'required|file|max:204800',
    ]);

    $currentPath = $request->input('path', '');
    $originalName = $request->file('file')->getClientOriginalName();
    // сохраняет файл на сервере
    $path = $request->file('file')->storeAs($currentPath, $originalName, 'vault');

    // сохраняет запись о файле в базе данных для быстрого поиска
    File::create([
        'name' => $request->file('file')->getClientOriginalName(),
        'path' => str_replace('/', '\\', $path)
    ]);
    return back()->with('success', "Файл загружен: $path");
});

Route::get('/download', function (request $request) {
    $file = $request->query('file');
    $path = storage_path(("app\\vault\\{$file}"));

    return response()->download($path);
});

Route::get('/search', [FileController::class, 'search'])->name('search');
