<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Stores absence records only. A missing row means the student was present,
 * so the table intentionally never holds attendance (present) entries.
 *
 * The `date` column holds a plain Gregorian "Y-m-d" string and therefore has
 * no cast: a Carbon cast would be persisted in the application datetime format.
 */
class StudentAbsence extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'student_id',
        'class_id',
    ];

    /**
     * Get the student (user) that was absent.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the school class the absence belongs to.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
