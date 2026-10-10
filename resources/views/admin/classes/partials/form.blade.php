@php
    $schoolClass = $schoolClass ?? null;
@endphp

<!-- اطلاعات کلاس -->
<section class="kt-card">

    <div class="kt-card-header">
        <h2 class="kt-card-title">
            اطلاعات کلاس
        </h2>
    </div>

    <div class="kt-card-content flex flex-col gap-5">

        <!-- عنوان کلاس -->
        <div>
            <label class="kt-form-label" for="title">
                عنوان کلاس
                <span class="text-destructive">*</span>
            </label>

            <input
                class="kt-input mt-2 @error('title') border-destructive @enderror"
                id="title"
                name="title"
                type="text"
                maxlength="255"
                value="{{ old('title', $schoolClass?->title) }}"
                required
            >

            @error('title')
                <span class="block mt-2 text-xs text-destructive">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <!-- سطح تحصیلی -->
        <div>
            <label class="kt-form-label" for="grade">
                پایه تحصیلی
                <span class="text-destructive">*</span>
            </label>

            <select
                class="kt-select mt-2 @error('grade') border-destructive @enderror"
                id="grade"
                name="grade"
            >
                <option value="">لطفاً پایه تحصیلی را انتخاب کنید</option>
                <option value="10" {{ old('grade', $schoolClass?->grade) == 10 ? 'selected' : '' }}>پایه دهم</option>
                <option value="11" {{ old('grade', $schoolClass?->grade) == 11 ? 'selected' : '' }}>پایه یازدهم</option>
                <option value="12" {{ old('grade', $schoolClass?->grade) == 12 ? 'selected' : '' }}>پایه دوازدهم</option>
            </select>

            @error('grade')
                <span class="block mt-2 text-xs text-destructive">
                    {{ $message }}
                </span>
            @enderror
        </div>

    </div>
</section>

<!-- دکمه‌های فرم -->
<div class="flex items-center justify-end gap-3 mt-5">

    <a
        class="kt-btn kt-btn-outline"
        href="{{ route('admin.classes.index') }}">
        <i class="ki-filled ki-arrow-right"></i>
        انصراف
    </a>

    <button class="kt-btn kt-btn-primary" type="submit">
        <i class="ki-filled ki-check"></i>

        {{ $schoolClass ? 'ذخیره تغییرات' : 'ایجاد کلاس' }}
    </button>

</div>