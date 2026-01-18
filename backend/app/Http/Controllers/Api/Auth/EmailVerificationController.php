<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    use ApiResponseTrait;

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return $this->success(null, 'Email verified successfully');
    }

    public function resend()
    {
        auth()->user()->sendEmailVerificationNotification();

        return $this->success(null, 'Verification link sent');
    }
}
