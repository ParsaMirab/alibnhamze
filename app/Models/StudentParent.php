<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentParent extends Model
{
    public const FATHER = 'father';

    public const MOTHER = 'mother';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'student_parents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'parent_id',
        'relationship_type',
    ];

    /**
     * Get the student of the relationship.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the parent (user) of the relationship.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * The supported relationship types.
     *
     * @return array<int, string>
     */
    public static function types(): array
    {
        return [self::FATHER, self::MOTHER];
    }
}
