<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="rtl" lang="fa">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | پنل مدیریت هنرستان</title>
    <link rel="icon" href="{{ asset('admin/media/app/mini-logo-circle-success.svg') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/keenicons/styles.bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/metronic.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/admin.css') }}">
    @stack('styles')
</head>
<body class="admin-shell antialiased flex h-full text-base text-foreground bg-mono [--header-height:60px] [--sidebar-width:270px] lg:overflow-hidden">
    <div class="flex grow">
        <header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-mono h-(--header-height)" id="header">
            <div class="kt-container-fixed flex items-center justify-between gap-3">
                <a class="flex items-center gap-2 text-inverse font-semibold" href="{{ route('admin.dashboard') }}">
                    <img class="size-[34px]" src="{{ asset('admin/media/app/mini-logo-circle-success.svg') }}" alt="شغلستون">
                    <span>علی بن حمزه</span>
                </a>
                <button class="kt-btn kt-btn-icon kt-btn-dim hover:text-white -me-2" type="button" data-admin-sidebar-toggle aria-label="نمایش منو">
                    <i class="ki-filled ki-menu"></i>
                </button>
            </div>
        </header>

        <div class="flex flex-col lg:flex-row grow pt-(--header-height) lg:pt-0">
            @include('admin.partials.sidebar')

            <div class="flex flex-col grow lg:rounded-r-xl bg-background border border-input lg:ms-(--sidebar-width)">
                <div class="flex flex-col grow kt-scrollable-y-auto lg:[--kt-scrollbar-width:auto] pt-5" id="scrollable_content">
                    <main class="grow" role="main">
                        @yield('content')
                    </main>

                    <footer>
                        <div class="kt-container-fixed">
                            <div class="flex justify-center md:justify-start items-center py-5">
                                <div class="text-sm text-muted-foreground">علی بن حمزه</div>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
    <div class="admin-sidebar-backdrop" data-admin-sidebar-close></div>

    <script src="{{ asset('admin/js/core.bundle.js') }}"></script>
    <script src="{{ asset('admin/vendors/ktui/ktui.min.js') }}"></script>
    <script src="{{ asset('admin/js/admin.js') }}"></script>
    @stack('scripts')
</body>
</html>
