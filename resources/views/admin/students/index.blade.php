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

            <div class="kt-card-header flex-wrap gap-2 py-3.5 sm:py-5">

                <div class="min-w-0">
                    <h2 class="kt-card-title">
                        فهرست دانش‌آموزان
                    </h2>

                    <p class="mt-1 text-xs text-secondary-foreground">
                        جستجو بر اساس نام، نام خانوادگی، کد ملی یا شماره تلفن
                    </p>
                </div>

                <span class="kt-badge kt-badge-outline shrink-0">
                    {{ number_format($students->total()) }} مورد
                </span>

            </div>


            <div class="kt-card-content">

                {{-- Search & filter --}}
                <div class="border-b border-border p-3 sm:p-4">

                    <form class="flex flex-wrap items-center gap-2 w-full"
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

                        <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">

                        <button class="kt-btn kt-btn-primary w-full justify-center sm:w-auto" type="submit">
                            <i class="ki-filled ki-magnifier"></i>
                            فیلتر
                        </button>

                        @if (($search ?? '') !== '' || ($gradeFilter ?? '') !== '')
                            <a
                                class="kt-btn kt-btn-outline w-full justify-center sm:w-auto"
                                href="{{ route('admin.students.index') }}"
                            >
                                <i class="ki-filled ki-cross"></i>
                                حذف فیلتر
                            </a>
                        @endif

                    </form>

                </div>


                {{-- Mobile: stacked cards --}}
                <div class="space-y-3 p-3 sm:p-4 lg:hidden">

                    @forelse ($students as $student)

                        <article class="rounded-xl border border-border bg-background p-4">

                            <div class="flex items-start justify-between gap-3">

                                <div class="flex min-w-0 items-center gap-3">

                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                        <i class="ki-filled ki-profile-user text-lg"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="truncate text-sm font-semibold text-mono">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </h3>

                                        <p class="mt-1 text-[11px] text-secondary-foreground">
                                            کد ملی:
                                            <span dir="ltr" class="inline-block">
                                                {{ $student->national_code }}
                                            </span>
                                        </p>
                                    </div>

                                </div>

                                <span class="shrink-0 kt-badge kt-badge-outline text-mono">
                                    #{{ $student->id }}
                                </span>

                            </div>


                            <div class="my-3 grid grid-cols-2 gap-2 rounded-lg bg-muted/40 p-3">

                                <div>
                                    <p class="text-[11px] text-secondary-foreground">
                                        شماره تلفن
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-mono" dir="ltr">
                                        {{ $student->phone ?: '—' }}
                                    </p>
                                </div>

                                <div class="border-r border-border pr-3">
                                    <p class="text-[11px] text-secondary-foreground">
                                        پایه تحصیلی
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-mono">
                                        @if ($student->grade == 10)
                                            پایه دهم
                                        @elseif ($student->grade == 11)
                                            پایه یازدهم
                                        @elseif ($student->grade == 12)
                                            پایه دوازدهم
                                        @else
                                            —
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[11px] text-secondary-foreground">
                                        اطلاعات پدر
                                    </p>

                                    <div class="mt-1">
                                        @if ($student->father)
                                            <span class="kt-badge kt-badge-light kt-badge-primary">
                                                ثبت شده
                                            </span>
                                        @else
                                            <span class="kt-badge kt-badge-light kt-badge-secondary">
                                                ثبت نشده
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="border-r border-border pr-3">
                                    <p class="text-[11px] text-secondary-foreground">
                                        اطلاعات مادر
                                    </p>

                                    <div class="mt-1">
                                        @if ($student->mother)
                                            <span class="kt-badge kt-badge-light kt-badge-primary">
                                                ثبت شده
                                            </span>
                                        @else
                                            <span class="kt-badge kt-badge-light kt-badge-secondary">
                                                ثبت نشده
                                            </span>
                                        @endif
                                    </div>
                                </div>

                            </div>


                            <div class="flex items-center gap-2">

                                @can('update', $student)
                                    <a
                                        class="kt-btn kt-btn-sm kt-btn-outline flex-1 justify-center"
                                        href="{{ route('admin.students.edit', $student) }}"
                                    >
                                        <i class="ki-filled ki-pencil"></i>
                                        ویرایش
                                    </a>
                                @endcan

                                @can('delete', $student)
                                    <form
                                        class="flex-1"
                                        method="POST"
                                        action="{{ route('admin.students.destroy', $student) }}"
                                        data-confirm-delete="آیا از حذف این دانش‌آموز مطمئن هستید؟ والدین او حذف نخواهند شد."
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="kt-btn kt-btn-sm kt-btn-outline w-full justify-center text-destructive"
                                            type="submit"
                                        >
                                            <i class="ki-filled ki-trash"></i>
                                            حذف
                                        </button>

                                    </form>
                                @endcan

                            </div>

                        </article>

                    @empty

                        <div class="py-10 text-center">
                            <i class="ki-filled ki-profile-user text-3xl text-secondary-foreground"></i>

                            <p class="mt-3 text-sm font-semibold text-mono">
                                دانش‌آموزی پیدا نشد
                            </p>

                            <p class="mt-1 text-xs text-secondary-foreground">
                                @if (($search ?? '') !== '' || ($gradeFilter ?? '') !== '')
                                    دانش‌آموزی با مشخصات جستجوشده پیدا نشد.
                                @else
                                    هنوز هیچ دانش‌آموزی ثبت نشده است.
                                @endif
                            </p>
                        </div>

                    @endforelse

                </div>


                {{-- Desktop: table --}}
                <div class="hidden lg:block">

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
                                                @if (($search ?? '') !== '' || ($gradeFilter ?? '') !== '')
                                                    دانش‌آموزی با مشخصات جستجوشده پیدا نشد.
                                                @else
                                                    هنوز هیچ دانش‌آموزی ثبت نشده است.
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

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
                            {{ number_format($students->total()) }} مورد
                        </span>

                        @include('admin.partials.pagination', ['paginator' => $students])
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
