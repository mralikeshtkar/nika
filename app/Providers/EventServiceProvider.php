<?php

namespace App\Providers;

use App\Models\Exercise;
use App\Models\IntelligenceFeedback;
use App\Models\IntelligencePoint;
use App\Models\Media;
use App\Models\Package;
use App\Models\User;
use App\Observers\V1\Exercise\ExerciseObserver;
use App\Observers\V1\IntelligenceFeedback\IntelligenceFeedbackObserver;
use App\Observers\V1\IntelligencePoint\IntelligencePointObserver;
use App\Observers\V1\Media\MediaObserver;
use App\Observers\V1\Package\PackageObserver;
use App\Observers\V1\User\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        $this->_registerObservers();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }

    /**
     * @return void
     */
    private function _registerObservers(): void
    {
        Media::observe(MediaObserver::class);
        Package::observe(PackageObserver::class);
        IntelligencePoint::observe(IntelligencePointObserver::class);
        IntelligenceFeedback::observe(IntelligenceFeedbackObserver::class);
        Exercise::observe(ExerciseObserver::class);
        User::observe(UserObserver::class);
    }
}
