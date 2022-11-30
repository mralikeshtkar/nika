<?php

namespace App\Exceptions\User;

use App\Responses\Api\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class UserAccountIsInactiveException extends Exception
{
    /**
     * @param $request
     * @return bool|JsonResponse
     */
    public function render($request): bool|JsonResponse
    {
        if ($request->wantsJson()){
            return ApiResponse::error($this->getMessage(),$this->getCode())->send();
        }
        return false;
    }
}
