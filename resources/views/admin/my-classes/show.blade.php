@extends('admin.layouts.app')

@section('title', 'مدیریت کلاس')

@php
    $gradeLabel = match ((int) $schoolClass->grade) {
        10 => 'پایه دهم',
        11 => 'پایه یازدهم',
        12 => 'پایه دوازدهم',
        default => '—',
    };

    // Persian weekday label for the selected Jalaali date.
    $jalaliParts = \App\Support\JalaliDate::parse($jalaliDate);
    $weekdayLabel = $jalaliParts ? \App\Support\JalaliDate::weekdayName($jalaliParts) : '';
@endphp

@section('content')

<div style="font-feature-settings: 'ss01';">

    <div class="pb-5">
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">

            <div class="flex flex-col gap-1">
                <h1 class="font-semibold text-xl text-mono">
                    کلاس: {{ $schoolClass->title }}
                </h1>

                <div class="text-sm text-secondary-foreground">
                    {{ $gradeLabel }}
                </div>
            </div>

            <a class="kt-btn kt-btn-outline"
               href="{{ route('admin.my-classes.index') }}">
                <i class="ki-filled ki-arrow-right"></i>
                بازگشت به کلاس‌های من
            </a>

        </div>
    </div>

    <div class="kt-container-fixed flex flex-col gap-5">

        @include('admin.classes.partials.flash')

        {{-- ■ بخش الف: اطلاعات کلاس --}}
        <div class="kt-card kt-card-grid min-w-full">
            <div class="kt-card-header py-5">
                <h2 class="kt-card-title">
                    اطلاعات کلاس
                </h2>
            </div>

            <div class="kt-card-content grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                <div class="flex items-center gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <i class="ki-filled ki-people"></i>
                    </span>
                    <div>
                        <div class="font-medium text-mono">عنوان کلاس</div>
                        <div class="text-sm text-secondary-foreground mt-1">{{ $schoolClass->title }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <i class="ki-filled ki-book"></i>
                    </span>
                    <div>
                        <div class="font-medium text-mono">پایه تحصیلی</div>
                        <div class="text-sm text-secondary-foreground mt-1">{{ $gradeLabel }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <i class="ki-filled ki-user-group"></i>
                    </span>
                    <div>
                        <div class="font-medium text-mono">تعداد دانش‌آموزان</div>
                        <div class="text-sm text-secondary-foreground mt-1">{{ $students->count() }} نفر</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <i class="ki-filled ki-teacher"></i>
                    </span>
                    <div>
                        <div class="font-medium text-mono">دروس شما در این کلاس</div>
                        <div class="text-sm text-secondary-foreground mt-1">
                            {{ $subjects->isEmpty() ? '—' : $subjects->join('، ') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ■ بخش ب: حضور و غیاب دانش‌آموزان --}}
        <div class="kt-card kt-card-grid min-w-full">

            <div class="kt-card-header py-5 flex-wrap gap-3">
                <div class="flex flex-col gap-1">
                    <h2 class="kt-card-title">
                        حضور و غیاب دانش‌آموزان
                    </h2>

                    <div class="text-sm text-secondary-foreground">
                        به صورت پیش‌فرض همه دانش‌آموزان حاضر در نظر گرفته می‌شوند.
                    </div>
                </div>
            </div>

            <div class="kt-card-content flex flex-col gap-5">

                {{-- انتخاب تاریخ شمسی (فقط بارگذاری وضعیت؛ ثبت خودکار انجام نمی‌شود) --}}
                <form
                    class="flex flex-wrap items-end gap-3"
                    method="GET"
                    action="{{ route('admin.my-classes.show', $schoolClass) }}"
                    data-attendance-date-form
                >
                    <div class="flex flex-col gap-1.5 w-full sm:w-72">
                        <label class="kt-form-label" for="attendanceDate">
                            تاریخ حضور و غیاب
                            <span class="text-destructive">*</span>
                        </label>

                        <div class="relative" data-jalali-picker>
                            <label class="kt-input w-full">
                                <i class="ki-filled ki-calendar"></i>
                                <input
                                    id="attendanceDate"
                                    name="date"
                                    type="text"
                                    value="{{ $jalaliDate }}"
                                    placeholder="لطفاً تاریخ امروز را وارد کنید"
                                    autocomplete="off"
                                    inputmode="numeric"
                                    data-jalali-datepicker
                                >
                            </label>

                            <div class="jalali-picker hidden" data-jalali-calendar>
                                <div class="jalali-picker-head">
                                    <button type="button" class="jalali-picker-nav" data-jalali-prev aria-label="ماه قبل">
                                        <i class="ki-filled ki-right"></i>
                                    </button>

                                    <div class="jalali-picker-title" data-jalali-title></div>

                                    <button type="button" class="jalali-picker-nav" data-jalali-next aria-label="ماه بعد">
                                        <i class="ki-filled ki-left"></i>
                                    </button>
                                </div>

                                <div class="jalali-picker-weekdays" data-jalali-weekdays></div>

                                <div class="jalali-picker-days" data-jalali-days></div>
                            </div>
                        </div>

                        @error('date')
                            <div class="text-xs text-destructive">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="kt-btn kt-btn-outline" type="submit">
                        <i class="ki-filled ki-magnifier"></i>
                        نمایش
                    </button>

                    <div class="text-sm text-secondary-foreground pb-2">
                        تاریخ ثبت‌شده: {{ $jalaliDate }}
                        @if ($weekdayLabel !== '')
                            ({{ $weekdayLabel }})
                        @endif
                        <span class="text-xs">· ذخیره‌سازی میلادی: <span dir="ltr">{{ $gregorianDate }}</span></span>
                    </div>
                </form>

                <div class="border-t border-input"></div>

                @error('absent')
                    <div class="kt-alert kt-alert-destructive" role="alert">
                        <i class="ki-filled ki-information-2 text-lg"></i>
                        {{ $message }}
                    </div>
                @enderror

                @if ($hasExistingAbsences)
                    <div class="kt-alert kt-alert-warning" role="status">
                        <i class="ki-filled ki-information-2 text-lg"></i>
                        برای این تاریخ قبلاً غیبت ثبت شده است. در صورت ثبت مجدد، فهرست غایبین همین تاریخ به‌روزرسانی می‌شود.
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('admin.my-classes.attendance.store', $schoolClass) }}"
                    data-attendance-form
                    data-confirm-replace="{{ $hasExistingAbsences ? 'برای این تاریخ قبلاً غیبت ثبت شده است. آیا از به‌روزرسانی حضور و غیاب این تاریخ مطمئن هستید؟' : '' }}"
                >
                    @csrf

                    {{-- تاریخ بارگذاری‌شده؛ تغییر تاریخ فقط از طریق فرم بالا انجام می‌شود --}}
                    <input type="hidden" name="date" value="{{ $jalaliDate }}">

                    <div class="kt-scrollable-x-auto">
                        <table class="kt-table kt-table-border">
                            <thead>
                            <tr>
                                <th class="w-[70px]">ردیف</th>
                                <th class="min-w-[200px]">دانش‌آموز</th>
                                <th class="min-w-[140px]">کد ملی</th>
                                <th class="min-w-[120px]">پایه</th>
                                <th class="min-w-[140px]">وضعیت</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse ($students as $student)
                                @php($isAbsent = in_array((int) $student->id, $absentStudentIds, true))

                                <tr class="{{ $isAbsent ? 'bg-destructive/5' : '' }}" data-student-row>
                                    <td class="font-medium text-mono">{{ $loop->iteration }}</td>

                                    <td>
                                        <span class="font-medium text-sm text-mono">
                                            {{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: '—' }}
                                        </span>
                                    </td>

                                    <td class="text-secondary-foreground" dir="ltr">
                                        {{ $student->national_code ?? '—' }}
                                    </td>

                                    <td class="text-secondary-foreground">
                                        {{ $student->grade ? 'پایه '.$student->grade : '—' }}
                                    </td>

                                    <td>
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input
                                                class="kt-checkbox"
                                                type="checkbox"
                                                name="absent[]"
                                                value="{{ $student->id }}"
                                                @checked($isAbsent)
                                                data-absent-toggle
                                            >

                                            <span class="text-sm font-medium {{ $isAbsent ? 'text-destructive' : 'text-secondary-foreground' }}" data-absent-label>
                                                غایب
                                            </span>
                                        </label>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="flex flex-col items-center justify-center gap-3 py-14 text-center">
                                            <span class="flex size-12 items-center justify-center rounded-full bg-primary/10 text-primary text-xl">
                                                <i class="ki-filled ki-user"></i>
                                            </span>

                                            <div class="font-medium text-mono">
                                                دانش‌آموزی در این کلاس ثبت نشده است
                                            </div>

                                            <p class="text-sm text-secondary-foreground">
                                                تا زمانی که دانش‌آموزی به این کلاس اضافه نشود، امکان ثبت حضور و غیاب وجود ندارد.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($students->isNotEmpty())
                        <div class="flex items-center justify-between flex-wrap gap-3 mt-5">
                            <div class="text-sm font-medium text-mono">
                                تعداد غایبین:
                                <span data-absent-count>۰</span>
                                نفر
                            </div>

                            <button class="kt-btn kt-btn-primary" type="submit" data-attendance-submit>
                                <i class="ki-filled ki-check"></i>
                                ثبت حضور و غیاب
                            </button>
                        </div>
                    @endif
                </form>

            </div>

        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
    .jalali-picker {
        position: absolute;
        z-index: 60;
        margin-top: 0.5rem;
        width: 17rem;
        padding: 0.75rem;
        background: #fff;
        border: 1px solid #e4e6ef;
        border-radius: 0.75rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
    }

    .jalali-picker-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .jalali-picker-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #071437;
    }

    .jalali-picker-nav {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 0.5rem;
        border: 1px solid #e4e6ef;
        background: #fcfcfc;
        color: #4b5675;
        cursor: pointer;
    }

    .jalali-picker-nav:hover {
        background: #f1f1f4;
    }

    .jalali-picker-weekdays,
    .jalali-picker-days {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 0.125rem;
    }

    .jalali-picker-weekdays span {
        text-align: center;
        font-size: 0.7rem;
        color: #99a1b7;
        padding: 0.25rem 0;
    }

    .jalali-day {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 2.1rem;
        font-size: 0.8rem;
        border-radius: 0.5rem;
        border: 1px solid transparent;
        color: #252f4a;
        cursor: pointer;
        background: transparent;
    }

    .jalali-day:hover {
        background: #f1f1f4;
    }

    .jalali-day.is-empty {
        visibility: hidden;
    }

    .jalali-day.is-today {
        border-color: #1b84ff;
        color: #1b84ff;
        font-weight: 600;
    }

    .jalali-day.is-selected {
        background: #1b84ff;
        border-color: #1b84ff;
        color: #fff;
        font-weight: 600;
    }
</style>
@endpush

@include('admin.my-classes.partials.scripts', [
    'jalaliDate' => $jalaliDate,
])
