<?php

namespace App\Http\Controllers\Auth;

use App\Class\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Services\Auth\EmailVerificationService;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    protected $emailVerificationService;
    
    public function __construct(EmailVerificationService $emailVerificationService)
    {
        $this->emailVerificationService = $emailVerificationService;
    }

    public function verify($userId, $token)
    {
        $user = User::find($userId);

        $result = $this->emailVerificationService->verify($user, $token);

        return ApiResponseClass::sendResponse(new AuthResource($result['user']), $result['message']);
    }
}
