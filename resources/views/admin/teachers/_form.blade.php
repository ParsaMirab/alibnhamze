@php
    $teacher = $teacher ?? null;
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

{{-- Teacher Information --}}
<section class="kt-card">

    <div class="kt-card-header">
        <h2 class="kt-card-title">
            اطلاعات معلم
        </h2>
    </div>

    <div class="kt-card-content grid gap-5 lg:grid-cols-2">

        {{-- First Name --}}
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
                value="{{ old('first_name', $teacher?->first_name) }}"
                required
            >

            @error('first_name')
            <span class="block mt-2 text-xs text-destructive">
                    {{ $message }}
                </span>
            @enderror
        </div>

        {{-- Last Name --}}
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
                value="{{ old('last_name', $teacher?->last_name) }}"
                required
            >

            @error('last_name')
            <span class="block mt-2 text-xs text-destructive">
                    {{ $message }}
                </span>
            @enderror
        </div>

        {{-- National Code --}}
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
                inputmode="numeric"
                maxlength="10"
                value="{{ old('national_code', $teacher?->national_code) }}"
                required
            >

            @error('national_code')
            <span class="block mt-2 text-xs text-destructive">
                    {{ $message }}
                </span>
            @enderror
        </div>

        {{-- Phone --}}
        <div>
            <label class="kt-form-label" for="phone">
                شماره تلفن
            </label>

            <input
                class="kt-input mt-2 @error('phone') border-destructive @enderror"
                dir="ltr"
                id="phone"
                name="phone"
                type="text"
                inputmode="tel"
                maxlength="20"
                value="{{ old('phone', $teacher?->phone) }}"
            >

            <p class="flex items-start gap-1.5 mt-2 text-xs text-secondary-foreground">
                <i class="ki-filled ki-information-2 mt-0.5"></i>
                <span>
                    این شماره تلفن برای ورود معلم به پنل کاربری وب‌سایت استفاده می‌شود.
                </span>
            </p>

            @error('phone')
            <span class="block mt-2 text-xs text-destructive">
                    {{ $message }}
                </span>
            @enderror
        </div>

    </div>

</section>

{{-- Form Actions --}}
<div class="flex items-center justify-end gap-3 mt-5">

    <a
        class="kt-btn kt-btn-outline"
        href="{{ route('admin.teachers.index') }}"
    >
        <i class="ki-filled ki-arrow-right"></i>
        انصراف
    </a>

    <button class="kt-btn kt-btn-primary" type="submit">
        <i class="ki-filled ki-check"></i>
        {{ $teacher ? 'ذخیره تغییرات' : 'ذخیره اطلاعات' }}
    </button>

</div>
