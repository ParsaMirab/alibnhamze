<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RankEnum;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Teacher::class);

        $search = trim($request->input('search', ''));
        $perPage = (int) $request->input('per_page', 10);

        if (! in_array($perPage, [10, 25, 50], true)) {
            $perPage = 10;
        }

        $teachers = Teacher::query()
            ->where('rank', RankEnum::TEACHER->value)
            ->when($search !== '', function ($query) use ($search) {
                $tokens = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY) ?: [];

                $query->where(function ($query) use ($search, $tokens) {
                    // Search by first name, last name, or full name.
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
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.teachers.index', compact(
            'teachers',
            'search',
            'perPage'
        ));
    }
    public function create()
    {
        $this->authorize('create', Teacher::class);

        return view('admin.teachers.create');
    }
    public function store(Request $request)
    {
        $this->authorize('create', Teacher::class);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],

            'national_code' => [
                'required',
                'digits:10',
                Rule::unique('users', 'national_code'),
            ],

            'phone' => [
                'nullable',
                'regex:/^09[0-9]{9}$/',
                Rule::unique('users', 'phone'),
            ],
        ], [
            'first_name.required' => 'وارد کردن نام الزامی است.',
            'last_name.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'national_code.required' => 'وارد کردن کد ملی الزامی است.',
            'national_code.digits' => 'کد ملی باید دقیقاً ۱۰ رقم باشد.',
            'national_code.unique' => 'این کد ملی قبلاً ثبت شده است.',
            'phone.regex' => 'شماره تلفن باید با 09 شروع شده و ۱۱ رقم باشد.',
            'phone.unique' => 'این شماره تلفن قبلاً ثبت شده است.',
        ]);

        $teacher = new Teacher();

        $teacher->first_name = $validated['first_name'];
        $teacher->last_name = $validated['last_name'];
        $teacher->national_code = $validated['national_code'];
        $teacher->phone = $validated['phone'] ?? null;
        $teacher->rank = RankEnum::TEACHER->value;
        $teacher->is_admin = false;

        $teacher->save();

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'معلم با موفقیت ثبت شد.');
    }

    public function edit(Teacher $teacher)
    {
        $this->authorize('update', $teacher);

        return view('admin.teachers.edit', compact('teacher'));
    }
    public function update(Request $request, Teacher $teacher)
    {
        $this->authorize('update', $teacher);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],

            'national_code' => [
                'required',
                'digits:10',
                Rule::unique('users', 'national_code')->ignore($teacher->id),
            ],

            'phone' => [
                'nullable',
                'regex:/^09[0-9]{9}$/',
                Rule::unique('users', 'phone')->ignore($teacher->id),
            ],
        ], [
            'first_name.required' => 'وارد کردن نام الزامی است.',
            'last_name.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'national_code.required' => 'وارد کردن کد ملی الزامی است.',
            'national_code.digits' => 'کد ملی باید دقیقاً ۱۰ رقم باشد.',
            'national_code.unique' => 'این کد ملی قبلاً ثبت شده است.',
            'phone.regex' => 'شماره تلفن باید با 09 شروع شده و ۱۱ رقم باشد.',
            'phone.unique' => 'این شماره تلفن قبلاً ثبت شده است.',
        ]);

        $teacher->first_name = $validated['first_name'];
        $teacher->last_name = $validated['last_name'];
        $teacher->national_code = $validated['national_code'];
        $teacher->phone = $validated['phone'] ?? null;

        $teacher->save();

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'اطلاعات معلم با موفقیت ویرایش شد.');
    }
    public function destroy(Teacher $teacher)
    {
        $this->authorize('delete', $teacher);

        $teacher->forceDelete();

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'معلم با موفقیت حذف شد.');
    }
}
