@extends('admin.layouts.app')

@section('title', 'افزودن معاون')

@section('content')

    <div style="font-feature-settings: 'ss01';">

        <div class="pb-5">

            <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">

                <div class="flex flex-col gap-1">

                    <h1 class="font-semibold text-xl text-mono">
                        افزودن معاون
                    </h1>

                    <div class="text-sm text-secondary-foreground">
                        ایجاد معاون جدید برای پنل مدیریت
                    </div>

                </div>

                <a
                    class="kt-btn kt-btn-outline"
                    href="{{ route('admin.admin-management.index') }}"
                >
                    <i class="ki-filled ki-arrow-right"></i>
                    بازگشت به فهرست
                </a>

            </div>

        </div>


        <div class="kt-container-fixed">

            <form
                method="POST"
                action="{{ route('admin.admin-management.store') }}"
            >

                @csrf

                @include('admin.admins._form')

            </form>

        </div>

    </div>
@endsection
