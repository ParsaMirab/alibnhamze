
@php
    use App\Enums\RankEnum;

    $user = auth()->user();
@endphp

<aside
    id="sidebar"
    class="admin-sidebar flex-col fixed top-0 bottom-0 start-0 z-20
           hidden lg:flex items-stretch shrink-0 w-(--sidebar-width)
           dark [--kt-drawer-enable:true] lg:[--kt-drawer-enable:false]"
>

    {{-- ================= HEADER ================= --}}
    <div class="shrink-0 border-b border-white/10" id="sidebar_header">
        <div class="flex items-center gap-3 px-5 h-[76px]">

            <div class="flex flex-col gap-1 min-w-0">
                <div class="text-white text-base font-bold">
                    علی بن حمزه
                </div>

                <div class="text-sm text-white/70">
                    مدیریت هنرستان
                </div>
            </div>

        </div>
    </div>

    {{-- ================= MENU ================= --}}
    <div
        class="flex grow items-stretch my-5 kt-scrollable-y-auto"
        id="sidebar_menu"
    >
        <div class="grow min-w-0">

            {{-- Dashboard --}}
            <div class="mb-7">

                <h3 class="text-xs font-semibold text-muted-foreground px-5 mb-3">
                    عمومی
                </h3>

                <nav
                    class="kt-menu flex flex-col w-full gap-1.5 px-3.5"
                    data-kt-menu="true"
                >

                    <div class="kt-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a
                            class="kt-menu-link gap-2.5 py-2.5 px-3 rounded-md
                                   kt-menu-item-active:bg-accent/60
                                   kt-menu-link-hover:bg-accent/60"
                            href="{{ route('admin.dashboard') }}"
                        >
                            <span class="kt-menu-icon text-lg text-secondary-foreground">
                                <i class="ki-filled ki-home-3"></i>
                            </span>

                            <span class="kt-menu-title text-sm text-foreground font-medium">
                                داشبورد
                            </span>
                        </a>
                    </div>

                </nav>
            </div>

            {{-- Management --}}
            @php
                $canManageDeputies = $user->rank === RankEnum::MANAGER->value;
                $canViewClasses = $user->can('viewAny', \App\Models\SchoolClass::class);
                $canViewStudents = $user->can('viewAny', \App\Models\Student::class);
                $canViewTeachers = $user->can('viewAny', \App\Models\Teacher::class);
            @endphp

            @if($canManageDeputies || $canViewClasses || $canViewStudents || $canViewTeachers)

                <div class="mb-5">

                    <h3 class="text-xs font-semibold text-muted-foreground px-5 mb-3">
                        مدیریت هنرستان
                    </h3>

                    <nav
                        class="kt-menu flex flex-col w-full gap-1.5 px-3.5"
                        data-kt-menu="true"
                    >

                        {{-- Deputy Management --}}
                        @if($canManageDeputies)

                            <div class="kt-menu-item {{ request()->routeIs('admin.admin-management.*') ? 'active' : '' }}">
                                <a
                                    class="kt-menu-link gap-2.5 py-2.5 px-3 rounded-md
                                           kt-menu-item-active:bg-accent/60
                                           kt-menu-link-hover:bg-accent/60"
                                    href="{{ route('admin.admin-management.index') }}"
                                >
                                    <span class="kt-menu-icon text-lg text-secondary-foreground">
                                        <i class="ki-filled ki-security-user"></i>
                                    </span>

                                    <span class="kt-menu-title text-sm text-foreground font-medium">
                                        مدیریت معاون‌ها
                                    </span>
                                </a>
                            </div>

                        @endif

                        {{-- Classes --}}
                        @if($canViewClasses)

                            <div class="kt-menu-item {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                                <a
                                    class="kt-menu-link gap-2.5 py-2.5 px-3 rounded-md
                                           kt-menu-item-active:bg-accent/60
                                           kt-menu-link-hover:bg-accent/60"
                                    href="{{ route('admin.classes.index') }}"
                                >
                                    <span class="kt-menu-icon text-lg text-secondary-foreground">
                                        <i class="ki-filled ki-people"></i>
                                    </span>

                                    <span class="kt-menu-title text-sm text-foreground font-medium">
                                        مدیریت کلاس‌ها
                                    </span>
                                </a>
                            </div>

                        @endif

                        {{-- Students --}}
                        @if($canViewStudents)

                            <div class="kt-menu-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                                <a
                                    class="kt-menu-link gap-2.5 py-2.5 px-3 rounded-md
                                           kt-menu-item-active:bg-accent/60
                                           kt-menu-link-hover:bg-accent/60"
                                    href="{{ route('admin.students.index') }}"
                                >
                                    <span class="kt-menu-icon text-lg text-secondary-foreground">
                                        <i class="ki-filled ki-users"></i>
                                    </span>

                                    <span class="kt-menu-title text-sm text-foreground font-medium">
                                        مدیریت دانش‌آموزان
                                    </span>
                                </a>
                            </div>

                        @endif

                        {{-- Teachers --}}
                        @if($canViewTeachers)

                            <div class="kt-menu-item {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                                <a
                                    class="kt-menu-link gap-2.5 py-2.5 px-3 rounded-md
                                           kt-menu-item-active:bg-accent/60
                                           kt-menu-link-hover:bg-accent/60"
                                    href="{{ route('admin.teachers.index') }}"
                                >
                                    <span class="kt-menu-icon text-lg text-secondary-foreground">
                                        <i class="ki-filled ki-teacher"></i>
                                    </span>

                                    <span class="kt-menu-title text-sm text-foreground font-medium">
                                        مدیریت معلمان
                                    </span>
                                </a>
                            </div>

                        @endif

                    </nav>

                </div>

            @endif

        </div>
    </div>

    {{-- ================= FOOTER ================= --}}
    <div
        class="shrink-0 px-3.5 pb-4 pt-3 mt-auto border-t border-white/10"
        id="sidebar_footer"
    >

        <div
            class="rounded-xl bg-white/5 ring-1 ring-white/10
                   p-3 flex items-center gap-3"
        >

            {{-- User Information --}}
            <div class="min-w-0 flex-1">

                <div class="truncate text-white text-sm font-semibold">
                    {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'کاربر' }}
                </div>

                <div class="truncate text-[11px] text-white mt-1 ">

                    {{ RankEnum::tryFrom($user->rank)?->label() ?? 'کاربر' }}

                    @if($user->phone)
                        · <span dir="ltr">{{ $user->phone }}</span>
                    @endif

                </div>

            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf

                <button
                    class="kt-btn kt-btn-sm kt-btn-icon kt-btn-dim"
                    type="submit"
                    title="خروج"
                    aria-label="خروج"
                >
                    <i class="ki-filled ki-exit-left"></i>
                </button>

            </form>

        </div>

    </div>

</aside>
