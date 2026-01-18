<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Http\Resources\PortfolioResource; // استدعاء الريسورس
use App\Models\Portfolio;
use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // عشان الـ authorize تشتغل
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    use AuthorizesRequests, ApiResponseTrait;

    public function store(Request $request)
    {
        // 1. الفالييشن
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'link'        => ['nullable', 'url'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $profile = $request->user()->profile;

        // 2. تفعيل الـ Policy (بنقوله اتأكد إن اليوزر ده يقدر يضيف لبروفايله)
        // ملاحظة: هنا بنعمل authorize على الـ Profile نفسه لأن الـ Portfolio لسه متكريتش
        $this->authorize('update', $profile);

        // 3. إنشاء سجل العمل
        $portfolio = $profile->portfolios()->create($validated);

        // 4. استخدام الباكدج لرفع الصورة (Media Library)
        if ($request->hasFile('image')) {
            $portfolio->addMediaFromRequest('image')
                      ->toMediaCollection('portfolio_images');
        }

        // 5. الرد باستخدام الـ Resource والـ Trait
        return $this->success(
            new PortfolioResource($portfolio), 
            'Work added to your portfolio successfully'
        );
    }

    public function destroy(Portfolio $portfolio)
    {
        // استخدام الـ Policy للتأكد من ملكية العمل قبل الحذف
        $this->authorize('manage', $portfolio);

        // الباكدج بتمسح الصور أوتوماتيك أول ما الـ Model يتمسح
        $portfolio->delete();

        return $this->success(null, 'Work deleted from portfolio');
    }
}