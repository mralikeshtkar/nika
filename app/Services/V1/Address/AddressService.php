<?php

namespace App\Services\V1\Address;

use App\Enums\AddressType;
use App\Http\Resources\V1\Address\AddressResource;
use App\Http\Resources\V1\Grade\GradeResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\Address;
use App\Models\City;
use App\Models\User;
use App\Repositories\V1\Address\Interfaces\AddressRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Rules\MobileOrPhoneNumberRule;
use App\Rules\PostalCodeRule;
use App\Services\V1\BaseService;
use BenSampo\Enum\Rules\EnumKey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class AddressService extends BaseService
{
    /**
     * @var AddressRepositoryInterface
     */
    private AddressRepositoryInterface $addressRepository;

    #region Constructor

    /**
     * AddressService constructor.
     *
     * @param AddressRepositoryInterface $addressRepository
     */
    public function __construct(AddressRepositoryInterface $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    #endregion

    #region Public methods

    public function index(Request $request)
    {
        $addresses = $this->addressRepository
            ->select(['id', 'user_id', 'city_id', 'address', 'type', 'postal_code', 'phone_number', 'created_at'])
            ->withCityName()
            ->withProvinceName()
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($addresses)->additional(['itemsResource' => AddressResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('cities', $resource)
            ->send();
    }

    /**
     * Store an address.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::validate($request->all(), collect([
            'city_id' => ['required', 'exists:' . City::class . ',id'],
            'address' => ['required', 'string'],
            'postal_code' => ['required', new PostalCodeRule()],
            'phone_number' => ['required', new MobileOrPhoneNumberRule()],
            'type' => ['required', new EnumKey(AddressType::class)],
        ])->when($request->user()->can('create', Address::class), function (Collection $collection) {
            $collection->put('user_id', ['nullable', 'exists:' . User::class . ',id']);
        })->toArray(), [], [
            'city_id' => trans('City'),
        ]);
        $address = $this->addressRepository->create([
            'user_id' => $request->filled('user_id') && $request->user()->can('create', Address::class) ? $request->user_id : $request->user()->id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'phone_number' => $request->phone_number,
            'type' => AddressType::getValue($request->type),
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Address')]), Response::HTTP_CREATED)
            ->addData('address', AddressResource::make($address))
            ->send();
    }

    /**
     * Update an address.
     *
     * @param Request $request
     * @param $address
     * @return JsonResponse
     */
    public function update(Request $request, $address): JsonResponse
    {
        $address = $this->addressRepository->findOrFailById($address);
        ApiResponse::authorize($request->user()->can('edit', $address));
        ApiResponse::validate($request->all(), [
            'city_id' => ['required', 'exists:' . City::class . ',id'],
            'address' => ['required', 'string'],
            'postal_code' => ['required', new PostalCodeRule()],
            'phone_number' => ['required', new MobileOrPhoneNumberRule()],
            'type' => ['required', new EnumKey(AddressType::class)],
        ], [], [
            'city_id' => trans('City'),
        ]);
        $address = $this->addressRepository->update($address, [
            'city_id' => $request->city_id,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'phone_number' => $request->phone_number,
            'type' => AddressType::getValue($request->type),
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Address')]))
            ->addData('address', AddressResource::make($address))
            ->send();
    }

    /**
     * Delete an address.
     *
     * @param Request $request
     * @param $address
     * @return JsonResponse
     */
    public function destroy(Request $request, $address): JsonResponse
    {
        $address = $this->addressRepository->findOrFailById($address);
        ApiResponse::authorize($request->user()->can('delete', $address));
        $this->addressRepository->destroy($address);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Address')]))->send();
    }

    #endregion
}
