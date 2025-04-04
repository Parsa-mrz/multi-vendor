<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Interfaces\OtpSenderInterface;
use App\Repositories\CacheRepository;
use App\Services\OtpSenders\EmailOtpSender;
use Symfony\Component\HttpFoundation\Response;
use function ucfirst;

/**
 * Class OtpService
 *
 * Handles the logic for sending and verifying OTPs.
 * The OTP can be sent via different channels (email, SMS, etc.) and it is cached for verification.
 */
class OtpService
{

    /**
     * @var CacheRepository
     */
    protected  $cacheRepository;

    /**
     * @var array<string, string> List of OTP senders mapped by type.
     */
    protected $senders =[
        'email' => EmailOtpSender::class
    ];

    /**
     * OtpService constructor.
     *
     * Initializes the OTP service with a CacheRepository to handle OTP storage.
     *
     * @param  CacheRepository  $cacheRepository  The repository for storing OTP data in the cache.
     */
    public function __construct(CacheRepository $cacheRepository)
    {
        $this->cacheRepository  = $cacheRepository;
    }

    /**
     * Send a verification code to the recipient.
     *
     * Checks if the recipient has recently requested an OTP and sends a new one if allowed.
     * The verification code is stored in the cache with a time-to-live (TTL) of 5 minutes.
     *
     * @param  string  $type  The type of recipient (e.g., 'email').
     * @param  string  $value  The value (e.g., email or phone number) for which OTP is sent.
     * @param  string  $prefix  The prefix used to identify the cache keys.
     * @param  OtpSenderInterface|null  $sender  The OTP sender instance, defaults to EmailOtpSender if null.
     *
     * @return array The result of the OTP sending operation, including success status, message, and HTTP status code.
     */
    public function sendVerificationCode(string $type, string $value, string $prefix, OtpSenderInterface $sender = null): array
    {
        $cacheCooldownKey = "{$prefix}_cooldown_{$type}_{$value}";

        if ($this->cacheRepository->has($cacheCooldownKey)) {
            return ResponseHelper::error (
                'You can request a new verification code after 2 minutes.',
                null,
                Response::HTTP_TOO_MANY_REQUESTS
            );
        }

        $verificationCode = mt_rand(1000, 9999);
        $cacheKey = "{$prefix}_{$type}_{$value}";

        $this->cacheRepository->store($cacheKey, [
            'code' => $verificationCode,
            'type' => $type,
            'value' => $value,
            'attempts' => 0,
        ], 300); // 5 minutes TTL

        $this->cacheRepository->storeFlag($cacheCooldownKey, true, 120);

        $sender = $sender ?? app($this->senders[$type]);
        $sender->send($value, (string) $verificationCode);

        return ResponseHelper::success (
            "Verification code sent to your new {$type}."
        );
    }

    /**
     * Verify a provided OTP for a recipient.
     *
     * Checks the provided verification code against the stored OTP in the cache.
     * If the code is incorrect or expired, an error is returned. If it is correct, success is returned.
     *
     * @param  string  $type  The type of recipient (e.g., 'email').
     * @param  string  $value  The value (e.g., email or phone number) to verify the OTP for.
     * @param  int  $code  The OTP code to verify.
     * @param  string  $prefix  The prefix used to identify the cache keys.
     * @return array The result of the OTP verification operation, including success status, message, and HTTP status code.
     */
    public function verifyVerificationCode(string $type, string $value, int $code, string $prefix): array
    {
        $cacheKey = "{$prefix}_{$type}_{$value}";
        $verificationData = $this->cacheRepository->get($cacheKey);

        if (!$verificationData) {
            return ResponseHelper::error (
                'Verification code expired or does not exist.'
            );
        }

        if ($verificationData['attempts'] >= 3) {
            $this->cacheRepository->forget($cacheKey);

            return ResponseHelper::error (
                'Maximum verification attempts reached. Please request a new code.',
                null,
                Response::HTTP_TOO_MANY_REQUESTS
            );
        }

        if ($verificationData['code'] != $code || $verificationData['value'] != $value) {
            $verificationData['attempts'] += 1;
            $this->cacheRepository->store($cacheKey, $verificationData, 300);

            return ResponseHelper::error (
                'Invalid verification code. Attempts remaining: ' . (3 - $verificationData['attempts']),
                null,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );

        }

        $this->cacheRepository->forget($cacheKey);

        return ResponseHelper::success (
            ucfirst($type) . ' Verification Passed Successfully.'
        );
    }
}
