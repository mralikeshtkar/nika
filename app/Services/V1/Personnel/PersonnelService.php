<?php

namespace App\Services\V1\Personnel;

use App\Enums\Personnel\PersonnelLanguageLevel;
use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Personnel\PersonnelResource;
use App\Http\Resources\V1\User\UserResource;
use App\Models\City;
use App\Models\Job;
use App\Models\Major;
use App\Models\Personnel;
use App\Repositories\V1\Personnel\Interfaces\PersonnelRepositoryInterface;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class PersonnelService extends BaseService
{
    private PersonnelRepositoryInterface $personnelRepository;

    #region Constructor

    /**
     * PersonnelService constructor.
     *
     * @param PersonnelRepositoryInterface $personnelRepository
     */
    public function __construct(PersonnelRepositoryInterface $personnelRepository)
    {
        $this->personnelRepository = $personnelRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get users with personnel information as pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', Personnel::class));
        $personnels = $this->personnelRepository->with(['user'])
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($personnels)->additional(['itemsResource' => PersonnelResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('personnels', $resource)
            ->send();
    }

    /**
     * Update or store personnel.
     *
     * @param Request $request
     * @param $user
     * @return JsonResponse
     */
    public function store(Request $request, $user): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', Personnel::class));
        $user = resolve(UserRepositoryInterface::class)->findOrFailById($user);
        abort_if(!$user->isPersonnel(), ApiResponse::error(trans("The user is not a personnel"), Response::HTTP_BAD_REQUEST)->send());
        ApiResponse::validate($request->all(), [
            'major_id' => ['nullable', 'exists:' . Major::class . ',id'],
            'job_id' => ['nullable', 'exists:' . Job::class . ',id'],
            'birth_certificate_place_id' => ['nullable', 'exists:' . City::class . ',id'],
            'is_married' => ['nullable', 'boolean'],
            'birth_certificate_number' => ['nullable', 'numeric'],
            'email' => ['nullable', 'email'],
            'language_level' => ['nullable', new EnumValue(PersonnelLanguageLevel::class)],
            'computer_level' => ['nullable', new EnumValue(PersonnelLanguageLevel::class)],
            'research_history' => ['nullable', 'string'],
            'is_working' => ['nullable', 'boolean'],
            'work_description' => ['nullable', 'string'],
        ], [], [
            'major_id' => trans('Major'),
            'job_id' => trans('Job'),
            'birth_certificate_place_id' => trans('Brith certificate place'),
            'is_married' => trans('Marital status'),
            'is_working' => trans('Work status'),
        ]);
        $personnel = $this->personnelRepository->updateOrCreate([
            'user_id' => $user->id,
        ], [
            'user_id' => $user->id,
            'major_id' => $request->major_id,
            'job_id' => $request->job_id,
            'birth_certificate_place_id' => $request->birth_certificate_place_id,
            'is_married' => $request->is_married,
            'birth_certificate_number' => $request->birth_certificate_number,
            'email' => $request->email,
            'language_level' => $request->language_level,
            'computer_level' => $request->computer_level,
            'research_history' => $request->research_history,
            'is_working' => $request->is_working,
            'work_description' => $request->work_description,
        ]);
        return ApiResponse::message(trans("The information was register successfully"))
            ->addData('personnel', PersonnelResource::make($personnel))
            ->send();
    }

    /**
     *  Show a user with personnel information.
     *
     * @param Request $request
     * @param $user
     * @return JsonResponse
     */
    public function show(Request $request, $user): JsonResponse
    {
        $user = resolve(UserRepositoryInterface::class)->findOrFailByIdWithPersonnel($user);
        abort_if(!$user->isPersonnel(), ApiResponse::error(trans("The user is not a personnel"), Response::HTTP_BAD_REQUEST)->send());
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('user', UserResource::make($user))
            ->send();
    }

    /**
     * Destroy a personnel.
     *
     * @param Request $request
     * @param $user
     * @return JsonResponse
     */
    public function destroy(Request $request, $user): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', Personnel::class));
        $user = resolve(UserRepositoryInterface::class)->findOrFailByIdHasPersonnel($user);
        $this->personnelRepository->destroy($user->personnel);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Personnel')]))->send();
    }

    #endregion
}
