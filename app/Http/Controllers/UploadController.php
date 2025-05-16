<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
        {
            if (!$request->hasFile('file')) {
                return response('Файл не найден в запросе', 400);
            }

            $path = $request->input('path');
            $file = $request->file('file');

            if (!$path) {
                return response('Путь не указан', 400);
            }

            // Получаем диск vault
            $disk = Storage::disk('vault');

            // Удаляем все файлы в указанной папке
            if ($disk->exists($path)) {
                $files = $disk->files($path);
                foreach ($files as $f) {
                    $disk->delete($f);
                }
            }

            // Сохраняем файл под оригинальным именем
            $filename = $file->getClientOriginalName();
            $storedPath = $disk->putFileAs($path, $file, $filename);

            return response("Файл загружен: $storedPath", 200);
        }
}
