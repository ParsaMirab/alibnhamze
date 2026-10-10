<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClassStudentRequest;
use App\Http\Requests\Admin\UpdateClassStudentRequest;
use App\Models\ClassStudent;
use App\Models\SchoolClass;

class ClassStudentController extends Controller
{
    /**
     * Redirect to the class edit page with the assignment ready for editing.
     */
    public function edit(SchoolClass $schoolClass, ClassStudent $assignment)
    {
        $this->authorize('update', $schoolClass);
        $this->ensureAssignmentBelongsToClass($schoolClass, $assignment);

        return redirect()->route('admin.classes.edit', [
            'schoolClass' => $schoolClass,
            'tab' => 'students',
            'edit' => $assignment->id,
        ]);
    }

    /**
     * Store a newly created student assignment.
     */
    public function store(StoreClassStudentRequest $request, SchoolClass $schoolClass)
    {
        $this->authorize('update', $schoolClass);

        $validated = $request->validated();
        $validated['class_id'] = $schoolClass->id;

        $exists = ClassStudent::query()
            ->where('class_id', $schoolClass->id)
            ->where('student_id', $validated['student_id'])
            ->exists();

        if ($exists) {
            return $this->backToStudents($schoolClass)
                ->withInput()
                ->withErrors(['student_id' => 'این دانش‌آموز قبلاً به این کلاس اضافه شده است.']);
        }

        ClassStudent::create($validated);

        return $this->backToStudents($schoolClass)
            ->with('success', 'دانش‌آموز با موفقیت به کلاس اضافه شد.');
    }

    /**
     * Update the specified student assignment.
     */
    public function update(UpdateClassStudentRequest $request, SchoolClass $schoolClass, ClassStudent $assignment)
    {
        $this->authorize('update', $schoolClass);
        $this->ensureAssignmentBelongsToClass($schoolClass, $assignment);

        $validated = $request->validated();

        $exists = ClassStudent::query()
            ->where('class_id', $schoolClass->id)
            ->where('student_id', $validated['student_id'])
            ->whereKeyNot($assignment->getKey())
            ->exists();

        if ($exists) {
            return $this->backToStudents($schoolClass)
                ->withInput()
                ->withErrors(['student_id' => 'این دانش‌آموز قبلاً به این کلاس اضافه شده است.']);
        }

        $assignment->update($validated);

        return $this->backToStudents($schoolClass)
            ->with('success', 'اطلاعات دانش‌آموز کلاس با موفقیت ویرایش شد.');
    }

    /**
     * Remove the specified student assignment from the class.
     */
    public function destroy(SchoolClass $schoolClass, ClassStudent $assignment)
    {
        $this->authorize('update', $schoolClass);
        $this->ensureAssignmentBelongsToClass($schoolClass, $assignment);

        $assignment->delete();

        return $this->backToStudents($schoolClass)
            ->with('success', 'دانش‌آموز با موفقیت از کلاس حذف شد.');
    }

    /**
     * The assignment must belong to the class in the current route.
     */
    protected function ensureAssignmentBelongsToClass(SchoolClass $schoolClass, ClassStudent $assignment): void
    {
        if ((int) $assignment->class_id !== (int) $schoolClass->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Redirect back to the current class edit page with the students tab active.
     */
    protected function backToStudents(SchoolClass $schoolClass)
    {
        return redirect()->route('admin.classes.edit', [
            'schoolClass' => $schoolClass,
            'tab' => 'students',
        ]);
    }
}
