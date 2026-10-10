<?php

namespace App\Providers;

use App\Models\SchoolClass;
use App\Models\User;
use App\Policies\SchoolClassPolicy;
use App\Policies\UserPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(SchoolClass::class, SchoolClassPolicy::class);
    }
}
