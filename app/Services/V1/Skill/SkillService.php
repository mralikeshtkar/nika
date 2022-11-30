<?php

namespace App\Services\V1\Skill;

use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Skill\SkillResource;
use App\Models\City;
use App\Models\Skill;
use App\Repositories\V1\Skill\Interfaces\SkillRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SkillService extends BaseService
{
    private SkillRepositoryInterface $skillRepository;

    #region Constance

    /**
     * @param SkillRepositoryInterface $skillRepository
     */
    public function __construct(SkillRepositoryInterface $skillRepository)
    {
        $this->skillRepository = $skillRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get skills as pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', Skill::class));
        $skills = $this->skillRepository->select(['id', 'title'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($skills)->additional(['itemsResource' => SkillResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('cities', $resource)
            ->send();
    }

    /**
     * Get all skills.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $skills = $this->skillRepository->select(['id', 'title'])->get();
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('skills', SkillResource::collection($skills))
            ->send();
    }

    /**
     * Store a skill.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', Skill::class));
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string', 'unique:' . Skill::class . ',title'],
        ]);
        $skill = $this->skillRepository->create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Skill')]), Response::HTTP_CREATED)
            ->addData('skill', new SkillResource($skill))
            ->send();
    }

    /**
     * Update a skill.
     *
     * @param Request $request
     * @param $skill
     * @return JsonResponse
     */
    public function update(Request $request, $skill): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('edit', Skill::class));
        $skill = $this->skillRepository->findOrFailById($skill);
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string', 'unique:' . Skill::class . ',title,' . $skill->id],
        ]);
        $skill = $this->skillRepository->update($skill, [
            'title' => $request->title,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Skill')]))
            ->addData('skill', new SkillResource($skill))
            ->send();
    }

    /**
     * Destroy a skill.
     *
     * @param Request $request
     * @param $skill
     * @return JsonResponse
     */
    public function destroy(Request $request, $skill): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', Skill::class));
        $skill = $this->skillRepository->findOrFailById($skill);
        $this->skillRepository->destroy($skill);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Skill')]))->send();
    }

    #endregion
}
