<?php

namespace App\Services\V1\User;

use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Rahjoo\RahjooResource;
use App\Models\Grade;
use App\Models\Rahjoo;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
    public function rahjoos(Request $request, $support): JsonResponse
    {
        /** @var LengthAwarePaginator $rahjoos */
        $rahjoos = resolve(RahjooRepositoryInterface::class)
            ->onlySupportRahjoos($support)
            ->withSupportIfIsSuperAdmin($request->user())
            ->filterSupportStep($request)
            ->filterPreparation($request)
            ->filterPosted($request)
            ->filterDelivered($request)
            ->with(['user:id,first_name,last_name,birthdate','requestSupport:id,user_id,conformer_id,created_at'])
            ->withCount(['payments' => function ($q) {
                $q->success();
            }])
            ->paginate($request->get('perPage', 10));
        $items = $rahjoos->getCollection()->map(function ($item) {
            $item->paid = (bool)$item->payments_count;
            return $item;
        });
        $rahjoos = $rahjoos->setCollection($items);
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
            ->with(['user:id,first_name,last_name,birthdate', 'support', 'support.support:id,first_name,last_name'])
            ->findOrFailById($rahjoo);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjoos', new RahjooResource($rahjoo))
            ->send();
    }

    #endregion
}
