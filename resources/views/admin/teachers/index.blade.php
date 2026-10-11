@extends('admin.layouts.app')

@section('title', 'مدیریت معلمان')

@section('content')

    <div style="font-feature-settings: 'ss01';">

        {{-- Header --}}
        <div class="pb-5">
            <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">

                <div class="flex flex-col gap-1">
                    <h1 class="font-semibold text-xl text-mono">
                        مدیریت معلمان
                    </h1>

                    <div class="text-sm text-secondary-foreground">
                        ثبت، ویرایش و مدیریت اطلاعات معلمان
                    </div>
                </div>

                @can('create', \App\Models\Teacher::class)
                    <a class="kt-btn kt-btn-primary"
                       href="{{ route('admin.teachers.create') }}">
                        <i class="ki-filled ki-plus"></i>
                        افزودن معلم
                    </a>
                @endcan

            </div>
        </div>

        <div class="kt-container-fixed">

            {{-- Alerts --}}
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

            {{-- Teachers Card --}}
            <div class="kt-card kt-card-grid min-w-full">

                {{-- Card Header --}}
                <div class="kt-card-header py-5 flex-wrap gap-2">

                    <div class="flex items-center gap-2.5">
                        <h2 class="kt-card-title">
                            فهرست معلمان
                        </h2>

                        <span class="kt-badge kt-badge-outline">
                        {{ $teachers->total() }} مورد
                    </span>
                    </div>

                    {{-- Search --}}
                    <form class="flex items-center gap-2 w-full sm:w-auto"
                          method="GET"
                          action="{{ route('admin.teachers.index') }}">

                        <label class="kt-input w-full sm:w-80">
                            <i class="ki-filled ki-magnifier"></i>

                            <input
                                name="search"
                                placeholder="جستجو در نام، نام خانوادگی، کد ملی یا تلفن"
                                type="search"
                                value="{{ $search ?? '' }}"
                            >
                        </label>

                        <input type="hidden"
                               name="per_page"
                               value="{{ $perPage ?? 10 }}">

                        <button class="kt-btn kt-btn-outline" type="submit">
                            جستجو
                        </button>

                        @if (($search ?? '') !== '')
                            <a
                                class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost"
                                href="{{ route('admin.teachers.index') }}"
                                title="پاک‌کردن جستجو"
                            >
                                <i class="ki-filled ki-cross"></i>
                            </a>
                        @endif

                    </form>

                </div>

                {{-- Card Content --}}
                <div class="kt-card-content">

                    <div class="grid">

                        <div class="kt-scrollable-x-auto">

                            <table class="kt-table kt-table-border">

                                <thead>
                                <tr>
                                    <th class="w-[90px]">شناسه</th>
                                    <th class="min-w-[200px]">نام و نام خانوادگی</th>
                                    <th class="min-w-[140px]">کد ملی</th>
                                    <th class="min-w-[150px]">شماره تلفن</th>
                                    <th class="w-[80px]"></th>
                                </tr>
                                </thead>

                                <tbody>

                                @forelse ($teachers as $teacher)

                                    <tr>

                                        {{-- ID --}}
                                        <td class="font-medium text-mono">
                                            #{{ $teacher->id }}
                                        </td>

                                        {{-- Full Name --}}
                                        <td>
                                        <span class="font-medium text-sm text-mono">
                                            {{ $teacher->first_name }}
                                            {{ $teacher->last_name }}
                                        </span>
                                        </td>

                                        {{-- National Code --}}
                                        <td class="text-secondary-foreground"
                                            dir="ltr">
                                            {{ $teacher->national_code }}
                                        </td>

                                        {{-- Phone --}}
                                        <td class="text-secondary-foreground"
                                            dir="ltr">
                                            {{ $teacher->phone ?: '—' }}
                                        </td>

                                        {{-- Actions --}}
                                        <td class="w-[80px]">

                                            <div class="kt-menu"
                                                 data-kt-menu="true">

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

                                                        {{-- Edit --}}
                                                        @can('update', $teacher)
                                                            <div class="kt-menu-item">
                                                                <a
                                                                    class="kt-menu-link"
                                                                    href="{{ route('admin.teachers.edit', $teacher) }}"
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

                                                        {{-- Delete --}}
                                                        @can('delete', $teacher)

                                                            <div class="kt-menu-separator"></div>

                                                            <div class="kt-menu-item">

                                                                <form
                                                                    class="w-full"
                                                                    method="POST"
                                                                    action="{{ route('admin.teachers.destroy', $teacher) }}"
                                                                    data-confirm-delete="آیا از حذف این معلم مطمئن هستید؟"
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
                                        <td colspan="5">
                                            <div class="flex flex-col items-center justify-center gap-3 py-14 text-center">

                                            <span class="flex size-12 items-center justify-center rounded-full bg-primary/10 text-primary text-xl">
                                                <i class="ki-filled ki-profile-user"></i>
                                            </span>

                                                <div class="font-medium text-mono">
                                                    معلمی پیدا نشد
                                                </div>

                                                <p class="text-sm text-secondary-foreground">
                                                    @if (($search ?? '') !== '')
                                                        معلمی با مشخصات جستجوشده پیدا نشد.
                                                    @else
                                                        هنوز هیچ معلمی ثبت نشده است.
                                                    @endif
                                                </p>

                                            </div>
                                        </td>
                                    </tr>

                                @endforelse

                                </tbody>

                            </table>

                        </div>

                        {{-- Pagination --}}
                        <div class="kt-card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-secondary-foreground text-sm font-medium">

                            <form class="flex items-center gap-2 order-2 md:order-1"
                                  method="GET"
                                  action="{{ route('admin.teachers.index') }}">

                            <span>
                                نمایش
                            </span>

                                <select
                                    class="kt-select w-20"
                                    name="per_page"
                                    onchange="this.form.submit()"
                                >
                                    @foreach ([10, 25, 50] as $size)
                                        <option
                                            value="{{ $size }}"
                                            {{ (int) ($perPage ?? 10) === $size ? 'selected' : '' }}
                                        >
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>

                                <span>
                                در هر صفحه
                            </span>

                                <input
                                    type="hidden"
                                    name="search"
                                    value="{{ $search ?? '' }}"
                                >

                            </form>

                            <div class="flex items-center gap-4 order-1 md:order-2">

                            <span>
                                {{ $teachers->total() }} مورد
                            </span>

                                @include('admin.partials.pagination', [
                                    'paginator' => $teachers
                                ])

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
