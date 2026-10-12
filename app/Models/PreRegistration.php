<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * A student pre-registration application submitted from the public form.
 *
 * Applications are not enrolled students: no `User` account is created here.
 */
class PreRegistration extends Model
{
    public const GRADE_TEN = 10;

    public const GRADE_ELEVEN = 11;

    public const GRADE_TWELVE = 12;

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_registrations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_full_name',
        'father_name',
        'mother_full_name',
        'national_code',
        'grade',
        'previous_year_average',
        'previous_year_discipline_score',
        'phone_number',
        'is_accepted',
        'phone_verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'grade' => 'integer',
            'previous_year_average' => 'decimal:2',
            'previous_year_discipline_score' => 'decimal:2',
            'is_accepted' => 'boolean',
            'phone_verified_at' => 'datetime',
        ];
    }

    /**
     * Limit the query to applications awaiting review.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull('is_accepted');
    }

    /**
     * Limit the query to approved applications.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_accepted', true);
    }

    /**
     * Limit the query to rejected applications.
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('is_accepted', false);
    }

    /**
     * Whether the application is still awaiting a decision.
     */
    public function isPending(): bool
    {
        return $this->is_accepted === null;
    }

    /**
     * Whether the application has been approved.
     */
    public function isApproved(): bool
    {
        return $this->is_accepted === true;
    }

    /**
     * Whether the application has been rejected.
     */
    public function isRejected(): bool
    {
        return $this->is_accepted === false;
    }

    /**
     * The machine readable status key (pending / approved / rejected).
     */
    public function status(): string
    {
        return match (true) {
            $this->isApproved() => self::STATUS_APPROVED,
            $this->isRejected() => self::STATUS_REJECTED,
            default => self::STATUS_PENDING,
        };
    }

    /**
     * The Persian label for the current status.
     */
    public function statusLabel(): string
    {
        return match ($this->status()) {
            self::STATUS_APPROVED => 'تأییدشده',
            self::STATUS_REJECTED => 'ردشده',
            default => 'در انتظار بررسی',
        };
    }

    /**
     * The Persian label for the grade level.
     */
    public function gradeLabel(): string
    {
        return match ((int) $this->grade) {
            self::GRADE_TEN => 'پایه دهم',
            self::GRADE_ELEVEN => 'پایه یازدهم',
            self::GRADE_TWELVE => 'پایه دوازدهم',
            default => '—',
        };
    }

    /**
     * The supported grade levels.
     *
     * @return array<int, int>
     */
    public static function grades(): array
    {
        return [self::GRADE_TEN, self::GRADE_ELEVEN, self::GRADE_TWELVE];
    }
}
