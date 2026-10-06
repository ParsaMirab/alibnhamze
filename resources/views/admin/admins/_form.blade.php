@php
    $deputy = $deputy ?? null;
@endphp

@if ($errors->any())
    <div class="kt-alert kt-alert-destructive mb-5" role="alert">
        <i class="ki-filled ki-information-2 text-lg"></i>

        <div>
            <div class="font-medium mb-1">
                اطلاعات فرم معتبر نیست.
            </div>

            <ul class="list-disc pe-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif


<div class="grid lg:grid-cols-2 gap-5 lg:gap-7.5">

    {{-- اطلاعات معاون --}}
    <section class="kt-card">

        <div class="kt-card-header">
            <h2 class="kt-card-title">
                اطلاعات معاون
            </h2>
        </div>

        <div class="kt-card-content flex flex-col gap-5">

            {{-- نام --}}
            <div>
                <label class="kt-form-label" for="first_name">
                    نام
                    <span class="text-destructive">*</span>
                </label>

                <input
                    class="kt-input mt-2 @error('first_name') border-destructive @enderror"
                    id="first_name"
                    name="first_name"
                    type="text"
                    maxlength="255"
                    value="{{ old('first_name', $deputy?->first_name) }}"
                    required
                >

                @error('first_name')
                <span class="block mt-2 text-xs text-destructive">
                        {{ $message }}
                    </span>
                @enderror
            </div>


            {{-- نام خانوادگی --}}
            <div>
                <label class="kt-form-label" for="last_name">
                    نام خانوادگی
                    <span class="text-destructive">*</span>
                </label>

                <input
                    class="kt-input mt-2 @error('last_name') border-destructive @enderror"
                    id="last_name"
                    name="last_name"
                    type="text"
                    maxlength="255"
                    value="{{ old('last_name', $deputy?->last_name) }}"
                    required
                >

                @error('last_name')
                <span class="block mt-2 text-xs text-destructive">
                        {{ $message }}
                    </span>
                @enderror
            </div>


            {{-- کد ملی --}}
            <div>
                <label class="kt-form-label" for="national_code">
                    کد ملی
                    <span class="text-destructive">*</span>
                </label>

                <input
                    class="kt-input mt-2 @error('national_code') border-destructive @enderror"
                    dir="ltr"
                    id="national_code"
                    name="national_code"
                    type="text"
                    maxlength="10"
                    value="{{ old('national_code', $deputy?->national_code) }}"
                    required
                >

                @error('national_code')
                <span class="block mt-2 text-xs text-destructive">
                        {{ $message }}
                    </span>
                @enderror
            </div>


            {{-- شماره موبایل --}}
            <div>
                <label class="kt-form-label" for="phone">
                    شماره موبایل
                </label>

                <input
                    class="kt-input mt-2 @error('phone') border-destructive @enderror"
                    dir="ltr"
                    id="phone"
                    name="phone"
                    type="text"
                    maxlength="20"
                    value="{{ old('phone', $deputy?->phone) }}"
                >

                @error('phone')
                <span class="block mt-2 text-xs text-destructive">
                        {{ $message }}
                    </span>
                @enderror
            </div>


            {{-- رمز عبور --}}
            <div>
                <label class="kt-form-label" for="password">
                    رمز عبور

                    @if ($deputy === null)
                        <span class="text-destructive">*</span>
                    @endif
                </label>

                <input
                    class="kt-input mt-2 @error('password') border-destructive @enderror"
                    dir="ltr"
                    id="password"
                    name="password"
                    type="password"
                    minlength="8"
                    @if ($deputy === null) required @endif
                    placeholder="{{ $deputy === null ? '' : 'برای تغییر رمز، مقدار جدید وارد کنید' }}"
                >

                @error('password')
                <span class="block mt-2 text-xs text-destructive">
                        {{ $message }}
                    </span>
                @enderror
            </div>


            {{-- تکرار رمز --}}
            <div>
                <label class="kt-form-label" for="password_confirmation">
                    تکرار رمز عبور

                    @if ($deputy === null)
                        <span class="text-destructive">*</span>
                    @endif
                </label>

                <input
                    class="kt-input mt-2 @error('password_confirmation') border-destructive @enderror"
                    dir="ltr"
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    minlength="8"
                    @if ($deputy === null) required @endif
                >

                @error('password_confirmation')
                <span class="block mt-2 text-xs text-destructive">
                        {{ $message }}
                    </span>
                @enderror
            </div>

        </div>
    </section>


    {{-- اطلاعات نقش --}}
    <section class="kt-card">

        <div class="kt-card-header">
            <h2 class="kt-card-title">
                سطح دسترسی
            </h2>
        </div>

        <div class="kt-card-content">

            <div class="flex items-start gap-3">

                <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                    <i class="ki-filled ki-shield-tick text-lg"></i>
                </span>

                <div>
                    <div class="font-medium text-mono">
                        معاون
                    </div>

                    <div class="text-sm text-secondary-foreground mt-1">
                        این کاربر به عنوان معاون پنل ثبت خواهد شد.
                    </div>

                    <div class="text-sm text-secondary-foreground mt-3">
                        معاون می‌تواند وارد پنل مدیریت شود، اما امکان مدیریت سایر معاونین را ندارد.
                    </div>
                </div>

            </div>

            <div class="border-t border-input my-5"></div>

            <div class="flex flex-col gap-3">

                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm text-secondary-foreground">
                        نوع حساب
                    </span>

                    <span class="kt-badge kt-badge-light kt-badge-primary">
                        ادمین
                    </span>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm text-secondary-foreground">
                        نقش
                    </span>

                    <span class="kt-badge kt-badge-light kt-badge-secondary">
                        معاون
                    </span>
                </div>

            </div>

        </div>

    </section>

</div>


<div class="flex items-center justify-end gap-3 mt-5">

    <a
        class="kt-btn kt-btn-outline"
        href="{{ route('admin.admin-management.index') }}"
    >
        <i class="ki-filled ki-arrow-right"></i>
        انصراف
    </a>

    <button class="kt-btn kt-btn-primary" type="submit">

        <i class="ki-filled ki-check"></i>

        {{ $deputy ? 'ذخیره تغییرات' : 'ایجاد معاون' }}

    </button>

</div>
