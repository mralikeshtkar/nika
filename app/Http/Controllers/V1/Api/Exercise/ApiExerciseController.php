<?php

namespace App\Http\Controllers\V1\Api\Exercise;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Models\Package;
use App\Services\V1\Exercise\ExerciseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiExerciseController extends ApiBaseController
{
    /**
     * @var ExerciseService
     */
    private ExerciseService $exerciseService;

    /**
     * @param ExerciseService $exerciseService
     */
    public function __construct(ExerciseService $exerciseService)
    {
        $this->exerciseService = $exerciseService;
    }

    public function index(Request $request)
    {
        DB::enableQueryLog();
        $packages = Package::query()->with('intelligences.pivot.exercises')->get();
//        dd(DB::getQueryLog());
        dd($packages->first()->intelligences->first()->pivot->exercises);
        dd(Package::first()->intelligences()->with('packageExercises')->first()->toArray());
    }
}
