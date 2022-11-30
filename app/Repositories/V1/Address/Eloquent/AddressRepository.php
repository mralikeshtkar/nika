<?php

namespace App\Repositories\V1\Address\Eloquent;

use App\Enums\AddressType;
use App\Models\Address;
use App\Repositories\V1\Address\Interfaces\AddressRepositoryInterface;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class AddressRepository extends BaseRepository implements AddressRepositoryInterface
{
    /**
     * AddressRepository constructor.
     *
     * @param Address $model
     */
    #[Pure] public function __construct(Address $model)
    {
        parent::__construct($model);
    }

    /**
     * Store an address.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        $address = parent::create($attributes);
        return $address->loadProvinceName()->loadCityName();
    }

    /**
     * Update an address.
     *
     * @param $model
     * @param array $attributes
     * @return mixed|void
     */
    public function update($model, array $attributes)
    {
        parent::update($model, $attributes);
        return $model->loadProvinceName()->loadCityName();
    }

    /**
     * Filter pagination.
     *
     * @param Request $request
     * @return $this
     */
    public function filterPagination(Request $request): static
    {
        $this->filterUserId($request)
            ->filterUserName($request)
            ->filterPhoneNumber($request)
            ->filterType($request)
            ->filterSort($request);
        return $this;
    }

    /**
     * @return $this
     */
    public function withProvinceName(): static
    {
        $this->model = $this->model->withCityName();
        return $this;
    }

    /**
     * @return $this
     */
    public function withCityName(): static
    {
        $this->model = $this->model->withProvinceName();
        return $this;
    }

    /**
     * Filter by username address.
     *
     * @param Request $request
     * @return $this
     */
    private function filterUserName(Request $request): static
    {
        $this->model = $this->model->when($request->user()->can('index', Address::class) && $request->filled('name'), function (Builder $builder) use ($request) {
            $builder->where(function (Builder $builder) use ($request) {
                $builder->whereHas('user', function (Builder $builder) use ($request) {
                    $builder->where('first_name', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
                });
            });
        });
        return $this;
    }

    /**
     * Filter by userId address.
     *
     * @param Request $request
     * @return $this
     */
    private function filterUserID(Request $request): static
    {
        $this->model = $this->model->when($request->user()->can('index', Address::class) && $request->filled('user_id'), function (Builder $builder) use ($request) {
            $builder->where('user_id', $request->user_id);
        });
        return $this;
    }

    /**
     * Filter by phone number address.
     *
     * @param Request $request
     * @return $this
     */
    public function filterPhoneNumber(Request $request): static
    {
        $this->model = $this->model->when($request->filled('phone_number'), function (Builder $builder) use ($request) {
            $builder->where('phone_number', 'LIKE', '%' . $request->phone_number . '%');
        });
        return $this;
    }

    /**
     * Filter by type address.
     *
     * @param Request $request
     * @return $this
     */
    public function filterType(Request $request): static
    {
        $this->model = $this->model->when($request->filled('type') && AddressType::hasKey($request->type), function (Builder $builder) use ($request) {
            $builder->where('type', AddressType::getValue($request->type));
        });
        return $this;
    }

    /**
     * Sort addresses.
     *
     * @param Request $request
     * @return $this
     */
    public function filterSort(Request $request): static
    {
        $this->model = $this->model->when($request->isOldest(), function (Builder $builder) use ($request) {
            $builder->oldest();
        }, function (Builder $builder) use ($request) {
            $builder->latest();
        });
        return $this;
    }


}
