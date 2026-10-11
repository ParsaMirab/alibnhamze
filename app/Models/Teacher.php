<?php

namespace App\Models;

use App\Enums\RankEnum;
use Illuminate\Database\Eloquent\Builder;

/**
 * Teachers are stored in the existing users table.
 * This model only narrows queries to users with the TEACHER rank.
 */
class Teacher extends User
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Limit every query to users with the TEACHER rank.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('teacher', function (Builder $builder) {
            $builder->where('users.rank', RankEnum::TEACHER->value);
        });
    }
}
