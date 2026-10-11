
@extends('admin.layouts.app')

@section('title', 'ویرایش معلم')

@section('content')

    <div style="font-feature-settings: 'ss01';">

        <div class="pb-5">
            <div class="kt-container-fixed">
                <div class="flex flex-col gap-1">
                    <h1 class="font-semibold text-xl text-mono">
                        ویرایش اطلاعات معلم
                    </h1>

                    <div class="text-sm text-secondary-foreground">
                        ویرایش اطلاعات
                        {{ $teacher->first_name }}
                        {{ $teacher->last_name }}
                    </div>
                </div>
            </div>
        </div>

        <div class="kt-container-fixed">

            <form method="POST"
                  action="{{ route('admin.teachers.update', $teacher) }}">

                @csrf
                @method('PUT')

                @include('admin.teachers._form', [
                    'teacher' => $teacher
                ])

            </form>

        </div>

    </div>

@endsection
