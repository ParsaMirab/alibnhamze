<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RankEnum;
use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentAbsence;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $isTeacher = $user->rank === RankEnum::TEACHER->value;

        $classIds = $isTeacher
            ? DB::table('class_teachers')
                ->where('teacher_id', $user->id)
                ->distinct()
                ->pluck('class_id')
            : SchoolClass::query()->pluck('id');

        // Scope absences to the teacher's classes.
        $absencesQuery = StudentAbsence::query();

        if ($isTeacher) {
            $absencesQuery->whereIn('class_id', $classIds);
        }

        $totalAbsences = (clone $absencesQuery)->count();

        $excusedAbsences = (clone $absencesQuery)
            ->where('is_excused', true)
            ->count();

        $unexcusedAbsences = (clone $absencesQuery)
            ->where('is_excused', false)
            ->count();

        $recentAbsences = (clone $absencesQuery)
            ->with('student')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        // Last 7 calendar days.
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        $dailyAbsences = (clone $absencesQuery)
            ->whereBetween('date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->selectRaw('date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);

            $chartLabels[] = substr(
                \App\Helpers\Jalali::format($date),
                5
            );

            $chartData[] = (int) (
                $dailyAbsences[$date->toDateString()] ?? 0
            );
        }

        // Administrative statistics.
        $totalStudents = $isTeacher
            ? null
            : Student::count();

        $totalTeachers = $isTeacher
            ? null
            : Teacher::count();

        $totalClasses = $isTeacher
            ? $classIds->count()
            : SchoolClass::count();

        $myClasses = $isTeacher
            ? SchoolClass::query()
                ->whereIn('id', $classIds)
                ->latest('id')
                ->limit(5)
                ->get()
            : collect();

        $mySubjects = $isTeacher
            ? DB::table('class_teachers')
                ->where('teacher_id', $user->id)
                ->distinct()
                ->pluck('subject')
            : collect();

        return view('admin.dashboard', compact(
            'user',
            'isTeacher',
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'totalAbsences',
            'excusedAbsences',
            'unexcusedAbsences',
            'recentAbsences',
            'chartLabels',
            'chartData',
            'myClasses',
            'mySubjects',
        ));
    }
}
