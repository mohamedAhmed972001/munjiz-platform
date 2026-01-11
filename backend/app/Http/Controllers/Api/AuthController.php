<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    // استخدام التريت اللي إنت عملته (success & error)
    use ApiResponseTrait;

    /**
     * تسجيل مستخدم جديد
     * Milestone 1.3: Validation & Sanitization
     */
// داخل AuthController.php - Register function

public function register(Request $request): JsonResponse
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users',
        'password' => 'required|confirmed',
        'role'     => 'required|in:freelancer,client', // لازم يختار واحد من دول
    ]);

    $user = User::create([
        'name'     => Str::title(trim(strip_tags($request->name))),
        'email'    => strtolower(trim($request->email)),
        'password' => Hash::make($request->password),
    ]);

    // دي الحركة السحرية بتاعة الباكدج
    $user->assignRole($request->role);
    Auth::login($user);

    return $this->success($user->load('roles'), 'Account created successfully', 201);
}

    /**
     * تسجيل الدخول
     * Milestone 1.3: Session-based Auth + Security
     */
    public function login(Request $request): JsonResponse
    {
        // 1. التحقق من المدخلات
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2. محاولة تسجيل الدخول
        if (Auth::attempt($credentials)) {
            // 3. تأمين الجلسة (Session Fixation Protection)
            $request->session()->regenerate();

            return $this->success(Auth::user(), 'Logged in successfully');
        }

        // 4. رد الفشل باستخدام الـ error function بتاعتك
        return $this->error('Invalid credentials', [], 401);
    }

    /**
     * تسجيل الخروج
     * Milestone 1.3: Invalidation
     */
    public function logout(Request $request): JsonResponse
    {
        // 1. تسجيل الخروج من Guard الـ Web (لأننا شغالين Sessions)
        Auth::guard('web')->logout();

        // 2. تدمير الجلسة وتوليد توكن CSRF جديد للأمان
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->success(null, 'Logged out successfully');
    }
    public function updateSkills(Request $request)
    {
        $request->validate([
            'skills'   => 'required|array',
            'skills.*' => 'exists:skills,id',
        ]);
    
        // السطر ده دلوقتي هيشتغل ويرجع اليوزر بدل null
        $user = auth()->user(); 
        
        if (!$user) {
            return $this->error('User not authenticated', [], 401);
        }
    
        $user->skills()->sync($request->skills); 
    
        // يفضل تستخدم التريت بتاعك هنا برضه عشان الرد يكون موحد
        return $this->success(null, 'Skills updated successfully!');
    }
}