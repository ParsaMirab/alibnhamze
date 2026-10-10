<?php

namespace App\Policies;

use App\Enums\RankEnum;
use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    /**
     * Ranks that are allowed to manage students.
     *
     * @var array<int, string>
     */
    protected array $allowedRanks = [
        RankEnum::MANAGER->value,
        RankEnum::DEPUTY->value,
    ];

    /**
     * Determine if the user can view any students.
     */
    public function viewAny(User $user): bool
    {
        return $this->canManageStudents($user);
    }

    /**
     * Determine if the user can view the student.
     */
    public function view(User $user, Student $student): bool
    {
        return $this->canManageStudents($user);
    }

    /**
     * Determine if the user can create students.
     */
    public function create(User $user): bool
    {
        return $this->canManageStudents($user);
    }

    /**
     * Determine if the user can update the student.
     */
    public function update(User $user, Student $student): bool
    {
        return $this->canManageStudents($user);
    }

    /**
     * Determine if the user can delete the student.
     */
    public function delete(User $user, Student $student): bool
    {
        return $this->canManageStudents($user);
    }

    /**
     * Only managers and deputies are authorized for the student management module.
     */
    protected function canManageStudents(User $user): bool
    {
        return $user->is_admin
            && in_array($user->rank, $this->allowedRanks, true);
    }
}
