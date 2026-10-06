@extends('admin.layouts.app')

@section('title', 'مدیریت معاونین')

@section('content')

    <div style="font-feature-settings: 'ss01';">

        <div class="pb-5">
            <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">

                <div class="flex flex-col gap-1">
                    <h1 class="font-semibold text-xl text-mono">
                        مدیریت معاونین
                    </h1>

                    <div class="text-sm text-secondary-foreground">
                        مدیریت معاونین پنل
                    </div>
                </div>

                <a class="kt-btn kt-btn-primary"
                   href="{{ route('admin.admin-management.create') }}">
                    <i class="ki-filled ki-plus"></i>
                    افزودن معاون
                </a>

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
                            فهرست معاونین
                        </h2>

                        <span class="kt-badge kt-badge-outline">
                        {{ $deputies->total() }} مورد
                    </span>
                    </div>

                    <form class="flex items-center gap-2 w-full sm:w-auto"
                          method="GET"
                          action="{{ route('admin.admin-management.index') }}">

                        <label class="kt-input w-full sm:w-72">
                            <i class="ki-filled ki-magnifier"></i>

                            <input
                                name="search"
                                placeholder="جستجو در نام، کد ملی یا موبایل"
                                type="search"
                                value="{{ $search ?? '' }}"
                            >
                        </label>

                        <button class="kt-btn kt-btn-outline" type="submit">
                            جستجو
                        </button>

                        @if (($search ?? '') !== '')
                            <a
                                class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost"
                                href="{{ route('admin.admin-management.index') }}"
                                title="پاک‌کردن جستجو"
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
                                        نام و نام خانوادگی
                                    </th>

                                    <th class="min-w-[160px]">
                                        کد ملی
                                    </th>

                                    <th class="min-w-[150px]">
                                        شماره موبایل
                                    </th>

                                    <th class="min-w-[120px]">
                                        نقش
                                    </th>

                                    <th class="min-w-[130px]">
                                        تاریخ ایجاد
                                    </th>

                                    <th class="w-[80px]"></th>

                                </tr>
                                </thead>


                                <tbody>

                                @forelse ($deputies as $deputy)

                                    <tr>

                                        <td class="font-medium text-mono">
                                            #{{ $deputy->id }}
                                        </td>


                                        <td>
                                        <span class="font-medium text-sm text-mono">
                                            {{ $deputy->first_name }}
                                            {{ $deputy->last_name }}
                                        </span>
                                        </td>


                                        <td class="text-secondary-foreground" dir="ltr">
                                            {{ $deputy->national_code }}
                                        </td>


                                        <td class="text-secondary-foreground" dir="ltr">
                                            {{ $deputy->phone ?: '—' }}
                                        </td>


                                        <td>
                                        <span class="kt-badge kt-badge-light kt-badge-primary">
                                            معاون
                                        </span>
                                        </td>


                                        <td>
                                            <div class="flex flex-col gap-0.5">
                                            <span>
                                                {{ $deputy->created_at?->format('Y/m/d') }}
                                            </span>

                                                <span class="text-xs text-secondary-foreground">
                                                {{ $deputy->created_at?->format('H:i') }}
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

                                                        <div class="kt-menu-item">

                                                            <a
                                                                class="kt-menu-link"
                                                                href="{{ route('admin.admin-management.edit', $deputy->id) }}"
                                                            >
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil"></i>
                                                            </span>

                                                                <span class="kt-menu-title">
                                                                ویرایش
                                                            </span>
                                                            </a>

                                                        </div>


                                                        <div class="kt-menu-separator"></div>


                                                        <div class="kt-menu-item">

                                                            <form
                                                                class="w-full"
                                                                method="POST"
                                                                action="{{ route('admin.admin-management.destroy', $deputy->id) }}"
                                                                data-confirm-delete="آیا از حذف این معاون مطمئن هستید؟"
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
                                                <i class="ki-filled ki-people"></i>
                                            </span>

                                                <div class="font-medium text-mono">
                                                    معاون‌ای پیدا نشد
                                                </div>

                                                <p class="text-sm text-secondary-foreground">
                                                    هنوز هیچ معاون‌ای ایجاد نشده است.
                                                </p>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                                </tbody>

                            </table>

                        </div>


                        <div class="kt-card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-secondary-foreground text-sm font-medium">

                            <div class="flex items-center gap-2 order-2 md:order-1">
                            <span>
                                نمایش
                            </span>

                                <select class="kt-select w-20">
                                    <option>10</option>
                                    <option>25</option>
                                    <option>50</option>
                                </select>

                                <span>
                                در هر صفحه
                            </span>
                            </div>


                            <div class="flex items-center gap-4 order-1 md:order-2">

                            <span>
                                {{ $deputies->total() }} مورد
                            </span>

                                @include('admin.partials.pagination', ['paginator' => $deputies])

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
