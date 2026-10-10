<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RankEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);

        $search = trim($request->input('search', ''));
        $gradeFilter = $request->input('grade');
        $perPage = (int) $request->input('per_page', 10);

        if (! in_array($perPage, [10, 25, 50], true)) {
            $perPage = 10;
        }

        $students = Student::query()
            ->with(['father.parent', 'mother.parent'])
            ->when($search !== '', function ($query) use ($search) {
                $tokens = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY) ?: [];

                $query->where(function ($query) use ($search, $tokens) {
                    // Every token must match the first or the last name (full name search).
                    $query->where(function ($query) use ($tokens) {
                        foreach ($tokens as $token) {
                            $query->where(function ($query) use ($token) {
                                $query->where('first_name', 'like', "%{$token}%")
                                    ->orWhere('last_name', 'like', "%{$token}%");
                            });
                        }
                    })
                        ->orWhere('national_code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($gradeFilter !== null && in_array((int) $gradeFilter, [10, 11, 12], true), function ($query) use ($gradeFilter) {
                $query->where('grade', (int) $gradeFilter);
            })
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.students.index', compact(
            'students',
            'search',
            'gradeFilter',
            'perPage'
        ));
    }

    /**
     * Show the form for registering a new student.
     */
    public function create()
    {
        $this->authorize('create', Student::class);

        return view('admin.students.create');
    }

    /**
     * Store a newly registered student together with any parent information.
     */
    public function store(StoreStudentRequest $request)
    {
        $this->authorize('create', Student::class);

        $data = $request->validated();

        $student = DB::transaction(function () use ($data) {
            $student = Student::create($this->studentAttributes($data));

            $this->attachParents($student, $data);

            return $student;
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', "دانش‌آموز «{$student->first_name} {$student->last_name}» با موفقیت ثبت شد.");
    }

    /**
     * Show the form for editing the student.
     */
    public function edit(Student $student)
    {
        $this->authorize('update', $student);

        $student->load('father.parent', 'mother.parent');

        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the student and synchronize the parent relationships.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $this->authorize('update', $student);

        $data = $request->validated();

        DB::transaction(function () use ($student, $data) {
            $student->update($this->studentAttributes($data));

            $this->syncParents($student, $data);
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'اطلاعات دانش‌آموز با موفقیت ویرایش شد.');
    }

    /**
     * Remove the student and its relationships without touching parent accounts.
     */
    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);

        DB::transaction(function () use ($student) {
            // Class memberships and parent links are detached; no user account is removed.
            $student->studentClasses()->delete();
            $student->parentLinks()->delete();
            $student->delete();
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'دانش‌آموز با موفقیت حذف شد.');
    }

    /**
     * Build the attributes used to create or update the student.
     *
     * rank and is_admin are always controlled by the backend.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function studentAttributes(array $data): array
    {
        return [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'national_code' => $data['national_code'],
            'phone' => ($data['phone'] ?? null) ?: null,
            'grade' => (int) $data['grade'],
            'rank' => RankEnum::STUDENT->value,
            'is_admin' => false,
        ];
    }

    /**
     * Whether the given parent section is toggled on.
     *
     * @param  array<string, mixed>  $data
     */
    protected function parentIsAlive(array $data, string $type): bool
    {
        return (string) ($data["{$type}_alive"] ?? '0') === '1';
    }

    /**
     * Create the parent relationships for a newly registered student.
     *
     * @param  array<string, mixed>  $data
     */
    protected function attachParents(Student $student, array $data): void
    {
        foreach (StudentParent::types() as $type) {
            if (! $this->parentIsAlive($data, $type)) {
                continue;
            }

            $parent = $this->firstOrCreateParent($type, $data);

            $this->linkParent($student, $parent, $type);
        }
    }

    /**
     * Reuse an existing parent account by national code, or create a new one.
     *
     * @param  array<string, mixed>  $data
     */
    protected function firstOrCreateParent(string $type, array $data): User
    {
        $nationalCode = trim((string) $data["{$type}_national_code"]);

        $existing = User::query()->where('national_code', $nationalCode)->first();

        if ($existing) {
            // Existing parent accounts are reused as-is and never overwritten.
            return $existing;
        }

        return $this->createParent($type, $data, $nationalCode);
    }

    /**
     * Create a new PARENT user from the requested section data.
     *
     * @param  array<string, mixed>  $data
     */
    protected function createParent(string $type, array $data, string $nationalCode): User
    {
        return User::create([
            'first_name' => $data["{$type}_first_name"],
            'last_name' => $data["{$type}_last_name"],
            'national_code' => $nationalCode,
            'phone' => ($data["{$type}_phone"] ?? null) ?: null,
            'rank' => RankEnum::PARENT->value,
            'is_admin' => false,
            'grade' => null,
        ]);
    }

    /**
     * Attach (or re-point) a single father / mother relationship.
     */
    protected function linkParent(Student $student, User $parent, string $type): void
    {
        StudentParent::updateOrCreate(
            [
                'student_id' => $student->getKey(),
                'relationship_type' => $type,
            ],
            [
                'parent_id' => $parent->getKey(),
            ]
        );
    }

    /**
     * Synchronize the parent relationships when editing a student.
     *
     * @param  array<string, mixed>  $data
     */
    protected function syncParents(Student $student, array $data): void
    {
        foreach (StudentParent::types() as $type) {
            $link = StudentParent::query()
                ->where('student_id', $student->getKey())
                ->where('relationship_type', $type)
                ->first();

            if (! $this->parentIsAlive($data, $type)) {
                // Only the relationship is removed; the parent account is preserved.
                $link?->delete();

                continue;
            }

            $parent = $this->resolveParentForUpdate($student, $type, $data, $link);

            $this->linkParent($student, $parent, $type);
        }
    }

    /**
     * Resolve the parent account used when editing an existing student.
     *
     * @param  array<string, mixed>  $data
     */
    protected function resolveParentForUpdate(Student $student, string $type, array $data, ?StudentParent $link): User
    {
        $nationalCode = trim((string) $data["{$type}_national_code"]);

        $matched = User::query()->where('national_code', $nationalCode)->first();

        if (! $matched) {
            return $this->createParent($type, $data, $nationalCode);
        }

        $isCurrentParent = $link !== null && (int) $link->parent_id === (int) $matched->getKey();

        if (! $isCurrentParent) {
            // A different parent account was selected: re-point the relationship only.
            return $matched;
        }

        $sharedWithOtherStudents = StudentParent::query()
            ->where('parent_id', $matched->getKey())
            ->where('student_id', '!=', $student->getKey())
            ->exists();

        if (! $sharedWithOtherStudents) {
            // Safe to correct the account because it belongs to this student only.
            $matched->update([
                'first_name' => $data["{$type}_first_name"],
                'last_name' => $data["{$type}_last_name"],
                'phone' => ($data["{$type}_phone"] ?? null) ?: null,
            ]);
        }

        return $matched;
    }
}
