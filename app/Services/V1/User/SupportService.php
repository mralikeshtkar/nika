<?php

namespace App\Services\V1\User;

use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Rahjoo\RahjooResource;
use App\Models\Grade;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SupportService extends BaseService
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    #region Constructor

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #endregion

    #region Public methods

    /**
     * @param Request $request
     * @param $support
     * @return JsonResponse
     */
    public function rahjoos(Request $request,$support): JsonResponse
    {
//        ApiResponse::authorize($request->user()->isSupport());
        $rahjoos = resolve(RahjooRepositoryInterface::class)
            ->onlySupportRahjoos($support)
            ->withSupportIfIsSuperAdmin($request->user())
            ->filterSupportStep($request)
            ->with(['user:id,first_name,last_name,birthdate'])
            ->withCount(['payments'=>function($q){
                $q->success();
            }])
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($rahjoos)->additional(['itemsResource' => RahjooResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjoos', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $support
     * @param $rahjoo
     * @return JsonResponse
     */
    public function rahjoo(Request $request, $support, $rahjoo): JsonResponse
    {
        $rahjoo = resolve(RahjooRepositoryInterface::class)
            ->onlySupportRahjoos($support)
            ->with(['user:id,first_name,last_name,birthdate','support', 'support.support:id,first_name,last_name'])
            ->findOrFailById($rahjoo);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjoos', new RahjooResource($rahjoo))
            ->send();
    }

    #endregion
}
