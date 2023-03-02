<?php

namespace App\Services\V1\Rahjoo;

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
use App\Models\QuestionPointRahjoo;
use App\Models\Rahjoo;
use App\Models\User;
use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use App\Repositories\V1\Intelligence\Interfaces\IntelligenceRepositoryInterface;
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

class RahjooSupportService extends BaseService
{
    private RahjooSupportRepositoryInterface $rahjooSupportRepository;

    #region Constructor

    /**
     * RahjooService constructor.
     *
     * @param RahjooSupportRepositoryInterface $rahjooSupportRepository
     */
    public function __construct(RahjooSupportRepositoryInterface $rahjooSupportRepository)
    {
        $this->rahjooSupportRepository = $rahjooSupportRepository;
    }

    #endregion

    #region Public methods



    #endregion

}
