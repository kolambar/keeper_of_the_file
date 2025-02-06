<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    $files = Storage::disk('vault')->allFiles();
    $folders = Storage::disk('vault')->allDirectories();

    return view('file_manager', compact('files', 'folders'));
});

Route::post('/upload', function (Request $request) {
    $request->validate([
        'file' => 'required|file|max:10240',
    ]);
    $path = $request->file('file')->store('', 'vault');
    return back()->with('success', "Файл загружен: $path");
});