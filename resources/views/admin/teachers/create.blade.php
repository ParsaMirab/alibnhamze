@extends('admin.layouts.app')

@section('title', 'افزودن معلم')

@section('content')

    <div style="font-feature-settings: 'ss01';">

        <div class="pb-5">
            <div class="kt-container-fixed">
                <div class="flex flex-col gap-1">
                    <h1 class="font-semibold text-xl text-mono">
                        افزودن معلم جدید
                    </h1>

                    <div class="text-sm text-secondary-foreground">
                        اطلاعات معلم جدید را وارد کنید.
                    </div>
                </div>
            </div>
        </div>

        <div class="kt-container-fixed">

            <form method="POST"
                  action="{{ route('admin.teachers.store') }}">

                @csrf

                @include('admin.teachers._form')

            </form>

        </div>

    </div>

@endsection
