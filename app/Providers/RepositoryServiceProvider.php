<?php

namespace App\Providers;

use App\Repositories\V1\Address\Eloquent\AddressRepository;
use App\Repositories\V1\Address\Interfaces\AddressRepositoryInterface;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\City\Eloquent\CityRepository;
use App\Repositories\V1\City\Interfaces\CityRepositoryInterface;
use App\Repositories\V1\DocumentGroup\Eloquent\DocumentGroupRepository;
use App\Repositories\V1\DocumentGroup\Interfaces\DocumentGroupRepositoryInterface;
use App\Repositories\V1\EloquentRepositoryInterface;
use App\Repositories\V1\Exercise\Eloquent\ExerciseRepository;
use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use App\Repositories\V1\Grade\Eloquent\GradeRepository;
use App\Repositories\V1\Grade\Interfaces\GradeRepositoryInterface;
use App\Repositories\V1\Intelligence\Eloquent\IntelligenceRepository;
use App\Repositories\V1\Intelligence\Interfaces\IntelligenceRepositoryInterface;
use App\Repositories\V1\IntelligenceFeedback\Eloquent\IntelligenceFeedbackRepository;
use App\Repositories\V1\IntelligenceFeedback\Interfaces\IntelligenceFeedbackRepositoryInterface;
use App\Repositories\V1\IntelligencePoint\Eloquent\IntelligencePointRepository;
use App\Repositories\V1\IntelligencePoint\Interfaces\IntelligencePointRepositoryInterface;
use App\Repositories\V1\IntelligencePointName\Eloquent\IntelligencePointNameRepository;
use App\Repositories\V1\IntelligencePointName\Interfaces\IntelligencePointNameRepositoryInterface;
use App\Repositories\V1\Job\Eloquent\JobRepository;
use App\Repositories\V1\Job\Interfaces\JobRepositoryInterface;
use App\Repositories\V1\Major\Eloquent\MajorRepository;
use App\Repositories\V1\Major\Interfaces\MajorRepositoryInterface;
use App\Repositories\V1\Media\Eloquent\MediaRepository;
use App\Repositories\V1\Media\Interfaces\MediaRepositoryInterface;
use App\Repositories\V1\Package\Eloquent\IntelligencePackageRepository;
use App\Repositories\V1\Package\Eloquent\PackageRepository;
use App\Repositories\V1\Package\Interfaces\IntelligencePackageRepositoryInterface;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use App\Repositories\V1\Permission\Eloquent\PermissionRepository;
use App\Repositories\V1\Permission\Interfaces\PermissionRepositoryInterface;
use App\Repositories\V1\Personnel\Eloquent\PersonnelRepository;
use App\Repositories\V1\Personnel\Interfaces\PersonnelRepositoryInterface;
use App\Repositories\V1\Province\Eloquent\ProvinceRepository;
use App\Repositories\V1\Province\Interfaces\ProvinceRepositoryInterface;
use App\Repositories\V1\PsychologicalQuestion\Eloquent\PsychologicalQuestionRepository;
use App\Repositories\V1\PsychologicalQuestion\Interfaces\PsychologicalQuestionRepositoryInterface;
use App\Repositories\V1\Question\Eloquent\QuestionAnswerRepository;
use App\Repositories\V1\Question\Eloquent\QuestionAnswerTypeServiceRepository;
use App\Repositories\V1\Question\Eloquent\QuestionRepository;
use App\Repositories\V1\Question\Interfaces\QuestionAnswerRepositoryInterface;
use App\Repositories\V1\Question\Interfaces\QuestionAnswerTypeServiceRepositoryInterface;
use App\Repositories\V1\Question\Interfaces\QuestionRepositoryInterface;
use App\Repositories\V1\Rahjoo\Eloquent\RahjooRepository;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Repositories\V1\RahjooCourse\Eloquent\RahjooCourseRepository;
use App\Repositories\V1\RahjooCourse\Interfaces\RahjooCourseRepositoryInterface;
use App\Repositories\V1\RahjooParent\Eloquent\RahjooParentRepository;
use App\Repositories\V1\RahjooParent\Interfaces\RahjooParentRepositoryInterface;
use App\Repositories\V1\Role\Eloquent\RoleRepository;
use App\Repositories\V1\Role\Interfaces\RoleRepositoryInterface;
use App\Repositories\V1\Skill\Eloquent\SkillRepository;
use App\Repositories\V1\Skill\Interfaces\SkillRepositoryInterface;
use App\Repositories\V1\User\Eloquent\UserRepository;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->_registerRepositories();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * @return void
     */
    private function _registerRepositories(): void
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(ProvinceRepositoryInterface::class, ProvinceRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(GradeRepositoryInterface::class, GradeRepository::class);
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);
        $this->app->bind(MajorRepositoryInterface::class, MajorRepository::class);
        $this->app->bind(JobRepositoryInterface::class, JobRepository::class);
        $this->app->bind(PersonnelRepositoryInterface::class, PersonnelRepository::class);
        $this->app->bind(RahjooRepositoryInterface::class, RahjooRepository::class);
        $this->app->bind(RahjooParentRepositoryInterface::class, RahjooParentRepository::class);
        $this->app->bind(RahjooCourseRepositoryInterface::class, RahjooCourseRepository::class);
        $this->app->bind(PsychologicalQuestionRepositoryInterface::class, PsychologicalQuestionRepository::class);
        $this->app->bind(SkillRepositoryInterface::class, SkillRepository::class);
        $this->app->bind(IntelligenceRepositoryInterface::class, IntelligenceRepository::class);
        $this->app->bind(PackageRepositoryInterface::class, PackageRepository::class);
        $this->app->bind(IntelligencePackageRepositoryInterface::class, IntelligencePackageRepository::class);
        $this->app->bind(MediaRepositoryInterface::class, MediaRepository::class);
        $this->app->bind(IntelligenceFeedbackRepositoryInterface::class, IntelligenceFeedbackRepository::class);
        $this->app->bind(IntelligencePointRepositoryInterface::class, IntelligencePointRepository::class);
        $this->app->bind(IntelligencePointNameRepositoryInterface::class, IntelligencePointNameRepository::class);
        $this->app->bind(DocumentGroupRepositoryInterface::class, DocumentGroupRepository::class);
        $this->app->bind(ExerciseRepositoryInterfaces::class, ExerciseRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        $this->app->bind(QuestionAnswerTypeServiceRepositoryInterface::class, QuestionAnswerTypeServiceRepository::class);
        $this->app->bind(QuestionAnswerRepositoryInterface::class, QuestionAnswerRepository::class);
    }
}
