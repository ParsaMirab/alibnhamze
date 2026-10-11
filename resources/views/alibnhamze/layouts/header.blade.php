
@php
    use App\Enums\RankEnum;

    $user = auth()->user();
    $isLoggedIn = auth()->check();

    $fullName = $isLoggedIn
        ? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))
        : '';

    $firstLetter = $isLoggedIn
        ? mb_substr($user->first_name ?: 'ک', 0, 1)
        : '';

    $isStudent = $isLoggedIn && $user->rank === RankEnum::STUDENT->value;

    // Change these names if your existing parent routes differ.
    $dashboardUrl = $isStudent
        ? url('/student/dashboard')
        : url('/parent/dashboard');

    $nav = [
        'صفحه اصلی',
        'درباره ما',
        'آموزش‌ها',
        'اخبار',
        'گالری',
        'تماس با ما',
    ];
@endphp

<header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-100">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="flex items-center justify-between h-16 sm:h-20">

            {{-- ================= LOGO ================= --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 min-w-0">

                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#0F3D62] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 14l6.16-3.42A12 12 0 0112 21a12 12 0 01-6.16-10.42L12 14z"/>
                    </svg>
                </div>

                <div class="min-w-0">
                    <p class="font-bold text-slate-900 text-sm sm:text-base leading-tight truncate">
                        هنرستان علی بن حمزه
                    </p>

                    <p class="text-[10px] sm:text-xs text-slate-500 truncate">
                        دانش، ایمان، آینده روشن
                    </p>
                </div>
            </a>

            {{-- ================= DESKTOP NAV ================= --}}
            <nav class="hidden lg:flex items-center gap-1">

                @foreach($nav as $i => $item)
                    <a href="#"
                       class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                              {{ $i === 0
                                  ? 'text-[#0F3D62] bg-slate-100'
                                  : 'text-slate-600 hover:text-[#0F3D62] hover:bg-slate-50' }}">
                        {{ $item }}
                    </a>
                @endforeach

            </nav>

            {{-- ================= DESKTOP ACTIONS ================= --}}
            <div class="hidden lg:flex items-center gap-3">

                @auth

                    {{-- User dropdown --}}
                    <div class="relative group">

                        <button type="button"
                                class="flex items-center gap-3 rounded-xl
                                       border border-slate-200 bg-white px-3 py-2
                                       hover:border-[#0F3D62]/30 hover:bg-slate-50
                                       transition-all">

                            {{-- Avatar --}}
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center
                                        rounded-full bg-[#0F3D62] text-white
                                        text-sm font-bold">
                                {{ $firstLetter }}
                            </div>

                            {{-- User info --}}
                            <div class="text-right">
                                <p class="text-xs text-slate-400">
                                    خوش آمدید
                                </p>

                                <p class="mt-0.5 max-w-36 truncate text-sm font-bold text-slate-800">
                                    {{ $fullName ?: 'کاربر' }}
                                </p>
                            </div>

                            <svg class="h-4 w-4 text-slate-400"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m6 9 6 6 6-6"/>
                            </svg>

                        </button>

                        {{-- Dropdown --}}
                        <div class="invisible absolute left-0 top-full z-50 w-56 pt-2
                                    opacity-0 translate-y-1
                                    transition-all duration-150
                                    group-hover:visible group-hover:opacity-100 group-hover:translate-y-0
                                    group-focus-within:visible group-focus-within:opacity-100 group-focus-within:translate-y-0">

                            <div class="overflow-hidden rounded-xl border border-slate-200
                                        bg-white p-2 shadow-xl shadow-slate-900/10">

                                <div class="border-b border-slate-100 px-3 py-3">
                                    <p class="text-sm font-bold text-slate-800">
                                        {{ $fullName ?: 'کاربر' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $isStudent ? 'دانش‌آموز' : 'ولی دانش‌آموز' }}
                                    </p>
                                </div>

                                <a href="{{ $dashboardUrl }}"
                                   class="mt-1 flex items-center gap-3 rounded-lg
                                          px-3 py-3 text-sm font-medium text-slate-700
                                          hover:bg-slate-50 hover:text-[#0F3D62]
                                          transition-colors">

                                    <svg class="h-5 w-5"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.7"
                                         viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                                        <path d="M8 9h8M8 13h8M8 17h5"/>
                                    </svg>

                                    پنل من
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <button type="submit"
                                            class="flex w-full items-center gap-3 rounded-lg
                                                   px-3 py-3 text-right text-sm font-medium
                                                   text-rose-600 hover:bg-rose-50
                                                   transition-colors">

                                        <svg class="h-5 w-5"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.7"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m16 17 5-5-5-5M21 12H9"/>
                                        </svg>

                                        خروج از حساب
                                    </button>
                                </form>

                            </div>

                        </div>

                    </div>

                @else

                    <a href="{{ route('login.showLoginForm') }}"
                       class="px-5 py-2.5 text-sm font-semibold rounded-lg
                              text-[#0F3D62] border border-slate-200
                              hover:border-[#0F3D62] transition-colors">
                        ورود به سامانه
                    </a>

                    <a href="{{route('pre-register')}}"
                       class="px-5 py-2.5 text-sm font-semibold rounded-lg
                              bg-[#0F3D62] text-white
                              hover:bg-[#0a2d4a] transition-colors">
                        ثبت‌نام
                    </a>

                @endauth

            </div>

            {{-- ================= MOBILE TOGGLE ================= --}}
            <button id="menu-toggle"
                    type="button"
                    aria-label="منو"
                    aria-controls="mobile-menu"
                    aria-expanded="false"
                    class="lg:hidden w-11 h-11 flex items-center justify-center
                           rounded-xl text-slate-700 hover:bg-slate-100
                           active:bg-slate-200 transition-colors shrink-0">

                <svg class="w-6 h-6"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>

            </button>

        </div>
    </div>

    {{-- ================= MOBILE MENU ================= --}}
    <div id="mobile-menu"
         class="hidden lg:hidden border-t border-slate-100 bg-white
                max-h-[calc(100vh-4rem)] overflow-y-auto">

        <nav class="px-4 py-4 flex flex-col gap-1">

            @auth
                {{-- Mobile user info --}}
                <div class="mb-3 flex items-center gap-3 rounded-xl
                            bg-slate-50 border border-slate-100 p-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center
                                rounded-full bg-[#0F3D62] text-white font-bold">
                        {{ $firstLetter }}
                    </div>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-slate-800">
                            {{ $fullName ?: 'کاربر' }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            {{ $isStudent ? 'دانش‌آموز' : 'ولی دانش‌آموز' }}
                        </p>
                    </div>

                </div>

                <a href="{{ $dashboardUrl }}"
                   class="mb-3 rounded-xl bg-[#0F3D62]/5 px-4 py-3.5
                          text-sm font-bold text-[#0F3D62]">
                    پنل من
                </a>
            @endauth

            @foreach($nav as $i => $item)
                <a href="#"
                   class="py-3.5 px-4 rounded-xl text-sm font-medium transition
                          {{ $i === 0
                              ? 'bg-slate-100 text-[#0F3D62]'
                              : 'text-slate-700 hover:bg-slate-50 hover:text-[#0F3D62]' }}">
                    {{ $item }}
                </a>
            @endforeach

            <div class="pt-4 mt-2 border-t border-slate-100">

                @auth

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="w-full rounded-xl border border-rose-200
                                       bg-rose-50 py-3 text-center
                                       text-sm font-semibold text-rose-600
                                       hover:bg-rose-100 transition-colors">
                            خروج از حساب کاربری
                        </button>
                    </form>

                @else

                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('login.showLoginForm') }}"
                           class="py-3 text-center text-sm font-semibold rounded-xl
                                  border border-slate-200 text-[#0F3D62]
                                  active:bg-slate-50">
                            ورود
                        </a>

                        <a href="#"
                           class="py-3 text-center text-sm font-semibold rounded-xl
                                  bg-[#0F3D62] text-white
                                  active:bg-[#0a2d4a]">
                            ثبت‌نام
                        </a>
                    </div>

                @endauth

            </div>

        </nav>
    </div>

</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        if (!menuToggle || !mobileMenu) return;

        function setMenuState(isOpen) {
            mobileMenu.classList.toggle('hidden', !isOpen);

            menuToggle.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );
        }

        // Open / close mobile menu
        menuToggle.addEventListener('click', function () {
            const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';

            setMenuState(!isOpen);
        });

        // Close when clicking a menu link
        mobileMenu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                setMenuState(false);
            });
        });

        // Close when pressing Escape
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                setMenuState(false);
            }
        });

        // Reset when switching to desktop layout
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024) {
                setMenuState(false);
            }
        });
    });
</script>
