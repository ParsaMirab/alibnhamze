<?php

namespace App\Http\Controllers\Alibnhamze;

use App\Enums\RankEnum;
use App\Http\Controllers\Controller;
use App\Models\StudentAbsence;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user();

        abort_unless(
            $student &&
            $student->rank === RankEnum::STUDENT->value,
            403
        );

        $absenceQuery = StudentAbsence::query()
            ->where('student_id', $student->id);

        $totalAbsences = (clone $absenceQuery)->count();

        $excusedAbsences = (clone $absenceQuery)
            ->where('is_excused', true)
            ->count();

        $unexcusedAbsences = (clone $absenceQuery)
            ->where('is_excused', false)
            ->count();

        $recentAbsences = (clone $absenceQuery)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('alibnhamze.student.dashboard', compact(
            'student',
            'totalAbsences',
            'excusedAbsences',
            'unexcusedAbsences',
            'recentAbsences'
        ));
    }
}
