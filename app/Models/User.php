<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the teacher class assignments for the user.
     */
    public function teacherClasses(): HasMany
    {
        return $this->hasMany(ClassTeacher::class, 'teacher_id');
    }

    /**
     * Get the student class assignments for the user.
     */
    public function studentClasses(): HasMany
    {
        return $this->hasMany(ClassStudent::class, 'student_id');
    }

    /**
     * Get the student relationships where this user acts as a parent.
     */
    public function parentLinks(): HasMany
    {
        return $this->hasMany(StudentParent::class, 'parent_id');
    }

    /**
     * Get the students this user is a parent of.
     */
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_parents', 'parent_id', 'student_id')
            ->withPivot('relationship_type')
            ->withTimestamps();
    }
}
