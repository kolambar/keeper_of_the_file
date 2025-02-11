<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Файловый менеджер</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
  <div class="w-full max-w-7xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-4 text-center">Keeper of the file</h1>
    
    <!-- Кнопка "Назад" (если не в корневой папке) -->
    @if ($currentPath)
      <?php
        $parentPath = dirname($currentPath);
        if ($parentPath === '.' || $parentPath === '/') {
          $parentPath = '';
        }
      ?>
        <div class="mb-4">
            <a href="?path={{ $parentPath }}" 
            class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-md transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Назад
            </a>
        </div>
    @endif

    <!-- Форма загрузки файла -->
    <form action="/upload" method="POST" enctype="multipart/form-data" class="mb-6">
        @csrf
        <!-- Скрытое поле для передачи текущего пути -->
        <input type="hidden" name="path" value="{{ request('path', '') }}">
    
        <div class="flex items-center space-x-4">
            <!-- Кастомная кнопка выбора файла -->
            <label for="file-upload" 
                   class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                Выбрать файл
            </label>
            <input id="file-upload" type="file" name="file" class="hidden" onchange="updateFileName(this)">
            
            <!-- Поле для отображения выбранного файла -->
            <span id="file-name" class="text-gray-700">Файл не выбран</span>
    
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                Загрузить
            </button>
        </div>
    </form>
    
    <script>
        function updateFileName(input) {
            const fileName = input.files.length ? input.files[0].name : 'Файл не выбран';
            document.getElementById('file-name').textContent = fileName;
        }
    </script>

    <!-- поле для поиска файлов -->
    <form action="{{ route('search') }}" method="GET" class="mb-4 flex items-center space-x-2">
      <input type="text" name="query" placeholder="Введите имя файла..." required
            class="border border-gray-300 rounded p-2 w-full">
      <button type="submit"
              class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
          Найти
      </button>
    </form>

    <!-- Панель файлов и папок -->
    <div class="grid grid-cols-2 gap-4 h-[70vh]">
      <!-- Папки -->
      <div>
        <h2 class="text-lg font-semibold mb-2">📁 Папки</h2>
        <div class="bg-gray-50 p-4 rounded-lg shadow-inner overflow-y-auto space-y-2" style="max-height: 600px;">
            @foreach ($folders as $folder)
                <a href="?path={{ $folder }}" 
                   class="block bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 7h4l2-2h6l2 2h4M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7" />
                    </svg>
                    {{ $folder }}
                </a>
            @endforeach
        </div>
    </div>
      <!-- Файлы -->
        <div>
            <h2 class="text-lg font-semibold mb-2">📄 Файлы</h2>
            <div class="bg-gray-50 p-4 rounded-lg shadow-inner overflow-y-auto space-y-2" style="max-height: 600px;">
                @foreach ($files as $file)
                    <div class="flex justify-between items-center bg-white p-2 rounded-lg shadow">
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