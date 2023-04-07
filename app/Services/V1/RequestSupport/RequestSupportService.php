<?php

namespace App\Services\V1\RequestSupport;

use App\Models\User;
use App\Repositories\V1\RequestSupport\Interfaces\RequestSupportRepositoryInterface;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Rules\MobileRule;
use App\Services\V1\BaseService;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class RequestSupportService extends BaseService
{
    /**
     * @var RequestSupportRepositoryInterface
     */
    private RequestSupportRepositoryInterface $requestSupportRepository;

    /**
     * @param RequestSupportRepositoryInterface $requestSupportRepository
     */
    public function __construct(RequestSupportRepositoryInterface $requestSupportRepository)
    {
        $this->requestSupportRepository = $requestSupportRepository;
    }

    public function store(Request $request)
    {
        ApiResponse::validate($request->all(), [
            'mobile' => ['required', new MobileRule()],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'birthdate' => ['nullable', 'jdate:' . User::BIRTHDATE_VALIDATION_FORMAT],
        ]);
        $request->merge([
            'mobile' => to_valid_mobile_number($request->mobile),
            'birthdate' => Verta::parseFormat(User::BIRTHDATE_VALIDATION_FORMAT, $request->birthdate),
        ]);
        try {
            return DB::transaction(function () use ($request) {
                $user = resolve(UserRepositoryInterface::class)->firstOrCreate([
                    'mobile' => $request->mobile,
                ], [
                    'mobile' => $request->mobile,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'birthdate' => $request->birthdate,
                ]);
                $this->requestSupportRepository->firstOrCreate([
                    'user_id' => $user->id,
                    'conformer_id' => null,
                ], [
                    'user_id' => $user->id,
                ]);
                return ApiResponse::message(trans('The information was register successfully'))->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans('Internal server error'))->send();
        }
    }
}
