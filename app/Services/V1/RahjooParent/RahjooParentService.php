<?php

namespace App\Services\V1\RahjooParent;

use App\Enums\RahjooParent\RahjooParentGender;
use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Rahjoo\RahjooResource;
use App\Http\Resources\V1\RahjooParent\RahjooParentResource;
use App\Models\Grade;
use App\Models\Job;
use App\Models\Major;
use App\Models\RahjooParent;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Repositories\V1\RahjooParent\Interfaces\RahjooParentRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Rules\MobileRule;
use App\Services\V1\BaseService;
use BenSampo\Enum\Rules\EnumKey;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RahjooParentService extends BaseService
{
    #region Constructor

    private RahjooParentRepositoryInterface $rahjooParentRepository;

    /**
     * RahjooParentService constructor.
     *
     * @param RahjooParentRepositoryInterface $rahjooParentRepository
     */
    public function __construct(RahjooParentRepositoryInterface $rahjooParentRepository)
    {
        $this->rahjooParentRepository = $rahjooParentRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get rahjoo parents as pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', RahjooParent::class));
        $rahjoos = resolve(RahjooRepositoryInterface::class)
            ->hasMotherOrFather()
            ->with(['mother','father'])
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($rahjoos)->additional(['itemsResource' => RahjooResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjoos', $resource)
            ->send();
    }

    /**
     * Store or update a rahjoo parent.
     *
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function store(Request $request, $rahjoo): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', RahjooParent::class));
        $rahjoo = resolve(RahjooRepositoryInterface::class)->findOrFailById($rahjoo);
        ApiResponse::validate($request->all(), [
            'job_id' => ['nullable', 'exists:' . Job::class . ',id'],
            'grade_id' => ['nullable', 'exists:' . Grade::class . ',id'],
            'major_id' => ['nullable', 'exists:' . Major::class . ',id'],
            'name' => ['required', 'string'],
            'mobile' => ['required', new MobileRule()],
            'gender' => ['required', new EnumKey(RahjooParentGender::class)],
            'birthdate' => ['nullable', 'jdate:' . RahjooParent::BIRTHDATE_VALIDATION_FORMAT],
            'child_count' => ['nullable', 'numeric'],
        ], [], [
            'job_id' => trans('Job'),
            'grade_id' => trans('Grade'),
            'major_id' => trans('Major'),
        ]);
        $request->merge([
            'mobile' => to_valid_mobile_number($request->mobile),
            'birthdate' => $request->filled('birthdate') ? Verta::parseFormat(RahjooParent::BIRTHDATE_VALIDATION_FORMAT, $request->birthdate) : null,
            'gender' => RahjooParentGender::coerce($request->gender),
        ]);
        $rahjooParent = $this->rahjooParentRepository->updateOrCreate([
            'rahjoo_id' => $rahjoo->id,
            'gender' => $request->gender->value,
        ], [
            'user_id' => $request->user()->id,
            'rahjoo_id' => $rahjoo->id,
            'job_id' => $request->job_id,
            'grade_id' => $request->grade_id,
            'major_id' => $request->major_id,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'gender' => $request->gender->value,
            'birthdate' => $request->filled('birthdate') ? $request->birthdate->datetime() : null,
            'child_count' => $request->child_count,
        ]);
        return ApiResponse::message(trans("The information was register successfully"))
            ->addData('rahjooParent', RahjooParentResource::make($rahjooParent))
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjooParent
     * @return JsonResponse
     */
    public function destroy(Request $request, $rahjooParent): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', RahjooParent::class));
        $rahjooParent = $this->rahjooParentRepository->findOrFailById($rahjooParent);
        $this->rahjooParentRepository->destroy($rahjooParent);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('RahjooParent')]))->send();
    }

    #endregion
}
