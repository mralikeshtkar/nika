<?php

namespace App\Services\V1\Order;

use App\Http\Resources\V1\Order\OrderResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\Order;
use App\Repositories\V1\Order\Interfaces\OrderRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Exception;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderService extends BaseService
{
    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderRepository->latest()
            ->with(['payment.paymentable','rahjooUser:users.id,users.first_name,users.last_name,users.mobile,users.birthdate'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 15));
        $resource = PaginationResource::make($orders)->additional(['itemsResource' => OrderResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('orders', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $order
     * @return JsonResponse
     */
    public function show(Request $request, $order): JsonResponse
    {
        $order = $this->orderRepository->with(['payment.paymentable'])
            ->findOrFailById($order);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('order', new OrderResource($order))
            ->send();
    }

    /**
     * @param Request $request
     * @param $order
     * @return JsonResponse
     */
    public function update(Request $request, $order): JsonResponse
    {
        $order = $this->orderRepository->findOrFailById($order);
        ApiResponse::validate($request->all(), [
            'tracking_code' => ['required', 'numeric'],
            'sent_at' => ['required', 'jdate:' . Order::SENT_AT_VALIDATION_FORMAT],
        ]);
        try {
            return DB::transaction(function () use ($order, $request) {
                $request->merge([
                    'sent_at' => Verta::parseFormat(Order::SENT_AT_VALIDATION_FORMAT, $request->sent_at)->datetime()
                ]);
                $this->orderRepository->update($order, [
                    'tracking_code' => $request->tracking_code,
                    'sent_at' => $request->sent_at,
                ]);
                return ApiResponse::message(trans("The information was register successfully"))
                    ->addData('order', new OrderResource($order))
                    ->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }
}
