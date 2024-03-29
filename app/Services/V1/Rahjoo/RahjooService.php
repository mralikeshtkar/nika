<?php

namespace App\Services\V1\Rahjoo;

use App\Enums\Order\OrderStatus;
use App\Enums\Question\QuestionAnswerType;
use App\Enums\Role;
use App\Http\Resources\V1\Comment\CommentResource;
use App\Http\Resources\V1\Exercise\ExerciseResource;
use App\Http\Resources\V1\IntelligencePackagePointRahjoo\IntelligencePackagePointRahjooResource;
use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Question\QuestionResource;
use App\Http\Resources\V1\Rahjoo\RahjooResource;
use App\Models\Exercise;
use App\Models\Intelligence;
use App\Models\IntelligencePackage;
use App\Models\Package;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionPointRahjoo;
use App\Models\Rahjoo;
use App\Models\User;
use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use App\Repositories\V1\Intelligence\Interfaces\IntelligenceRepositoryInterface;
use App\Repositories\V1\Order\Interfaces\OrderRepositoryInterface;
use App\Repositories\V1\Package\Interfaces\IntelligencePackageRepositoryInterface;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use App\Repositories\V1\Question\Interfaces\QuestionRepositoryInterface;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooSupportRepositoryInterface;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Rules\UserHasRoleRule;
use App\Services\V1\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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
     * @param Request $request
     * @return JsonResponse
     */
    public function packages(Request $request): JsonResponse
    {
        $rahjoos = Rahjoo::query()
            ->select(['id', 'user_id', 'rahyab_id', 'package_id', 'code'])
            ->lastExercise(true)
            ->whereNotNull('package_id')
            ->with(['user:id,first_name,last_name,birthdate', 'package:id,title', 'rahyab:id,first_name,last_name'])
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($rahjoos)->additional(['itemsResource' => RahjooResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjoos', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function haveNotSupport(Request $request): JsonResponse
    {
        $rahjoos = Rahjoo::query()
            ->with(['user:id,first_name,last_name', 'requestSupport:id,user_id,conformer_id,created_at'])
            ->withCount(['payments' => function ($q) {
                $q->success();
            }])->doesntHave('support')
            ->paginate($request->get('perPage', 10));
        $items = $rahjoos->getCollection()->map(function ($item) {
            $item->paid = (bool)$item->payments_count;
            return $item;
        });
        $rahjoos = $rahjoos->setCollection($items);
        $resource = PaginationResource::make($rahjoos)->additional(['itemsResource' => RahjooResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjoos', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function exercises(Request $request, $rahjoo): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])->findOrFailById($rahjoo);
        $exercises = $rahjoo->packageExercises()
            ->withAggregate('questionAnswer AS latest_answer_at', 'question_answers.created_at')
            ->with(['intelligence:id,title'])
            ->whereHas('questions', function ($q) use ($request, $rahjoo) {
                $q->whereHas('answerTypes', function ($q) use ($request, $rahjoo) {
                    $q->when($request->filled('answered'), function ($q) use ($request, $rahjoo) {
                        $q->whereHas('answer', function ($q) use ($request, $rahjoo) {
                            $q->where('rahjoo_id', $rahjoo->id);
                        });
                    })->when($request->filled('notAnswered'), function ($q) use ($request, $rahjoo) {
                        $q->whereDoesntHave('answer', function ($q) use ($request, $rahjoo) {
                            $q->where('rahjoo_id', $rahjoo->id);
                        });
                    });
                });
            })
            ->paginate();
        $resource = PaginationResource::make($exercises)->additional(['itemsResource' => ExerciseResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('exercises', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $exercise
     * @return JsonResponse
     */
    public function questions(Request $request, $rahjoo, $exercise): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])->findOrFailById($rahjoo);
        $exercise = $rahjoo->packageExercises()
            ->withAggregate('questionAnswer AS latest_answer_at', 'question_answers.created_at')
            ->with(['intelligence:id,title'])
            ->whereHas('questions', function ($q) use ($request, $rahjoo) {
                $q->has('answerTypes');
            })->findOrFail($exercise);
        /** @var Exercise $exercise */
        $questions = $exercise->questions()
            ->withAggregate('latestAnswer', 'created_at')
            ->withAggregate('questionDurationStart', 'start')
            ->withWhereHas('answerTypes', function ($q) use ($request, $rahjoo) {
                $q->with(['answer' => function ($q) use ($rahjoo) {
                    $q->with(['file'])->where('rahjoo_id', $rahjoo->id);
                }])->when($request->filled('answered'), function ($q) use ($request, $rahjoo) {
                    $q->whereHas('answer', function ($q) use ($request, $rahjoo) {
                        $q->with(['file'])->where('rahjoo_id', $rahjoo->id);
                    });
                })->when($request->filled('notAnswered'), function ($q) use ($request, $rahjoo) {
                    $q->whereDoesntHave('answer', function ($q) use ($request, $rahjoo) {
                        $q->where('rahjoo_id', $rahjoo->id);
                    });
                });
            })->paginate();
        $resource = PaginationResource::make($questions)->additional(['itemsResource' => QuestionResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('exercise', new ExerciseResource($exercise))
            ->addData('questions', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $exercise
     * @param $question
     * @return JsonResponse
     */
    public function question(Request $request, $rahjoo, $exercise, $question): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])->findOrFailById($rahjoo);
        $exercise = $rahjoo->packageExercises()
            ->withAggregate('questionAnswer AS latest_answer_at', 'question_answers.created_at')
            ->with(['intelligence:id,title'])
            ->whereHas('questions', function ($q) use ($request, $rahjoo) {
                $q->has('answerTypes');
            })->findOrFail($exercise);
        $question = $exercise->questions()
            ->withAggregate('latestAnswer', 'created_at')
            ->withAggregate('questionDurationStart', 'start')->with(['files' => function ($q) {
                $q->select(['id', 'question_id', 'media_id', 'text'])
                    ->with(['media']);
            }])
            ->withWhereHas('answerTypes', function ($q) use ($request, $rahjoo) {
                $q->with(['answer' => function ($q) use ($rahjoo) {
                    $q->with(['file'])->where('rahjoo_id', $rahjoo->id);
                }]);
            })->findOrFail($question);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('exercise', new ExerciseResource($exercise))
            ->addData('questions', new QuestionResource($question))
            ->send();
    }

    /**
     * @param Request $request
     * @param $exercise
     * @param $rahjoo
     * @param $question
     * @return JsonResponse
     */
    public function questionIsCompleted(Request $request, $rahjoo, $exercise, $question): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])->findOrFailById($rahjoo);
        $exercise = $rahjoo->packageExercises()
            ->whereHas('questions', function ($q) use ($request) {
                $q->has('answerTypes');
            })->findOrFail($exercise);
        $question = $exercise->questions()
            ->withWhereHas('answerTypes', function ($q) use ($request, $rahjoo) {
                $q->with(['answer' => function ($q) use ($rahjoo) {
                    $q->with(['file'])->where('rahjoo_id', $rahjoo->id);
                }]);
            })->addSelect(['rahjoo_answers_count' => QuestionAnswer::query()->selectRaw('COUNT(*)')
                ->where('question_answers.rahjoo_id', $rahjoo)
                ->whereColumn('questions.id', '=', 'question_answers.question_id'),
            ])->findOrFail($question);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('question', new QuestionResource($question))
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
        //ApiResponse::authorize($request->user()->can('show', Rahjoo::class));
        $rahjoo = $this->rahjooRepository->select([
            'id', 'user_id', 'rahyab_id', 'agent_id', 'package_id', 'code', 'school', 'which_child_of_family', 'disease_background',
        ])->with([
            'support',
            'support.support:id,first_name,last_name',
            'package:id,title',
            'user:id,first_name,last_name,mobile,birthdate',
            'user.profile',
            'user.profile',
        ])->findorFailById($rahjoo);
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
    public function verifyOrder(Request $request, $rahjoo): JsonResponse
    {
        /** @var Rahjoo $rahjoo */
        $rahjoo = $this->rahjooRepository->select(['id'])->findorFailById($rahjoo);
        ApiResponse::validate($request->all(), [
            'code' => ['required', 'string'],
        ]);
        $order = $rahjoo->orders()
            ->where(DB::raw('lower(code)'),  strtolower($request->code))
//            ->where('status', OrderStatus::Delivered)
            ->notUsed()
            ->first();
        abort_if(!$order, ApiResponse::message(trans("Verification code is invalid"), Response::HTTP_BAD_REQUEST)->send());
        try {
            return DB::transaction(function () use ($request, $rahjoo, $order) {
                $this->rahjooRepository->update($rahjoo, [
                    'package_id' => $order->payment->paymentable->id,
                ]);
                resolve(OrderRepositoryInterface::class)->update($order, [
                    'is_used' => true,
                    'status' => OrderStatus::Delivered,
                ]);
                return ApiResponse::message(trans("Mission accomplished"))->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::message(trans("Internal server error"), Response::HTTP_INTERNAL_SERVER_ERROR)->send();
        }
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function assignSupport(Request $request, $rahjoo): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id'])->findorFailById($rahjoo);
        ApiResponse::validate($request->all(), [
            'support_id' => ['required', new UserHasRoleRule(Role::SUPPORT)],
        ]);
        resolve(RahjooSupportRepositoryInterface::class)->updateOrCreate([
            'rahjoo_id' => $rahjoo->id,
            'support_id' => $request->support_id,
        ], [
            'rahjoo_id' => $rahjoo->id,
            'support_id' => $request->support_id,
        ]);
        return ApiResponse::message(trans("Mission accomplished"))->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $user
     * @return JsonResponse
     */
    public function assignRahyab(Request $request, $rahjoo, $user): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id'])->findorFailById($rahjoo);
        $user = resolve(UserRepositoryInterface::class)->select(['id'])->findOrFailById($user);
        $this->rahjooRepository->update($rahjoo, [
            'rahyab_id' => $user->id,
        ]);
        return ApiResponse::message(trans("Mission accomplished"))->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $user
     * @return JsonResponse
     */
    public function assignRahnama(Request $request, $rahjoo, $user): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id'])->findorFailById($rahjoo);
        $user = resolve(UserRepositoryInterface::class)->select(['id'])->findOrFailById($user);
        $this->rahjooRepository->update($rahjoo, [
            'rahnama_id' => $user->id,
        ]);
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
        $exercise = resolve(PackageRepositoryInterface::class)->getNextExercise($request, $rahjoo->package, $rahjoo);
        return ApiResponse::message(trans("The information was register successfully"))
            ->addData('exercise', $exercise ? new ExerciseResource($exercise) : null)
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
        $questions = resolve(ExerciseRepositoryInterfaces::class)->paginateQuestions($request, $exercise, $rahjoo->id);
        $resource = PaginationResource::make($questions)->additional(['itemsResource' => QuestionResource::class]);
        return ApiResponse::message(trans("The information was register successfully"))
            ->addData('questions', $resource)
            ->send();
    }

    public function exerciseSingleQuestion(Request $request, $rahjoo, $exercise, $question)
    {
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])
            ->with(['package:id'])
            ->findorFailById($rahjoo);
        $exercise = resolve(PackageRepositoryInterface::class)->findPackageExerciseById($request, $rahjoo->package, $exercise);
        $question = resolve(ExerciseRepositoryInterfaces::class)->findSingleQuestion($request, $exercise, $question, $rahjoo->id);;
        return ApiResponse::message(trans("The information was register successfully"))
            ->addData('question', new QuestionResource($question))
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
        ApiResponse::authorize($request->user()->can('manageQuestionPoints', [$rahjoo, $question]));
        $rules = collect($question->pivotPoints)->mapWithKeys(function ($item) {
            return ['points.' . $item->intelligence_point_id => ['nullable', 'numeric', 'min:0', 'max:' . $item->max_point],];
        })->put('points', ['required', 'array'])->toArray();
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
        ApiResponse::authorize($request->user()->can('manageQuestionPoints', [$rahjoo, $question]));
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
            'point' => ['required', 'numeric', 'between:0,' . $point->pivot->max_point],
        ]);
        $this->rahjooRepository->updateQuestionPoints($rahjoo, $point->id, $question->id, $request->point);
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
        /** @var Question $question */
        $question = $this->rahjooRepository->query($rahjoo->questions())->findOrFailById($question);
        $question->load([
            'points' => function ($q) use ($question, $rahjoo) {
                $q->withPointName()
                    ->with(['pivotQuestionPointRahjoo' => function ($q) use ($question, $rahjoo) {
                        $q->with(['user:id,first_name,last_name,mobile'])
                            ->where('question_id', $question->id)
                            ->where('rahjoo_id', $rahjoo->id);
                    }]);
            },
        ]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('question', new QuestionResource($question))
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $question
     * @return JsonResponse
     */
    public function storeQuestionComment(Request $request, $rahjoo, $question): JsonResponse
    {
        /** @var Rahjoo $rahjoo */
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])->findorFailById($rahjoo);
        ApiResponse::authorize($request->user()->can('storeQuestionComment', $rahjoo));
        $question = $this->rahjooRepository->query($rahjoo->questions())->findOrFailById($question);
        ApiResponse::validate($request->all(), [
            'body' => ['required', 'string'],
        ]);
        $comment = resolve(QuestionRepositoryInterface::class)->storeComment($question, [
            'user_id' => $request->user()->id,
            'rahjoo_id' => $rahjoo->id,
            'body' => $request->body,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Comment')]), Response::HTTP_CREATED)
            ->addData('comment', new CommentResource($comment))
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $question
     * @return JsonResponse
     */
    public function questionComments(Request $request, $rahjoo, $question): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])->findorFailById($rahjoo);
        $questionRepository = resolve(QuestionRepositoryInterface::class);
        $question = $this->rahjooRepository->query($rahjoo->questions())->findOrFailById($question);
        $comments = $questionRepository->query($question->comments()->latest())
            ->select(['id', 'user_id', 'body', 'created_at'])
            ->with(['user' => function ($q) {
                $q->select(['id', 'first_name', 'last_name'])
                    ->with('roles:id,name,name_fa');
            }])
            ->where('rahjoo_id', $rahjoo->id)
            ->paginate($request->get('perPage', 15));
        $resource = PaginationResource::make($comments)->additional(['itemsResource' => CommentResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('comments', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $intelligencePackage
     * @return JsonResponse
     */
    public function storeIntelligencePackageComment(Request $request, $rahjoo, $intelligencePackage): JsonResponse
    {
        /** @var Rahjoo $rahjoo */
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])->findorFailById($rahjoo);
        $intelligencePackageRepository = resolve(IntelligencePackageRepositoryInterface::class);
        $intelligencePackage = $intelligencePackageRepository->query($rahjoo->packagePivotIntelligencePackage())
            ->select(['pivot_id'])
            ->findOrFailByPivotId($intelligencePackage);
        ApiResponse::validate($request->all(), [
            'body' => ['required', 'string'],
        ]);
        $comment = $intelligencePackageRepository->storeComment($intelligencePackage, [
            'user_id' => $request->user()->id,
            'rahjoo_id' => $rahjoo->id,
            'body' => $request->body,
        ]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('comment', new CommentResource($comment))
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $intelligencePackage
     * @return JsonResponse
     */
    public function intelligencePackageComments(Request $request, $rahjoo, $intelligencePackage): JsonResponse
    {
        /** @var Rahjoo $rahjoo */
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])->findorFailById($rahjoo);
        $intelligencePackageRepository = resolve(IntelligencePackageRepositoryInterface::class);
        /** @var IntelligencePackage $intelligencePackage */
        $intelligencePackage = $intelligencePackageRepository->query($rahjoo->pivotIntelligencePackage())
            ->select(['pivot_id'])
            ->findOrFailByPivotId($intelligencePackage);
        $comments = $intelligencePackageRepository->query($intelligencePackage->comments()->latest())
            ->select(['id', 'user_id', 'body', 'created_at'])
            ->with(['user:id,first_name,last_name'])
            ->where('rahjoo_id', $rahjoo->id)
            ->paginate($request->get('perPage', 15));
        $resource = PaginationResource::make($comments)->additional(['itemsResource' => CommentResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('comments', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function intelligenceRahnama(Request $request, $rahjoo): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id'])
            ->with(['pivotIntelligenceRahnama'])
            ->findorFailById($rahjoo);
        $intelligences = resolve(IntelligenceRepositoryInterface::class)
            ->select(['id', 'title'])
            ->get()
            ->map(function ($item) use ($rahjoo) {
                /** @var Intelligence $item */
                if ($i = $rahjoo->pivotIntelligenceRahnama->firstWhere('intelligence_id', $item->id))
                    return collect($item)->merge($i);
                else
                    return collect($item)->merge(['rahnama_id' => null, 'intelligence_id' => null, 'rahjoo_id' => null]);
            });
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('intelligences', $intelligences)
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function storeIntelligenceRahnama(Request $request, $rahjoo): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])
            ->with(['package:id', 'package.pivotIntelligences:package_id,intelligence_id'])
            ->findorFailById($rahjoo);
        ApiResponse::validate($request->all(), [
            'rahnama_id' => ['required', 'exists:' . User::class . ',id'],
            'intelligence_id' => ['required', 'exists:' . Intelligence::class . ',id'],
        ]);
        try {
            return DB::transaction(function () use ($rahjoo, $request) {
                $this->rahjooRepository->storeIntelligenceRahnama($rahjoo, $request->rahnama_id, $request->intelligence_id);
                return ApiResponse::message(trans("Mission accomplished"))
                    ->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::message(trans("Internal server error"), Response::HTTP_INTERNAL_SERVER_ERROR)->send();
        }
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function haveNotRahnamaRahyab(Request $request): JsonResponse
    {
        $rahjoos = $this->rahjooRepository->select(['id', 'user_id', 'package_id', 'rahnama_id', 'rahyab_id'])
            ->with(['user:id,first_name,last_name,mobile'])
            ->haveNotRahnamaRahyab($request)
            ->whereNotNull('package_id')
            ->paginate($request->get('perPage', 10));
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjoos', RahjooResource::collection($rahjoos))
            ->send();
    }

    public function storeIntelligencePackagePoints(Request $request, $rahjoo, $intelligencePackage)
    {
        /** @var Rahjoo $rahjoo */
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])->findorFailById($rahjoo);
        /** @var IntelligencePackage $intelligencePackage */
        $intelligencePackage = resolve(IntelligencePackageRepositoryInterface::class)
            ->query($rahjoo->packagePivotIntelligencePackage())
            ->findOrFailByPivotId($intelligencePackage);
        $rules = $intelligencePackage->points->pluck('max_point', 'id')->mapWithKeys(function ($max_point, $intelligence_id) {
            return [
                'points.' . $intelligence_id => ['nullable', 'numeric', 'between:0,' . $max_point],
            ];
        })->put('points', ['required', 'array', 'min:1'])
            ->toArray();
        ApiResponse::validate($request->all(), $rules);
        try {
            return DB::transaction(function () use ($rahjoo, $intelligencePackage, $request) {
                $points = collect($request->points)->mapWithKeys(function ($point, $intelligence_id) use ($request, $rahjoo, $intelligencePackage) {
                    return [
                        $intelligence_id => [
                            'user_id' => $request->user()->id,
                            'intelligence_package_id' => $intelligencePackage->pivot_id,
                            'point' => $point,
                        ],
                    ];
                });
                $intelligencePackagePoints = $rahjoo->pivotIntelligencePackagePoints()
                    ->where('intelligence_package_id', $intelligencePackage->pivot_id)
                    ->get();
                $this->rahjooRepository->attachIntelligencePackagePoints($rahjoo, $intelligencePackagePoints, $points);
                return ApiResponse::message(trans("Mission accomplished"))->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::message(trans("Internal server error"), Response::HTTP_INTERNAL_SERVER_ERROR)->send();
        }
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $intelligencePackage
     * @return JsonResponse
     */
    public function showIntelligencePackagePoints(Request $request, $rahjoo, $intelligencePackage): JsonResponse
    {
        /** @var Rahjoo $rahjoo */
        $rahjoo = $this->rahjooRepository->select(['id', 'package_id'])->findorFailById($rahjoo);
        $intelligencePackageRepository = resolve(IntelligencePackageRepositoryInterface::class);
        /** @var IntelligencePackage $intelligencePackage */
        $intelligencePackage = $intelligencePackageRepository->query($rahjoo->packagePivotIntelligencePackage())
            ->findOrFailByPivotId($intelligencePackage);
        $points = $this->rahjooRepository->query($rahjoo->pivotIntelligencePackagePoints()->where('intelligence_package_id', $intelligencePackage->pivot_id))
            ->select(['user_id', 'rahjoo_id', 'intelligence_package_id', 'intelligence_point_id', 'point', 'created_at',])
            ->get();
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('points', IntelligencePackagePointRahjooResource::collection($points))
            ->send();
    }

    #endregion

}
