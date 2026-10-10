@extends('admin.layouts.app')

@section('title', 'مشاهده کلاس')

@section('content')

<div style="font-feature-settings: 'ss01';">

    <div class="pb-5">
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">

            <div class="flex flex-col gap-1">
                <h1 class="font-semibold text-xl text-mono">
                    کلاس: {{ $schoolClass->title }}
                </h1>

                <div class="text-sm text-secondary-foreground">
                    @if($schoolClass->grade == 10)
                        پایه دهم
                    @elseif($schoolClass->grade == 11)
                        پایه یازدهم
                    @elseif($schoolClass->grade == 12)
                        پایه دوازدهم
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2">
                @can('update', $schoolClass)
                    <a class="kt-btn kt-btn-primary"
                       href="{{ route('admin.classes.edit', $schoolClass) }}">
                        <i class="ki-filled ki-pencil"></i>
                        ویرایش کلاس
                    </a>
                @endcan

                <a class="kt-btn kt-btn-outline"
                   href="{{ route('admin.classes.index') }}">
                    <i class="ki-filled ki-arrow-right"></i>
                    بازگشت به فهرست
                </a>
            </div>

        </div>
    </div>

    <div class="kt-container-fixed flex flex-col gap-5">

        @include('admin.classes.partials.flash')

        <div class="kt-card kt-card-grid min-w-full">
            <div class="kt-card-header py-5">
                <h2 class="kt-card-title">
                    آمار کلاس
                </h2>
            </div>

            <div class="kt-card-content grid gap-5 sm:grid-cols-3">
                <div class="flex items-center gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <i class="ki-filled ki-people"></i>
                    </span>
                    <div>
                        <div class="font-medium text-mono">معلمان</div>
                        <div class="text-sm text-secondary-foreground mt-1">{{ $schoolClass->teacherAssignments->count() }} نفر</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <i class="ki-filled ki-user-group"></i>
                    </span>
                    <div>
                        <div class="font-medium text-mono">دانش‌آموزان</div>
                        <div class="text-sm text-secondary-foreground mt-1">{{ $schoolClass->studentAssignments->count() }} نفر</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <i class="ki-filled ki-calendar"></i>
                    </span>
                    <div>
                        <div class="font-medium text-mono">تاریخ ایجاد</div>
                        <div class="text-sm text-secondary-foreground mt-1">{{ $schoolClass->created_at?->format('Y/m/d') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- تب‌های دانش‌آموزان و معلمان --}}
        <div data-class-tabs data-initial-tab="{{ $activeTab ?? 'students' }}" class="kt-card kt-card-grid min-w-full">

            <div class="kt-card-header p-0 border-b border-input">
                <div class="flex items-center w-full">
                    <button class="active" type="button" data-class-tab="students" aria-selected="true">
                        دانش‌آموزان
                    </button>

                    <button type="button" data-class-tab="teachers" aria-selected="false">
                        معلمان
                    </button>
                </div>
            </div>

            <div class="kt-card-content pt-5">
                <div data-class-tab-panel="students" class="{{ ($activeTab ?? 'students') === 'students' ? '' : 'hidden' }}">
                    @include('admin.classes.partials.students-tab', ['schoolClass' => $schoolClass])
                </div>

                <div data-class-tab-panel="teachers" class="{{ ($activeTab ?? 'students') === 'teachers' ? '' : 'hidden' }}">
                    @include('admin.classes.partials.teachers-tab', ['schoolClass' => $schoolClass])
                </div>
            </div>

        </div>

    </div>

</div>

@endsection

@include('admin.classes.partials.scripts')
