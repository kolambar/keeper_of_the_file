<?php

use App\Http\Controllers\FileController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\AuthController;


//VerifyCsrfToken::except(['/upload']);

Route::get('/login', function () {
    return redirect('/auth/redirect');
})->name('login');

Route::middleware('auth')->group(function() {

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
        $path = storage_path("app\\vault\\{$file}");

        return response()->download($path);
    });

    Route::get('/get_link', function (Request $request) {
        $folderPath = rtrim($request->query('path'), '/'); // Удаляем слэш в конце
        $fullPath = storage_path("app\\vault\\{$folderPath}");
        
        // Проверяем существование папки
        if (!is_dir($fullPath)) {
            return response('Папка не существует', 404);
        }

        // Получаем список файлов в папке (исключаем подпапки)
        $files = array_filter(scandir($fullPath), function ($item) use ($fullPath) {
            return is_file($fullPath . '/' . $item) && !in_array($item, ['.', '..']);
        });

        // Проверяем количество файлов
        if (count($files) !== 1) {
            return response('-1', 400);
        }

        // Получаем имя единственного файла
        $fileName = reset($files);
        $filePath = "{$folderPath}/{$fileName}";
        
        // Получаем ссылку на скачивание
        return response(url("/download?file={$filePath}"), 200);
    });

    Route::post('/logout', function () {
        Auth::logout();

        $logoutUrl = 'https://auth.petrsu.ru/auth/realms/iias/protocol/openid-connect/logout';
        $redirectAfterLogout = urlencode('https://auth.petrsu.ru/');

        return redirect($logoutUrl.'?redirect_uri='.$redirectAfterLogout);
    });

    Route::get('/search', [FileController::class, 'search'])->name('search');

});

Route::get('/auth/redirect', function() {
    return Socialite::driver('keycloak')->redirect();
});

Route::get('/auth/kc_callback', function() {
    $user = Socialite::driver('keycloak')->user();

    AuthController::login($user);

    return redirect('/');
});
