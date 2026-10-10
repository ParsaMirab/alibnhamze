@extends('admin.layouts.app')

@section('title', 'مدیریت دانش‌آموزان')

@section('content')

<div style="font-feature-settings: 'ss01';">

    <div class="pb-5">
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">

            <div class="flex flex-col gap-1">
                <h1 class="font-semibold text-xl text-mono">
                    مدیریت دانش‌آموزان
                </h1>

                <div class="text-sm text-secondary-foreground">
                    ثبت، ویرایش و مدیریت دانش‌آموزان و اطلاعات اولیا
                </div>
            </div>

            @can('create', \App\Models\Student::class)
                <a class="kt-btn kt-btn-primary"
                   href="{{ route('admin.students.create') }}">
                    <i class="ki-filled ki-plus"></i>
                    افزودن دانش‌آموز
                </a>
            @endcan

        </div>
    </div>


    <div class="kt-container-fixed">

        @if (session('success'))
            <div class="kt-alert kt-alert-success mb-5" role="status">
                <i class="ki-filled ki-check-circle text-lg"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="kt-alert kt-alert-destructive mb-5" role="alert">
                <i class="ki-filled ki-information-2 text-lg"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="kt-card kt-card-grid min-w-full">

            <div class="kt-card-header py-5 flex-wrap gap-2">

                <div class="flex items-center gap-2.5">
                    <h2 class="kt-card-title">
                        فهرست دانش‌آموزان
                    </h2>

                    <span class="kt-badge kt-badge-outline">
                        {{ $students->total() }} مورد
                    </span>
                </div>

                <form class="flex items-center gap-2 w-full sm:w-auto"
                      method="GET"
                      action="{{ route('admin.students.index') }}">

                    <label class="kt-input w-full sm:w-72">
                        <i class="ki-filled ki-magnifier"></i>

                        <input
                            name="search"
                            placeholder="جستجو در نام، نام خانوادگی، کد ملی یا تلفن"
                            type="search"
                            value="{{ $search ?? '' }}"
                        >
                    </label>

                    <label class="kt-input w-full sm:w-44">
                        <select name="grade" class="kt-select">
                            <option value="">همه پایه‌ها</option>
                            <option value="10" {{ (string) $gradeFilter === '10' ? 'selected' : '' }}>پایه دهم</option>
                            <option value="11" {{ (string) $gradeFilter === '11' ? 'selected' : '' }}>پایه یازدهم</option>
                            <option value="12" {{ (string) $gradeFilter === '12' ? 'selected' : '' }}>پایه دوازدهم</option>
                        </select>
                    </label>

                    <button class="kt-btn kt-btn-outline" type="submit">
                        فیلتر
                    </button>

                    @if (($search ?? '') !== '' || ($gradeFilter ?? '') !== '')
                        <a
                            class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost"
                            href="{{ route('admin.students.index') }}"
                            title="پاک‌کردن فیلترها"
                        >
                            <i class="ki-filled ki-cross"></i>
                        </a>
                    @endif

                </form>

            </div>


            <div class="kt-card-content">

                <div class="grid">

                    <div class="kt-scrollable-x-auto">

                        <table class="kt-table kt-table-border">

                            <thead>
                            <tr>
                                <th class="w-[90px]">شناسه</th>
                                <th class="min-w-[180px]">نام و نام خانوادگی</th>
                                <th class="min-w-[130px]">کد ملی</th>
                                <th class="min-w-[130px]">شماره تلفن</th>
                                <th class="min-w-[110px]">پایه تحصیلی</th>
                                <th class="min-w-[110px]">پدر</th>
                                <th class="min-w-[110px]">مادر</th>
                                <th class="w-[80px]"></th>
                            </tr>
                            </thead>

                            <tbody>

                            @forelse ($students as $student)

                                <tr>

                                    <td class="font-medium text-mono">
                                        #{{ $student->id }}
                                    </td>

                                    <td>
                                        <span class="font-medium text-sm text-mono">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </span>
                                    </td>

                                    <td class="text-secondary-foreground" dir="ltr">
                                        {{ $student->national_code }}
                                    </td>

                                    <td class="text-secondary-foreground" dir="ltr">
                                        {{ $student->phone ?: '—' }}
                                    </td>

                                    <td class="text-secondary-foreground">
                                        @if ($student->grade == 10)
                                            پایه دهم
                                        @elseif ($student->grade == 11)
                                            پایه یازدهم
                                        @elseif ($student->grade == 12)
                                            پایه دوازدهم
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td>
                                        @if ($student->father)
                                            <span class="kt-badge kt-badge-light kt-badge-primary" title="{{ $student->father->parent?->first_name }} {{ $student->father->parent?->last_name }}">
                                                ثبت شده
                                            </span>
                                        @else
                                            <span class="kt-badge kt-badge-light kt-badge-secondary">
                                                ثبت نشده
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($student->mother)
                                            <span class="kt-badge kt-badge-light kt-badge-primary" title="{{ $student->mother->parent?->first_name }} {{ $student->mother->parent?->last_name }}">
                                                ثبت شده
                                            </span>
                                        @else
                                            <span class="kt-badge kt-badge-light kt-badge-secondary">
                                                ثبت نشده
                                            </span>
                                        @endif
                                    </td>

                                    <td class="w-[80px]">

                                        <div class="kt-menu" data-kt-menu="true">

                                            <div
                                                class="kt-menu-item"
                                                data-kt-menu-item-offset="0, 10px"
                                                data-kt-menu-item-placement="bottom-end"
                                                data-kt-menu-item-placement-rtl="bottom-start"
                                                data-kt-menu-item-toggle="dropdown"
                                                data-kt-menu-item-trigger="click"
                                            >

                                                <button
                                                    class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost"
                                                    type="button"
                                                    aria-label="عملیات"
                                                >
                                                    <i class="ki-filled ki-dots-vertical text-lg"></i>
                                                </button>

                                                <div
                                                    class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]"
                                                    data-kt-menu-dismiss="true"
                                                >

                                                    @can('update', $student)
                                                        <div class="kt-menu-item">
                                                            <a
                                                                class="kt-menu-link"
                                                                href="{{ route('admin.students.edit', $student) }}"
                                                            >
                                                                <span class="kt-menu-icon">
                                                                    <i class="ki-filled ki-pencil"></i>
                                                                </span>

                                                                <span class="kt-menu-title">
                                                                    ویرایش
                                                                </span>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('delete', $student)
                                                        <div class="kt-menu-separator"></div>

                                                        <div class="kt-menu-item">
                                                            <form
                                                                class="w-full"
                                                                method="POST"
                                                                action="{{ route('admin.students.destroy', $student) }}"
                                                                data-confirm-delete="آیا از حذف این دانش‌آموز مطمئن هستید؟ والدین او حذف نخواهند شد."
                                                            >

                                                                @csrf
                                                                @method('DELETE')

                                                                <button
                                                                    class="kt-menu-link w-full text-destructive"
                                                                    type="submit"
                                                                >
                                                                    <span class="kt-menu-icon text-destructive">
                                                                        <i class="ki-filled ki-trash"></i>
                                                                    </span>

                                                                    <span class="kt-menu-title">
                                                                        حذف
                                                                    </span>
                                                                </button>

                                                            </form>
                                                        </div>
                                                    @endcan

                                                </div>

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8">
                                        <div class="flex flex-col items-center justify-center gap-3 py-14 text-center">
                                            <span class="flex size-12 items-center justify-center rounded-full bg-primary/10 text-primary text-xl">
                                                <i class="ki-filled ki-profile-user"></i>
                                            </span>

                                            <div class="font-medium text-mono">
                                                دانش‌آموزی پیدا نشد
                                            </div>

                                            <p class="text-sm text-secondary-foreground">
                                                هنوز هیچ دانش‌آموزی ثبت نشده است.
                                            </p>
                                        </div>
                                    </td>
                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                    <div class="kt-card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-secondary-foreground text-sm font-medium">

                        <form class="flex items-center gap-2 order-2 md:order-1"
                              method="GET"
                              action="{{ route('admin.students.index') }}">
                            <span>
                                نمایش
                            </span>

                            <select class="kt-select w-20" name="per_page" onchange="this.form.submit()">
                                @foreach ([10, 25, 50] as $size)
                                    <option value="{{ $size }}" {{ (int) ($perPage ?? 10) === $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>

                            <span>
                                در هر صفحه
                            </span>

                            <input type="hidden" name="search" value="{{ $search ?? '' }}">
                            <input type="hidden" name="grade" value="{{ $gradeFilter ?? '' }}">
                        </form>

                        <div class="flex items-center gap-4 order-1 md:order-2">
                            <span>
                                {{ $students->total() }} مورد
                            </span>

                            @include('admin.partials.pagination', ['paginator' => $students])
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
