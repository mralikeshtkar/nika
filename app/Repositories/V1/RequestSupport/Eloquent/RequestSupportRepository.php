<?php

namespace App\Repositories\V1\RequestSupport\Eloquent;

use App\Models\RequestSupport;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\RequestSupport\Interfaces\RequestSupportRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RequestSupportRepository extends BaseRepository implements RequestSupportRepositoryInterface
{
    public function __construct(RequestSupport $model)
    {
        parent::__construct($model);
    }

    /**
     * @return $this
     */
    public function notConfirmed(): static
    {
        $this->model->whereNull('conformer_id');
        return $this;
    }

    public function filterPagination(Request $request): static
    {
        $this->model->when($request->file('name'), function ($q) use ($request) {
            $q->whereHas('user', function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('first_name', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
                });
            });
        })->when($request->file('mobile'), function ($q) use ($request) {
            $q->whereHas('user', function ($q) use ($request) {
                $q->where('mobile', 'LIKE', '%' . $request->mobile . '%');
            });
        })->when($request->file('confirmed'), function ($q) use ($request) {
            $q->when($request->confirmed == 0, function ($q) use ($request) {
                $q->whereNull('conformer_id');
            })->when($request->confirmed == 1, function ($q) use ($request) {
                $q->whereNotNull('conformer_id')->with(['conformer:id,first_name,last_name']);
            });
        });
        return $this;
    }
}
