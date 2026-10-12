@extends('admin.layouts.app')

@section('title', 'پیش‌ثبت‌نام‌ها')

@php
    // Status badge colour per application state.
    $statusBadge = function ($preRegistration): string {
        return match ($preRegistration->status()) {
            'approved' => 'kt-badge-success',
            'rejected' => 'kt-badge-destructive',
            default => 'kt-badge-warning',
        };
    };

    $hasFilters = ($search ?? '') !== ''
        || ($statusFilter ?? '') !== ''
        || ($gradeFilter ?? '') !== ''
        || ($sort ?? 'newest') !== 'newest';
@endphp

@section('content')
    <div class="kt-container-fixed pb-10" dir="rtl" style="font-feature-settings: 'ss01';">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="mb-5 flex flex-wrap items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0">
                <div class="mb-1.5 flex items-center gap-2 text-xs text-secondary-foreground">
                    <i class="ki-filled ki-notepad-edit"></i>
                    <span>مدیریت هنرستان</span><span>/</span><span>پیش‌ثبت‌نام‌ها</span>
                </div>

                <h1 class="text-xl font-bold text-mono sm:text-2xl">پیش‌ثبت‌نام‌ها</h1>

                <p class="mt-1.5 text-xs leading-6 text-secondary-foreground sm:text-sm">
                    بررسی، تأیید و رد درخواست‌های پیش‌ثبت‌نام دانش‌آموزان
                </p>
            </div>

            <span class="hidden shrink-0 kt-badge kt-badge-outline sm:inline-flex">مدیر و معاون</span>
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

        {{-- ================= SUMMARY CARDS ================= --}}
        <div class="mb-4 grid grid-cols-2 gap-2.5 sm:gap-4 xl:grid-cols-4">
            <div class="kt-card min-w-0">
                <div class="kt-card-content p-3.5 sm:p-5">
                    <div class="flex items-center gap-3">
                        <span class="hidden size-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary sm:flex">
                            <i class="ki-filled ki-notepad-edit text-lg"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] font-medium text-secondary-foreground sm:text-xs">کل درخواست‌ها</p>
                            <p class="mt-1 text-xl font-bold text-mono sm:text-3xl">{{ number_format($stats['total']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-card min-w-0">
                <div class="kt-card-content p-3.5 sm:p-5">
                    <div class="flex items-center gap-3">
                        <span class="hidden size-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary sm:flex">
                            <i class="ki-filled ki-time text-lg"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] font-medium text-secondary-foreground sm:text-xs">در انتظار بررسی</p>
                            <p class="mt-1 text-xl font-bold text-mono sm:text-3xl">{{ number_format($stats['pending']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-card min-w-0">
                <div class="kt-card-content p-3.5 sm:p-5">
                    <div class="flex items-center gap-3">
                        <span class="hidden size-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary sm:flex">
                            <i class="ki-filled ki-check-circle text-lg"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] font-medium text-secondary-foreground sm:text-xs">تأییدشده</p>
                            <p class="mt-1 text-xl font-bold text-mono sm:text-3xl">{{ number_format($stats['approved']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-card min-w-0">
                <div class="kt-card-content p-3.5 sm:p-5">
                    <div class="flex items-center gap-3">
                        <span class="hidden size-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary sm:flex">
                            <i class="ki-filled ki-cross-circle text-lg"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] font-medium text-secondary-foreground sm:text-xs">ردشده</p>
                            <p class="mt-1 text-xl font-bold text-mono sm:text-3xl">{{ number_format($stats['rejected']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= FILTERS ================= --}}
        <section class="kt-card mb-4">
            <div class="kt-card-content p-3.5 sm:p-5">
                <div class="mb-3 flex items-center gap-2 sm:mb-4">
                    <i class="ki-filled ki-filter text-primary"></i>
                    <h2 class="text-sm font-bold text-mono">جستجو و فیلتر</h2>
                </div>

                <form action="{{ route('admin.pre-registrations.index') }}" method="GET"
                      class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 lg:items-end">

                    <div class="min-w-0 lg:col-span-2">
                        <label for="pre-registration-search" class="mb-1.5 block text-xs font-medium text-mono">
                            نام دانش‌آموز، کد ملی یا شماره همراه
                        </label>
                        <label class="kt-input w-full">
                            <i class="ki-filled ki-magnifier"></i>
                            <input id="pre-registration-search" name="search" type="search"
                                   placeholder="جستجو در نام، کد ملی یا شماره همراه..."
                                   value="{{ $search ?? '' }}">
                        </label>
                    </div>

                    <div class="min-w-0">
                        <label for="pre-registration-status" class="mb-1.5 block text-xs font-medium text-mono">وضعیت</label>
                        <select id="pre-registration-status" name="status" class="kt-select w-full">
                            <option value="">همه وضعیت‌ها</option>
                            <option value="pending" @selected(($statusFilter ?? '') === 'pending')>در انتظار بررسی</option>
                            <option value="approved" @selected(($statusFilter ?? '') === 'approved')>تأییدشده</option>
                            <option value="rejected" @selected(($statusFilter ?? '') === 'rejected')>ردشده</option>
                        </select>
                    </div>

                    <div class="min-w-0">
                        <label for="pre-registration-grade" class="mb-1.5 block text-xs font-medium text-mono">پایه تحصیلی</label>
                        <select id="pre-registration-grade" name="grade" class="kt-select w-full">
                            <option value="">همه پایه‌ها</option>
                            <option value="10" @selected(($gradeFilter ?? '') === '10')>پایه دهم</option>
                            <option value="11" @selected(($gradeFilter ?? '') === '11')>پایه یازدهم</option>
                            <option value="12" @selected(($gradeFilter ?? '') === '12')>پایه دوازدهم</option>
                        </select>
                    </div>

                    <div class="min-w-0">
                        <label for="pre-registration-sort" class="mb-1.5 block text-xs font-medium text-mono">ترتیب</label>
                        <select id="pre-registration-sort" name="sort" class="kt-select w-full">
                            <option value="newest" @selected(($sort ?? 'newest') === 'newest')>جدیدترین</option>
                            <option value="oldest" @selected(($sort ?? 'newest') === 'oldest')>قدیمی‌ترین</option>
                        </select>
                    </div>

                    <div class="min-w-0">
                        <label for="pre-registration-per-page" class="mb-1.5 block text-xs font-medium text-mono">تعداد در هر صفحه</label>
                        <select id="pre-registration-per-page" name="per_page" class="kt-select w-full">
                            @foreach ([10, 15, 25, 50] as $size)
                                <option value="{{ $size }}" @selected((int) ($perPage ?? 15) === $size)>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2 lg:col-span-2">
                        <button type="submit" class="kt-btn kt-btn-primary w-full justify-center">
                            <i class="ki-filled ki-magnifier"></i>
                            اعمال فیلتر
                        </button>

                        <a href="{{ route('admin.pre-registrations.index') }}"
                           class="kt-btn kt-btn-outline w-full justify-center {{ $hasFilters ? '' : 'pointer-events-none opacity-50' }}">
                            حذف فیلترها
                        </a>
                    </div>

                </form>
            </div>
        </section>

        {{-- ================= RESULTS ================= --}}
        <section class="kt-card mb-4 overflow-hidden">

            <div class="kt-card-header flex-wrap gap-2 py-3.5 sm:py-5">
                <div class="min-w-0">
                    <h2 class="kt-card-title">فهرست درخواست‌ها</h2>
                    <p class="mt-1 text-xs text-secondary-foreground">درخواست‌های پیش‌ثبت‌نام ثبت‌شده در سامانه</p>
                </div>

                <span class="kt-badge kt-badge-outline shrink-0">{{ number_format($preRegistrations->total()) }} مورد</span>
            </div>

            {{-- Mobile: stacked cards --}}
            <div class="space-y-3 p-3 sm:p-4 lg:hidden">
                @forelse ($preRegistrations as $preRegistration)
                    <article class="rounded-xl border border-border bg-background p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                    <i class="ki-filled ki-user text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="truncate text-sm font-bold text-mono">{{ $preRegistration->student_full_name }}</h3>
                                    <p class="mt-1 text-[11px] text-secondary-foreground">
                                        کد ملی: <span dir="ltr" class="inline-block">{{ $preRegistration->national_code }}</span>
                                    </p>
                                </div>
                            </div>

                            <span class="shrink-0 kt-badge {{ $statusBadge($preRegistration) }} kt-badge-light">
                                {{ $preRegistration->statusLabel() }}
                            </span>
                        </div>

                        <div class="my-3 grid grid-cols-2 gap-2 rounded-lg bg-muted/40 p-3">
                            <div>
                                <p class="text-[11px] text-secondary-foreground">پایه تحصیلی</p>
                                <p class="mt-1 text-sm font-semibold text-mono">{{ $preRegistration->gradeLabel() }}</p>
                            </div>
                            <div class="border-r border-border pr-3">
                                <p class="text-[11px] text-secondary-foreground">شماره همراه</p>
                                <p class="mt-1 text-sm font-semibold text-mono" dir="ltr">{{ $preRegistration->phone_number }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3">
                            <span class="text-[11px] text-secondary-foreground">
                                {{ \App\Helpers\Jalali::format($preRegistration->created_at) }}
                            </span>

                            <a href="{{ route('admin.pre-registrations.show', $preRegistration) }}"
                               class="kt-btn kt-btn-sm kt-btn-outline">
                                <i class="ki-filled ki-eye"></i>
                                مشاهده جزئیات
                            </a>
                        </div>
                    </article>
                @empty
                    @include('admin.pre-registrations.partials.empty', ['colspan' => 1])
                @endforelse
            </div>

            {{-- Desktop: table --}}
            <div class="hidden lg:block">
                <div class="kt-scrollable-x-auto">
                    <table class="kt-table kt-table-border">
                        <thead>
                        <tr>
                            <th class="w-[70px]">شماره ردیف</th>
                            <th class="min-w-[190px]">نام و نام خانوادگی دانش‌آموز</th>
                            <th class="min-w-[120px]">نام پدر</th>
                            <th class="min-w-[120px]">کد ملی</th>
                            <th class="min-w-[110px]">پایه تحصیلی</th>
                            <th class="min-w-[140px]">شماره همراه</th>
                            <th class="min-w-[130px]">تاریخ ثبت درخواست</th>
                            <th class="min-w-[130px]">وضعیت</th>
                            <th class="w-[140px]">عملیات</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse ($preRegistrations as $preRegistration)
                            <tr>
                                <td class="font-medium text-mono">
                                    {{ ($preRegistrations->currentPage() - 1) * $preRegistrations->perPage() + $loop->iteration }}
                                </td>

                                <td>
                                    <span class="font-medium text-sm text-mono">{{ $preRegistration->student_full_name }}</span>
                                </td>

                                <td class="text-secondary-foreground">{{ $preRegistration->father_name }}</td>

                                <td class="text-secondary-foreground" dir="ltr">{{ $preRegistration->national_code }}</td>

                                <td class="text-secondary-foreground">{{ $preRegistration->gradeLabel() }}</td>

                                <td class="text-secondary-foreground" dir="ltr">{{ $preRegistration->phone_number }}</td>

                                <td class="text-secondary-foreground">{{ \App\Helpers\Jalali::format($preRegistration->created_at) }}</td>

                                <td>
                                    <span class="kt-badge {{ $statusBadge($preRegistration) }} kt-badge-light">
                                        {{ $preRegistration->statusLabel() }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('admin.pre-registrations.show', $preRegistration) }}"
                                       class="kt-btn kt-btn-sm kt-btn-outline">
                                        <i class="ki-filled ki-eye"></i>
                                        مشاهده جزئیات
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-0">
                                    @include('admin.pre-registrations.partials.empty', ['colspan' => 9])
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="kt-card-footer flex-col justify-center gap-5 text-sm font-medium text-secondary-foreground md:flex-row md:justify-between">
                <span>{{ number_format($preRegistrations->total()) }} مورد</span>

                <div class="flex items-center gap-4">
                    @include('admin.partials.pagination', ['paginator' => $preRegistrations])
                </div>
            </div>

        </section>
    </div>
@endsection
