@extends('admin.layouts.app')

@section('title', 'جزئیات پیش‌ثبت‌نام')

@php
    $statusBadge = match ($preRegistration->status()) {
        'approved' => 'kt-badge-success',
        'rejected' => 'kt-badge-destructive',
        default => 'kt-badge-warning',
    };

    $simulatedSms = session('simulated_sms');

    $smsPreview = match ($preRegistration->status()) {
        'approved' => 'پیش‌ثبت‌نام شما در هنرستان علی بن حمزه تأیید شد. برای تکمیل ثبت‌نام به هنرستان مراجعه کنید.',
        'rejected' => 'متأسفانه درخواست پیش‌ثبت‌نام شما در هنرستان علی بن حمزه پذیرفته نشد.',
        default => 'پس از بررسی درخواست، پیامک اطلاع‌رسانی به‌صورت آزمایشی شبیه‌سازی می‌شود.',
    };
@endphp

@section('content')
    <div class="kt-container-fixed pb-10" dir="rtl" style="font-feature-settings: 'ss01';">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="mb-5 flex flex-wrap items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0">
                <div class="mb-1.5 flex items-center gap-2 text-xs text-secondary-foreground">
                    <i class="ki-filled ki-notepad-edit"></i>
                    <a href="{{ route('admin.pre-registrations.index') }}" class="hover:text-primary">پیش‌ثبت‌نام‌ها</a>
                    <span>/</span>
                    <span>جزئیات درخواست</span>
                </div>

                <h1 class="text-xl font-bold text-mono sm:text-2xl">
                    {{ $preRegistration->student_full_name }}
                </h1>

                <div class="mt-2 flex flex-wrap items-center gap-2">
                    <span class="kt-badge {{ $statusBadge }} kt-badge-light">{{ $preRegistration->statusLabel() }}</span>
                    <span class="kt-badge kt-badge-outline">{{ $preRegistration->gradeLabel() }}</span>
                </div>
            </div>

            <a href="{{ route('admin.pre-registrations.index') }}" class="kt-btn kt-btn-outline shrink-0">
                <i class="ki-filled ki-arrow-right"></i>
                بازگشت به فهرست
            </a>
        </div>

        {{-- ================= FLASH ================= --}}
        @if (session('success'))
            <div class="kt-alert kt-alert-success mb-4" role="status">
                <i class="ki-filled ki-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="kt-alert kt-alert-destructive mb-4" role="alert">
                <i class="ki-filled ki-information-2 text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="kt-alert kt-alert-destructive mb-4" role="alert">
                <i class="ki-filled ki-information-2 text-lg"></i>
                <div class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ================= SIMULATED SMS NOTICE ================= --}}
        @if ($simulatedSms)
            <div class="kt-alert kt-alert-warning mb-4" role="status">
                <i class="ki-filled ki-sms text-lg"></i>
                <div class="min-w-0">
                    <p class="font-semibold">پیامک آزمایشی (شبیه‌سازی‌شده)</p>
                    <p class="mt-1 text-sm leading-6">
                        این پیامک ارسال نشده است. متن زیر تنها به‌صورت آزمایشی برای شماره
                        <span dir="ltr" class="font-semibold">{{ $simulatedSms['phone'] }}</span>
                        شبیه‌سازی شده است.
                    </p>
                    <p class="mt-2 rounded-lg bg-background/60 px-3 py-2 text-sm leading-6">{{ $simulatedSms['message'] }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

            {{-- ================= STUDENT INFORMATION ================= --}}
            <section class="kt-card min-w-0">
                <div class="kt-card-header">
                    <h2 class="kt-card-title">اطلاعات دانش‌آموز</h2>
                </div>

                <div class="kt-card-content p-4 sm:p-5">
                    <dl class="divide-y divide-border">
                        <div class="flex items-start justify-between gap-4 py-3 first:pt-0">
                            <dt class="text-sm text-secondary-foreground">نام و نام خانوادگی دانش‌آموز</dt>
                            <dd class="text-sm font-semibold text-mono text-left">{{ $preRegistration->student_full_name }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 py-3">
                            <dt class="text-sm text-secondary-foreground">نام پدر</dt>
                            <dd class="text-sm font-semibold text-mono text-left">{{ $preRegistration->father_name }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 py-3">
                            <dt class="text-sm text-secondary-foreground">نام و نام خانوادگی مادر</dt>
                            <dd class="text-sm font-semibold text-mono text-left">{{ $preRegistration->mother_full_name }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 py-3 last:pb-0">
                            <dt class="text-sm text-secondary-foreground">کد ملی دانش‌آموز</dt>
                            <dd class="text-sm font-semibold text-mono text-left" dir="ltr">{{ $preRegistration->national_code }}</dd>
                        </div>
                    </dl>
                </div>
            </section>

            {{-- ================= ACADEMIC INFORMATION ================= --}}
            <section class="kt-card min-w-0">
                <div class="kt-card-header">
                    <h2 class="kt-card-title">اطلاعات تحصیلی</h2>
                </div>

                <div class="kt-card-content p-4 sm:p-5">
                    <dl class="divide-y divide-border">
                        <div class="flex items-start justify-between gap-4 py-3 first:pt-0">
                            <dt class="text-sm text-secondary-foreground">پایه تحصیلی</dt>
                            <dd class="text-sm font-semibold text-mono text-left">{{ $preRegistration->gradeLabel() }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 py-3">
                            <dt class="text-sm text-secondary-foreground">معدل کل سال تحصیلی قبل</dt>
                            <dd class="text-sm font-semibold text-mono text-left" dir="ltr">{{ $preRegistration->previous_year_average }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 py-3 last:pb-0">
                            <dt class="text-sm text-secondary-foreground">نمره انضباط سال قبل</dt>
                            <dd class="text-sm font-semibold text-mono text-left" dir="ltr">{{ $preRegistration->previous_year_discipline_score }}</dd>
                        </div>
                    </dl>
                </div>
            </section>

            {{-- ================= CONTACT INFORMATION ================= --}}
            <section class="kt-card min-w-0">
                <div class="kt-card-header">
                    <h2 class="kt-card-title">اطلاعات تماس</h2>
                </div>

                <div class="kt-card-content p-4 sm:p-5">
                    <dl class="divide-y divide-border">
                        <div class="flex items-start justify-between gap-4 py-3 first:pt-0">
                            <dt class="text-sm text-secondary-foreground">شماره تلفن همراه</dt>
                            <dd class="text-sm font-semibold text-mono text-left" dir="ltr">{{ $preRegistration->phone_number }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 py-3 last:pb-0">
                            <dt class="text-sm text-secondary-foreground">زمان تأیید شماره تلفن</dt>
                            <dd class="text-sm font-semibold text-mono text-left">
                                @if ($preRegistration->phone_verified_at)
                                    {{ \App\Helpers\Jalali::format($preRegistration->phone_verified_at) }}
                                    <span class="text-secondary-foreground">-</span>
                                    <span dir="ltr">{{ $preRegistration->phone_verified_at->format('H:i') }}</span>
                                @else
                                    تأیید نشده
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </section>

            {{-- ================= REQUEST INFORMATION ================= --}}
            <section class="kt-card min-w-0">
                <div class="kt-card-header">
                    <h2 class="kt-card-title">وضعیت درخواست</h2>
                </div>

                <div class="kt-card-content p-4 sm:p-5">
                    <dl class="divide-y divide-border">
                        <div class="flex items-start justify-between gap-4 py-3 first:pt-0">
                            <dt class="text-sm text-secondary-foreground">زمان ثبت درخواست</dt>
                            <dd class="text-sm font-semibold text-mono text-left">
                                {{ \App\Helpers\Jalali::format($preRegistration->created_at) }}
                                <span class="text-secondary-foreground">-</span>
                                <span dir="ltr">{{ $preRegistration->created_at?->format('H:i') }}</span>
                            </dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 py-3 last:pb-0">
                            <dt class="text-sm text-secondary-foreground">وضعیت فعلی درخواست</dt>
                            <dd class="text-left">
                                <span class="kt-badge {{ $statusBadge }} kt-badge-light">{{ $preRegistration->statusLabel() }}</span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </section>
        </div>

        {{-- ================= DECISION ================= --}}
        <section class="kt-card mt-5 min-w-0">
            <div class="kt-card-header">
                <h2 class="kt-card-title">تصمیم‌گیری</h2>
            </div>

            <div class="kt-card-content p-4 sm:p-5">
                @if ($preRegistration->isPending())
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex min-w-0 items-start gap-3">
                            <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <i class="ki-filled ki-information-2 text-xl"></i>
                            </span>
                            <div class="min-w-0">
                                <h3 class="text-sm font-bold text-mono sm:text-base">این درخواست در انتظار بررسی است</h3>
                                <p class="mt-1.5 text-xs leading-6 text-secondary-foreground">
                                    پس از تصمیم‌گیری، وضعیت درخواست نهایی می‌شود و امکان تغییر آن وجود ندارد.
                                    پیامک اطلاع‌رسانی فقط به‌صورت آزمایشی شبیه‌سازی می‌شود و ارسال واقعی انجام نخواهد شد.
                                </p>
                            </div>
                        </div>

                        <div class="flex shrink-0 flex-col gap-2 sm:flex-row">
                            <form method="POST"
                                  action="{{ route('admin.pre-registrations.approve', $preRegistration) }}"
                                  data-confirm="آیا از تأیید این درخواست پیش‌ثبت‌نام مطمئن هستید؟"
                                  data-decision>
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="kt-btn kt-btn-primary w-full justify-center sm:w-auto">
                                    <i class="ki-filled ki-check-circle"></i>
                                    تأیید درخواست
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('admin.pre-registrations.reject', $preRegistration) }}"
                                  data-confirm="آیا از رد این درخواست پیش‌ثبت‌نام مطمئن هستید؟"
                                  data-decision>
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="kt-btn kt-btn-outline w-full justify-center text-destructive sm:w-auto">
                                    <i class="ki-filled ki-cross-circle"></i>
                                    رد درخواست
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex min-w-0 items-start gap-3">
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <i class="ki-filled {{ $preRegistration->isApproved() ? 'ki-check-circle' : 'ki-cross-circle' }} text-xl"></i>
                        </span>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-mono sm:text-base">
                                {{ $preRegistration->isApproved() ? 'این درخواست تأیید شده است.' : 'این درخواست رد شده است.' }}
                            </h3>
                            <p class="mt-1.5 text-xs leading-6 text-secondary-foreground">
                                تصمیم ثبت‌شده نهایی است و امکان بازبینی مجدد از این صفحه وجود ندارد.
                                رکورد برای بررسی‌های اداری در سامانه باقی می‌ماند.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        {{-- ================= SIMULATED SMS PREVIEW ================= --}}
        <section class="kt-card mt-5 min-w-0">
            <div class="kt-card-header flex-wrap gap-2">
                <div class="min-w-0">
                    <h2 class="kt-card-title">پیش‌نمایش پیامک اطلاع‌رسانی</h2>
                    <p class="mt-1 text-xs text-secondary-foreground">فقط برای نمایش — هیچ پیامکی ارسال نمی‌شود</p>
                </div>

                <span class="kt-badge kt-badge-warning kt-badge-light shrink-0">
                    <i class="ki-filled ki-sms"></i>
                    شبیه‌سازی
                </span>
            </div>

            <div class="kt-card-content p-4 sm:p-5">
                <div class="rounded-xl border border-border bg-muted/30 p-4">
                    <div class="flex items-center justify-between gap-3 border-b border-border pb-3">
                        <span class="text-xs text-secondary-foreground">گیرنده</span>
                        <span class="text-sm font-semibold text-mono" dir="ltr">{{ $preRegistration->phone_number }}</span>
                    </div>

                    <p class="mt-3 text-sm leading-7 text-mono">{{ $smsPreview }}</p>
                </div>

                <p class="mt-3 text-xs leading-6 text-secondary-foreground">
                    <i class="ki-filled ki-information-2"></i>
                    این متن صرفاً پیش‌نمایش است؛ اتصال به درگاه پیامک انجام نشده و پیام واقعی ارسال نمی‌شود.
                </p>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            // Prevent double submission while keeping the confirm dialog intact.
            document.querySelectorAll('form[data-decision]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    // A cancelled confirmation calls preventDefault first.
                    if (event.defaultPrevented) return;

                    const button = form.querySelector('button[type="submit"]');
                    if (button) {
                        button.disabled = true;
                        button.classList.add('opacity-60', 'pointer-events-none');
                    }
                });
            });
        })();
    </script>
@endpush
