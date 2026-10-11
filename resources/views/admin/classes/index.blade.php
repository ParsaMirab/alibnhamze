@extends('admin.layouts.app')

@section('title', 'مدیریت کلاس‌ها')

@section('content')

<div style="font-feature-settings: 'ss01';">

    <div class="pb-5">
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">

            <div class="flex flex-col gap-1">
                <h1 class="font-semibold text-xl text-mono">
                    مدیریت کلاس‌ها
                </h1>

                <div class="text-sm text-secondary-foreground">
                    مدیریت کلاس‌های آموزشی
                </div>
            </div>

            @can('create', \App\Models\SchoolClass::class)
                <a class="kt-btn kt-btn-primary"
                   href="{{ route('admin.classes.create') }}">
                    <i class="ki-filled ki-plus"></i>
                    افزودن کلاس
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
                        فهرست کلاس‌ها
                    </h2>

                    <span class="kt-badge kt-badge-outline">
                        {{ $classes->total() }} مورد
                    </span>
                </div>

                <form class="flex items-center gap-2 w-full sm:w-auto"
                      method="GET"
                      action="{{ route('admin.classes.index') }}">

                    <label class="kt-input w-full sm:w-72">
                        <i class="ki-filled ki-magnifier"></i>

                        <input
                            name="search"
                            placeholder="جستجو در عنوان کلاس"
                            type="search"
                            value="{{ $search ?? '' }}"
                        >
                    </label>

                    <label class="kt-input w-full sm:w-48">
                        <select name="grade" class="kt-select">
                            <option value="">همه پایه‌ها</option>
                            <option value="10" {{ $gradeFilter == 10 ? 'selected' : '' }}>پایه دهم</option>
                            <option value="11" {{ $gradeFilter == 11 ? 'selected' : '' }}>پایه یازدهم</option>
                            <option value="12" {{ $gradeFilter == 12 ? 'selected' : '' }}>پایه دوازدهم</option>
                        </select>
                    </label>

                    <button class="kt-btn kt-btn-outline" type="submit">
                        فیلتر
                    </button>

                    @if (($search ?? '') !== '' || ($gradeFilter ?? '') !== '')
                        <a
                            class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost"
                            href="{{ route('admin.classes.index') }}"
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

                                <th class="w-[90px]">
                                    شناسه
                                </th>

                                <th class="min-w-[180px]">
                                    عنوان کلاس
                                </th>

                                <th class="min-w-[80px]">
                                    سطح
                                </th>

                                <th class="min-w-[120px]">
                                    تعداد معلمان
                                </th>

                                <th class="min-w-[120px]">
                                    تعداد دانش‌آموزان
                                </th>

                                <th class="min-w-[130px]">
                                    تاریخ ایجاد
                                </th>

                                <th class="w-[80px]"></th>

                            </tr>
                            </thead>


                            <tbody>

                                @forelse ($classes as $class)

                                    <tr>

                                        <td class="font-medium text-mono">
                                            #{{ $class->id }}
                                        </td>

                                        <td>
                                            <span class="font-medium text-sm text-mono">
                                                {{ $class->title }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="font-medium text-sm text-mono">
                                                @if($class->grade == 10)
                                                    پایه دهم
                                                @elseif($class->grade == 11)
                                                    پایه یازدهم
                                                @elseif($class->grade == 12)
                                                    پایه دوازدهم
                                                @endif
                                            </span>
                                        </td>

                                        <td class="text-secondary-foreground">
                                            {{ $class->teacher_assignments_count }}
                                        </td>

                                        <td class="text-secondary-foreground">
                                            {{ $class->student_assignments_count }}
                                        </td>

                                        <td>
                                            <div class="flex flex-col gap-0.5">
    <span>
        {{ \App\Helpers\Jalali::format($class->created_at) }}
    </span>

                                                <span class="text-xs text-secondary-foreground" dir="ltr">
        {{ $class->created_at?->format('H:i') ?? '—' }}
    </span>
                                            </div>
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

                                                        @can('view', $class)
                                                            <div class="kt-menu-item">
                                                                <a
                                                                    class="kt-menu-link"
                                                                    href="{{ route('admin.classes.show', $class->id) }}"
                                                                >
                                                                    <span class="kt-menu-icon">
                                                                        <i class="ki-filled ki-eye"></i>
                                                                    </span>

                                                                    <span class="kt-menu-title">
                                                                        مشاهده
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('update', $class)
                                                            <div class="kt-menu-item">
                                                                <a
                                                                    class="kt-menu-link"
                                                                    href="{{ route('admin.classes.edit', $class->id) }}"
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

                                                        @can('delete', $class)
                                                            <div class="kt-menu-separator"></div>

                                                            <div class="kt-menu-item">
                                                                <form
                                                                    class="w-full"
                                                                    method="POST"
                                                                    action="{{ route('admin.classes.destroy', $class->id) }}"
                                                                    data-confirm-delete="آیا از حذف این کلاس مطمئن هستید؟"
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

                                        <td colspan="7">

                                            <div class="flex flex-col items-center justify-center gap-3 py-14 text-center">

                                                <span class="flex size-12 items-center justify-center rounded-full bg-primary/10 text-primary text-xl">
                                                    <i class="ki-filled ki-school"></i>
                                                </span>

                                                <div class="font-medium text-mono">
                                                    کلاسی پیدا نشد
                                                </div>

                                                <p class="text-sm text-secondary-foreground">
                                                    هنوز هیچ کلاسی ایجاد نشده است.
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
                              action="{{ route('admin.classes.index') }}">
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
                                {{ $classes->total() }} مورد
                            </span>

                            @include('admin.partials.pagination', ['paginator' => $classes])

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
