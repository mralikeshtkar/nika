<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->_URLForceScheme();
        $this->_jsonResources();
        $this->_requestMacros();
        $this->_URLForceScheme();
    }

    /**
     * @return void
     */
    private function _requestMacros(): void
    {
        Request::macro('isOldest', function () {
            return $this->filled('sort') && $this->sort == "oldest";
        });
    }

    /**
     * @return void
     */
    private function _jsonResources(): void
    {
        JsonResource::withoutWrapping();
    }

    /**
     * @return void
     */
    private function _URLForceScheme(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
