<?php

namespace Tests\Feature;

use App\Enums\RankEnum;
use App\Models\ClassStudent;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentManagementTest extends TestCase
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

    /**
     * A valid student registration payload.
     *
     * @return array<string, mixed>
     */
    protected function payload(array $overrides = []): array
    {
        $this->counter++;
        $n = $this->counter;

        return array_merge([
            'first_name' => "Student{$n}",
            'last_name' => 'Test',
            'national_code' => str_pad((string) (2000000000 + $n), 10, '0', STR_PAD_LEFT),
            'phone' => '0912'.str_pad((string) $n, 7, '0', STR_PAD_LEFT),
            'grade' => 10,
            'father_alive' => '0',
            'mother_alive' => '0',
        ], $overrides);
    }

    /**
     * Father section payload.
     *
     * @return array<string, mixed>
     */
    protected function fatherPayload(array $overrides = []): array
    {
        $this->counter++;
        $n = $this->counter;

        return array_merge([
            'father_alive' => '1',
            'father_first_name' => "Father{$n}",
            'father_last_name' => 'Test',
            'father_national_code' => str_pad((string) (4000000000 + $n), 10, '0', STR_PAD_LEFT),
            'father_phone' => '0933'.str_pad((string) $n, 7, '0', STR_PAD_LEFT),
        ], $overrides);
    }

    /**
     * Mother section payload.
     *
     * @return array<string, mixed>
     */
    protected function motherPayload(array $overrides = []): array
    {
        $this->counter++;
        $n = $this->counter;

        return array_merge([
            'mother_alive' => '1',
            'mother_first_name' => "Mother{$n}",
            'mother_last_name' => 'Test',
            'mother_national_code' => str_pad((string) (5000000000 + $n), 10, '0', STR_PAD_LEFT),
            'mother_phone' => '0934'.str_pad((string) $n, 7, '0', STR_PAD_LEFT),
        ], $overrides);
    }

    protected function makeStudent(array $overrides = []): Student
    {
        $data = $this->payload($overrides);

        return Student::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'national_code' => $data['national_code'],
            'phone' => $data['phone'] ?? null,
            'grade' => $data['grade'] ?? 10,
            'rank' => RankEnum::STUDENT->value,
            'is_admin' => false,
        ]);
    }

    protected function makeParent(array $overrides = []): User
    {
        $this->counter++;
        $n = $this->counter;

        return User::create(array_merge([
            'first_name' => "Parent{$n}",
            'last_name' => 'Test',
            'national_code' => str_pad((string) (6000000000 + $n), 10, '0', STR_PAD_LEFT),
            'phone' => '0936'.str_pad((string) $n, 7, '0', STR_PAD_LEFT),
            'rank' => RankEnum::PARENT->value,
            'is_admin' => false,
            'grade' => null,
        ], $overrides));
    }

    public function test_manager_has_full_crud_access(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $this->get(route('admin.students.index'))->assertOk()->assertSee('افزودن دانش‌آموز');
        $this->get(route('admin.students.create'))->assertOk();

        $payload = $this->payload();
        $this->post(route('admin.students.store'), $payload)
            ->assertRedirect(route('admin.students.index'))
            ->assertSessionHas('success');

        $student = Student::query()->where('national_code', $payload['national_code'])->firstOrFail();

        $this->get(route('admin.students.edit', $student))->assertOk();

        $this->put(route('admin.students.update', $student), array_merge($payload, ['first_name' => 'Updated']))
            ->assertRedirect(route('admin.students.index'));

        $this->assertSame('Updated', $student->fresh()->first_name);

        $this->delete(route('admin.students.destroy', $student))
            ->assertRedirect(route('admin.students.index'));

        $this->assertDatabaseMissing('users', ['id' => $student->id]);
    }

    public function test_deputy_has_full_crud_access(): void
    {
        $this->actingAs($this->deputy(), 'admin');

        $this->get(route('admin.students.index'))->assertOk();
        $this->get(route('admin.students.create'))->assertOk();

        $payload = $this->payload();
        $this->post(route('admin.students.store'), $payload)->assertRedirect(route('admin.students.index'));

        $student = Student::query()->where('national_code', $payload['national_code'])->firstOrFail();

        $this->put(route('admin.students.update', $student), array_merge($payload, ['grade' => 11]))
            ->assertRedirect(route('admin.students.index'));

        $this->delete(route('admin.students.destroy', $student))->assertRedirect(route('admin.students.index'));
    }

    public function test_other_ranks_receive_403(): void
    {
        $student = $this->makeStudent();

        foreach ([RankEnum::TEACHER->value, RankEnum::STUDENT->value, RankEnum::PARENT->value] as $rank) {
            $isAdmin = $rank === RankEnum::TEACHER->value;

            $this->actingAs($this->createUser($rank, $isAdmin), 'admin');

            $this->get(route('admin.students.index'))->assertForbidden();
            $this->get(route('admin.students.create'))->assertForbidden();
            $this->post(route('admin.students.store'), $this->payload())->assertForbidden();
            $this->get(route('admin.students.edit', $student))->assertForbidden();
            $this->put(route('admin.students.update', $student), $this->payload())->assertForbidden();
            $this->delete(route('admin.students.destroy', $student))->assertForbidden();
        }
    }

    public function test_student_can_be_registered_without_parents(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $payload = $this->payload();
        $this->post(route('admin.students.store'), $payload)->assertSessionHas('success');

        $student = Student::query()->where('national_code', $payload['national_code'])->firstOrFail();

        $this->assertSame(RankEnum::STUDENT->value, $student->rank);
        $this->assertFalse((bool) $student->is_admin);

        $this->assertDatabaseCount('student_parents', 0);
        $this->assertSame(0, User::query()->where('rank', RankEnum::PARENT->value)->count());
    }

    public function test_student_can_be_registered_with_father_only(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $payload = array_merge($this->payload(), $this->fatherPayload());
        $this->post(route('admin.students.store'), $payload)->assertSessionHas('success');

        $student = Student::query()->where('national_code', $payload['national_code'])->firstOrFail();
        $father = User::query()->where('national_code', $payload['father_national_code'])->firstOrFail();

        $this->assertSame(RankEnum::PARENT->value, $father->rank);
        $this->assertFalse((bool) $father->is_admin);
        $this->assertNull($father->grade);

        $this->assertDatabaseHas('student_parents', [
            'student_id' => $student->id,
            'parent_id' => $father->id,
            'relationship_type' => StudentParent::FATHER,
        ]);

        $this->assertSame(1, StudentParent::query()->count());
    }

    public function test_student_can_be_registered_with_mother_only(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $payload = array_merge($this->payload(), $this->motherPayload());
        $this->post(route('admin.students.store'), $payload)->assertSessionHas('success');

        $student = Student::query()->where('national_code', $payload['national_code'])->firstOrFail();
        $mother = User::query()->where('national_code', $payload['mother_national_code'])->firstOrFail();

        $this->assertSame(RankEnum::PARENT->value, $mother->rank);
        $this->assertDatabaseHas('student_parents', [
            'student_id' => $student->id,
            'parent_id' => $mother->id,
            'relationship_type' => StudentParent::MOTHER,
        ]);
    }

    public function test_student_can_be_registered_with_both_parents(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $payload = array_merge($this->payload(), $this->fatherPayload(), $this->motherPayload());
        $this->post(route('admin.students.store'), $payload)->assertSessionHas('success');

        $student = Student::query()->where('national_code', $payload['national_code'])->firstOrFail();

        $this->assertSame(2, StudentParent::query()->where('student_id', $student->id)->count());
        $this->assertSame(2, User::query()->where('rank', RankEnum::PARENT->value)->count());

        $this->assertNotNull($student->father);
        $this->assertNotNull($student->mother);
        $this->assertNotNull($student->father->parent);
        $this->assertNotNull($student->mother->parent);
    }

    public function test_parent_sections_are_validated_conditionally(): void
    {
        $this->actingAs($this->manager(), 'admin');

        // Toggled on without the required fields.
        $payload = array_merge($this->payload(), ['father_alive' => '1', 'mother_alive' => '1']);
        $this->post(route('admin.students.store'), $payload)
            ->assertSessionHasErrors(['father_first_name', 'father_last_name', 'father_national_code'])
            ->assertSessionHasErrors(['mother_first_name', 'mother_last_name', 'mother_national_code']);

        $this->assertDatabaseCount('student_parents', 0);

        // Toggled off: the parent fields are not required.
        $payload = array_merge($this->payload(), [
            'father_alive' => '0',
            'father_first_name' => '',
            'father_last_name' => '',
            'father_national_code' => '',
            'mother_alive' => '0',
            'mother_first_name' => '',
            'mother_last_name' => '',
            'mother_national_code' => '',
        ]);

        $this->post(route('admin.students.store'), $payload)->assertSessionHasNoErrors();
        $this->assertDatabaseCount('student_parents', 0);
    }

    public function test_student_rank_cannot_be_manipulated(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $payload = $this->payload([
            'rank' => RankEnum::MANAGER->value,
            'is_admin' => '1',
        ]);

        $this->post(route('admin.students.store'), $payload)->assertSessionHas('success');

        $student = Student::query()->where('national_code', $payload['national_code'])->firstOrFail();

        $this->assertSame(RankEnum::STUDENT->value, $student->rank);
        $this->assertFalse((bool) $student->is_admin);
    }

    public function test_parent_rank_cannot_be_manipulated(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $payload = array_merge($this->payload(), $this->fatherPayload([
            'father_rank' => RankEnum::MANAGER->value,
            'father_is_admin' => '1',
        ]));

        $this->post(route('admin.students.store'), $payload)->assertSessionHas('success');

        $father = User::query()->where('national_code', $payload['father_national_code'])->firstOrFail();

        $this->assertSame(RankEnum::PARENT->value, $father->rank);
        $this->assertFalse((bool) $father->is_admin);
    }

    public function test_duplicate_national_codes_are_rejected(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $existing = $this->payload();
        $this->post(route('admin.students.store'), $existing)->assertSessionHas('success');

        $this->post(route('admin.students.store'), $this->payload(['national_code' => $existing['national_code']]))
            ->assertSessionHasErrors('national_code');

        // A national code that already belongs to a non-parent user cannot be used as a parent.
        $manager = $this->createUser(RankEnum::TEACHER->value);

        $this->post(route('admin.students.store'), array_merge(
            $this->payload(),
            $this->fatherPayload(['father_national_code' => $manager->national_code])
        ))->assertSessionHasErrors('father_national_code');
    }

    public function test_phone_uniqueness_is_enforced(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $owner = $this->makeParent();

        // Student phone already taken.
        $this->post(route('admin.students.store'), $this->payload(['phone' => $owner->phone]))
            ->assertSessionHasErrors('phone');

        // Father phone already taken by another account.
        $this->post(route('admin.students.store'), array_merge(
            $this->payload(),
            $this->fatherPayload(['father_phone' => $owner->phone])
        ))->assertSessionHasErrors('father_phone');
    }

    public function test_existing_parent_is_reused_for_multiple_children(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $father = $this->fatherPayload();
        $this->post(route('admin.students.store'), array_merge($this->payload(), $father))
            ->assertSessionHas('success');

        $second = array_merge($this->payload(), $father);
        $this->post(route('admin.students.store'), $second)->assertSessionHas('success');

        $parent = User::query()->where('national_code', $father['father_national_code'])->firstOrFail();

        $this->assertSame(1, User::query()->where('national_code', $father['father_national_code'])->count());
        $this->assertSame(2, StudentParent::query()->where('parent_id', $parent->id)->count());
        $this->assertSame(2, $parent->children()->count());
    }

    public function test_student_can_be_edited(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $payload = array_merge($this->payload(), $this->fatherPayload());
        $this->post(route('admin.students.store'), $payload)->assertSessionHas('success');

        $student = Student::query()->where('national_code', $payload['national_code'])->firstOrFail();

        $updated = array_merge($payload, [
            'first_name' => 'Edited',
            'last_name' => 'Student',
            'grade' => 12,
        ]);

        $this->put(route('admin.students.update', $student), $updated)
            ->assertRedirect(route('admin.students.index'))
            ->assertSessionHas('success');

        $student->refresh();

        $this->assertSame('Edited', $student->first_name);
        $this->assertSame(12, $student->grade);
        $this->assertSame(1, StudentParent::query()->where('student_id', $student->id)->count());
    }

    public function test_parent_relationships_can_be_modified_safely(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $payload = array_merge($this->payload(), $this->fatherPayload());
        $this->post(route('admin.students.store'), $payload)->assertSessionHas('success');

        $student = Student::query()->where('national_code', $payload['national_code'])->firstOrFail();
        $originalFather = User::query()->where('national_code', $payload['father_national_code'])->firstOrFail();

        // Add a previously missing mother relationship.
        $mother = $this->motherPayload();
        $this->put(route('admin.students.update', $student), array_merge($payload, $mother))
            ->assertSessionHas('success');

        $motherUser = User::query()->where('national_code', $mother['mother_national_code'])->firstOrFail();

        $this->assertDatabaseHas('student_parents', [
            'student_id' => $student->id,
            'parent_id' => $motherUser->id,
            'relationship_type' => StudentParent::MOTHER,
        ]);

        // Re-point the father relationship to another existing parent account.
        $replacement = $this->makeParent();

        $repointed = array_merge($payload, $mother, [
            'father_national_code' => $replacement->national_code,
            'father_first_name' => $replacement->first_name,
            'father_last_name' => $replacement->last_name,
            'father_phone' => '',
        ]);

        $this->put(route('admin.students.update', $student), $repointed)->assertSessionHas('success');

        $this->assertDatabaseHas('student_parents', [
            'student_id' => $student->id,
            'parent_id' => $replacement->id,
            'relationship_type' => StudentParent::FATHER,
        ]);

        // The previous parent account is preserved.
        $this->assertDatabaseHas('users', ['id' => $originalFather->id]);

        // Unchecking the father detaches the relationship but keeps the account.
        $this->put(route('admin.students.update', $student), array_merge($repointed, ['father_alive' => '0']))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('student_parents', [
            'student_id' => $student->id,
            'relationship_type' => StudentParent::FATHER,
        ]);

        $this->assertDatabaseHas('users', ['id' => $replacement->id]);
        $this->assertDatabaseHas('users', ['id' => $originalFather->id]);
    }

    public function test_shared_parent_is_not_modified_silently(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $father = $this->fatherPayload();
        $this->post(route('admin.students.store'), array_merge($this->payload(), $father))
            ->assertSessionHas('success');

        $secondStudent = array_merge($this->payload(), $father);
        $this->post(route('admin.students.store'), $secondStudent)->assertSessionHas('success');

        $parent = User::query()->where('national_code', $father['father_national_code'])->firstOrFail();
        $originalName = $parent->first_name;

        $student = Student::query()->where('national_code', $secondStudent['national_code'])->firstOrFail();

        $this->put(route('admin.students.update', $student), array_merge($secondStudent, [
            'father_first_name' => 'ChangedName',
        ]))->assertSessionHas('success');

        $this->assertSame($originalName, $parent->fresh()->first_name);
    }

    public function test_deleting_a_student_preserves_parents_and_class_relationships_are_cleaned(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $payload = array_merge($this->payload(), $this->fatherPayload(), $this->motherPayload());
        $this->post(route('admin.students.store'), $payload)->assertSessionHas('success');

        $student = Student::query()->where('national_code', $payload['national_code'])->firstOrFail();
        $father = User::query()->where('national_code', $payload['father_national_code'])->firstOrFail();

        $class = SchoolClass::create(['title' => 'دهم تجربی', 'grade' => 10]);
        ClassStudent::create(['class_id' => $class->id, 'student_id' => $student->id]);

        $this->delete(route('admin.students.destroy', $student))
            ->assertRedirect(route('admin.students.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $student->id]);
        $this->assertDatabaseMissing('student_parents', ['student_id' => $student->id]);
        $this->assertDatabaseMissing('class_students', ['student_id' => $student->id]);

        // Parent accounts are never deleted.
        $this->assertDatabaseHas('users', ['id' => $father->id, 'rank' => RankEnum::PARENT->value]);
        $this->assertSame(2, User::query()->where('rank', RankEnum::PARENT->value)->count());
    }

    public function test_search_matches_names_national_codes_and_phones(): void
    {
        $this->actingAs($this->manager(), 'admin');

        $ali = $this->makeStudent([
            'first_name' => 'Ali',
            'last_name' => 'Rezaei',
            'national_code' => '1111111111',
            'phone' => '09111111111',
        ]);

        $sara = $this->makeStudent([
            'first_name' => 'Sara',
            'last_name' => 'Ahmadi',
            'national_code' => '2222222222',
            'phone' => '09222222222',
        ]);

        // First name
        $this->get(route('admin.students.index', ['search' => 'Ali']))
            ->assertOk()
            ->assertSee('Ali')
            ->assertDontSee('Sara');

        // Last name
        $this->get(route('admin.students.index', ['search' => 'Ahmadi']))
            ->assertOk()
            ->assertSee('Ahmadi')
            ->assertDontSee('Rezaei');

        // Full name
        $this->get(route('admin.students.index', ['search' => 'Ali Rezaei']))
            ->assertOk()
            ->assertSee('Ali')
            ->assertDontSee('Sara');

        // National code
        $this->get(route('admin.students.index', ['search' => '2222222222']))
            ->assertOk()
            ->assertSee('Sara')
            ->assertDontSee('Ali Rezaei');

        // Phone
        $this->get(route('admin.students.index', ['search' => '09111111111']))
            ->assertOk()
            ->assertSee('Ali')
            ->assertDontSee('Ahmadi');

        $this->assertNotSame($ali->id, $sara->id);
    }

    public function test_students_route_cannot_reach_non_student_users(): void
    {
        $manager = $this->manager();

        $this->actingAs($manager, 'admin')
            ->get(route('admin.students.edit', $manager))
            ->assertNotFound();
    }

    public function test_class_and_deputy_management_authorization_is_unchanged(): void
    {
        // Deputy: class management allowed, deputy management forbidden.
        $this->actingAs($this->deputy(), 'admin');
        $this->get(route('admin.classes.index'))->assertOk();
        $this->get(route('admin.admin-management.index'))->assertForbidden();

        // Manager: both allowed.
        $this->actingAs($this->manager(), 'admin');
        $this->get(route('admin.classes.index'))->assertOk();
        $this->get(route('admin.admin-management.index'))->assertOk();
    }

    public function test_sidebar_student_item_follows_the_policy(): void
    {
        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('مدیریت دانش‌آموزان');

        $this->actingAs($this->deputy(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('مدیریت دانش‌آموزان');

        $this->actingAs($this->createUser(RankEnum::TEACHER->value), 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('مدیریت دانش‌آموزان');
    }
}
