<?php

namespace App\Services\V1\RequestSupport;

use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\RequestSupport\RequestSupportResource;
use App\Models\User;
use App\Repositories\V1\RequestSupport\Interfaces\RequestSupportRepositoryInterface;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Rules\MobileRule;
use App\Services\V1\BaseService;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\JsonResponse;
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

    public function index(Request $request): JsonResponse
    {
        $request_supports = $this->requestSupportRepository->select(['id', 'user_id', 'conformer_id', 'created_at'])
            ->with(['user:id,first_name,last_name,mobile,birthdate'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 15));
        $resource = PaginationResource::make($request_supports)->additional(['itemsResource' => RequestSupportResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('request_supports', $resource)
            ->send();
    }

    public function store(Request $request)
    {
        ApiResponse::validate($request->all(), [
            'mobile' => ['required', new MobileRule()],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'birthdate' => ['required', 'jdate:' . User::BIRTHDATE_VALIDATION_FORMAT],
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

    /**
     * @param Request $request
     * @param $requestSupport
     * @return JsonResponse
     */
    public function show(Request $request, $requestSupport): JsonResponse
    {
        $request_support = $this->requestSupportRepository->select(['id', 'user_id', 'conformer_id', 'created_at'])
            ->with(['user:id,first_name,last_name,mobile,birthdate', 'conformer:id,first_name,last_name'])
            ->findOrFailById($requestSupport);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('request_support', $request_support)
            ->send();
    }

    /**
     * @param Request $request
     * @param $requestSupport
     * @return JsonResponse
     */
    public function confirm(Request $request, $requestSupport): JsonResponse
    {
        $requestSupport = $this->requestSupportRepository->notConfirmed()
            ->findOrFailById($requestSupport);
        $this->requestSupportRepository->update($requestSupport, [
            'conformer_id' => $request->user()->id,
        ]);
        return ApiResponse::message(trans('Mission accomplished'))->send();
    }
}
