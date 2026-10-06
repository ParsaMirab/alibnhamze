@php
    $user = auth()->user();
    use App\Enums\RankEnum;
@endphp
<aside class="admin-sidebar flex-col fixed top-0 bottom-0 start-0 z-20 hidden lg:flex items-stretch shrink-0 w-(--sidebar-width) dark [--kt-drawer-enable:true] lg:[--kt-drawer-enable:false]" id="sidebar">
    <div class="flex flex-col gap-2.5" id="sidebar_header">
        <div class="flex items-center gap-2.5 px-4 h-[70px]">
            <p class="text-white bold">علی بن حمزه</p>
            <div class="text-lg font-semibold text-inverse">مدیریت هنرستان</div>
        </div>
    </div>

    <div class="flex grow items-stretch justify-center my-5 kt-scrollable-y-auto" id="sidebar_menu">
        <div class="grow">
            <div class="mb-5">
                <h3 class="text-sm text-muted-foreground px-5 mb-3">داشبورد</h3>
                <nav class="kt-menu flex flex-col w-full gap-1.5 px-3.5" data-kt-menu="true">
                    <div class="kt-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md kt-menu-item-active:bg-accent/60 kt-menu-link-hover:bg-accent/60" href="{{ route('admin.dashboard') }}">
                            <span class="kt-menu-icon text-lg text-secondary-foreground">
                                <i class="ki-filled ki-home-3"></i>
                            </span>
                            <span class="kt-menu-title text-sm text-foreground font-medium">داشبورد</span>
                        </a>
                    </div>
                </nav>
            </div>
            @if($user->rank === RankEnum::MANAGER->value)
                <div class="mb-5">
                    <h3 class="text-sm text-muted-foreground px-5 mb-3">مدیریت معاون ها</h3>
                    <nav class="kt-menu flex flex-col w-full gap-1.5 px-3.5" data-kt-menu="true">
                        <div class="kt-menu-item {{ request()->routeIs('admin.admin-management.*') ? 'active' : '' }}">
                            <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md kt-menu-item-active:bg-accent/60 kt-menu-link-hover:bg-accent/60" href="{{ route('admin.admin-management.index') }}">
                            <span class="kt-menu-icon text-lg text-secondary-foreground">
                                <i class="ki-filled ki-security-user"></i>
                            </span>
                                <span class="kt-menu-title text-sm text-foreground font-medium">مدیریت معاون ها</span>
                            </a>
                        </div>
                    </nav>
                </div>
            @endif
        </div>
    </div>

    <div class="shrink-0 px-3.5 pb-4 mt-auto" id="sidebar_footer">
        <div class="rounded-xl bg-white/5 ring-1 ring-white/10 p-2.5
                flex items-center gap-3">
            {{-- اطلاعات کاربر --}}
            <div class="min-w-0 flex-1">
                <div class="truncate text-white text-sm font-semibold">
                    {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'کاربر' }}
                </div>
                <div class="truncate text-[11px] text-white mt-0.5">
                    {{ \App\Enums\RankEnum::from($user->rank)->label() }}
                    @if($user->phone)
                        · <span dir="ltr">{{ $user->phone }}</span>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-dim" type="submit" title="خروج" aria-label="خروج">
                    <i class="ki-filled ki-exit-left"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
