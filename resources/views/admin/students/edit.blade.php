@extends('admin.layouts.app')

@section('title', 'ویرایش دانش‌آموز')

@section('content')

<div style="font-feature-settings: 'ss01';">

    <div class="pb-5">
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">

            <div class="flex flex-col gap-1">
                <h1 class="font-semibold text-xl text-mono">
                    ویرایش دانش‌آموز: {{ $student->first_name }} {{ $student->last_name }}
                </h1>

                <div class="text-sm text-secondary-foreground">
                    ویرایش اطلاعات دانش‌آموز و اولیا
                </div>
            </div>

            <a class="kt-btn kt-btn-outline"
               href="{{ route('admin.students.index') }}">
                <i class="ki-filled ki-arrow-right"></i>
                بازگشت به فهرست
            </a>

        </div>
    </div>


    <div class="kt-container-fixed">

        <form method="POST" action="{{ route('admin.students.update', $student) }}" class="flex flex-col gap-5">

            @csrf
            @method('PUT')

            @include('admin.students.partials.form', ['student' => $student])

        </form>

    </div>

</div>

@endsection

@include('admin.students.partials.scripts')
