<?php

namespace App\Providers;

use App\Models\PreRegistration;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use App\Policies\PreRegistrationPolicy;
use App\Policies\SchoolClassPolicy;
use App\Policies\StudentPolicy;
use App\Policies\UserPolicy;
use App\Services\Sms\LogSmsProvider;
use App\Services\Sms\SmsProvider;
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
        // The SMS gateway is swappable: tests replace this binding with a fake
        // and production can point `services.sms.driver` at a real provider.
        $this->app->bind(SmsProvider::class, function (): SmsProvider {
            return match (config('services.sms.driver', 'log')) {
                default => new LogSmsProvider,
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(SchoolClass::class, SchoolClassPolicy::class);
        Gate::policy(Student::class, StudentPolicy::class);
        Gate::policy(PreRegistration::class, PreRegistrationPolicy::class);
    }
}
