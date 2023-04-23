<?php

namespace App\Services\V1\Discount;

use App\Enums\Discount\DiscountStatus;
use App\Http\Resources\V1\Discount\DiscountResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\Discount;
use App\Repositories\V1\Discount\Interfaces\DiscountRepositoryInterface;
use App\Responses\Api\ApiResponse;
use BenSampo\Enum\Rules\EnumValue;
use Exception;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class DiscountService extends \App\Services\V1\BaseService
{
    /**
     * @var DiscountRepositoryInterface
     */
    private DiscountRepositoryInterface $discountRepository;

    /**
     * @param DiscountRepositoryInterface $discountRepository
     */
    public function __construct(DiscountRepositoryInterface $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $discounts = $this->discountRepository->select([
            'id',
            'code',
            'is_percent',
            'amount',
            'enable_at',
            'expire_at',
            'usage_limitation',
            'status',
        ])->paginate($request->get('perPage', 15));
        $resource = PaginationResource::make($discounts)->additional(['itemsResource' => DiscountResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('discounts', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $discount
     * @return JsonResponse
     */
    public function show(Request $request, $discount): JsonResponse
    {
        $discount = $this->discountRepository->select([
            'id',
            'code',
            'is_percent',
            'amount',
            'enable_at',
            'expire_at',
            'usage_limitation',
            'status',
        ])->findOrFailById($discount);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('discount', new DiscountResource($discount))
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::validate($request->all(), [
            'code' => ['required', 'string', 'unique:' . Discount::class . ',code'],
            'is_percent' => ['nullable', 'boolean'],
            'amount' => collect(['required', 'numeric', 'min:1'])
                ->when($request->filled('is_percent') && $request->is_percent, function (Collection $collection) {
                    $collection->push('max:100');
                })->toArray(),
            'enable_at' => ['nullable', 'jdate:' . Discount::ENABLE_AT_VALIDATION_FORMAT],
            'expire_at' => ['nullable', 'jdate:' . Discount::EXPIRE_AT_VALIDATION_FORMAT],
            'usage_limitation' => ['nullable', 'numeric', 'min:1'],
            'status' => ['nullable', new EnumValue(DiscountStatus::class)],
        ]);
        $discount = $this->discountRepository->create(collect([
            'user_id' => $request->user()->id,
            'code' => $request->code,
            'is_percent' => $request->filled('is_percent') && $request->is_percent,
            'amount' => $request->amount,
            'enable_at' => $request->filled('enable_at') ? Verta::parseFormat(Discount::ENABLE_AT_VALIDATION_FORMAT, $request->enable_at)->datetime() : null,
            'expire_at' => $request->filled('expire_at') ? Verta::parseFormat(Discount::EXPIRE_AT_VALIDATION_FORMAT, $request->expire_at)->datetime() : null,
            'usage_limitation' => $request->filled('usage_limitation') ? $request->usage_limitation : 1,
        ])->when($request->filled('status'), function (Collection $collection) use ($request) {
            $collection->put('status', $request->status);
        })->toArray());
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Discount')]), Response::HTTP_CREATED)
            ->addData('discount', new DiscountResource($discount))
            ->send();
    }

    /**
     * @param Request $request
     * @param $discount
     * @return JsonResponse
     * @throws Exception
     */
    public function update(Request $request, $discount): JsonResponse
    {
        $discount = $this->discountRepository->findOrFailById($discount);
        ApiResponse::validate($request->all(), [
            'code' => ['nullable', 'string', 'unique:' . Discount::class . ',code,' . $discount->id],
            'is_percent' => ['nullable', 'boolean'],
            'amount' => collect(['nullable', 'numeric', 'min:1'])
                ->when($request->filled('is_percent') && $request->is_percent, function (Collection $collection) {
                    $collection->put('max', '100');
                })->toArray(),
            'enable_at' => ['nullable', 'jdate:' . Discount::ENABLE_AT_VALIDATION_FORMAT],
            'expire_at' => ['nullable', 'jdate:' . Discount::EXPIRE_AT_VALIDATION_FORMAT],
            'usage_limitation' => ['nullable', 'numeric', 'min:1'],
            'status' => ['nullable', new EnumValue(DiscountStatus::class)],
        ]);
        $this->discountRepository->update($discount, collect([])
            ->when($request->filled('code'), function (Collection $collection) use ($request) {
                $collection->put('code', $request->code);
            })->when($request->filled('amount'), function (Collection $collection) use ($request) {
                $collection->put('amount', $request->amount);
            })->when($request->filled('enable_at'), function (Collection $collection) use ($request) {
                $collection->put('enable_at', Verta::parseFormat(Discount::ENABLE_AT_VALIDATION_FORMAT, $request->enable_at)->datetime());
            })->when($request->filled('expire_at'), function (Collection $collection) use ($request) {
                $collection->put('expire_at', Verta::parseFormat(Discount::EXPIRE_AT_VALIDATION_FORMAT, $request->expire_at)->datetime());
            })->when($request->filled('usage_limitation'), function (Collection $collection) use ($request) {
                $collection->put('usage_limitation', $request->usage_limitation);
            })->when($request->filled('is_percent'), function (Collection $collection) use ($request) {
                $collection->put('is_percent', $request->is_percent);
            })->when($request->filled('status'), function (Collection $collection) use ($request) {
                $collection->put('status', $request->status);
            })->toArray());
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Discount')]))->send();
    }

    /**
     * @param Request $request
     * @param $discount
     * @return JsonResponse
     */
    public function destroy(Request $request, $discount): JsonResponse
    {
        $discount = $this->discountRepository->findOrFailById($discount);
        $discount->delete();
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Discount')]))->send();
    }
}
