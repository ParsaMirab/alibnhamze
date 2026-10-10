@extends('admin.layouts.app')

@section('title', 'افزودن کلاس')

@section('content')

<div style="font-feature-settings: 'ss01';">

    <div class="pb-5">
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">

            <div class="flex flex-col gap-1">
                <h1 class="font-semibold text-xl text-mono">
                    افزودن کلاس
                </h1>

                <div class="text-sm text-secondary-foreground">
                    ایجاد کلاس جدید برای مدیریت آموزشی
                </div>
            </div>

            <a class="kt-btn kt-btn-outline"
               href="{{ route('admin.classes.index') }}">
                <i class="ki-filled ki-arrow-right"></i>
                بازگشت به فهرست
            </a>

        </div>
    </div>


    <div class="kt-container-fixed">

        <form method="POST" action="{{ route('admin.classes.store') }}">

            @csrf

            @include('admin.classes.partials.form')

        </form>

    </div>

</div>

@endsection