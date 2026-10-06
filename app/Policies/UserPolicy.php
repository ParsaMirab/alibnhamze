<?php

namespace App\Policies;

use App\Enums\RankEnum;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin && $user->rank === RankEnum::MANAGER->value;
    }

    public function create(User $user): bool
    {
        return $user->is_admin && $user->rank === RankEnum::MANAGER->value;
    }

    public function update(User $user, User $model): bool
    {
        return $user->is_admin && $user->rank === RankEnum::MANAGER->value;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->is_admin && $user->rank === RankEnum::MANAGER->value;
    }
}
