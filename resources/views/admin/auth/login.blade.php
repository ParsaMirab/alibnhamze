```blade
@extends('admin.layouts.auth')

@section('title', 'ورود')

@section('content')
    <div class="grid lg:grid-cols-2 grow w-full">
        <div class="flex justify-center items-center p-6 sm:p-8 lg:p-10 order-2 lg:order-1">
            <div class="kt-card max-w-[390px] w-full">
                <form action="{{ route('admin.login.store') }}"
                      class="kt-card-content flex flex-col gap-5 p-7 sm:p-10"
                      method="POST">
                    @csrf

                    <div class="text-center mb-2.5">
                        <h1 class="text-xl font-semibold text-mono leading-none mb-3">
                            ورود به پنل مدیریت
                        </h1>

                        <p class="text-sm text-secondary-foreground">
                            برای ادامه اطلاعات حساب خود را وارد کنید.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div
                            class="rounded-md border border-destructive/30 bg-destructive/10 px-4 py-3 text-sm text-destructive"
                            role="alert"
                        >
                            {{ $errors->first() }}
                        </div>
                    @endif

                    {{-- National Code --}}
                    <div class="flex flex-col gap-1.5">
                        <label
                            class="kt-form-label font-normal text-mono"
                            for="national_code"
                        >
                            کد ملی
                        </label>

                        <input
                            class="kt-input @error('national_code') border-destructive @enderror"
                            id="national_code"
                            name="national_code"
                            placeholder="مثلاً 1234567890"
                            type="text"
                            inputmode="numeric"
                            maxlength="10"
                            value="{{ old('national_code') }}"
                            autocomplete="username"
                            autofocus
                        >

                        @error('national_code')
                        <span class="text-xs text-destructive">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center justify-between gap-1">
                            <label
                                class="kt-form-label font-normal text-mono"
                                for="password"
                            >
                                رمز عبور
                            </label>

                            <a class="text-sm kt-link shrink-0" href="#">
                                رمز عبور را فراموش کرده‌اید؟
                            </a>
                        </div>

                        <div class="kt-input" data-admin-password>
                            <input
                                id="password"
                                name="password"
                                placeholder="رمز عبور"
                                type="password"
                                autocomplete="current-password"
                            >

                            <button
                                class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5"
                                data-admin-password-toggle
                                type="button"
                                aria-label="نمایش رمز عبور"
                            >
                                <i class="ki-filled ki-eye text-muted-foreground"></i>
                            </button>
                        </div>

                        @error('password')
                        <span class="text-xs text-destructive">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <label class="kt-label cursor-pointer">
                        <input
                            class="kt-checkbox kt-checkbox-sm"
                            name="remember"
                            type="checkbox"
                            value="1"
                            @checked(old('remember'))
                        >

                        <span class="kt-checkbox-label">
                            مرا به خاطر بسپار
                        </span>
                    </label>

                    <button
                        class="kt-btn kt-btn-primary flex justify-center grow"
                        type="submit"
                    >
                        ورود به داشبورد
                    </button>
                </form>
            </div>
        </div>

        <div
            class="login-brand lg:rounded-xl lg:border lg:border-border lg:m-5 order-1 lg:order-2 bg-cover bg-center bg-no-repeat min-h-[230px] lg:min-h-0"
        >
            <div class="flex flex-col p-8 lg:p-16 gap-5">
                <a class="flex items-center gap-3" href="{{ url('/') }}">
                    <img
                        class="size-10"
                        src="{{ asset('admin/media/app/mini-logo.svg') }}"
                        alt="علی بن حمزه"
                    >

                    <span class="text-xl font-bold text-mono">
                        علی بن حمزه
                    </span>
                </a>

                <div class="flex flex-col gap-3 max-w-lg">
                    <h2 class="text-2xl lg:text-3xl font-bold text-mono">
                        مدیریت ساده‌تر دانش‌آموزان
                    </h2>

                    <p class="text-base font-medium text-secondary-foreground leading-8">
                        مدیریت ساده‌تر، بهتر و تخصصی‌تر
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
```
