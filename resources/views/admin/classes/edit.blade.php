@extends('admin.layouts.app')

@section('title', 'مدیریت کلاس')

@section('content')

<div style="font-feature-settings: 'ss01';">

    <div class="pb-5">
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">

            <div class="flex flex-col gap-1">
                <h1 class="font-semibold text-xl text-mono">
                    مدیریت کلاس: {{ $schoolClass->title }}
                </h1>

                <div class="text-sm text-secondary-foreground">
                    ویرایش اطلاعات کلاس، دانش‌آموزان و معلمان
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a class="kt-btn kt-btn-outline"
                   href="{{ route('admin.classes.show', $schoolClass) }}">
                    <i class="ki-filled ki-eye"></i>
                    مشاهده
                </a>

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

        {{-- اطلاعات کلاس --}}
        <form method="POST" action="{{ route('admin.classes.update', $schoolClass) }}">

            @csrf
            @method('PUT')

            @include('admin.classes.partials.form', ['schoolClass' => $schoolClass])

        </form>

        {{-- تب‌های دانش‌آموزان و معلمان --}}
        <div data-class-tabs data-initial-tab="{{ $activeTab ?? 'students' }}" class="kt-card kt-card-grid min-w-full">

            <div class="kt-card-header p-0 border-b border-input">
                <div class="flex items-center w-full">
                    <button
                        class="active"
                        type="button"
                        data-class-tab="students"
                        aria-selected="true"
                    >
                        دانش‌آموزان
                    </button>

                    <button
                        type="button"
                        data-class-tab="teachers"
                        aria-selected="false"
                    >
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
