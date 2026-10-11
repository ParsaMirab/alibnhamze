@extends('admin.layouts.app')

@section('title', 'مدیریت غیبت‌ها')

@section('content')
    <div class="kt-container-fixed pb-10" dir="rtl">
        {{-- Page header --}}
        <div class="mb-5 flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0">
                <div class="mb-1.5 flex items-center gap-2 text-xs text-secondary-foreground">
                    <i class="ki-filled ki-calendar"></i>
                    <span>مدیریت هنرستان</span><span>/</span><span>غیبت‌ها</span>
                </div>
                <h1 class="text-xl font-bold text-mono sm:text-2xl">مدیریت غیبت‌ها</h1>
                <p class="mt-1.5 text-xs leading-6 text-secondary-foreground sm:text-sm">
                    بررسی و تعیین وضعیت غیبت دانش‌آموزان
                </p>
            </div>
            <span class="hidden shrink-0 kt-badge kt-badge-outline sm:inline-flex">مدیر و معاون</span>
        </div>

        @if(session('success'))
            <div class="kt-alert kt-alert-success mb-4" role="status">
                <i class="ki-filled ki-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="kt-alert kt-alert-destructive mb-4" role="alert">
                <i class="ki-filled ki-information-2"></i>
                <div class="space-y-1">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
            </div>
        @endif

        {{-- Compact statistics: always three across --}}
        <div class="mb-4 grid grid-cols-3 gap-2 sm:gap-4">
            <div class="kt-card min-w-0">
                <div class="kt-card-content px-2.5 py-3 sm:p-5">
                    <div class="flex items-center justify-center gap-2 sm:justify-start">
                        <span class="hidden size-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary sm:flex"><i class="ki-filled ki-calendar text-lg"></i></span>
                        <div class="min-w-0 text-center sm:text-right">
                            <p class="text-[10px] font-medium leading-4 text-secondary-foreground sm:text-xs">کل روزهای غیبت</p>
                            <p class="mt-1 text-xl font-bold leading-tight text-mono sm:text-3xl">{{ number_format($summary->total ?? 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-card min-w-0">
                <div class="kt-card-content px-2.5 py-3 sm:p-5">
                    <div class="flex items-center justify-center gap-2 sm:justify-start">
                        <span class="hidden size-10 shrink-0 items-center justify-center rounded-lg bg-success/10 text-success sm:flex"><i class="ki-filled ki-check-circle text-lg"></i></span>
                        <div class="min-w-0 text-center sm:text-right">
                            <p class="text-[10px] font-medium leading-4 text-secondary-foreground sm:text-xs">موجه</p>
                            <p class="mt-1 text-xl font-bold leading-tight text-success sm:text-3xl">{{ number_format($summary->excused ?? 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-card min-w-0">
                <div class="kt-card-content px-2.5 py-3 sm:p-5">
                    <div class="flex items-center justify-center gap-2 sm:justify-start">
                        <span class="hidden size-10 shrink-0 items-center justify-center rounded-lg bg-danger/10 text-danger sm:flex"><i class="ki-filled ki-information-2 text-lg"></i></span>
                        <div class="min-w-0 text-center sm:text-right">
                            <p class="text-[10px] font-medium leading-4 text-secondary-foreground sm:text-xs">غیرموجه</p>
                            <p class="mt-1 text-xl font-bold leading-tight text-danger sm:text-3xl">{{ number_format($summary->unexcused ?? 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <section class="kt-card mb-4">
            <div class="kt-card-content p-3.5 sm:p-5">
                <div class="mb-3 flex items-center gap-2 sm:mb-4">
                    <i class="ki-filled ki-filter text-primary"></i>
                    <h2 class="text-sm font-bold text-mono">جستجو و فیلتر</h2>
                </div>
                <form action="{{ route('admin.absences.index') }}" method="GET" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 lg:items-end">
                    <div class="min-w-0">
                        <label for="absence-search" class="mb-1.5 block text-xs font-medium text-mono">نام یا کد ملی دانش‌آموز</label>
                        <input id="absence-search" name="search" type="search" class="kt-input w-full" placeholder="جستجوی دانش‌آموز..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="min-w-0">
                        <label for="absence-date" class="mb-1.5 block text-xs font-medium text-mono">تاریخ غیبت</label>
                        {{-- The controller currently expects YYYY-MM-DD (Gregorian). Keep this name/value in sync if using the project's Jalali picker. --}}
                        <input id="absence-date" name="date" type="date" class="kt-input w-full" value="{{ $filters['date'] ?? '' }}">
                    </div>
                    <div class="min-w-0">
                        <label for="absence-status" class="mb-1.5 block text-xs font-medium text-mono">وضعیت</label>
                        <select id="absence-status" name="status" class="kt-select w-full">
                            <option value="">همه غیبت‌ها</option>
                            <option value="excused" @selected(($filters['status'] ?? '') === 'excused')>موجه</option>
                            <option value="unexcused" @selected(($filters['status'] ?? '') === 'unexcused')>غیرموجه</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="submit" class="kt-btn kt-btn-primary w-full justify-center"><i class="ki-filled ki-magnifier"></i>اعمال فیلتر</button>
                        <a href="{{ route('admin.absences.index') }}" class="kt-btn kt-btn-outline w-full justify-center">حذف فیلتر</a>
                    </div>
                </form>
            </div>
        </section>

        {{-- Results --}}
        <section class="kt-card mb-4 overflow-hidden">
            <div class="kt-card-header flex-wrap gap-2 py-3.5 sm:py-5">
                <div class="min-w-0">
                    <h2 class="kt-card-title">فهرست غیبت‌ها</h2>
                    <p class="mt-1 text-xs text-secondary-foreground">هر دانش‌آموز در هر روز فقط یک ردیف دارد</p>
                </div>
                <span class="kt-badge kt-badge-outline shrink-0">{{ number_format($absences->total()) }} مورد</span>
            </div>

            {{-- Mobile: comfortable stacked cards --}}
            <div class="space-y-3 p-3 sm:p-4 lg:hidden">
                @forelse($absences as $absence)
                    @php
                        $isExcused = (int) $absence->excused_count === (int) $absence->absence_count;
                    @endphp
                    <article class="rounded-xl border border-border bg-background p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary"><i class="ki-filled ki-user text-lg"></i></div>
                                <div class="min-w-0">
                                    <h3 class="truncate text-sm font-bold text-mono">{{ $absence->first_name }} {{ $absence->last_name }}</h3>
                                    <p class="mt-1 text-[11px] text-secondary-foreground">کد ملی: <span dir="ltr" class="inline-block">{{ $absence->national_code }}</span></p>
                                </div>
                            </div>
                            <span class="shrink-0 kt-badge {{ $isExcused ? 'kt-badge-success kt-badge-light' : 'kt-badge-danger kt-badge-light' }}">{{ $isExcused ? 'موجه' : 'غیرموجه' }}</span>
                        </div>
                        <div class="my-3 grid grid-cols-2 gap-2 rounded-lg bg-muted/40 p-3">
                            <div>
                                <p class="text-[11px] text-secondary-foreground">تاریخ غیبت</p>
                                <p class="mt-1 text-sm font-semibold text-mono">{{ \App\Helpers\Jalali::format($absence->date) }}</p>
                            </div>
                            <div class="border-r border-border pr-3">
                                <p class="text-[11px] text-secondary-foreground">تعداد زنگ غیبت</p>
                                <p class="mt-1 text-sm font-semibold text-mono">{{ $absence->absence_count }} زنگ</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.absences.status') }}" onsubmit="return confirm('وضعیت تمام غیبت‌های این دانش‌آموز در این روز تغییر کند؟')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="student_id" value="{{ $absence->student_id }}">
                            <input type="hidden" name="date" value="{{ $absence->date }}">
                            <input type="hidden" name="is_excused" value="{{ $isExcused ? 0 : 1 }}">
                            <button type="submit" class="kt-btn w-full justify-center {{ $isExcused ? 'kt-btn-outline' : 'kt-btn-primary' }}">
                                <i class="ki-filled {{ $isExcused ? 'ki-cross-circle' : 'ki-check-circle' }}"></i>
                                {{ $isExcused ? 'تغییر وضعیت به غیرموجه' : 'تأیید غیبت به‌عنوان موجه' }}
                            </button>
                        </form>
                    </article>
                @empty
                    <div class="py-12 text-center">
                        <i class="ki-filled ki-calendar text-3xl text-secondary-foreground"></i>
                        <p class="mt-3 text-sm font-semibold text-mono">غیبتی پیدا نشد</p>
                        <p class="mt-1 text-xs text-secondary-foreground">فیلترها را تغییر دهید و دوباره بررسی کنید.</p>
                    </div>
                @endforelse
            </div>

            {{-- Desktop: dense table --}}
            <div class="hidden lg:block">
                <div class="kt-scrollable-x-auto">
                    <table class="kt-table kt-table-border">
                        <thead><tr><th>دانش‌آموز</th><th>تاریخ</th><th>زنگ‌های غیبت</th><th>وضعیت</th><th>عملیات</th></tr></thead>
                        <tbody>
                        @forelse($absences as $absence)
                            @php $isExcused = (int) $absence->excused_count === (int) $absence->absence_count; @endphp
                            <tr>
                                <td><div class="font-semibold text-mono">{{ $absence->first_name }} {{ $absence->last_name }}</div><div class="mt-1 text-xs text-secondary-foreground">{{ $absence->national_code }}</div></td>
                                <td class="font-medium text-mono">{{ \App\Helpers\Jalali::format($absence->date) }}</td>
                                <td><span class="kt-badge kt-badge-outline">{{ $absence->absence_count }} زنگ</span></td>
                                <td><span class="kt-badge {{ $isExcused ? 'kt-badge-success kt-badge-light' : 'kt-badge-danger kt-badge-light' }}">{{ $isExcused ? 'موجه' : 'غیرموجه' }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.absences.status') }}" onsubmit="return confirm('وضعیت تمام غیبت‌های این دانش‌آموز در این روز تغییر کند؟')">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="student_id" value="{{ $absence->student_id }}">
                                        <input type="hidden" name="date" value="{{ $absence->date }}">
                                        <input type="hidden" name="is_excused" value="{{ $isExcused ? 0 : 1 }}">
                                        <button type="submit" class="kt-btn kt-btn-sm {{ $isExcused ? 'kt-btn-outline' : 'kt-btn-primary' }}">{{ $isExcused ? 'تبدیل به غیرموجه' : 'تبدیل به موجه' }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-12 text-center text-secondary-foreground">غیبتی پیدا نشد.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($absences->hasPages())
                <div class="border-t border-border px-3 py-4 sm:px-5">{{ $absences->links() }}</div>
            @endif
        </section>

        {{-- Warning: simulation only --}}

        <section class="kt-card min-w-0 overflow-hidden">
            <div class="kt-card-content min-w-0 p-4 sm:p-6">

                <div class="grid grid-cols-1 gap-5 xl:grid-cols-2 xl:items-center">

                    {{-- Information --}}
                    <div class="flex min-w-0 items-start gap-3">

                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                    <i class="ki-filled ki-sms text-xl"></i>
                </span>

                        <div class="min-w-0">
                            <h2 class="text-sm font-bold text-mono sm:text-base">
                                اخطار غیبت به والدین
                            </h2>

                            <p class="mt-1.5 text-xs leading-6 text-secondary-foreground">
                                نمایش نتیجه آزمایشی برای والدین دانش‌آموزانی
                                که در تاریخ انتخابی غیبت غیرموجه دارند؛
                                پیامکی ارسال نمی‌شود.
                            </p>
                        </div>

                    </div>

                    {{-- Form --}}
                    <form
                        method="POST"
                        action="{{ route('admin.absences.send-warning') }}"
                        onsubmit="return confirm('اخطار آزمایشی غیبت اجرا شود؟')"
                        class="grid min-w-0 grid-cols-1 gap-3 sm:grid-cols-2 xl:justify-self-end xl:w-full xl:max-w-[430px]"
                    >
                        @csrf

                        <input
                            type="date"
                            name="date"
                            required
                            value="{{ $filters['date'] ?? now()->toDateString() }}"
                            class="kt-input block w-full min-w-0 max-w-full"
                        >

                        <button
                            type="submit"
                            class="kt-btn kt-btn-primary flex w-full min-w-0 items-center justify-center gap-2"
                        >
                            <i class="ki-filled ki-sms shrink-0"></i>
                            <span class="text-xs sm:text-sm">
                        اجرای آزمایشی اخطار
                    </span>
                        </button>

                    </form>

                </div>

            </div>
        </section>

    </div>
@endsection
