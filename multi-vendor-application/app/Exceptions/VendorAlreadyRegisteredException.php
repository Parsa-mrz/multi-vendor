<?php

namespace App\Exceptions;

use App\Helpers\ResponseHelper;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class VendorAlreadyRegisteredException extends Exception
{
    /**
     * VendorAlreadyRegisteredException constructor.
     *
     * @param  string  $message  The exception message.
     * @param  int  $code  The exception code (default to 0).
     */
    public function __construct($message = 'You are already registered as a vendor.', $code = 0)
    {
        parent::__construct($message, $code);
    }

    /**
     * Render the exception as a JSON response using ResponseHelper.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return ResponseHelper::error(
            $this->getMessage(),
            null,
            Response::HTTP_BAD_REQUEST
        );
    }
}
