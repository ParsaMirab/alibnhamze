<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RankEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSchoolClassRequest;
use App\Http\Requests\Admin\UpdateSchoolClassRequest;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the classes.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', SchoolClass::class);

        $search = trim($request->input('search', ''));
        $gradeFilter = $request->input('grade');
        $perPage = (int) $request->input('per_page', 10);

        if (! in_array($perPage, [10, 25, 50], true)) {
            $perPage = 10;
        }

        $classes = SchoolClass::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($gradeFilter !== null && in_array((int) $gradeFilter, [10, 11, 12], true), function ($query) use ($gradeFilter) {
                $query->where('grade', (int) $gradeFilter);
            })
            ->withCount(['teacherAssignments', 'studentAssignments'])
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.classes.index', compact(
            'classes',
            'search',
            'gradeFilter',
            'perPage'
        ));
    }

    /**
     * Show the form for creating a new class.
     */
    public function create()
    {
        $this->authorize('create', SchoolClass::class);

        return view('admin.classes.create');
    }

    /**
     * Store a newly created class in storage.
     */
    public function store(StoreSchoolClassRequest $request)
    {
        $this->authorize('create', SchoolClass::class);

        $schoolClass = SchoolClass::create($request->validated());

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'کلاس با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified class.
     */
    public function show(SchoolClass $schoolClass)
    {
        $this->authorize('view', $schoolClass);

        $schoolClass->load('studentAssignments.student', 'teacherAssignments.teacher');

        return view('admin.classes.show', [
            'schoolClass' => $schoolClass,
            'activeTab' => $this->resolveActiveTab(request('tab')),
            'editAssignmentId' => null,
            'students' => $this->availableStudents(),
            'teachers' => $this->availableTeachers(),
        ]);
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit(Request $request, SchoolClass $schoolClass)
    {
        $this->authorize('update', $schoolClass);

        $schoolClass->load('studentAssignments.student', 'teacherAssignments.teacher');

        return view('admin.classes.edit', [
            'schoolClass' => $schoolClass,
            'activeTab' => $this->resolveActiveTab($request->input('tab')),
            'editAssignmentId' => $request->input('edit'),
            'students' => $this->availableStudents(),
            'teachers' => $this->availableTeachers(),
        ]);
    }

    /**
     * Update the specified class in storage.
     */
    public function update(UpdateSchoolClassRequest $request, SchoolClass $schoolClass)
    {
        $this->authorize('update', $schoolClass);

        $schoolClass->update($request->validated());

        return redirect()
            ->route('admin.classes.edit', $schoolClass)
            ->with('success', 'کلاس با موفقیت ویرایش شد.');
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroy(SchoolClass $schoolClass)
    {
        $this->authorize('delete', $schoolClass);

        $schoolClass->delete();

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'کلاس با موفقیت حذف شد.');
    }

    /**
     * Normalize the requested tab name.
     */
    protected function resolveActiveTab(?string $tab): string
    {
        return $tab === 'teachers' ? 'teachers' : 'students';
    }

    /**
     * Students that can be assigned to a class.
     *
     * @return Collection<int, User>
     */
    protected function availableStudents()
    {
        return User::query()
            ->where('rank', RankEnum::STUDENT->value)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }

    /**
     * Teachers that can be assigned to a class.
     *
     * @return Collection<int, User>
     */
    protected function availableTeachers()
    {
        return User::query()
            ->where('rank', RankEnum::TEACHER->value)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }
}
