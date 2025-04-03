<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Auth\VerifyRequest;

/**
 * Class VerificationController
 *
 * Handles email verification using OTP.
 */
class VerificationController extends AuthController
{
    /**
     * Verify the email using OTP and register the user.
     *
     * @param VerifyRequest $request The request containing verification details.
     *
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure.
     */
    public function verify(VerifyRequest $request){
        $otpResult = $this->otpService->verifyVerificationCode('email', $request->value, $request->code, 'email_otp');

        if(!$otpResult['success']){
            return ResponseHelper::error($otpResult['message'], null, $otpResult['status']);
        }

        $requestData = [
            "$request->type" => "$request->value",
            "password" => $request->password
        ];

        $result = $this->authService->registerUser ($requestData);

        if (! $result['success']) {
            return ResponseHelper::error($result['message'], null, $result['status']);
        }

        $this->userRepository->verifyEmail ($request->value);

        return ResponseHelper::success(
            $result['message'],
            [$result['data']],
            $result['status']
        );
    }
}
