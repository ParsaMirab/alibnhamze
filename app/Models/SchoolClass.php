<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SchoolClass extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'grade',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'grade' => 'integer',
        ];
    }

    /**
     * Get the teacher assignments for the class.
     */
    public function teacherAssignments(): HasMany
    {
        return $this->hasMany(ClassTeacher::class, 'class_id');
    }

    /**
     * Get the student assignments for the class.
     */
    public function studentAssignments(): HasMany
    {
        return $this->hasMany(ClassStudent::class, 'class_id');
    }

    /**
     * Get the teachers for the class.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_teachers', 'class_id', 'teacher_id')
            ->withPivot('subject')
            ->withTimestamps();
    }

    /**
     * Get the students for the class.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_students', 'class_id', 'student_id')
            ->withTimestamps();
    }
}