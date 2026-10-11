<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RankEnum;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AbsenceController extends Controller
{
    private function authorizeAccess(): void
    {
        abort_unless(
            in_array(auth()->user()?->rank, [
                RankEnum::MANAGER->value,
                RankEnum::DEPUTY->value,
            ], true),
            403
        );
    }

    private function dailyQuery()
    {
        return DB::table('student_absences as a')
            ->join('users as u', 'u.id', '=', 'a.student_id')
            ->where('u.rank', RankEnum::STUDENT->value)
            ->selectRaw('
                a.student_id,
                a.date,
                u.first_name,
                u.last_name,
                u.national_code,
                COUNT(*) AS absence_count,
                SUM(CASE WHEN a.is_excused = 1 THEN 1 ELSE 0 END) AS excused_count
            ')
            ->groupBy(
                'a.student_id',
                'a.date',
                'u.first_name',
                'u.last_name',
                'u.national_code'
            );
    }

    public function index(Request $request)
    {
        $this->authorizeAccess();

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'status' => ['nullable', Rule::in(['excused', 'unexcused'])],
        ]);

        $query = $this->dailyQuery();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {
                $q->where('u.first_name', 'like', "%{$search}%")
                    ->orWhere('u.last_name', 'like', "%{$search}%")
                    ->orWhere('u.national_code', 'like', "%{$search}%")
                    ->orWhereRaw(
                        "CONCAT(u.first_name, ' ', u.last_name) LIKE ?",
                        ["%{$search}%"]
                    );
            });
        }

        if (!empty($filters['date'])) {
            $query->where('a.date', $filters['date']);
        }

        // Counts before applying the status filter.
        $summary = DB::query()
            ->fromSub(clone $query, 'daily')
            ->selectRaw('
                COUNT(*) AS total,
                COALESCE(SUM(
                    CASE WHEN excused_count = absence_count
                    THEN 1 ELSE 0 END
                ), 0) AS excused,
                COALESCE(SUM(
                    CASE WHEN excused_count < absence_count
                    THEN 1 ELSE 0 END
                ), 0) AS unexcused
            ')
            ->first();

        if (($filters['status'] ?? null) === 'excused') {
            $query->havingRaw(
                'SUM(CASE WHEN a.is_excused = 1 THEN 1 ELSE 0 END) = COUNT(*)'
            );
        }

        if (($filters['status'] ?? null) === 'unexcused') {
            $query->havingRaw(
                'SUM(CASE WHEN a.is_excused = 1 THEN 1 ELSE 0 END) < COUNT(*)'
            );
        }

        $absences = $query
            ->orderByDesc('a.date')
            ->orderBy('u.last_name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.absences.index', compact(
            'absences',
            'summary',
            'filters'
        ));
    }

    public function updateStatus(Request $request)
    {
        $this->authorizeAccess();

        $data = $request->validate([
            'student_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->where('rank', RankEnum::STUDENT->value),
            ],
            'date' => ['required', 'date_format:Y-m-d'],
            'is_excused' => ['required', 'boolean'],
        ]);

        $query = DB::table('student_absences')
            ->where('student_id', $data['student_id'])
            ->where('date', $data['date']);

        abort_unless((clone $query)->exists(), 404);

        $count = $query->update([
            'is_excused' => (bool) $data['is_excused'],
            'updated_at' => now(),
        ]);

        return back()->with(
            'success',
            "وضعیت غیبت روزانه با موفقیت ثبت شد. {$count} رکورد به‌روزرسانی شد."
        );
    }

    public function sendWarnings(Request $request)
    {
        $this->authorizeAccess();

        $data = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
        ]);

        // Students with at least one unexcused absence on this date.
        $studentIds = DB::table('student_absences')
            ->where('date', $data['date'])
            ->where('is_excused', false)
            ->distinct()
            ->pluck('student_id');

        // Uses the existing student-parent relationship.
        $students = Student::query()
            ->whereIn('id', $studentIds)
            ->with('parentLinks')
            ->get();

        $parentCount = $students
            ->flatMap(fn ($student) =>
            $student->parentLinks->pluck('parent_id')
            )
            ->filter()
            ->unique()
            ->count();

        // No SMS service is called here.
        return back()->with(
            'success',
            "عملیات آزمایشی موفق بود. پیام اخطار برای {$parentCount} والد شبیه‌سازی شد."
        );
    }
}
