<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException; // مهمة للـ 404
use Illuminate\Http\Request; // السطر ده كان ناقص عندك ومهم جداً
use Illuminate\Http\Exceptions\ThrottleRequestsException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi(); 

        // ملاحظة احترافية: Sanctum عادة بيحتاج الـ CSRF يكون شغال
        // إحنا بنستثني بس لو إنت بتجرب بـ Postman، لكن مع React خليهم محميين
        $middleware->validateCsrfTokens(except: [
            'sanctum/csrf-cookie',
            // 'api/register', // يفضل تخليهم محميين في الإنتاج
            // 'api/login',
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // 1. معالجة أخطاء الـ Validation
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'بيانات المدخلات غير صحيحة',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });

        // 2. معالجة الـ 404 (لو اللينك غلط) - ضيف الجزء ده
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'هذا المسار غير موجود أو تم حذفه',
                ], 404);
            }
        });

        // 3. معالجة الـ Rate Limit (الـ Throttle بتاعك)
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'محاولات كثيرة جداً، يرجى الانتظار قليلاً.',
                    'errors'  => ['throttle' => 'Too many attempts.'],
                ], 429);
            }
        });

    })->create();