```

@extends('admin.layouts.app')

@section('title', 'داشبورد مدیریت')

@section('content')
    @php
        $fullName = trim(
            ($user->first_name ?? '') . ' ' .
            ($user->last_name ?? '')
        );

        $roleName = \App\Enums\RankEnum::tryFrom($user->rank)?->label() ?? 'کاربر';

        $stats = $isTeacher
            ? [
                [
                    'title' => 'کلاس‌های من',
                    'value' => $totalClasses,
                    'icon' => 'ki-people',
                    'color' => 'primary',
                    'description' => 'کلاس‌های اختصاص‌یافته',
                ],
                [
                    'title' => 'مجموع غیبت‌ها',
                    'value' => $totalAbsences,
                    'icon' => 'ki-calendar',
                    'color' => 'primary',
                    'description' => 'در کلاس‌های من',
                ],
                [
                    'title' => 'غیبت موجه',
                    'value' => $excusedAbsences,
                    'icon' => 'ki-check-circle',
                    'color' => 'success',
                    'description' => 'سوابق موجه',
                ],
                [
                    'title' => 'غیبت غیرموجه',
                    'value' => $unexcusedAbsences,
                    'icon' => 'ki-information-2',
                    'color' => 'danger',
                    'description' => 'سوابق غیرموجه',
                ],
            ]
            : [
                [
                    'title' => 'دانش‌آموزان',
                    'value' => $totalStudents,
                    'icon' => 'ki-users',
                    'color' => 'primary',
                    'description' => 'کل دانش‌آموزان ثبت‌شده',
                ],
                [
                    'title' => 'معلمان',
                    'value' => $totalTeachers,
                    'icon' => 'ki-teacher',
                    'color' => 'primary',
                    'description' => 'کل معلمان هنرستان',
                ],
                [
                    'title' => 'کلاس‌ها',
                    'value' => $totalClasses,
                    'icon' => 'ki-people',
                    'color' => 'primary',
                    'description' => 'کلاس‌های ثبت‌شده',
                ],
                [
                    'title' => 'مجموع غیبت‌ها',
                    'value' => $totalAbsences,
                    'icon' => 'ki-calendar',
                    'color' => 'danger',
                    'description' => 'کل سوابق غیبت',
                ],
            ];

        $maxChartValue = max(1, ...$chartData);
    @endphp

    <div class="pb-5">
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">

            <div class="flex flex-col gap-1">
                <h1 class="font-semibold text-xl text-mono">
                    داشبورد مدیریت
                </h1>

                <p class="text-sm text-secondary-foreground">
                    نمای کلی اطلاعات و فعالیت‌های هنرستان
                </p>
            </div>

            <span class="kt-badge kt-badge-outline">
            {{ $roleName }}
        </span>

        </div>
    </div>

    <div class="kt-container-fixed">

        {{-- Welcome --}}
        <section class="kt-card mb-6 overflow-hidden">
            <div class="kt-card-content p-6 lg:p-8">

                <div class="flex flex-col lg:flex-row lg:items-center
                        lg:justify-between gap-6">

                    <div class="max-w-2xl">

                        <div class="mb-3 flex items-center gap-2">
                            <span class="size-2 rounded-full bg-primary"></span>

                            <span class="text-xs font-semibold text-primary">
                            هنرستان علی بن حمزه
                        </span>
                        </div>

                        <h2 class="text-xl lg:text-2xl font-bold text-mono leading-relaxed">
                            خوش آمدید، {{ $fullName ?: 'کاربر' }}
                        </h2>

                        <p class="mt-3 text-sm leading-8 text-secondary-foreground">
                            @if($isTeacher)
                                از این بخش می‌توانید وضعیت کلاس‌های خود
                                و سوابق حضور و غیاب دانش‌آموزان را دنبال کنید.
                            @else
                                اطلاعات آموزشی هنرستان، آمار دانش‌آموزان،
                                معلمان و وضعیت حضور و غیاب را
                                در یک نگاه مشاهده کنید.
                            @endif
                        </p>
                    </div>

                    <div class="shrink-0">
                        <div class="flex items-center gap-3 rounded-xl
                                bg-muted/50 border border-border px-5 py-4">

                            <div class="flex size-11 items-center justify-center
                                    rounded-lg bg-primary/10 text-primary">
                                <i class="ki-filled ki-calendar text-xl"></i>
                            </div>

                            <div>
                                <p class="text-xs text-secondary-foreground">
                                    تاریخ امروز
                                </p>

                                <p class="mt-1 text-sm font-semibold text-mono">
                                    {{ \App\Helpers\Jalali::format(now()) }}                                </p>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- Statistics --}}
        <section class="mb-7">

            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-mono">
                        آمار کلی
                    </h2>
                    <p class="mt-1 text-xs text-secondary-foreground">
                        خلاصه اطلاعات ثبت‌شده در سامانه
                    </p>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-5">

                @foreach($stats as $stat)
                    <div class="kt-card">

                        <div class="kt-card-content p-5">

                            <div class="flex items-center justify-between mb-5">

                                <div class="flex size-11 items-center justify-center
                                        rounded-xl bg-muted/60">

                                    <i class="ki-filled {{ $stat['icon'] }}
                                          text-xl text-{{ $stat['color'] }}"></i>
                                </div>

                                <span class="text-xs text-secondary-foreground">
                                ثبت‌شده
                            </span>

                            </div>

                            <div class="text-3xl font-bold text-mono">
                                {{ number_format($stat['value']) }}
                            </div>

                            <h3 class="mt-2 text-sm font-semibold text-foreground">
                                {{ $stat['title'] }}
                            </h3>

                            <p class="mt-1 text-xs text-secondary-foreground">
                                {{ $stat['description'] }}
                            </p>

                        </div>

                    </div>
                @endforeach

            </div>
        </section>

        {{-- ================= ANALYTICS ================= --}}
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2 mb-6 items-stretch">

            {{-- Weekly trend --}}
            <section class="kt-card min-w-0 h-full">
                <div class="kt-card-header flex-wrap gap-2">
                    <div class="flex flex-col gap-1">
                        <h3 class="kt-card-title">روند غیبت‌های ۷ روز اخیر</h3>
                        <p class="text-xs text-secondary-foreground">تعداد رکوردهای ثبت‌شده در هر روز</p>
                    </div>
                    <span class="kt-badge kt-badge-outline">هفتگی</span>
                </div>
                <div class="kt-card-content p-4 sm:p-6">
                    @php
                        $chartValues = collect($chartData ?? [])->map(fn ($value) => (int) $value);
                        $maxChartValue = max(1, (int) $chartValues->max());
                        $weekTotal = $chartValues->sum();
                    @endphp

                    <div class="mb-5 flex items-end justify-between gap-3">
                        <div>
                            <p class="text-xs text-secondary-foreground">مجموع غیبت‌های این هفته</p>
                            <div class="mt-1 text-2xl font-bold text-mono">{{ number_format($weekTotal) }}</div>
                        </div>
                        <span class="text-xs text-secondary-foreground">بر اساس تاریخ غیبت</span>
                    </div>

                    <div class="grid grid-cols-7 gap-2 sm:gap-3 h-52 items-end" dir="ltr">
                        @foreach(($chartData ?? []) as $i => $value)
                            @php
                                $count = (int) $value;
                                $barHeight = $count > 0 ? max(10, (int) round($count / $maxChartValue * 100)) : 0;
                            @endphp
                            <div class="min-w-0 h-full flex flex-col items-center justify-end gap-2" dir="rtl">
                                <span class="text-[11px] font-semibold text-mono tabular-nums">{{ $count }}</span>
                                <div class="w-full flex-1 flex items-end justify-center rounded-lg bg-muted/30 overflow-hidden">
                                    <div class="w-full max-w-9 rounded-t-md bg-primary/80"
                                         style="height: {{ $barHeight }}%"
                                         title="{{ $count }} غیبت"></div>
                                </div>
                                <span class="text-[10px] sm:text-xs text-secondary-foreground whitespace-nowrap tabular-nums">{{ $chartLabels[$i] ?? '—' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Absence breakdown --}}
            <section class="kt-card min-w-0 h-full">
                <div class="kt-card-header flex-wrap gap-2">
                    <div class="flex flex-col gap-1">
                        <h3 class="kt-card-title">وضعیت غیبت‌ها</h3>
                        <p class="text-xs text-secondary-foreground">تفکیک غیبت‌های موجه و غیرموجه</p>
                    </div>
                    <div class="flex size-9 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <i class="ki-filled ki-chart-pie-3 text-lg"></i>
                    </div>
                </div>
                <div class="kt-card-content p-4 sm:p-6">
                    @php
                        $excusedPercent = $totalAbsences > 0 ? round($excusedAbsences / $totalAbsences * 100) : 0;
                        $unexcusedPercent = $totalAbsences > 0 ? round($unexcusedAbsences / $totalAbsences * 100) : 0;
                    @endphp
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <p class="text-xs text-secondary-foreground">کل رکوردهای غیبت</p>
                            <p class="text-3xl font-bold text-mono mt-2">{{ number_format($totalAbsences) }}</p>
                        </div>
                        <div class="rounded-xl bg-muted/40 p-3 text-center">
                            <p class="text-xs text-secondary-foreground">سهم غیرموجه</p>
                            <p class="mt-1 text-xl font-bold text-danger">{{ $unexcusedPercent }}٪</p>
                        </div>
                    </div>

                    <div class="flex h-3 w-full overflow-hidden rounded-full bg-muted mb-6" dir="ltr">
                        <div class="bg-success" style="width: {{ $excusedPercent }}%"></div>
                        <div class="bg-danger" style="width: {{ $unexcusedPercent }}%"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl border border-border p-4 min-w-0">
                            <div class="flex items-center gap-2 text-secondary-foreground text-xs sm:text-sm">
                                <span class="size-2.5 shrink-0 rounded-full bg-success"></span>
                                غیبت موجه
                            </div>
                            <p class="mt-3 text-xl sm:text-2xl font-bold text-mono">{{ number_format($excusedAbsences) }}</p>
                            <p class="mt-1 text-xs text-success">{{ $excusedPercent }}٪ از کل</p>
                        </div>
                        <div class="rounded-xl border border-border p-4 min-w-0">
                            <div class="flex items-center gap-2 text-secondary-foreground text-xs sm:text-sm">
                                <span class="size-2.5 shrink-0 rounded-full bg-danger"></span>
                                غیبت غیرموجه
                            </div>
                            <p class="mt-3 text-xl sm:text-2xl font-bold text-mono">{{ number_format($unexcusedAbsences) }}</p>
                            <p class="mt-1 text-xs text-danger">{{ $unexcusedPercent }}٪ از کل</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- ================= RECENT + QUICK ACCESS ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-7 items-stretch">
            <section class="kt-card min-w-0 h-full">
                <div class="kt-card-header flex-wrap gap-2">
                    <div class="flex flex-col gap-1">
                        <h3 class="kt-card-title">آخرین غیبت‌های ثبت‌شده</h3>
                        <p class="text-xs text-secondary-foreground">جدیدترین سوابق کلاس‌ها</p>
                    </div>
                    <span class="kt-badge kt-badge-outline">{{ $recentAbsences->count() }} رکورد</span>
                </div>
                <div class="kt-card-content px-4 sm:px-5 py-1">
                    @forelse($recentAbsences as $absence)
                        <div class="flex min-w-0 flex-wrap sm:flex-nowrap items-center justify-between gap-3 py-3.5 border-b border-border last:border-0">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-muted/50 text-primary">
                                    <i class="ki-filled ki-user text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="truncate text-sm font-semibold text-mono">
                                        {{ trim(($absence->student?->first_name ?? '') . ' ' . ($absence->student?->last_name ?? '')) ?: 'دانش‌آموز نامشخص' }}
                                    </div>
                                    <div class="text-xs text-secondary-foreground mt-1">
                                        {{ \App\Helpers\Jalali::format($absence->date) }}
                                    </div>
                                </div>
                            </div>
                            <span class="kt-badge {{ $absence->is_excused ? 'kt-badge-success' : 'kt-badge-danger' }} kt-badge-light shrink-0">
                                {{ $absence->is_excused ? 'موجه' : 'غیرموجه' }}
                            </span>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <i class="ki-filled ki-calendar text-3xl text-secondary-foreground"></i>
                            <p class="mt-3 text-sm text-secondary-foreground">هنوز غیبتی ثبت نشده است.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="kt-card min-w-0 h-full">
                <div class="kt-card-header">
                    <div class="flex flex-col gap-1">
                        <h3 class="kt-card-title">دسترسی سریع</h3>
                        <p class="text-xs text-secondary-foreground">میانبرهای مرتبط با نقش شما</p>
                    </div>
                </div>
                <div class="kt-card-content p-4 sm:p-5">
                    @if($isTeacher)
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="rounded-xl border border-border bg-muted/30 p-3 sm:p-4">
                                <div class="text-xs text-secondary-foreground">کلاس‌های من</div>
                                <div class="text-2xl font-bold text-mono mt-2">{{ number_format($totalClasses) }}</div>
                            </div>
                            <div class="rounded-xl border border-border bg-muted/30 p-3 sm:p-4">
                                <div class="text-xs text-secondary-foreground">غیبت غیرموجه</div>
                                <div class="text-2xl font-bold text-danger mt-2">{{ number_format($unexcusedAbsences) }}</div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            @forelse($myClasses as $class)
                                <a href="{{ url('/admin/my-classes/' . $class->id) }}"
                                   class="flex min-w-0 items-center justify-between gap-3 rounded-xl border border-border px-4 py-3 hover:border-primary/30 hover:bg-muted/40 transition-colors">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <i class="ki-filled ki-book text-primary text-lg"></i>
                                        <span class="truncate text-sm font-medium text-mono">{{ $class->title ?? 'کلاس ' . $class->id }}</span>
                                    </div>
                                    <i class="ki-filled ki-left text-secondary-foreground shrink-0"></i>
                                </a>
                            @empty
                                <p class="text-sm text-secondary-foreground py-5 text-center">کلاسی به شما اختصاص داده نشده است.</p>
                            @endforelse
                        </div>
                        @if(Route::has('admin.my-classes.index'))
                            <a href="{{ route('admin.my-classes.index') }}" class="kt-btn kt-btn-outline w-full justify-center mt-4">مشاهده همه کلاس‌های من</a>
                        @endif
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @can('viewAny', \App\Models\Student::class)
                                <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 rounded-xl border border-border p-4 hover:bg-muted/40 hover:border-primary/30 transition-colors">
                                    <i class="ki-filled ki-users text-primary text-xl"></i><span class="text-sm font-medium text-mono">مدیریت دانش‌آموزان</span>
                                </a>
                            @endcan
                            @can('viewAny', \App\Models\Teacher::class)
                                <a href="{{ route('admin.teachers.index') }}" class="flex items-center gap-3 rounded-xl border border-border p-4 hover:bg-muted/40 hover:border-primary/30 transition-colors">
                                    <i class="ki-filled ki-teacher text-primary text-xl"></i><span class="text-sm font-medium text-mono">مدیریت معلمان</span>
                                </a>
                            @endcan
                            @can('viewAny', \App\Models\SchoolClass::class)
                                <a href="{{ route('admin.classes.index') }}" class="flex items-center gap-3 rounded-xl border border-border p-4 hover:bg-muted/40 hover:border-primary/30 transition-colors">
                                    <i class="ki-filled ki-people text-primary text-xl"></i><span class="text-sm font-medium text-mono">مدیریت کلاس‌ها</span>
                                </a>
                            @endcan
                            @if(in_array($user->rank, [\App\Enums\RankEnum::MANAGER->value, \App\Enums\RankEnum::DEPUTY->value], true) && Route::has('admin.absences.index'))
                                <a href="{{ route('admin.absences.index') }}" class="flex items-center gap-3 rounded-xl border border-border p-4 hover:bg-muted/40 hover:border-primary/30 transition-colors">
                                    <i class="ki-filled ki-calendar text-primary text-xl"></i><span class="text-sm font-medium text-mono">مدیریت غیبت‌ها</span>
                                </a>
                            @endif
                        </div>
                        <div class="mt-5 rounded-xl bg-muted/40 border border-border p-4">
                            <div class="text-xs text-secondary-foreground">غیبت‌های غیرموجه ثبت‌شده</div>
                            <div class="text-2xl font-bold text-danger mt-1">{{ number_format($unexcusedAbsences) }}</div>
                            <p class="text-xs text-secondary-foreground mt-2">برای بررسی و رسیدگی به وضعیت دانش‌آموزان</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection
