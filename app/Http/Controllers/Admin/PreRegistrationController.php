<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreRegistration;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PreRegistrationController extends Controller
{
    /**
     * Display the pre-registration list with statistics, filters and paging.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', PreRegistration::class);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in([
                PreRegistration::STATUS_PENDING,
                PreRegistration::STATUS_APPROVED,
                PreRegistration::STATUS_REJECTED,
            ])],
            'grade' => ['nullable', Rule::in(['10', '11', '12'])],
            'sort' => ['nullable', Rule::in(['newest', 'oldest'])],
            'per_page' => ['nullable', 'integer', Rule::in(['10', '15', '25', '50'])],
        ]);

        $search = trim((string) ($filters['search'] ?? ''));
        $status = $filters['status'] ?? null;
        $grade = $filters['grade'] ?? null;
        $sort = $filters['sort'] ?? 'newest';
        $perPage = (int) ($filters['per_page'] ?? 15);

        $stats = [
            'total' => PreRegistration::query()->count(),
            'pending' => PreRegistration::query()->pending()->count(),
            'approved' => PreRegistration::query()->approved()->count(),
            'rejected' => PreRegistration::query()->rejected()->count(),
        ];

        $preRegistrations = PreRegistration::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('student_full_name', 'like', "%{$search}%")
                        ->orWhere('national_code', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                });
            })
            ->when($status === PreRegistration::STATUS_PENDING, fn ($query) => $query->pending())
            ->when($status === PreRegistration::STATUS_APPROVED, fn ($query) => $query->approved())
            ->when($status === PreRegistration::STATUS_REJECTED, fn ($query) => $query->rejected())
            ->when(in_array($grade, ['10', '11', '12'], true), fn ($query) => $query->where('grade', (int) $grade))
            ->orderBy('created_at', $sort === 'oldest' ? 'asc' : 'desc')
            ->orderBy('id', $sort === 'oldest' ? 'asc' : 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pre-registrations.index', [
            'preRegistrations' => $preRegistrations,
            'stats' => $stats,
            'search' => $search,
            'statusFilter' => $status ?? '',
            'gradeFilter' => $grade ?? '',
            'sort' => $sort,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Display a single application with its full details.
     */
    public function show(PreRegistration $preRegistration): View
    {
        $this->authorize('view', $preRegistration);

        return view('admin.pre-registrations.show', compact('preRegistration'));
    }

    /**
     * Approve a pending application.
     */
    public function approve(PreRegistration $preRegistration): RedirectResponse
    {
        $this->authorize('approve', $preRegistration);

        return $this->decide($preRegistration, true);
    }

    /**
     * Reject a pending application.
     */
    public function reject(PreRegistration $preRegistration): RedirectResponse
    {
        $this->authorize('reject', $preRegistration);

        return $this->decide($preRegistration, false);
    }

    /**
     * Apply a decision atomically: only a still-pending row can be decided,
     * so concurrent or repeated submissions cannot overwrite a decision.
     */
    protected function decide(PreRegistration $preRegistration, bool $accepted): RedirectResponse
    {
        $updated = PreRegistration::query()
            ->whereKey($preRegistration->getKey())
            ->pending()
            ->update([
                'is_accepted' => $accepted,
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            return redirect()
                ->route('admin.pre-registrations.show', $preRegistration)
                ->with('error', 'این درخواست پیش‌تر بررسی شده است و امکان تغییر وضعیت آن وجود ندارد.');
        }

        $phone = $preRegistration->phone_number;

        // SMS delivery is simulated only: no provider is contacted here.
        $success = $accepted
            ? "درخواست پیش‌ثبت‌نام با موفقیت تأیید شد. پیامک اطلاع‌رسانی به شماره {$phone} به‌صورت آزمایشی شبیه‌سازی شد."
            : "درخواست پیش‌ثبت‌نام رد شد. پیامک اطلاع‌رسانی به شماره {$phone} به‌صورت آزمایشی شبیه‌سازی شد.";

        $simulatedMessage = $accepted
            ? 'پیش‌ثبت‌نام شما در هنرستان علی بن حمزه تأیید شد. برای تکمیل ثبت‌نام به هنرستان مراجعه کنید.'
            : 'متأسفانه درخواست پیش‌ثبت‌نام شما در هنرستان علی بن حمزه پذیرفته نشد.';

        return redirect()
            ->route('admin.pre-registrations.show', $preRegistration)
            ->with('success', $success)
            ->with('simulated_sms', [
                'phone' => $phone,
                'type' => $accepted ? PreRegistration::STATUS_APPROVED : PreRegistration::STATUS_REJECTED,
                'message' => $simulatedMessage,
            ]);
    }
}
