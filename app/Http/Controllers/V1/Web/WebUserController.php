<?php

namespace App\Http\Controllers\V1\Web;

use App\Services\V1\User\UserService;
use Illuminate\Http\Request;

class WebUserController extends WebBaseController
{
    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function verifyPayment(Request $request)
    {
        return $this->userService->verifyPayment($request);
    }
}
