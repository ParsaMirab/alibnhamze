<?php

namespace Tests\Feature;

use App\Enums\RankEnum;
use App\Models\PreRegistration;
use App\Models\User;
use App\Services\Sms\SmsProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Support\FakeSmsProvider;
use Tests\TestCase;

class PreRegistrationManagementTest extends TestCase
{
    use RefreshDatabase;

    private int $counter = 0;

    private FakeSmsProvider $sms;

    protected function setUp(): void
    {
        parent::setUp();

        // Fake gateway: proves no real SMS service is contacted.
        $this->sms = new FakeSmsProvider;
        $this->app->instance(SmsProvider::class, $this->sms);
    }

    // ---------------------------------------------------------------------
    // Access control
    // ---------------------------------------------------------------------

    public function test_manager_can_access_pre_registration_management(): void
    {
        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.pre-registrations.index'))
            ->assertOk()
            ->assertSee('پیش‌ثبت‌نام‌ها');
    }

    public function test_deputy_can_access_pre_registration_management(): void
    {
        $this->actingAs($this->deputy(), 'admin')
            ->get(route('admin.pre-registrations.index'))
            ->assertOk();
    }

    public function test_teacher_student_and_parent_cannot_access_the_module(): void
    {
        foreach ([RankEnum::TEACHER->value, RankEnum::STUDENT->value, RankEnum::PARENT->value] as $rank) {
            $this->actingAs($this->createUser($rank), 'admin')
                ->get(route('admin.pre-registrations.index'))
                ->assertForbidden();
        }
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.pre-registrations.index'))
            ->assertRedirect(route('login.showLoginForm'));
    }

    public function test_sidebar_entry_visibility_follows_the_policy(): void
    {
        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('پیش‌ثبت‌نام‌ها');

        $this->actingAs($this->createUser(RankEnum::TEACHER->value), 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('پیش‌ثبت‌نام‌ها');
    }

    // ---------------------------------------------------------------------
    // List, search, filters, pagination
    // ---------------------------------------------------------------------

    public function test_index_lists_pre_registration_data(): void
    {
        $preRegistration = $this->makePreRegistration([
            'student_full_name' => 'Ali Rezaei',
            'national_code' => $this->nationalCodeFor(555),
        ]);

        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.pre-registrations.index'))
            ->assertOk()
            ->assertSee('Ali Rezaei')
            ->assertSee($preRegistration->national_code)
            ->assertSee($preRegistration->phone_number)
            ->assertSee('مشاهده جزئیات');
    }

    public function test_search_matches_name_national_code_and_phone(): void
    {
        $ali = $this->makePreRegistration([
            'student_full_name' => 'Ali Rezaei',
            'national_code' => $this->nationalCodeFor(111),
            'phone_number' => '09110000001',
        ]);

        $sara = $this->makePreRegistration([
            'student_full_name' => 'Sara Ahmadi',
            'national_code' => $this->nationalCodeFor(222),
            'phone_number' => '09110000002',
        ]);

        $manager = $this->manager();

        // By name
        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['search' => 'Ali']))
            ->assertOk()
            ->assertSee('Ali Rezaei')
            ->assertDontSee('Sara Ahmadi');

        // By national code
        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['search' => $sara->national_code]))
            ->assertOk()
            ->assertSee('Sara Ahmadi')
            ->assertDontSee('Ali Rezaei');

        // By phone
        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['search' => $ali->phone_number]))
            ->assertOk()
            ->assertSee('Ali Rezaei')
            ->assertDontSee('Sara Ahmadi');
    }

    public function test_status_filtering_works(): void
    {
        $this->makePreRegistration(['student_full_name' => 'Pending Pupil', 'is_accepted' => null]);
        $this->makePreRegistration(['student_full_name' => 'Approved Pupil', 'is_accepted' => true]);
        $this->makePreRegistration(['student_full_name' => 'Rejected Pupil', 'is_accepted' => false]);

        $manager = $this->manager();

        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['status' => 'pending']))
            ->assertOk()
            ->assertSee('Pending Pupil')
            ->assertDontSee('Approved Pupil')
            ->assertDontSee('Rejected Pupil');

        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['status' => 'approved']))
            ->assertOk()
            ->assertSee('Approved Pupil')
            ->assertDontSee('Pending Pupil')
            ->assertDontSee('Rejected Pupil');

        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['status' => 'rejected']))
            ->assertOk()
            ->assertSee('Rejected Pupil')
            ->assertDontSee('Pending Pupil')
            ->assertDontSee('Approved Pupil');
    }

    public function test_grade_filtering_works(): void
    {
        $this->makePreRegistration(['student_full_name' => 'Grade Ten', 'grade' => 10]);
        $this->makePreRegistration(['student_full_name' => 'Grade Twelve', 'grade' => 12]);

        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.pre-registrations.index', ['grade' => '10']))
            ->assertOk()
            ->assertSee('Grade Ten')
            ->assertDontSee('Grade Twelve');
    }

    public function test_pagination_and_per_page_size_work(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $this->makePreRegistration([
                'student_full_name' => 'Student '.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
            ]);
        }

        $manager = $this->manager();

        // Newest first on the first page (default 15 per page).
        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index'))
            ->assertOk()
            ->assertSee('Student 20')
            ->assertDontSee('Student 01');

        // The oldest records live on the second page.
        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Student 01')
            ->assertDontSee('Student 20');

        // The per-page size is honoured.
        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['per_page' => 10]))
            ->assertOk()
            ->assertSee('Student 20')
            ->assertDontSee('Student 10');
    }

    public function test_pagination_links_preserve_filters(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $this->makePreRegistration(['student_full_name' => 'Filter Pupil '.$i, 'is_accepted' => null]);
        }

        // The next-page link keeps the active status filter.
        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.pre-registrations.index', ['status' => 'pending', 'per_page' => 10]))
            ->assertOk()
            ->assertSee('status=pending', false)
            ->assertSee('page=2', false);
    }

    public function test_sorting_by_oldest_and_newest_works(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            $this->makePreRegistration([
                'student_full_name' => 'Student '.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
            ]);
        }

        $manager = $this->manager();

        // Default: newest first, so the oldest record is not on page 1.
        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['per_page' => 10]))
            ->assertOk()
            ->assertSee('Student 12')
            ->assertDontSee('Student 01');

        // Oldest first, so the newest record is not on page 1.
        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['per_page' => 10, 'sort' => 'oldest']))
            ->assertOk()
            ->assertSee('Student 01')
            ->assertDontSee('Student 12');
    }

    public function test_invalid_filter_parameters_are_rejected(): void
    {
        $manager = $this->manager();

        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['status' => 'bogus']))
            ->assertSessionHasErrors('status');

        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['grade' => '99']))
            ->assertSessionHasErrors('grade');

        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['sort' => 'random']))
            ->assertSessionHasErrors('sort');

        $this->actingAs($manager, 'admin')
            ->get(route('admin.pre-registrations.index', ['per_page' => '999']))
            ->assertSessionHasErrors('per_page');
    }

    public function test_summary_cards_are_rendered(): void
    {
        $this->makePreRegistration(['is_accepted' => null]);

        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.pre-registrations.index'))
            ->assertOk()
            ->assertSee('کل درخواست‌ها')
            ->assertSee('در انتظار بررسی')
            ->assertSee('تأییدشده')
            ->assertSee('ردشده');
    }

    // ---------------------------------------------------------------------
    // Detail page
    // ---------------------------------------------------------------------

    public function test_detail_page_shows_the_full_application(): void
    {
        $preRegistration = $this->makePreRegistration([
            'student_full_name' => 'Detail Pupil',
            'mother_full_name' => 'Mother Name',
        ]);

        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.pre-registrations.show', $preRegistration))
            ->assertOk()
            ->assertSee('Detail Pupil')
            ->assertSee('Mother Name')
            ->assertSee('تأیید درخواست')
            ->assertSee('رد درخواست');
    }

    public function test_decided_application_hides_the_decision_buttons(): void
    {
        $preRegistration = $this->makePreRegistration(['is_accepted' => true]);

        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.pre-registrations.show', $preRegistration))
            ->assertOk()
            ->assertSee('تأییدشده')
            ->assertDontSee('تأیید درخواست')
            ->assertDontSee('رد درخواست');
    }

    public function test_unknown_application_returns_not_found(): void
    {
        $this->actingAs($this->manager(), 'admin')
            ->get(route('admin.pre-registrations.show', 999999))
            ->assertNotFound();
    }

    // ---------------------------------------------------------------------
    // Decisions
    // ---------------------------------------------------------------------

    public function test_manager_can_approve_a_pending_request(): void
    {
        $preRegistration = $this->makePreRegistration(['is_accepted' => null]);

        $this->actingAs($this->manager(), 'admin')
            ->patch(route('admin.pre-registrations.approve', $preRegistration))
            ->assertRedirect(route('admin.pre-registrations.show', $preRegistration))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pre_registrations', [
            'id' => $preRegistration->id,
            'is_accepted' => true,
        ]);
    }

    public function test_deputy_can_reject_a_pending_request(): void
    {
        $preRegistration = $this->makePreRegistration(['is_accepted' => null]);

        $this->actingAs($this->deputy(), 'admin')
            ->patch(route('admin.pre-registrations.reject', $preRegistration))
            ->assertRedirect(route('admin.pre-registrations.show', $preRegistration));

        $this->assertDatabaseHas('pre_registrations', [
            'id' => $preRegistration->id,
            'is_accepted' => false,
        ]);
    }

    public function test_unauthorized_ranks_cannot_approve_or_reject(): void
    {
        $preRegistration = $this->makePreRegistration(['is_accepted' => null]);

        foreach ([RankEnum::TEACHER->value, RankEnum::STUDENT->value, RankEnum::PARENT->value] as $rank) {
            $this->actingAs($this->createUser($rank), 'admin')
                ->patch(route('admin.pre-registrations.approve', $preRegistration))
                ->assertForbidden();

            $this->actingAs($this->createUser($rank), 'admin')
                ->patch(route('admin.pre-registrations.reject', $preRegistration))
                ->assertForbidden();
        }

        // The application was never decided.
        $this->assertDatabaseHas('pre_registrations', [
            'id' => $preRegistration->id,
            'is_accepted' => null,
        ]);
    }

    public function test_guest_cannot_approve_or_reject(): void
    {
        $preRegistration = $this->makePreRegistration(['is_accepted' => null]);

        $this->patch(route('admin.pre-registrations.approve', $preRegistration))
            ->assertRedirect(route('login.showLoginForm'));

        $this->assertDatabaseHas('pre_registrations', [
            'id' => $preRegistration->id,
            'is_accepted' => null,
        ]);
    }

    public function test_new_requests_remain_pending(): void
    {
        $preRegistration = $this->makePreRegistration();

        $this->assertNull($preRegistration->fresh()->is_accepted);
        $this->assertTrue($preRegistration->fresh()->isPending());
        $this->assertSame('در انتظار بررسی', $preRegistration->fresh()->statusLabel());
    }

    public function test_duplicate_decisions_are_prevented(): void
    {
        $preRegistration = $this->makePreRegistration(['is_accepted' => null]);
        $manager = $this->manager();

        // First approval succeeds.
        $this->actingAs($manager, 'admin')
            ->patch(route('admin.pre-registrations.approve', $preRegistration))
            ->assertSessionHas('success');

        // A second decision must not overwrite the first one.
        $this->actingAs($manager, 'admin')
            ->patch(route('admin.pre-registrations.reject', $preRegistration))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('pre_registrations', [
            'id' => $preRegistration->id,
            'is_accepted' => true,
        ]);
    }

    public function test_no_real_sms_service_is_used_on_decisions(): void
    {
        $preRegistration = $this->makePreRegistration(['is_accepted' => null]);

        $this->actingAs($this->manager(), 'admin')
            ->patch(route('admin.pre-registrations.approve', $preRegistration))
            ->assertSessionHas('success');

        // The SMS gateway was never invoked: delivery is simulated only.
        $this->assertSame([], $this->sms->messages);
    }

    // ---------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------

    protected function createUser(string $rank, bool $isAdmin = true, array $attributes = []): User
    {
        $this->counter++;
        $n = $this->counter;

        return User::create(array_merge([
            'first_name' => "User{$n}",
            'last_name' => 'Test',
            'national_code' => str_pad((string) (7000000000 + $n), 10, '0', STR_PAD_LEFT),
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
     * @param  array<string, mixed>  $overrides
     */
    protected function makePreRegistration(array $overrides = []): PreRegistration
    {
        $this->counter++;
        $n = $this->counter;

        return PreRegistration::create(array_merge([
            'student_full_name' => "دانش‌آموز {$n}",
            'father_name' => 'پدر نمونه',
            'mother_full_name' => 'مادر نمونه',
            'national_code' => $this->nationalCodeFor($n),
            'grade' => 10,
            'previous_year_average' => 18.25,
            'previous_year_discipline_score' => 19,
            'phone_number' => '0912'.str_pad((string) $n, 7, '0', STR_PAD_LEFT),
            'is_accepted' => null,
            'phone_verified_at' => now(),
        ], $overrides));
    }

    /**
     * Produce a national code with a valid checksum from a numeric seed.
     */
    private function nationalCodeFor(int $seed): string
    {
        $base = str_pad((string) (200000000 + $seed), 9, '0', STR_PAD_LEFT);

        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += ((int) $base[$i]) * (10 - $i);
        }

        $remainder = $sum % 11;
        $check = $remainder < 2 ? $remainder : 11 - $remainder;

        return $base.$check;
    }
}
