<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Comment;
use App\Models\DocumentGroup;
use App\Models\Grade;
use App\Models\Intelligence;
use App\Models\IntelligenceFeedback;
use App\Models\IntelligencePoint;
use App\Models\IntelligencePointName;
use App\Models\Job;
use App\Models\Major;
use App\Models\Package;
use App\Models\Permission;
use App\Models\Personnel;
use App\Models\Province;
use App\Models\PsychologicalQuestion;
use App\Models\Question;
use App\Models\Rahjoo;
use App\Models\RahjooCourse;
use App\Models\RahjooParent;
use App\Models\RahjooSupport;
use App\Models\Role;
use App\Models\Skill;
use App\Models\SupportComment;
use App\Models\User;
use App\Policies\Comment\CommentPolicy;
use App\Policies\DocumentGroup\DocumentGroupPolicy;
use App\Policies\GradePolicy;
use App\Policies\Intelligence\IntelligencePolicy;
use App\Policies\IntelligenceFeedback\IntelligenceFeedbackPolicy;
use App\Policies\IntelligencePoint\IntelligencePointPolicy;
use App\Policies\IntelligencePointName\IntelligencePointNamePolicy;
use App\Policies\JobPolicy;
use App\Policies\MajorPolicy;
use App\Policies\Package\PackagePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\PersonnelPolicy;
use App\Policies\ProvincePolicy;
use App\Policies\PsychologicalQuestionPolicy;
use App\Policies\Question\QuestionPolicy;
use App\Policies\RahjooCoursePolicy;
use App\Policies\RahjooParentPolicy;
use App\Policies\RahjooPolicy;
use App\Policies\RahjooSupport\RahjooSupportPolicy;
use App\Policies\RolePolicy;
use App\Policies\SkillPolicy;
use App\Policies\SupportComment\SupportCommentPolicy;
use App\Policies\UserPolicy;
use App\Repositories\V1\Rahjoo\Eloquent\RahjooRepository;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        Province::class => ProvincePolicy::class,
        Grade::class => GradePolicy::class,
        Major::class => MajorPolicy::class,
        Job::class => JobPolicy::class,
        User::class => UserPolicy::class,
        Personnel::class => PersonnelPolicy::class,
        Rahjoo::class => RahjooPolicy::class,
        RahjooParent::class => RahjooParentPolicy::class,
        RahjooCourse::class => RahjooCoursePolicy::class,
        PsychologicalQuestion::class => PsychologicalQuestionPolicy::class,
        Skill::class => SkillPolicy::class,
        Package::class => PackagePolicy::class,
        Intelligence::class => IntelligencePolicy::class,
        IntelligencePoint::class => IntelligencePointPolicy::class,
        IntelligencePointName::class => IntelligencePointNamePolicy::class,
        IntelligenceFeedback::class => IntelligenceFeedbackPolicy::class,
        DocumentGroup::class => DocumentGroupPolicy::class,
        Question::class => QuestionPolicy::class,
        Comment::class => CommentPolicy::class,
        RahjooSupport::class => RahjooSupportPolicy::class,
        SupportComment::class => SupportCommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function (User $user) {
            return $user->hasRole(\App\Enums\Role::SUPER_ADMIN) ? true : null;
        });
    }
}
