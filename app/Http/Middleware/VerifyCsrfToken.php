<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * УРЛы, для которых CSRF-проверка отключена.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/upload', // Отключаем CSRF-защиту для этого маршрута
    ];
}
