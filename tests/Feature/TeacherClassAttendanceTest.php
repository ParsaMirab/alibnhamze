<?php

namespace Tests\Feature;

use App\Enums\RankEnum;
use App\Models\ClassStudent;
use App\Models\ClassTeacher;
use App\Models\SchoolClass;
use App\Models\StudentAbsence;
use App\Models\User;
use App\Support\JalaliDate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TeacherClassAttendanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Jalaali date used across the tests, with its Gregorian equivalent.
     */
    private const JALALI_DATE = '1405/07/19';

    private const GREGORIAN_DATE = '2026-10-11';

    private const OTHER_JALALI_DATE = '1405/07/18';

    private const OTHER_GREGORIAN_DATE = '2026-10-10';

    private int $counter = 0;

    protected function createUser(string $rank, bool $isAdmin = true, array $attributes = []): User
    {
        $this->counter++;
        $n = $this->counter;

        return User::create(array_merge([
            'first_name' => "User{$n}",
            'last_name' => 'Test',
            'national_code' => str_pad((string) $n, 10, '0', STR_PAD_LEFT),
            'phone' => '09'.str_pad((string) $n, 9, '0', STR_PAD_LEFT),
            'is_admin' => $isAdmin,
            'password' => Hash::make('password'),
            'rank' => $rank,
        ], $attributes));
    }

    protected function teacher(): User
    {
        return $this->createUser(RankEnum::TEACHER->value);
    }

    protected function student(string $firstName = 'Student'): User
    {
        return $this->createUser(RankEnum::STUDENT->value, false, ['first_name' => $firstName]);
    }

    protected function createClass(string $title = 'دهم تجربی', int $grade = 10): SchoolClass
    {
        return SchoolClass::create(['title' => $title, 'grade' => $grade]);
    }

    /**
     * Create a class owned by the teacher with the given students assigned.
     *
     * @param  array<int, User>  $students
     */
    protected function classFor(User $teacher, array $students = [], string $title = 'دهم تجربی'): SchoolClass
    {
        $class = $this->createClass($title);

        ClassTeacher::create([
            'class_id' => $class->id,
            'teacher_id' => $teacher->id,
            'subject' => 'ریاضی',
        ]);

        foreach ($students as $student) {
            ClassStudent::create(['class_id' => $class->id, 'student_id' => $student->id]);
        }

        return $class;
    }

    public function test_teacher_can_view_their_classes(): void
    {
        $teacher = $this->teacher();
        $class = $this->classFor($teacher, [$this->student()], 'یازدهم ریاضی');

        $this->actingAs($teacher, 'admin')
            ->get(route('admin.my-classes.index'))
            ->assertOk()
            ->assertSee('کلاس‌های من')
            ->assertSee('یازدهم ریاضی')
            ->assertSee('مدیریت کلاس');
    }

    public function test_teacher_list_shows_only_their_own_classes(): void
    {
        $teacherA = $this->teacher();
        $teacherB = $this->teacher();

        $ownClass = $this->classFor($teacherA, [], 'کلاس خودم');
        $this->classFor($teacherB, [], 'کلاس معلم دیگر');

        $this->actingAs($teacherA, 'admin')
            ->get(route('admin.my-classes.index'))
            ->assertOk()
            ->assertSee('کلاس خودم')
            ->assertDontSee('کلاس معلم دیگر');

        $this->assertSame($ownClass->id, SchoolClass::query()->firstOrFail()->id);
    }

    public function test_teacher_sees_a_professional_empty_state_without_classes(): void
    {
        $this->actingAs($this->teacher(), 'admin')
            ->get(route('admin.my-classes.index'))
            ->assertOk()
            ->assertSee('هنوز کلاسی به شما تخصیص داده نشده است');
    }

    public function test_teacher_cannot_view_another_teachers_class(): void
    {
        $teacherA = $this->teacher();
        $teacherB = $this->teacher();

        $class = $this->classFor($teacherA, [$this->student()]);

        $this->actingAs($teacherB, 'admin')
            ->get(route('admin.my-classes.show', $class))
            ->assertForbidden();

        $this->actingAs($teacherB, 'admin')
            ->post(route('admin.my-classes.attendance.store', $class), [
                'date' => self::JALALI_DATE,
                'absent' => [],
            ])
            ->assertForbidden();
    }

    public function test_non_teacher_roles_cannot_access_the_section(): void
    {
        $teacher = $this->teacher();
        $class = $this->classFor($teacher, [$this->student()]);

        $roles = [
            [RankEnum::MANAGER->value, true],
            [RankEnum::DEPUTY->value, true],
            [RankEnum::STUDENT->value, false],
            [RankEnum::PARENT->value, false],
        ];

        foreach ($roles as [$rank, $isAdmin]) {
            $user = $this->createUser($rank, $isAdmin);

            $this->actingAs($user, 'admin')
                ->get(route('admin.my-classes.index'))
                ->assertForbidden();

            $this->actingAs($user, 'admin')
                ->get(route('admin.my-classes.show', $class))
                ->assertForbidden();

            $this->actingAs($user, 'admin')
                ->post(route('admin.my-classes.attendance.store', $class), [
                    'date' => self::JALALI_DATE,
                    'absent' => [],
                ])
                ->assertForbidden();
        }
    }

    public function test_teacher_can_view_students_belonging_to_their_class(): void
    {
        $teacher = $this->teacher();
        $studentA = $this->student('علی');
        $studentB = $this->student('سارا');

        $class = $this->classFor($teacher, [$studentA]);

        $this->actingAs($teacher, 'admin')
            ->get(route('admin.my-classes.show', $class))
            ->assertOk()
            ->assertSee('علی')
            ->assertSee($studentA->national_code)
            ->assertSee('غایب')
            ->assertSee('ثبت حضور و غیاب')
            ->assertSee('تاریخ حضور و غیاب')
            ->assertSee('لطفاً تاریخ امروز را وارد کنید')
            ->assertSee('data-jalali-datepicker', false)
            ->assertSee('data-jalali-calendar', false)
            ->assertSee('jalali-picker-head', false)
            // The pushed date picker script must reach the layout script stack.
            ->assertSee('PERSIAN_DIGITS', false)
            ->assertSee('data-attendance-form', false)
            ->assertSee('name="absent[]"', false)
            ->assertSee('value="'.JalaliDate::format(JalaliDate::today()).'"', false)
            ->assertDontSee($studentB->national_code);
    }

    public function test_absent_student_creates_an_absence_record_and_present_student_does_not(): void
    {
        $teacher = $this->teacher();
        $absent = $this->student('Absent');
        $present = $this->student('Present');

        $class = $this->classFor($teacher, [$absent, $present]);

        $this->actingAs($teacher, 'admin')
            ->post(route('admin.my-classes.attendance.store', $class), [
                'date' => self::JALALI_DATE,
                'absent' => [$absent->id],
            ])
            ->assertRedirect(route('admin.my-classes.show', [
                'schoolClass' => $class,
                'date' => self::JALALI_DATE,
            ]))
            ->assertSessionHas('success', 'حضور و غیاب دانش‌آموزان با موفقیت ثبت شد.');

        // The Jalaali date must be stored as a Gregorian DATE.
        $this->assertDatabaseHas('student_absences', [
            'class_id' => $class->id,
            'student_id' => $absent->id,
            'date' => self::GREGORIAN_DATE,
        ]);

        $this->assertDatabaseMissing('student_absences', [
            'student_id' => $present->id,
        ]);

        $this->assertSame(1, StudentAbsence::query()->count());
    }

    public function test_submitting_the_same_attendance_twice_does_not_create_duplicates(): void
    {
        $teacher = $this->teacher();
        $student = $this->student();

        $class = $this->classFor($teacher, [$student]);

        $payload = ['date' => self::JALALI_DATE, 'absent' => [$student->id]];

        $this->actingAs($teacher, 'admin')->post(route('admin.my-classes.attendance.store', $class), $payload);
        $this->actingAs($teacher, 'admin')->post(route('admin.my-classes.attendance.store', $class), $payload);

        $this->assertSame(1, StudentAbsence::query()
            ->where('class_id', $class->id)
            ->where('student_id', $student->id)
            ->whereDate('date', self::GREGORIAN_DATE)
            ->count());
    }

    public function test_changing_absent_to_present_deletes_the_record_and_back_creates_it(): void
    {
        $teacher = $this->teacher();
        $student = $this->student();

        $class = $this->classFor($teacher, [$student]);

        $this->actingAs($teacher, 'admin')->post(route('admin.my-classes.attendance.store', $class), [
            'date' => self::JALALI_DATE,
            'absent' => [$student->id],
        ])->assertSessionHas('success');

        // Absent -> present: the absence record is removed.
        $this->actingAs($teacher, 'admin')->post(route('admin.my-classes.attendance.store', $class), [
            'date' => self::JALALI_DATE,
            'absent' => [],
        ])->assertSessionHas('success');

        $this->assertSame(0, StudentAbsence::query()->count());

        // Present -> absent: the absence record is created again.
        $this->actingAs($teacher, 'admin')->post(route('admin.my-classes.attendance.store', $class), [
            'date' => self::JALALI_DATE,
            'absent' => [$student->id],
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('student_absences', [
            'class_id' => $class->id,
            'student_id' => $student->id,
            'date' => self::GREGORIAN_DATE,
        ]);
    }

    public function test_existing_absence_state_is_loaded_for_the_selected_date(): void
    {
        $teacher = $this->teacher();
        $student = $this->student();

        $class = $this->classFor($teacher, [$student]);

        $this->actingAs($teacher, 'admin')->post(route('admin.my-classes.attendance.store', $class), [
            'date' => self::JALALI_DATE,
            'absent' => [$student->id],
        ]);

        $this->actingAs($teacher, 'admin')
            ->get(route('admin.my-classes.show', ['schoolClass' => $class, 'date' => self::JALALI_DATE]))
            ->assertOk()
            ->assertViewHas('absentStudentIds', [$student->id])
            // Existing records trigger the "replace" confirmation before submitting.
            ->assertSee('برای این تاریخ قبلاً غیبت ثبت شده است', false)
            ->assertSee('data-confirm-replace', false);

        // A different date with no records must default to everyone present.
        $this->actingAs($teacher, 'admin')
            ->get(route('admin.my-classes.show', ['schoolClass' => $class, 'date' => self::OTHER_JALALI_DATE]))
            ->assertOk()
            ->assertViewHas('absentStudentIds', []);
    }

    public function test_invalid_or_foreign_student_ids_are_rejected(): void
    {
        $teacher = $this->teacher();
        $student = $this->student();

        $class = $this->classFor($teacher, [$student]);

        // A student id that does not exist at all.
        $this->actingAs($teacher, 'admin')
            ->post(route('admin.my-classes.attendance.store', $class), [
                'date' => self::JALALI_DATE,
                'absent' => [999999],
            ])
            ->assertSessionHasErrors('absent');

        // A student that belongs to another class.
        $foreignStudent = $this->student('Foreign');
        $this->classFor($this->teacher(), [$foreignStudent], 'کلاس دیگر');

        $this->actingAs($teacher, 'admin')
            ->post(route('admin.my-classes.attendance.store', $class), [
                'date' => self::JALALI_DATE,
                'absent' => [$foreignStudent->id],
            ])
            ->assertSessionHasErrors('absent');

        $this->assertSame(0, StudentAbsence::query()->count());
    }

    public function test_invalid_dates_are_rejected(): void
    {
        $teacher = $this->teacher();
        $student = $this->student();

        $class = $this->classFor($teacher, [$student]);

        $invalidDates = [
            '1405/13/01',   // month out of range
            '1405/00/10',   // month out of range
            '1405/12/30',   // 1405 is not a leap year
            '2026-10-11',   // Gregorian date
            'not-a-date',
            '',
        ];

        foreach ($invalidDates as $invalid) {
            $this->actingAs($teacher, 'admin')
                ->post(route('admin.my-classes.attendance.store', $class), [
                    'date' => $invalid,
                    'absent' => [$student->id],
                ])
                ->assertSessionHasErrors('date');
        }

        // A valid leap date is accepted.
        $this->actingAs($teacher, 'admin')
            ->post(route('admin.my-classes.attendance.store', $class), [
                'date' => '1403/12/30',
                'absent' => [$student->id],
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('student_absences', [
            'student_id' => $student->id,
            'date' => JalaliDate::toGregorianString('1403/12/30'),
        ]);

        $this->assertSame(0, StudentAbsence::query()->whereDate('date', self::GREGORIAN_DATE)->count());
    }

    public function test_attendance_of_other_classes_and_dates_remains_unchanged(): void
    {
        $teacher = $this->teacher();
        $student = $this->student();

        $class = $this->classFor($teacher, [$student]);

        // Pre-existing record on another date of the same class.
        StudentAbsence::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'date' => self::OTHER_GREGORIAN_DATE,
        ]);

        // Pre-existing record on the same date of another class.
        $otherStudent = $this->student('Other');
        $otherClass = $this->classFor($this->teacher(), [$otherStudent], 'کلاس دیگر');

        StudentAbsence::create([
            'class_id' => $otherClass->id,
            'student_id' => $otherStudent->id,
            'date' => self::GREGORIAN_DATE,
        ]);

        $this->actingAs($teacher, 'admin')->post(route('admin.my-classes.attendance.store', $class), [
            'date' => self::JALALI_DATE,
            'absent' => [],
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('student_absences', [
            'class_id' => $class->id,
            'student_id' => $student->id,
            'date' => self::OTHER_GREGORIAN_DATE,
        ]);

        $this->assertDatabaseHas('student_absences', [
            'class_id' => $otherClass->id,
            'student_id' => $otherStudent->id,
            'date' => self::GREGORIAN_DATE,
        ]);

        $this->assertSame(2, StudentAbsence::query()->count());
    }

    public function test_empty_submission_clears_absences_for_the_selected_date_only(): void
    {
        $teacher = $this->teacher();
        $student = $this->student();

        $class = $this->classFor($teacher, [$student]);

        StudentAbsence::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'date' => self::GREGORIAN_DATE,
        ]);

        StudentAbsence::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'date' => self::OTHER_GREGORIAN_DATE,
        ]);

        $this->actingAs($teacher, 'admin')->post(route('admin.my-classes.attendance.store', $class), [
            'date' => self::JALALI_DATE,
            'absent' => [],
        ])->assertSessionHas('success');

        $this->assertDatabaseMissing('student_absences', [
            'class_id' => $class->id,
            'date' => self::GREGORIAN_DATE,
        ]);

        $this->assertDatabaseHas('student_absences', [
            'class_id' => $class->id,
            'date' => self::OTHER_GREGORIAN_DATE,
        ]);
    }

    public function test_attendance_works_for_a_class_without_students(): void
    {
        $teacher = $this->teacher();
        $class = $this->classFor($teacher, []);

        $this->actingAs($teacher, 'admin')
            ->get(route('admin.my-classes.show', $class))
            ->assertOk()
            ->assertSee('دانش‌آموزی در این کلاس ثبت نشده است');

        $this->actingAs($teacher, 'admin')
            ->post(route('admin.my-classes.attendance.store', $class), [
                'date' => self::JALALI_DATE,
                'absent' => [],
            ])
            ->assertSessionHas('success');

        $this->assertSame(0, StudentAbsence::query()->count());
    }

    public function test_persian_date_conversion_is_accurate(): void
    {
        $this->assertSame('1405/07/19', JalaliDate::fromGregorianString('2026-10-11'));
        $this->assertSame('2026-10-11', JalaliDate::toGregorianString('1405/07/19'));
        $this->assertSame('2024-03-20', JalaliDate::toGregorianString('1403/01/01'));
        $this->assertTrue(JalaliDate::isValid('1403/12/30'));
        $this->assertFalse(JalaliDate::isValid('1404/12/30'));
    }

    public function test_sidebar_my_classes_item_is_teacher_only(): void
    {
        $this->actingAs($this->teacher(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('کلاس‌های من');

        $this->actingAs($this->createUser(RankEnum::MANAGER->value), 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('کلاس‌های من');

        $this->actingAs($this->createUser(RankEnum::DEPUTY->value), 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('کلاس‌های من');
    }

    public function test_existing_class_management_still_requires_manager_or_deputy(): void
    {
        $teacher = $this->teacher();
        $this->classFor($teacher, []);

        $this->actingAs($teacher, 'admin')
            ->get(route('admin.classes.index'))
            ->assertForbidden();
    }
}
