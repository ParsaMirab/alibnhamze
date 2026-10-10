<?php

namespace App\Models;

use App\Enums\RankEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Students are stored in the existing `users` table; this model only narrows
 * the table to rows with the STUDENT rank so the module keeps its own policy.
 */
class Student extends User
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Limit every query to users with the STUDENT rank.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('student', function (Builder $builder) {
            $builder->where('users.rank', RankEnum::STUDENT->value);
        });
    }

    /**
     * Get the parent relationships (father / mother) of the student.
     */
    public function parentLinks(): HasMany
    {
        return $this->hasMany(StudentParent::class, 'student_id');
    }

    /**
     * Get the father relationship of the student.
     */
    public function father(): HasOne
    {
        return $this->hasOne(StudentParent::class, 'student_id')
            ->where('relationship_type', StudentParent::FATHER);
    }

    /**
     * Get the mother relationship of the student.
     */
    public function mother(): HasOne
    {
        return $this->hasOne(StudentParent::class, 'student_id')
            ->where('relationship_type', StudentParent::MOTHER);
    }
}
