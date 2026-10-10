<?php

namespace Tests\Feature;

use App\Enums\RankEnum;
use App\Models\ClassStudent;
use App\Models\ClassTeacher;
use App\Models\SchoolClass;
use App\Models\User;
use Database\Seeders\ClassManagementSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ClassManagementTest extends TestCase
{
    use RefreshDatabase;

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

    protected function manager(): User
    {
        return $this->createUser(RankEnum::MANAGER->value);
    }

    protected function deputy(): User
    {
        return $this->createUser(RankEnum::DEPUTY->value);
    }

    protected function createClass(string $title = 'دهم تجربی', int $grade = 10): SchoolClass
    {
        return SchoolClass::create(['title' => $title, 'grade' => $grade]);
    }

    public function test_manager_can_view_classes_list(): void
    {
        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.classes.index'))
            ->assertOk()
            ->assertSee('مدیریت کلاس‌ها')
            ->assertSee('افزودن کلاس');
    }

    public function test_deputy_can_view_classes_list(): void
    {
        $this->actingAs($this->deputy(), 'admin')
            ->get(route('admin.classes.index'))
            ->assertOk()
            ->assertSee('افزودن کلاس');
    }

    public function test_other_ranks_cannot_view_classes_list(): void
    {
        foreach ([RankEnum::TEACHER->value, RankEnum::STUDENT->value, RankEnum::PARENT->value] as $rank) {
            $isAdmin = $rank !== RankEnum::STUDENT->value && $rank !== RankEnum::PARENT->value;

            $this->actingAs($this->createUser($rank, $isAdmin), 'admin')
                ->get(route('admin.classes.index'))
                ->assertForbidden();
        }
    }

    public function test_manager_can_create_update_and_delete_a_class(): void
    {
        $manager = $this->manager();
        $this->actingAs($manager, 'admin');

        $this->get(route('admin.classes.create'))
            ->assertOk()
            ->assertSee('افزودن کلاس')
            ->assertSee('پایه تحصیلی');

        $this->post(route('admin.classes.store'), ['title' => 'دهم تجربی', 'grade' => 10])
            ->assertRedirect(route('admin.classes.index'))
            ->assertSessionHas('success');

        $class = SchoolClass::firstOrFail();
        $this->assertSame('دهم تجربی', $class->title);
        $this->assertSame(10, $class->grade);

        $this->put(route('admin.classes.update', $class), ['title' => 'یازدهم تجربی', 'grade' => 11])
            ->assertRedirect(route('admin.classes.edit', $class))
            ->assertSessionHas('success');

        $class->refresh();
        $this->assertSame('یازدهم تجربی', $class->title);
        $this->assertSame(11, $class->grade);

        $this->delete(route('admin.classes.destroy', $class))
            ->assertRedirect(route('admin.classes.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('classes', ['id' => $class->id]);
    }

    public function test_deputy_can_create_update_and_delete_a_class(): void
    {
        $this->actingAs($this->deputy(), 'admin');

        $this->post(route('admin.classes.store'), ['title' => 'دهم ریاضی', 'grade' => 10])
            ->assertRedirect(route('admin.classes.index'));

        $class = SchoolClass::firstOrFail();

        $this->put(route('admin.classes.update', $class), ['title' => 'دوازدهم ریاضی', 'grade' => 12])
            ->assertRedirect(route('admin.classes.edit', $class));

        $this->delete(route('admin.classes.destroy', $class))
            ->assertRedirect(route('admin.classes.index'));

        $this->assertDatabaseMissing('classes', ['id' => $class->id]);
    }

    public function test_other_ranks_cannot_create_update_or_delete_a_class(): void
    {
        $class = $this->createClass();
        $teacher = $this->createUser(RankEnum::TEACHER->value);

        $this->actingAs($teacher, 'admin');
        $this->get(route('admin.classes.create'))->assertForbidden();
        $this->post(route('admin.classes.store'), ['title' => 'x', 'grade' => 10])->assertForbidden();
        $this->put(route('admin.classes.update', $class), ['title' => 'y', 'grade' => 11])->assertForbidden();
        $this->delete(route('admin.classes.destroy', $class))->assertForbidden();
    }

    public function test_class_list_shows_edit_and_delete_actions(): void
    {
        $class = $this->createClass();

        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.classes.index'))
            ->assertOk()
            ->assertSee(route('admin.classes.edit', $class), false)
            ->assertSee(route('admin.classes.destroy', $class), false)
            ->assertSee('ویرایش')
            ->assertSee('حذف');
    }

    public function test_edit_page_displays_tabs_and_prefilled_class_information(): void
    {
        $class = $this->createClass('یازدهم ریاضی', 11);

        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.classes.edit', $class))
            ->assertOk()
            ->assertSee('دانش‌آموزان')
            ->assertSee('معلمان')
            ->assertSee('ذخیره تغییرات')
            ->assertSee($class->title)
            ->assertSee('value="'.$class->title.'"', false)
            ->assertSee('<option value="11" selected>', false)
            ->assertSee('data-class-tabs', false)
            ->assertSee('data-class-tab="students"', false)
            ->assertSee('data-class-tab="teachers"', false)
            ->assertSee('data-class-tab-panel="students"', false)
            ->assertSee('افزودن دانش‌آموز')
            ->assertSee('افزودن معلم')
            ->assertSee('data-modal-open="addStudentModal"', false)
            ->assertSee('data-modal-open="addTeacherModal"', false)
            ->assertSee('class="admin-modal hidden"', false)
            ->assertSee('id="addStudentModal"', false)
            ->assertSee('id="addTeacherModal"', false);
    }

    public function test_assignment_modals_are_body_level_and_use_searchable_comboboxes(): void
    {
        $class = $this->createClass();
        $assigned = $this->createUser(RankEnum::STUDENT->value, false, ['first_name' => 'Assigned', 'last_name' => 'Pupil']);
        $unassigned = $this->createUser(RankEnum::STUDENT->value, false, [
            'first_name' => 'Other',
            'last_name' => 'Pupil',
            'national_code' => '9999999999',
        ]);
        $teacher = $this->createUser(RankEnum::TEACHER->value, true, ['first_name' => 'Tara', 'last_name' => 'Teacher']);

        ClassStudent::create(['class_id' => $class->id, 'student_id' => $assigned->id]);

        $response = $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.classes.edit', $class))
            ->assertOk()
            ->assertSee('data-combobox', false)
            ->assertSee('data-combobox-search', false)
            ->assertSee('name="student_id"', false)
            ->assertSee('name="teacher_id"', false)
            // Every student of the school is searchable, not only the assigned ones.
            ->assertSee('Other Pupil')
            ->assertSee('9999999999')
            ->assertSee('Tara Teacher');

        $html = $response->getContent();

        // The modal markup must live at body level (rendered by the modals stack),
        // not inside the scrollable content where it would be pushed to the footer.
        $modalPos = strpos($html, 'id="addStudentModal"');
        $scriptPos = strpos($html, 'core.bundle.js');

        $this->assertNotFalse($modalPos);
        $this->assertNotFalse($scriptPos);
        $this->assertLessThan($scriptPos, $modalPos);
    }

    public function test_sidebar_class_management_item_follows_the_policy(): void
    {
        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('مدیریت کلاس‌ها');

        $this->actingAs($this->deputy(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('مدیریت کلاس‌ها');

        $this->actingAs($this->createUser(RankEnum::TEACHER->value), 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('مدیریت کلاس‌ها');
    }

    public function test_show_page_renders_assignments_and_tabs(): void
    {
        $class = $this->createClass();
        $student = $this->createUser(RankEnum::STUDENT->value, false);
        $teacher = $this->createUser(RankEnum::TEACHER->value);

        ClassStudent::create(['class_id' => $class->id, 'student_id' => $student->id]);
        ClassTeacher::create(['class_id' => $class->id, 'teacher_id' => $teacher->id, 'subject' => 'شیمی']);

        $this->actingAs($this->deputy(), 'admin')
            ->get(route('admin.classes.show', ['schoolClass' => $class, 'tab' => 'teachers']))
            ->assertOk()
            ->assertSee('data-initial-tab="teachers"', false)
            ->assertSee('شیمی')
            ->assertSee($student->first_name)
            ->assertSee('function openModal', false)
            ->assertSee('[data-class-tab-panel]', false);
    }

    public function test_deputy_can_open_the_edit_page(): void
    {
        $class = $this->createClass();

        $this->actingAs($this->deputy(), 'admin')
            ->get(route('admin.classes.edit', $class))
            ->assertOk();
    }

    public function test_students_tab_only_displays_current_class_students(): void
    {
        $classA = $this->createClass('کلاس الف', 10);
        $classB = $this->createClass('کلاس ب', 11);

        $studentA = $this->createUser(RankEnum::STUDENT->value, false);
        $studentB = $this->createUser(RankEnum::STUDENT->value, false);

        ClassStudent::create(['class_id' => $classA->id, 'student_id' => $studentA->id]);
        ClassStudent::create(['class_id' => $classB->id, 'student_id' => $studentB->id]);

        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.classes.edit', ['schoolClass' => $classA, 'tab' => 'students']))
            ->assertOk()
            ->assertViewHas('schoolClass', function (SchoolClass $schoolClass) use ($studentA, $studentB) {
                $studentIds = $schoolClass->studentAssignments->pluck('student_id');

                return $studentIds->contains($studentA->id) && ! $studentIds->contains($studentB->id);
            });
    }

    public function test_teachers_tab_only_displays_current_class_teachers(): void
    {
        $classA = $this->createClass('کلاس الف', 10);
        $classB = $this->createClass('کلاس ب', 11);

        $teacherA = $this->createUser(RankEnum::TEACHER->value);
        $teacherB = $this->createUser(RankEnum::TEACHER->value);

        ClassTeacher::create(['class_id' => $classA->id, 'teacher_id' => $teacherA->id, 'subject' => 'ریاضی']);
        ClassTeacher::create(['class_id' => $classB->id, 'teacher_id' => $teacherB->id, 'subject' => 'فیزیک']);

        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.classes.edit', ['schoolClass' => $classA, 'tab' => 'teachers']))
            ->assertOk()
            ->assertViewHas('schoolClass', function (SchoolClass $schoolClass) use ($teacherA, $teacherB) {
                $teacherIds = $schoolClass->teacherAssignments->pluck('teacher_id');

                return $teacherIds->contains($teacherA->id) && ! $teacherIds->contains($teacherB->id);
            });
    }

    public function test_student_assignment_can_be_added_edited_and_removed(): void
    {
        $class = $this->createClass();
        $student1 = $this->createUser(RankEnum::STUDENT->value, false);
        $student2 = $this->createUser(RankEnum::STUDENT->value, false);

        $this->actingAs($this->deputy(), 'admin');

        $this->post(route('admin.classes.students.store', $class), ['student_id' => $student1->id])
            ->assertRedirect(route('admin.classes.edit', ['schoolClass' => $class, 'tab' => 'students']))
            ->assertSessionHas('success');

        $assignment = ClassStudent::firstOrFail();
        $this->assertSame($class->id, $assignment->class_id);
        $this->assertSame($student1->id, $assignment->student_id);

        $this->get(route('admin.classes.students.edit', [$class, $assignment]))
            ->assertRedirect(route('admin.classes.edit', ['schoolClass' => $class, 'tab' => 'students', 'edit' => $assignment->id]));

        $this->put(route('admin.classes.students.update', [$class, $assignment]), ['student_id' => $student2->id])
            ->assertRedirect(route('admin.classes.edit', ['schoolClass' => $class, 'tab' => 'students']))
            ->assertSessionHas('success');

        $assignment->refresh();
        $this->assertSame($student2->id, $assignment->student_id);

        $this->delete(route('admin.classes.students.destroy', [$class, $assignment]))
            ->assertRedirect(route('admin.classes.edit', ['schoolClass' => $class, 'tab' => 'students']))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('class_students', ['id' => $assignment->id]);

        // Removing the assignment must never remove the underlying user.
        $this->assertDatabaseHas('users', ['id' => $student1->id]);
        $this->assertDatabaseHas('users', ['id' => $student2->id]);
    }

    public function test_teacher_assignment_can_be_added_edited_and_removed(): void
    {
        $class = $this->createClass();
        $teacher = $this->createUser(RankEnum::TEACHER->value);

        $this->actingAs($this->manager(), 'admin');

        $this->post(route('admin.classes.teachers.store', $class), ['teacher_id' => $teacher->id, 'subject' => 'ریاضی'])
            ->assertRedirect(route('admin.classes.edit', ['schoolClass' => $class, 'tab' => 'teachers']))
            ->assertSessionHas('success');

        $assignment = ClassTeacher::firstOrFail();

        $this->put(route('admin.classes.teachers.update', [$class, $assignment]), ['teacher_id' => $teacher->id, 'subject' => 'فیزیک'])
            ->assertRedirect(route('admin.classes.edit', ['schoolClass' => $class, 'tab' => 'teachers']))
            ->assertSessionHas('success');

        $assignment->refresh();
        $this->assertSame('فیزیک', $assignment->subject);

        $this->delete(route('admin.classes.teachers.destroy', [$class, $assignment]))
            ->assertRedirect(route('admin.classes.edit', ['schoolClass' => $class, 'tab' => 'teachers']));

        $this->assertDatabaseMissing('class_teachers', ['id' => $assignment->id]);
        $this->assertDatabaseHas('users', ['id' => $teacher->id]);
    }

    public function test_teacher_can_teach_multiple_subjects_in_the_same_class(): void
    {
        $class = $this->createClass();
        $teacher = $this->createUser(RankEnum::TEACHER->value);

        $this->actingAs($this->manager(), 'admin');

        $this->post(route('admin.classes.teachers.store', $class), ['teacher_id' => $teacher->id, 'subject' => 'ریاضی'])
            ->assertSessionHas('success');

        $this->post(route('admin.classes.teachers.store', $class), ['teacher_id' => $teacher->id, 'subject' => 'فیزیک'])
            ->assertSessionHas('success');

        $this->assertSame(2, ClassTeacher::where('class_id', $class->id)->where('teacher_id', $teacher->id)->count());
    }

    public function test_duplicate_student_assignment_is_rejected(): void
    {
        $class = $this->createClass();
        $student = $this->createUser(RankEnum::STUDENT->value, false);

        $this->actingAs($this->manager(), 'admin');

        $this->post(route('admin.classes.students.store', $class), ['student_id' => $student->id])
            ->assertSessionHas('success');

        $this->post(route('admin.classes.students.store', $class), ['student_id' => $student->id])
            ->assertSessionHasErrors('student_id')
            ->assertRedirect(route('admin.classes.edit', ['schoolClass' => $class, 'tab' => 'students']));

        $this->assertSame(1, ClassStudent::where('class_id', $class->id)->where('student_id', $student->id)->count());
    }

    public function test_duplicate_teacher_subject_assignment_is_rejected_but_other_subjects_allowed(): void
    {
        $class = $this->createClass();
        $teacher = $this->createUser(RankEnum::TEACHER->value);

        $this->actingAs($this->manager(), 'admin');

        $this->post(route('admin.classes.teachers.store', $class), ['teacher_id' => $teacher->id, 'subject' => 'ریاضی'])
            ->assertSessionHas('success');

        $this->post(route('admin.classes.teachers.store', $class), ['teacher_id' => $teacher->id, 'subject' => 'ریاضی'])
            ->assertSessionHasErrors('subject')
            ->assertRedirect(route('admin.classes.edit', ['schoolClass' => $class, 'tab' => 'teachers']));

        $this->assertSame(1, ClassTeacher::where('class_id', $class->id)->where('teacher_id', $teacher->id)->where('subject', 'ریاضی')->count());
    }

    public function test_cross_class_assignment_manipulation_is_rejected(): void
    {
        $classA = $this->createClass('کلاس الف', 10);
        $classB = $this->createClass('کلاس ب', 11);

        $student = $this->createUser(RankEnum::STUDENT->value, false);
        $teacher = $this->createUser(RankEnum::TEACHER->value);

        $studentAssignment = ClassStudent::create(['class_id' => $classA->id, 'student_id' => $student->id]);
        $teacherAssignment = ClassTeacher::create(['class_id' => $classA->id, 'teacher_id' => $teacher->id, 'subject' => 'ریاضی']);

        $this->actingAs($this->manager(), 'admin');

        $this->put(route('admin.classes.students.update', [$classB, $studentAssignment]), ['student_id' => $student->id])
            ->assertForbidden();

        $this->delete(route('admin.classes.students.destroy', [$classB, $studentAssignment]))
            ->assertForbidden();

        $this->put(route('admin.classes.teachers.update', [$classB, $teacherAssignment]), ['teacher_id' => $teacher->id, 'subject' => 'ریاضی'])
            ->assertForbidden();

        $this->delete(route('admin.classes.teachers.destroy', [$classB, $teacherAssignment]))
            ->assertForbidden();

        $this->assertDatabaseHas('class_students', ['id' => $studentAssignment->id, 'class_id' => $classA->id]);
        $this->assertDatabaseHas('class_teachers', ['id' => $teacherAssignment->id, 'class_id' => $classA->id]);
    }

    public function test_other_ranks_cannot_manage_assignments(): void
    {
        $class = $this->createClass();
        $student = $this->createUser(RankEnum::STUDENT->value, false);
        $teacher = $this->createUser(RankEnum::TEACHER->value);
        $assignment = ClassStudent::create(['class_id' => $class->id, 'student_id' => $student->id]);

        $this->actingAs($this->createUser(RankEnum::TEACHER->value), 'admin');

        $this->post(route('admin.classes.students.store', $class), ['student_id' => $student->id])->assertForbidden();
        $this->put(route('admin.classes.students.update', [$class, $assignment]), ['student_id' => $student->id])->assertForbidden();
        $this->delete(route('admin.classes.students.destroy', [$class, $assignment]))->assertForbidden();
        $this->post(route('admin.classes.teachers.store', $class), ['teacher_id' => $teacher->id, 'subject' => 'ریاضی'])->assertForbidden();
    }

    public function test_selected_tab_is_preserved_after_submissions_and_errors(): void
    {
        $class = $this->createClass();
        $teacher = $this->createUser(RankEnum::TEACHER->value);

        $this->actingAs($this->manager(), 'admin');

        $this->post(route('admin.classes.teachers.store', $class), ['teacher_id' => $teacher->id, 'subject' => 'ریاضی'])
            ->assertRedirect(route('admin.classes.edit', ['schoolClass' => $class, 'tab' => 'teachers']));

        $this->post(route('admin.classes.teachers.store', $class), ['teacher_id' => $teacher->id, 'subject' => 'ریاضی'])
            ->assertSessionHasErrors('subject')
            ->assertRedirect(route('admin.classes.edit', ['schoolClass' => $class, 'tab' => 'teachers']));
    }

    public function test_deputy_management_module_is_still_manager_only(): void
    {
        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.admin-management.index'))
            ->assertOk();

        $this->actingAs($this->deputy(), 'admin')
            ->get(route('admin.admin-management.index'))
            ->assertForbidden();
    }

    public function test_custom_permission_system_has_been_removed(): void
    {
        $this->assertFileDoesNotExist(
            database_path('migrations/2026_10_10_000004_add_permissions_to_users_table.php')
        );

        $this->assertFalse(Schema::hasColumn('users', 'permissions'));

        $this->assertFalse(method_exists(User::class, 'hasPermission'));
    }

    public function test_class_management_seeder_no_longer_seeds_permissions(): void
    {
        $this->seed(ClassManagementSeeder::class);

        $this->assertTrue(User::where('rank', RankEnum::DEPUTY->value)->exists());
        $this->assertTrue(User::where('rank', RankEnum::MANAGER->value)->exists());
        $this->assertTrue(SchoolClass::query()->exists());

        // Deputies are authorized for classes without any permission column.
        $deputy = User::where('rank', RankEnum::DEPUTY->value)->firstOrFail();
        $this->assertTrue($deputy->can('viewAny', SchoolClass::class));
    }
}
