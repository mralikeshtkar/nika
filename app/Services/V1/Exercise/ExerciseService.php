<?php

namespace App\Services\V1\Exercise;

use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        ApiResponse::validate($request->all(),[

        ]);
    }

    #endregion
}
