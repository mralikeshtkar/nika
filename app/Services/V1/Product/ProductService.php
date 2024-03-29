<?php

namespace App\Services\V1\Product;

use App\Enums\Product\ProductStatus;
use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Product\ProductResource;
use App\Models\Product;
use App\Repositories\V1\Product\Interfaces\ProductRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ProductService extends BaseService
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $products = $this->productRepository->select(['id', 'title', 'body', 'quantity', 'created_at'])
            ->withPackageTitle()
            ->paginate($request->get('perPage', 15));
        $resource = PaginationResource::make($products)->additional(['itemsResource' => ProductResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('products', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $products = $this->productRepository->select(['id', 'title', 'body','status', 'quantity', 'created_at'])
            ->withPackageTitle()
            ->filterStatus($request)
            ->get();
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('products', ProductResource::collection($products))
            ->send();
    }

    /**
     * @param Request $request
     * @param $product
     * @return JsonResponse
     */
    public function show(Request $request, $product): JsonResponse
    {
        $product = $this->productRepository->select(['id', 'title', 'body', 'quantity','status', 'created_at'])
            ->withPackageTitle()
            ->withCount(['payments' => function ($q) {
                $q->success();
            }])->findOrFailById($product);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('product', new ProductResource($product))
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string', 'unique:' . Product::class . ',title'],
            'body' => ['required', 'string', 'unique:' . Product::class . ',body'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', new EnumValue(ProductStatus::class)],
        ]);
        $product = $this->productRepository->create(collect([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'body' => $request->body,
            'quantity' => $request->quantity,
        ])->when($request->filled('status'), function (Collection $collection) use ($request) {
            $collection->put('status', $request->status);
        })->toArray());
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Product')]), Response::HTTP_CREATED)
            ->addData('product', new ProductResource($product))
            ->send();
    }

    /**
     * @param Request $request
     * @param $product
     * @return JsonResponse
     */
    public function update(Request $request, $product): JsonResponse
    {
        $product = $this->productRepository->findOrFailById($product);
        ApiResponse::validate($request->all(), [
            'title' => ['nullable', 'string', 'unique:' . Product::class . ',title,' . $product->id],
            'body' => ['nullable', 'string', 'unique:' . Product::class . ',body,' . $product->id],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', new EnumValue(ProductStatus::class)],
        ]);
        $this->productRepository->update($product, collect()->when($request->filled('quantity'), function (Collection $collection) use ($request) {
            $collection->put('quantity', $request->quantity);
        })->when($request->filled('title'), function (Collection $collection) use ($request) {
            $collection->put('title', $request->title);
        })->when($request->filled('body'), function (Collection $collection) use ($request) {
            $collection->put('body', $request->body);
        })->when($request->filled('status'), function (Collection $collection) use ($request) {
            $collection->put('status', $request->status);
        })->toArray());
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Product')]))->send();
    }
}
