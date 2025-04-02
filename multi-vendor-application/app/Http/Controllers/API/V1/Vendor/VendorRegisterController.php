<?php

namespace App\Http\Controllers\API\V1\Vendor;

use App\Exceptions\VendorAlreadyRegisteredException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\VendorRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Service\VendorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VendorRegisterController extends Controller
{
    /**
     * @var VendorService
     */
    protected $vendorService;

    /**
     * RegisterController constructor.
     */
    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    /**
     * Handle the registration of a new vendor.
     */
    public function register(VendorRegisterRequest $request): JsonResponse
    {
        try {
            /**
             * @var User $user The authenticated user.
             */
            $user = Auth::user();

            $user = $this->vendorService->registerAsVendor($user, $request->validated());

            return ResponseHelper::success(
                'Vendor registered successfully.',
                ['user' => new UserResource($user->load('profile'))],
                Response::HTTP_CREATED
            );
        } catch (VendorAlreadyRegisteredException $e) {
            return $e->render($request);
        } catch (\Exception $e) {
            return ResponseHelper::error(
                'An error occurred while processing your request.',
                null,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
