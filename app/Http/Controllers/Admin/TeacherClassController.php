<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAttendanceRequest;
use App\Models\SchoolClass;
use App\Models\StudentAbsence;
use App\Models\User;
use App\Support\JalaliDate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherClassController extends Controller
{
    /**
     * Display the classes assigned to the authenticated teacher.
     */
    public function index(Request $request)
    {
        $this->authorize('viewTeacherClasses', SchoolClass::class);

        $teacher = $request->user();
        $perPage = (int) $request->input('per_page', 10);

        if (! in_array($perPage, [10, 25, 50], true)) {
            $perPage = 10;
        }

        $classes = SchoolClass::query()
            ->whereHas('teacherAssignments', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->getKey());
            })
            ->withCount('studentAssignments')
            ->with(['teacherAssignments' => function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->getKey());
            }])
            ->orderBy('grade')
            ->orderBy('title')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.my-classes.index', compact('classes', 'perPage'));
    }

    /**
     * Show a class and its attendance form.
     */
    public function show(Request $request, SchoolClass $schoolClass)
    {
        $this->authorize('manageAttendance', $schoolClass);

        $jalali = JalaliDate::parse($request->input('date')) ?? JalaliDate::today();
        $jalaliDate = JalaliDate::format($jalali);
        $gregorianDate = JalaliDate::toGregorianString($jalaliDate);

        $students = $this->classStudents($schoolClass);

        $absentStudentIds = StudentAbsence::query()
            ->where('class_id', $schoolClass->getKey())
            ->whereDate('date', $gregorianDate)
            ->pluck('student_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $subjects = $schoolClass->teacherAssignments()
            ->where('teacher_id', $request->user()->getKey())
            ->pluck('subject')
            ->filter()
            ->unique()
            ->values();

        return view('admin.my-classes.show', [
            'schoolClass' => $schoolClass,
            'students' => $students,
            'subjects' => $subjects,
            'absentStudentIds' => $absentStudentIds,
            'jalaliDate' => $jalaliDate,
            'gregorianDate' => $gregorianDate,
            'hasExistingAbsences' => count($absentStudentIds) > 0,
        ]);
    }

    /**
     * Store (sync) the attendance of the class for the selected date.
     */
    public function storeAttendance(StoreAttendanceRequest $request, SchoolClass $schoolClass)
    {
        $this->authorize('manageAttendance', $schoolClass);

        $validated = $request->validated();

        // The validation rule guarantees the date is a real Jalaali date.
        $gregorianDate = JalaliDate::toGregorianString($validated['date']);

        $classStudentIds = $this->classStudents($schoolClass)->pluck('id')->map(fn ($id) => (int) $id);

        $submittedIds = collect($validated['absent'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        // Never trust the frontend: every submitted student must belong to the class.
        if ($submittedIds->diff($classStudentIds)->isNotEmpty()) {
            return back()
                ->withInput()
                ->withErrors(['absent' => 'برخی از دانش‌آموزان ارسال‌شده به این کلاس تعلق ندارند.']);
        }

        $absentIds = $submittedIds->intersect($classStudentIds)->values();

        DB::transaction(function () use ($schoolClass, $gregorianDate, $absentIds) {
            $currentAbsentIds = StudentAbsence::query()
                ->where('class_id', $schoolClass->getKey())
                ->whereDate('date', $gregorianDate)
                ->pluck('student_id')
                ->map(fn ($id) => (int) $id);

            // Previously absent, now present: remove the absence record.
            $toDelete = $currentAbsentIds->diff($absentIds);

            if ($toDelete->isNotEmpty()) {
                StudentAbsence::query()
                    ->where('class_id', $schoolClass->getKey())
                    ->whereDate('date', $gregorianDate)
                    ->whereIn('student_id', $toDelete->all())
                    ->delete();
            }

            // Newly absent: create exactly one absence record. Existing records are preserved.
            $toInsert = $absentIds->diff($currentAbsentIds);

            if ($toInsert->isNotEmpty()) {
                $now = now();

                $rows = $toInsert->map(fn (int $studentId) => [
                    'class_id' => $schoolClass->getKey(),
                    'student_id' => $studentId,
                    'date' => $gregorianDate,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

                StudentAbsence::query()->insert($rows);
            }
        });

        return redirect()
            ->route('admin.my-classes.show', [
                'schoolClass' => $schoolClass,
                'date' => $validated['date'],
            ])
            ->with('success', 'حضور و غیاب دانش‌آموزان با موفقیت ثبت شد.');
    }

    /**
     * Students belonging to the given class, ordered by name.
     *
     * @return Collection<int, User>
     */
    protected function classStudents(SchoolClass $schoolClass)
    {
        return $schoolClass->students()
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get();
    }
}
