<?php

namespace App\Policies;

use App\Enums\RankEnum;
use App\Models\ClassTeacher;
use App\Models\SchoolClass;
use App\Models\User;

class SchoolClassPolicy
{
    /**
     * Ranks that are allowed to manage classes.
     *
     * @var array<int, string>
     */
    protected array $allowedRanks = [
        RankEnum::MANAGER->value,
        RankEnum::DEPUTY->value,
    ];

    /**
     * Determine if the user can view any classes.
     */
    public function viewAny(User $user): bool
    {
        return $this->canManageClasses($user);
    }

    /**
     * Determine if the user can view the class.
     */
    public function view(User $user, SchoolClass $schoolClass): bool
    {
        return $this->canManageClasses($user);
    }

    /**
     * Determine if the user can create classes.
     */
    public function create(User $user): bool
    {
        return $this->canManageClasses($user);
    }

    /**
     * Determine if the user can update the class.
     */
    public function update(User $user, SchoolClass $schoolClass): bool
    {
        return $this->canManageClasses($user);
    }

    /**
     * Determine if the user can delete the class.
     */
    public function delete(User $user, SchoolClass $schoolClass): bool
    {
        return $this->canManageClasses($user);
    }

    /**
     * Determine if the user can open the "my classes" section (teachers only).
     */
    public function viewTeacherClasses(User $user): bool
    {
        return $user->rank === RankEnum::TEACHER->value;
    }

    /**
     * Determine if the teacher is assigned to the given class.
     */
    public function manageAttendance(User $user, SchoolClass $schoolClass): bool
    {
        if ($user->rank !== RankEnum::TEACHER->value) {
            return false;
        }

        return ClassTeacher::query()
            ->where('class_id', $schoolClass->getKey())
            ->where('teacher_id', $user->getKey())
            ->exists();
    }

    /**
     * Only managers and deputies are authorized for the class management module.
     */
    protected function canManageClasses(User $user): bool
    {
        return $user->is_admin
            && in_array($user->rank, $this->allowedRanks, true);
    }
}
