<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Файловый менеджер</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen p-6">
    <div class="w-full bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Файловый менеджер</h1>
        
        <!-- Форма загрузки файла -->
        <form action="/upload" method="POST" enctype="multipart/form-data" class="mb-6">
            @csrf
            
            <div class="flex items-center space-x-4">
                <input type="file" name="file" class="border border-gray-300 rounded p-2 w-full">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Загрузить</button>
            </div>
        </form>

        <!-- Списки файлов и папок -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Папки -->
            <div>
                <h2 class="text-lg font-semibold mb-2">📁 Папки</h2>
                <div class="bg-gray-50 p-4 rounded-lg shadow-inner max-h-40 overflow-y-auto">
                    @foreach ($folders as $folder)
                        <div class="mb-2">
                            <a href="?path={{ $folder }}" class="text-blue-500 hover:underline">{{ $folder }}</a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Файлы -->
            <div>
                <h2 class="text-lg font-semibold mb-2">📄 Файлы</h2>
                <div class="bg-gray-50 p-4 rounded-lg shadow-inner max-h-40 overflow-y-auto">
                    @foreach ($files as $file)
                        <div class="mb-2 flex justify-between">
                            <span>{{ basename($file) }}</span>
                            <a href="/download?file={{ $file }}" class="text-blue-500 hover:underline">Скачать</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>