<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
      $middleware->statefulApi(); // تفعيل نظام الجلسات للـ API
      
      // إعدادات الـ CORS برمجياً لضمان عدم حدوث تعارض
      $middleware->validateCsrfTokens(except: [
          'sanctum/csrf-cookie',
          'api/register',
          'api/login',
      ]);
      $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
  })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();