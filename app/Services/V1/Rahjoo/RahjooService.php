<?php

namespace App\Services\V1\Rahjoo;

use App\Enums\Question\QuestionAnswerType;
use App\Enums\Role;
use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Question\QuestionResource;
use App\Http\Resources\V1\Rahjoo\RahjooResource;
use App\Models\Package;
use App\Models\Question;
use App\Models\QuestionPointRahjoo;
use App\Models\Rahjoo;
use App\Models\User;
use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use App\Repositories\V1\Question\Interfaces\QuestionRepositoryInterface;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Rules\UserHasRoleRule;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class RahjooService extends BaseService
{
    private RahjooRepositoryInterface $rahjooRepository;

    #region Constructor

    /**
     * RahjooService constructor.
     *
     * @param RahjooRepositoryInterface $rahjooRepository
     */
    public function __construct(RahjooRepositoryInterface $rahjooRepository)
    {
        $this->rahjooRepository = $rahjooRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get rahjoos with user,mother and father as pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', Rahjoo::class));
        $rahjoos = $this->rahjooRepository->with(['user'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($rahjoos)->additional(['itemsResource' => RahjooResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjoos', $resource)
            ->send();
    }

    /**
     * Show a rahjoo.
     *
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function show(Request $request, $rahjoo): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('show', Rahjoo::class));
        $rahjoo = $this->rahjooRepository->select([
            'id', 'user_id', 'agent_id', 'package_id', 'school', 'which_child_of_family', 'disease_background',
        ])->with(['user', 'father', 'mother', 'package:id,title,is_completed,price,description'])->findorFailById($rahjoo);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjoos', RahjooResource::make($rahjoo))
            ->send();
    }

    /**
     * Update or create rahjoo information.
     *
     * @param Request $request
     * @param $user
     * @return JsonResponse
     */
    public function store(Request $request, $user): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', Rahjoo::class));
        $user = resolve(UserRepositoryInterface::class)->findOrFailById($user);
        abort_if(!$user->isRahjoo(), ApiResponse::error(trans("The user is not a rahjoo"), Response::HTTP_BAD_REQUEST)->send());
        ApiResponse::validate($request->all(), [
            'agent_id' => ['nullable', new UserHasRoleRule(Role::AGENT)],
            'school' => ['nullable', 'string'],
            'which_child_of_family' => ['nullable', 'numeric', 'min:1'],
        ], [], [
            'agent_id' => trans('Agent'),
        ]);
        $rahjoo = $this->rahjooRepository->updateOrCreate([
            'user_id' => $user->id,
        ], [
            'user_id' => $user->id,
            'agent_id' => $request->agent_id,
            'school' => $request->school,
            'which_child_of_family' => $request->which_child_of_family,
            'disease_background' => $request->disease_background,
        ]);
        return ApiResponse::message(trans("The information was register successfully"))
            ->addData('rahjoo', RahjooResource::make($rahjoo))
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function assignPackage(Request $request, $rahjoo): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id'])->findorFailById($rahjoo);
        ApiResponse::validate($request->all(), [
            'package_id' => ['required', 'exists:' . Package::class . ',id'],
        ]);
        /** @var Package $package */
        $package = resolve(PackageRepositoryInterface::class)
            ->select(['id', 'status'])
            ->findOrFailById($request->package_id);
        abort_if($package->isInactive(), ApiResponse::error(trans("Package is inactive"), Response::HTTP_BAD_REQUEST)->send());
        $this->rahjooRepository->updatePackage($rahjoo, $package->id);
        return ApiResponse::message(trans("Mission accomplished"))->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function packageExercises(Request $request, $rahjoo): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])
            ->with(['package:id', 'package.pivotExercisePriority'])
            ->findorFailById($rahjoo);
        $exercises = resolve(PackageRepositoryInterface::class)->getPaginateExercises($request, $rahjoo->package, $rahjoo);
        $resource = PaginationResource::make($exercises)->additional(['itemsResource' => QuestionResource::class]);
        return ApiResponse::message(trans("The information was register successfully"))
            ->addData('questions', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $exercise
     * @return JsonResponse
     */
    public function packageQuestions(Request $request, $rahjoo, $exercise): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])
            ->with(['package:id'])
            ->findorFailById($rahjoo);
        $exercise = resolve(PackageRepositoryInterface::class)->findPackageExerciseById($request, $rahjoo->package, $exercise);
        $questions = resolve(ExerciseRepositoryInterfaces::class)->paginateQuestions($request, $exercise);
        $resource = PaginationResource::make($questions)->additional(['itemsResource' => QuestionResource::class]);
        return ApiResponse::message(trans("The information was register successfully"))
            ->addData('questions', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $question
     * @return JsonResponse
     */
    public function storeQuestionPoints(Request $request, $rahjoo, $question): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])->findorFailById($rahjoo);
        $question = $this->rahjooRepository->query($rahjoo->questions())
            ->with(['pivotPoints'])
            ->findOrFailById($question);
        $rules = collect($question->pivotPoints)->mapWithKeys(function ($item) {
            return ['points.' . $item->intelligence_point_id => ['required', 'numeric', 'min:0', 'max:' . $item->max_point],];
        })->put('points', ['required', 'array', 'size:' . $question->pivotPoints->count()])->toArray();
        ApiResponse::validate($request->all(), $rules);
        $points = collect($request->points)->mapWithKeys(function ($item, $key) use ($request, $rahjoo, $question) {
            return [
                $question->id => [
                    'user_id' => $request->user()->id,
                    'rahjoo_id' => $rahjoo->id,
                    'intelligence_point_id' => $key,
                    'point' => $item,
                ]
            ];
        })->toArray();
        $this->rahjooRepository->attachQuestionPoints($rahjoo, $points);
        return ApiResponse::message(trans("The information was register successfully"))->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $question
     * @return JsonResponse
     */
    public function updateQuestionPoints(Request $request, $rahjoo, $question): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])->findorFailById($rahjoo);
        $question = $this->rahjooRepository->query($rahjoo->questions())->findOrFailById($question);
        ApiResponse::validate($request->all(), [
            'intelligence_point_id' => [
                'required',
                Rule::exists(QuestionPointRahjoo::class, 'intelligence_point_id')
                    ->where('rahjoo_id', $rahjoo->id)
                    ->where('question_id', $question->id),
            ],
        ]);
        $point = resolve(QuestionRepositoryInterface::class)->query($question->points())->findOrFailById($request->intelligence_point_id);
        ApiResponse::validate($request->all(), [
            'point' => ['required','numeric', 'between:0,'. $point->pivot->max_point],
        ]);
        $this->rahjooRepository->updateQuestionPoints($rahjoo, $question->id,$request->point);
        return ApiResponse::message(trans("The information was register successfully"))->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $question
     * @return JsonResponse
     */
    public function showQuestionPoints(Request $request, $rahjoo, $question): JsonResponse
    {
        /** @var Rahjoo $rahjoo */
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])->findorFailById($rahjoo);
        $question = $this->rahjooRepository->query($rahjoo->questions())
            ->with([
                'pivotRahjooPoints' => function ($q) use ($rahjoo) {
                    $q->with([
                        'intelligencePointName:intelligence_point_names.id,intelligence_point_names.name',
                        'user:id,first_name,last_name,mobile',
                    ])->where('rahjoo_id', $rahjoo->id);
                },
            ])->findOrFailById($question);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('question', new QuestionResource($question))
            ->send();
    }

    /**
     * Destroy a rahjoo.
     *
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function destroy(Request $request, $rahjoo): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', Rahjoo::class));
        $rahjoo = $this->rahjooRepository->findOrFailById($rahjoo);
        $this->rahjooRepository->destroy($rahjoo);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Rahjoo')]))->send();
    }

    #endregion

}
