<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClassTeacherRequest;
use App\Http\Requests\Admin\UpdateClassTeacherRequest;
use App\Models\ClassTeacher;
use App\Models\SchoolClass;

class ClassTeacherController extends Controller
{
    /**
     * Redirect to the class edit page with the assignment ready for editing.
     */
    public function edit(SchoolClass $schoolClass, ClassTeacher $assignment)
    {
        $this->authorize('update', $schoolClass);
        $this->ensureAssignmentBelongsToClass($schoolClass, $assignment);

        return redirect()->route('admin.classes.edit', [
            'schoolClass' => $schoolClass,
            'tab' => 'teachers',
            'edit' => $assignment->id,
        ]);
    }

    /**
     * Store a newly created teacher assignment.
     */
    public function store(StoreClassTeacherRequest $request, SchoolClass $schoolClass)
    {
        $this->authorize('update', $schoolClass);

        $validated = $request->validated();
        $validated['class_id'] = $schoolClass->id;

        $exists = ClassTeacher::query()
            ->where('class_id', $schoolClass->id)
            ->where('teacher_id', $validated['teacher_id'])
            ->where('subject', $validated['subject'])
            ->exists();

        if ($exists) {
            return $this->backToTeachers($schoolClass)
                ->withInput()
                ->withErrors(['subject' => 'این معلم با این درس قبلاً به این کلاس اضافه شده است.']);
        }

        ClassTeacher::create($validated);

        return $this->backToTeachers($schoolClass)
            ->with('success', 'معلم با موفقیت به کلاس اضافه شد.');
    }

    /**
     * Update the specified teacher assignment.
     */
    public function update(UpdateClassTeacherRequest $request, SchoolClass $schoolClass, ClassTeacher $assignment)
    {
        $this->authorize('update', $schoolClass);
        $this->ensureAssignmentBelongsToClass($schoolClass, $assignment);

        $validated = $request->validated();

        $exists = ClassTeacher::query()
            ->where('class_id', $schoolClass->id)
            ->where('teacher_id', $validated['teacher_id'])
            ->where('subject', $validated['subject'])
            ->whereKeyNot($assignment->getKey())
            ->exists();

        if ($exists) {
            return $this->backToTeachers($schoolClass)
                ->withInput()
                ->withErrors(['subject' => 'این معلم با این درس قبلاً به این کلاس اضافه شده است.']);
        }

        $assignment->update($validated);

        return $this->backToTeachers($schoolClass)
            ->with('success', 'اطلاعات معلم کلاس با موفقیت ویرایش شد.');
    }

    /**
     * Remove the specified teacher assignment from the class.
     */
    public function destroy(SchoolClass $schoolClass, ClassTeacher $assignment)
    {
        $this->authorize('update', $schoolClass);
        $this->ensureAssignmentBelongsToClass($schoolClass, $assignment);

        $assignment->delete();

        return $this->backToTeachers($schoolClass)
            ->with('success', 'معلم با موفقیت از کلاس حذف شد.');
    }

    /**
     * The assignment must belong to the class in the current route.
     */
    protected function ensureAssignmentBelongsToClass(SchoolClass $schoolClass, ClassTeacher $assignment): void
    {
        if ((int) $assignment->class_id !== (int) $schoolClass->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Redirect back to the current class edit page with the teachers tab active.
     */
    protected function backToTeachers(SchoolClass $schoolClass)
    {
        return redirect()->route('admin.classes.edit', [
            'schoolClass' => $schoolClass,
            'tab' => 'teachers',
        ]);
    }
}
