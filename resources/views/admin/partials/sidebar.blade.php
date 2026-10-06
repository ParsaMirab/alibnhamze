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
            <div class="mb-5">
                <h3 class="text-sm text-muted-foreground px-5 mb-3">مدیریت معاون ها</h3>
                <nav class="kt-menu flex flex-col w-full gap-1.5 px-3.5" data-kt-menu="true">
                    <div class="kt-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md kt-menu-item-active:bg-accent/60 kt-menu-link-hover:bg-accent/60" href="{{ route('admin.dashboard') }}">
                            <span class="kt-menu-icon text-lg text-secondary-foreground">
                                <i class="ki-filled ki-security-user"></i>
                            </span>
                            <span class="kt-menu-title text-sm text-foreground font-medium">مدیریت معاون ها</span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between shrink-0 px-4 mb-4" id="sidebar_footer">
        <div class="flex min-w-0 items-center gap-2.5">
            <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-success text-white font-semibold">

            </div>
            <div class="min-w-0">
                <div class="truncate text-sm font-medium text-inverse">admin phone</div>
                <div class="truncate max-w-36 text-xs text-muted-foreground">admin mail</div>
            </div>
        </div>
        <form method="POST" action="}">
            @csrf
            <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-dim" type="submit" title="خروج" aria-label="خروج">
                <i class="ki-filled ki-exit-left"></i>
            </button>
        </form>
    </div>
</aside>
