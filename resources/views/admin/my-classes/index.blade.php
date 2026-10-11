@extends('admin.layouts.app')

@section('title', 'کلاس‌های من')

@php
    $gradeLabel = function (?int $grade): string {
        return match ((int) $grade) {
            10 => 'پایه دهم',
            11 => 'پایه یازدهم',
            12 => 'پایه دوازدهم',
            default => '—',
        };
    };
@endphp

@section('content')

<div style="font-feature-settings: 'ss01';">

    <div class="pb-5">
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">

            <div class="flex flex-col gap-1">
                <h1 class="font-semibold text-xl text-mono">
                    کلاس‌های من
                </h1>

                <div class="text-sm text-secondary-foreground">
                    کلاس‌هایی که به شما تخصیص داده شده است
                </div>
            </div>

        </div>
    </div>

    <div class="kt-container-fixed">

        @include('admin.classes.partials.flash')

        <div class="kt-card kt-card-grid min-w-full">

            <div class="kt-card-header py-5">
                <div class="flex items-center gap-2.5">
                    <h2 class="kt-card-title">
                        فهرست کلاس‌ها
                    </h2>

                    <span class="kt-badge kt-badge-outline">
                        {{ $classes->total() }} مورد
                    </span>
                </div>
            </div>

            <div class="kt-card-content">

                <div class="grid">

                    <div class="kt-scrollable-x-auto">

                        <table class="kt-table kt-table-border">

                            <thead>
                            <tr>
                                <th class="w-[80px]">ردیف</th>
                                <th class="min-w-[180px]">عنوان کلاس</th>
                                <th class="min-w-[120px]">پایه تحصیلی</th>
                                <th class="min-w-[120px]">تعداد دانش‌آموزان</th>
                                <th class="min-w-[180px]">دروس</th>
                                <th class="min-w-[130px]">تاریخ ایجاد</th>
                                <th class="w-[150px]"></th>
                            </tr>
                            </thead>

                            <tbody>

                                @forelse ($classes as $class)

                                    <tr>

                                        <td class="font-medium text-mono">
                                            {{ $classes->firstItem() + $loop->index }}
                                        </td>

                                        <td>
                                            <span class="font-medium text-sm text-mono">
                                                {{ $class->title }}
                                            </span>
                                        </td>

                                        <td class="text-secondary-foreground">
                                            {{ $gradeLabel($class->grade) }}
                                        </td>

                                        <td class="text-secondary-foreground">
                                            {{ $class->student_assignments_count }} نفر
                                        </td>

                                        <td>
                                            @php($subjects = $class->teacherAssignments->pluck('subject')->filter()->unique())

                                            @if ($subjects->isEmpty())
                                                <span class="text-secondary-foreground">—</span>
                                            @else
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($subjects as $subject)
                                                        <span class="kt-badge kt-badge-outline">{{ $subject }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="flex flex-col gap-0.5">
                                                <span>{{ \App\Helpers\Jalali::format($class->created_at)}}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <a
                                                class="kt-btn kt-btn-sm kt-btn-primary"
                                                href="{{ route('admin.my-classes.show', $class) }}"
                                            >
                                                <i class="ki-filled ki-calendar"></i>
                                                مدیریت کلاس
                                            </a>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="7">
                                            <div class="flex flex-col items-center justify-center gap-3 py-14 text-center">
                                                <span class="flex size-12 items-center justify-center rounded-full bg-primary/10 text-primary text-xl">
                                                    <i class="ki-filled ki-teacher"></i>
                                                </span>

                                                <div class="font-medium text-mono">
                                                    هنوز کلاسی به شما تخصیص داده نشده است
                                                </div>

                                                <p class="text-sm text-secondary-foreground">
                                                    در حال حاضر هیچ کلاسی برای مدیریت حضور و غیاب به شما اختصاص داده نشده است.
                                                    در صورت نیاز با مدیر هنرستان هماهنگ کنید.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    <div class="kt-card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-secondary-foreground text-sm font-medium">

                        <form class="flex items-center gap-2 order-2 md:order-1"
                              method="GET"
                              action="{{ route('admin.my-classes.index') }}">
                            <span>نمایش</span>

                            <select class="kt-select w-20" name="per_page" onchange="this.form.submit()">
                                @foreach ([10, 25, 50] as $size)
                                    <option value="{{ $size }}" {{ (int) ($perPage ?? 10) === $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>

                            <span>در هر صفحه</span>
                        </form>

                        <div class="flex items-center gap-4 order-1 md:order-2">
                            <span>{{ $classes->total() }} مورد</span>

                            @include('admin.partials.pagination', ['paginator' => $classes])
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
