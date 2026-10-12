<?php

namespace App\Policies;

use App\Enums\RankEnum;
use App\Models\PreRegistration;
use App\Models\User;

class PreRegistrationPolicy
{
    /**
     * Ranks that are allowed to manage pre-registrations.
     *
     * @var array<int, string>
     */
    protected array $allowedRanks = [
        RankEnum::MANAGER->value,
        RankEnum::DEPUTY->value,
    ];

    /**
     * Determine if the user can view the pre-registration list.
     */
    public function viewAny(User $user): bool
    {
        return $this->canManagePreRegistrations($user);
    }

    /**
     * Determine if the user can view the given application.
     */
    public function view(User $user, PreRegistration $preRegistration): bool
    {
        return $this->canManagePreRegistrations($user);
    }

    /**
     * Determine if the user can approve the application.
     */
    public function approve(User $user, PreRegistration $preRegistration): bool
    {
        return $this->canManagePreRegistrations($user);
    }

    /**
     * Determine if the user can reject the application.
     */
    public function reject(User $user, PreRegistration $preRegistration): bool
    {
        return $this->canManagePreRegistrations($user);
    }

    /**
     * Only managers and deputies are authorized for the pre-registration module.
     */
    protected function canManagePreRegistrations(User $user): bool
    {
        return $user->is_admin
            && in_array($user->rank, $this->allowedRanks, true);
    }
}
