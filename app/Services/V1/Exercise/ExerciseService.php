<?php

namespace App\Services\V1\Exercise;

use App\Http\Resources\V1\Exercise\ExerciseResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\IntelligencePackage;
use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use App\Repositories\V1\Question\Interfaces\QuestionRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ExerciseService extends BaseService
{
    /**
     * @var ExerciseRepositoryInterfaces
     */
    private ExerciseRepositoryInterfaces $exerciseRepository;

    #region Constructor

    /**
     * @param ExerciseRepositoryInterfaces $exerciseRepository
     */
    public function __construct(ExerciseRepositoryInterfaces $exerciseRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
    }

    #endregion

    #region Public methods

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $exercises = $this->exerciseRepository->select(['id', 'intelligence_package_id', 'title', 'is_locked', 'created_at'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($exercises)->additional(['itemsResource' => ExerciseResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('exercises', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $exercise
     * @return JsonResponse
     */
    public function show(Request $request, $exercise): JsonResponse
    {
        $exercise=$this->exerciseRepository->select(['id', 'intelligence_package_id', 'title', 'is_locked', 'created_at'])
            ->findOrFailById($exercise);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('exercises', new ExerciseResource($exercise))
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::validate($request->all(), [
            'package_id' => ['required', Rule::exists(IntelligencePackage::class, 'package_id')->where('intelligence_id', $request->intelligence_id)],
            'intelligence_id' => ['required', Rule::exists(IntelligencePackage::class, 'intelligence_id')->where('package_id', $request->package_id)],
            'title' => ['required', 'string'],
            'is_locked' => ['nullable', 'boolean'],
        ]);
        $exercise = $this->exerciseRepository->create([
            'user_id' => $request->user()->id,
            'package_id' => $request->package_id,
            'intelligence_id' => $request->intelligence_id,
            'title' => $request->title,
            'is_locked' => $request->get('is_locked', false),
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Exercise')]), Response::HTTP_CREATED)
            ->addData('exercise', new ExerciseResource($exercise))
            ->send();
    }

    /**
     * @param Request $request
     * @param $exercise
     * @return JsonResponse
     */
    public function update(Request $request, $exercise): JsonResponse
    {
        $exercise = $this->exerciseRepository->select(['id'])->findOrFailById($exercise);
        ApiResponse::validate($request->all(), [
            'intelligence_package_id' => ['nullable', 'exists:' . IntelligencePackage::class . ',pivot_id'],
            'title' => ['required', 'string'],
            'is_locked' => ['required', 'boolean'],
        ]);
        $data = collect([
            'title' => $request->title,
            'is_locked' => $request->get('is_locked', false),
        ])->when($request->filled('intelligence_package_id'), function (Collection $collection) use ($request) {
            $collection->put('intelligence_package_id', $request->intelligence_package_id);
        })->toArray();
        $this->exerciseRepository->update($exercise, $data);
        $exercise = $this->exerciseRepository->select(['id', 'intelligence_package_id', 'title', 'is_locked'])
            ->findOrFailById($exercise->id);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Exercise')]))
            ->addData('exercise', new ExerciseResource($exercise))
            ->send();
    }

    /**
     * @param Request $request
     * @param $exercise
     * @return JsonResponse
     */
    public function questions(Request $request, $exercise): JsonResponse
    {
        $exercise = $this->exerciseRepository->select(['id'])->findOrFailById($exercise);
        $questions = $this->exerciseRepository->paginateQuestions($request, $exercise);
        $resource = PaginationResource::make($questions)->additional(['itemsResource' => ExerciseResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('exercises', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $exercise
     * @return JsonResponse
     */
    public function destroy(Request $request, $exercise): JsonResponse
    {
        $exercise = $this->exerciseRepository->select(['id'])->findOrFailById($exercise);
        $this->exerciseRepository->destroy($exercise);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Exercise')]))->send();
    }

    #endregion
}
