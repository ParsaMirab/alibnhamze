@php
    $student = $student ?? null;
    $father = $student?->father?->parent;
    $mother = $student?->mother?->parent;

    $fatherAlive = (string) old('father_alive', $father ? '1' : '0') === '1';
    $motherAlive = (string) old('mother_alive', $mother ? '1' : '0') === '1';
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


{{-- اطلاعات دانش‌آموز --}}
<section class="kt-card">

    <div class="kt-card-header">
        <h2 class="kt-card-title">
            اطلاعات دانش‌آموز
        </h2>
    </div>

    <div class="kt-card-content grid gap-5 lg:grid-cols-2">

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
                value="{{ old('first_name', $student?->first_name) }}"
                required
            >

            @error('first_name')
                <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
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
                value="{{ old('last_name', $student?->last_name) }}"
                required
            >

            @error('last_name')
                <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
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
                value="{{ old('national_code', $student?->national_code) }}"
                required
            >

            @error('national_code')
                <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
            @enderror
        </div>

        {{-- شماره تلفن --}}
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
                maxlength="20"
                value="{{ old('phone', $student?->phone) }}"
            >

            <p class="flex items-start gap-1.5 mt-2 text-xs text-secondary-foreground">
                <i class="ki-filled ki-information-2 mt-0.5"></i>
                <span>این شماره تلفن برای ورود دانش‌آموز به پنل کاربری وب‌سایت استفاده می‌شود.</span>
            </p>

            @error('phone')
                <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
            @enderror
        </div>

        {{-- پایه تحصیلی --}}
        <div class="lg:col-span-2">
            <label class="kt-form-label" for="grade">
                پایه تحصیلی
                <span class="text-destructive">*</span>
            </label>

            <select
                class="kt-select mt-2 @error('grade') border-destructive @enderror"
                id="grade"
                name="grade"
                required
            >
                <option value="">لطفاً پایه تحصیلی را انتخاب کنید</option>
                <option value="10" {{ (string) old('grade', $student?->grade) === '10' ? 'selected' : '' }}>پایه دهم</option>
                <option value="11" {{ (string) old('grade', $student?->grade) === '11' ? 'selected' : '' }}>پایه یازدهم</option>
                <option value="12" {{ (string) old('grade', $student?->grade) === '12' ? 'selected' : '' }}>پایه دوازدهم</option>
            </select>

            @error('grade')
                <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
            @enderror
        </div>

    </div>

</section>


{{-- اطلاعات پدر --}}
<section class="kt-card">

    <div class="kt-card-header">
        <h2 class="kt-card-title">
            اطلاعات پدر
        </h2>
    </div>

    <div class="kt-card-content flex flex-col gap-5">

        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="font-medium text-mono">
                آیا پدر دانش‌آموز در قید حیات هستند؟
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="father_alive" value="0">

                <input
                    class="kt-checkbox"
                    id="father_alive"
                    name="father_alive"
                    type="checkbox"
                    value="1"
                    data-parent-toggle="#father_fields"
                    @checked($fatherAlive)
                >

                <span class="text-sm text-secondary-foreground">
                    بله، پدر در قید حیات است
                </span>
            </label>
        </div>

        <div
            id="father_fields"
            data-parent-fields
            class="grid gap-5 lg:grid-cols-2 {{ $fatherAlive ? '' : 'hidden' }}"
        >

            {{-- نام پدر --}}
            <div>
                <label class="kt-form-label" for="father_first_name">
                    نام
                    <span class="text-destructive">*</span>
                </label>

                <input
                    class="kt-input mt-2 @error('father_first_name') border-destructive @enderror"
                    id="father_first_name"
                    name="father_first_name"
                    type="text"
                    maxlength="255"
                    value="{{ old('father_first_name', $father?->first_name) }}"
                >

                @error('father_first_name')
                    <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
                @enderror
            </div>

            {{-- نام خانوادگی پدر --}}
            <div>
                <label class="kt-form-label" for="father_last_name">
                    نام خانوادگی
                    <span class="text-destructive">*</span>
                </label>

                <input
                    class="kt-input mt-2 @error('father_last_name') border-destructive @enderror"
                    id="father_last_name"
                    name="father_last_name"
                    type="text"
                    maxlength="255"
                    value="{{ old('father_last_name', $father?->last_name) }}"
                >

                @error('father_last_name')
                    <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
                @enderror
            </div>

            {{-- کد ملی پدر --}}
            <div>
                <label class="kt-form-label" for="father_national_code">
                    کد ملی
                    <span class="text-destructive">*</span>
                </label>

                <input
                    class="kt-input mt-2 @error('father_national_code') border-destructive @enderror"
                    dir="ltr"
                    id="father_national_code"
                    name="father_national_code"
                    type="text"
                    maxlength="10"
                    value="{{ old('father_national_code', $father?->national_code) }}"
                >

                @error('father_national_code')
                    <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
                @enderror
            </div>

            {{-- شماره تلفن پدر --}}
            <div>
                <label class="kt-form-label" for="father_phone">
                    شماره تلفن
                </label>

                <input
                    class="kt-input mt-2 @error('father_phone') border-destructive @enderror"
                    dir="ltr"
                    id="father_phone"
                    name="father_phone"
                    type="text"
                    maxlength="20"
                    value="{{ old('father_phone', $father?->phone) }}"
                >

                <p class="flex items-start gap-1.5 mt-2 text-xs text-secondary-foreground">
                    <i class="ki-filled ki-information-2 mt-0.5"></i>
                    <span>این شماره تلفن برای ورود ولی دانش‌آموز به پنل کاربری وب‌سایت استفاده می‌شود.</span>
                </p>

                @error('father_phone')
                    <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
                @enderror
            </div>

        </div>

    </div>

</section>


{{-- اطلاعات مادر --}}
<section class="kt-card">

    <div class="kt-card-header">
        <h2 class="kt-card-title">
            اطلاعات مادر
        </h2>
    </div>

    <div class="kt-card-content flex flex-col gap-5">

        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="font-medium text-mono">
                آیا مادر دانش‌آموز در قید حیات هستند؟
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="mother_alive" value="0">

                <input
                    class="kt-checkbox"
                    id="mother_alive"
                    name="mother_alive"
                    type="checkbox"
                    value="1"
                    data-parent-toggle="#mother_fields"
                    @checked($motherAlive)
                >

                <span class="text-sm text-secondary-foreground">
                    بله، مادر در قید حیات است
                </span>
            </label>
        </div>

        <div
            id="mother_fields"
            data-parent-fields
            class="grid gap-5 lg:grid-cols-2 {{ $motherAlive ? '' : 'hidden' }}"
        >

            {{-- نام مادر --}}
            <div>
                <label class="kt-form-label" for="mother_first_name">
                    نام
                    <span class="text-destructive">*</span>
                </label>

                <input
                    class="kt-input mt-2 @error('mother_first_name') border-destructive @enderror"
                    id="mother_first_name"
                    name="mother_first_name"
                    type="text"
                    maxlength="255"
                    value="{{ old('mother_first_name', $mother?->first_name) }}"
                >

                @error('mother_first_name')
                    <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
                @enderror
            </div>

            {{-- نام خانوادگی مادر --}}
            <div>
                <label class="kt-form-label" for="mother_last_name">
                    نام خانوادگی
                    <span class="text-destructive">*</span>
                </label>

                <input
                    class="kt-input mt-2 @error('mother_last_name') border-destructive @enderror"
                    id="mother_last_name"
                    name="mother_last_name"
                    type="text"
                    maxlength="255"
                    value="{{ old('mother_last_name', $mother?->last_name) }}"
                >

                @error('mother_last_name')
                    <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
                @enderror
            </div>

            {{-- کد ملی مادر --}}
            <div>
                <label class="kt-form-label" for="mother_national_code">
                    کد ملی
                    <span class="text-destructive">*</span>
                </label>

                <input
                    class="kt-input mt-2 @error('mother_national_code') border-destructive @enderror"
                    dir="ltr"
                    id="mother_national_code"
                    name="mother_national_code"
                    type="text"
                    maxlength="10"
                    value="{{ old('mother_national_code', $mother?->national_code) }}"
                >

                @error('mother_national_code')
                    <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
                @enderror
            </div>

            {{-- شماره تلفن مادر --}}
            <div>
                <label class="kt-form-label" for="mother_phone">
                    شماره تلفن
                </label>

                <input
                    class="kt-input mt-2 @error('mother_phone') border-destructive @enderror"
                    dir="ltr"
                    id="mother_phone"
                    name="mother_phone"
                    type="text"
                    maxlength="20"
                    value="{{ old('mother_phone', $mother?->phone) }}"
                >

                <p class="flex items-start gap-1.5 mt-2 text-xs text-secondary-foreground">
                    <i class="ki-filled ki-information-2 mt-0.5"></i>
                    <span>این شماره تلفن برای ورود ولی دانش‌آموز به پنل کاربری وب‌سایت استفاده می‌شود.</span>
                </p>

                @error('mother_phone')
                    <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
                @enderror
            </div>

        </div>

    </div>

</section>


<div class="flex items-center justify-end gap-3 mt-5">

    <a
        class="kt-btn kt-btn-outline"
        href="{{ route('admin.students.index') }}"
    >
        <i class="ki-filled ki-arrow-right"></i>
        انصراف
    </a>

    <button class="kt-btn kt-btn-primary" type="submit">
        <i class="ki-filled ki-check"></i>

        {{ $student ? 'ذخیره تغییرات' : 'ذخیره اطلاعات' }}
    </button>

</div>
