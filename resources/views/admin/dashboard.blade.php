
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

        {{-- Analytics --}}
        <div class="grid xl:grid-cols-3 gap-5 mb-7">
            {{-- Absence Breakdown --}}
            <section class="kt-card">

                <div class="kt-card-header">
                    <h3 class="kt-card-title">
                        وضعیت غیبت‌ها
                    </h3>
                </div>

                <div class="kt-card-content p-6">

                    <div class="mb-6 text-center">

                        <p class="text-xs text-secondary-foreground">
                            مجموع غیبت‌های ثبت‌شده
                        </p>

                        <div class="mt-2 text-4xl font-bold text-mono">
                            {{ number_format($totalAbsences) }}
                        </div>

                    </div>

                    @php
                        $excusedPercent = $totalAbsences > 0
                            ? round($excusedAbsences / $totalAbsences * 100)
                            : 0;

                        $unexcusedPercent = $totalAbsences > 0
                            ? round($unexcusedAbsences / $totalAbsences * 100)
                            : 0;
                    @endphp

                    <div class="flex h-3 overflow-hidden rounded-full bg-muted mb-7">

                        <div class="bg-success"
                             style="width: {{ $excusedPercent }}%"></div>

                        <div class="bg-danger"
                             style="width: {{ $unexcusedPercent }}%"></div>

                    </div>

                    <div class="space-y-5">

                        <div class="flex items-center justify-between gap-3">

                            <div class="flex items-center gap-2">
                                <span class="size-2.5 rounded-full bg-success"></span>
                                <span class="text-sm text-secondary-foreground">
                                غیبت موجه
                            </span>
                            </div>

                            <span class="text-sm font-bold text-mono">
                            {{ $excusedAbsences }}
                            <span class="text-xs font-normal text-secondary-foreground">
                                ({{ $excusedPercent }}٪)
                            </span>
                        </span>

                        </div>

                        <div class="flex items-center justify-between gap-3">

                            <div class="flex items-center gap-2">
                                <span class="size-2.5 rounded-full bg-danger"></span>
                                <span class="text-sm text-secondary-foreground">
                                غیبت غیرموجه
                            </span>
                            </div>

                            <span class="text-sm font-bold text-mono">
                            {{ $unexcusedAbsences }}
                            <span class="text-xs font-normal text-secondary-foreground">
                                ({{ $unexcusedPercent }}٪)
                            </span>
                        </span>

                        </div>

                    </div>
                </div>
            </section>

        </div>

        {{-- Recent Activity --}}
        <div class="grid xl:grid-cols-3 gap-5 mb-7">

            <section class="kt-card xl:col-span-2">

                <div class="kt-card-header">
                    <div>
                        <h3 class="kt-card-title">
                            آخرین غیبت‌های ثبت‌شده
                        </h3>

                        <p class="mt-1 text-xs text-secondary-foreground">
                            آخرین فعالیت‌های حضور و غیاب
                        </p>
                    </div>
                </div>

                <div class="kt-card-content">

                    <div class="kt-scrollable-x-auto">

                        <table class="kt-table kt-table-border">

                            <thead>
                            <tr>
                                <th>دانش‌آموز</th>
                                <th>تاریخ</th>
                                <th>وضعیت</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($recentAbsences as $absence)

                                <tr>
                                    <td>
                                    <span class="font-medium text-mono">
                                        {{ $absence->student?->first_name }}
                                        {{ $absence->student?->last_name }}
                                    </span>
                                    </td>

                                    <td class="text-secondary-foreground">
                                        {{ \App\Helpers\Jalali::format($absence->date) }}
                                    </td>

                                    <td>
                                        @if($absence->is_excused)
                                            <span class="kt-badge kt-badge-success kt-badge-light">
                                            موجه
                                        </span>
                                        @else
                                            <span class="kt-badge kt-badge-danger kt-badge-light">
                                            غیرموجه
                                        </span>
                                        @endif
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="py-12 text-center">
                                            <i class="ki-filled ki-calendar
                                                  text-3xl text-secondary-foreground"></i>

                                            <p class="mt-3 text-sm text-secondary-foreground">
                                                هنوز غیبتی ثبت نشده است.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>

                        </table>

                    </div>

                </div>
            </section>

            {{-- Quick Access --}}
            <section class="kt-card">

                <div class="kt-card-header">
                    <h3 class="kt-card-title">
                        دسترسی سریع
                    </h3>
                </div>

                <div class="kt-card-content p-5 space-y-3">

                    @if($isTeacher)

                        <div class="rounded-xl border border-border bg-muted/30 p-4">

                            <div class="flex items-center gap-3">
                                <div class="flex size-10 items-center justify-center
                                        rounded-lg bg-primary/10 text-primary">
                                    <i class="ki-filled ki-people text-lg"></i>
                                </div>

                                <div>
                                    <h4 class="text-sm font-semibold text-mono">
                                        کلاس‌های من
                                    </h4>

                                    <p class="mt-1 text-xs text-secondary-foreground">
                                        {{ $totalClasses }} کلاس اختصاص‌یافته
                                    </p>
                                </div>
                            </div>

                        </div>

                        @foreach($myClasses as $class)
                            <a href="{{ url('/admin/my-classes/' . $class->id) }}"
                               class="flex items-center justify-between rounded-lg border border-border p-3
              transition-all duration-200
              hover:bg-muted/50 hover:border-primary/30">

                                <div class="flex items-center gap-2">
                                    <i class="ki-filled ki-book text-primary"></i>

                                    <span class="text-sm font-medium text-mono">
                {{ $class->name ?? $class->title ?? 'کلاس ' . $class->id }}
            </span>
                                </div>

                                <i class="ki-filled ki-left text-secondary-foreground"></i>
                            </a>
                        @endforeach

                    @else

                        @can('viewAny', \App\Models\Student::class)
                            <a href="{{ route('admin.students.index') }}"
                               class="flex items-center gap-3 rounded-xl
                                  border border-border p-4
                                  hover:bg-muted/50 transition-colors">

                                <i class="ki-filled ki-users text-xl text-primary"></i>

                                <span class="text-sm font-medium text-mono">
                                مدیریت دانش‌آموزان
                            </span>

                            </a>
                        @endcan

                        @can('viewAny', \App\Models\Teacher::class)
                            <a href="{{ route('admin.teachers.index') }}"
                               class="flex items-center gap-3 rounded-xl
                                  border border-border p-4
                                  hover:bg-muted/50 transition-colors">

                                <i class="ki-filled ki-teacher text-xl text-primary"></i>

                                <span class="text-sm font-medium text-mono">
                                مدیریت معلمان
                            </span>

                            </a>
                        @endcan

                        @can('viewAny', \App\Models\SchoolClass::class)
                            <a href="{{ route('admin.classes.index') }}"
                               class="flex items-center gap-3 rounded-xl
                                  border border-border p-4
                                  hover:bg-muted/50 transition-colors">

                                <i class="ki-filled ki-people text-xl text-primary"></i>

                                <span class="text-sm font-medium text-mono">
                                مدیریت کلاس‌ها
                            </span>

                            </a>
                        @endcan

                    @endif

                </div>

            </section>

        </div>

    </div>

@endsection
