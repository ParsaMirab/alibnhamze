
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

    $isStudent = $isLoggedIn
        && $user->rank === RankEnum::STUDENT->value;

    $dashboardUrl = $isStudent
        ? url('/student/dashboard')
        : url('/parent/dashboard');

    $homeUrl = route('home');

    $nav = [
        [
            'title' => 'صفحه اصلی',
            'url' => $homeUrl,
            'section' => 'school-home',
        ],
        [
            'title' => 'درباره ما',
            'url' => $homeUrl . '#ab-about',
            'section' => 'ab-about',
        ],
        [
            'title' => 'آموزش‌ها',
            'url' => $homeUrl . '#ab-programs',
            'section' => 'ab-programs',
        ],
        [
            'title' => 'اخبار',
            'url' => $homeUrl . '#ab-news',
            'section' => 'ab-news',
        ],
        [
            'title' => 'ثبت‌نام',
            'url' => $homeUrl . '#ab-admissions',
            'section' => 'ab-admissions',
        ],
        [
            'title' => 'تماس با ما',
            'url' => $homeUrl . '#ab-contact',
            'section' => 'ab-contact',
        ],
    ];
@endphp

@push('styles')
    <style>
        @media (prefers-reduced-motion: no-preference) {
            html {
                scroll-behavior: smooth;
            }
        }

        .school-header {
            direction: rtl;
        }

        .school-header .nav-link {
            position: relative;
            color: #64748b;
            transition: color .2s ease, background-color .2s ease;
        }

        .school-header .nav-link:hover {
            color: #0F3D62;
            background: #f8fafc;
        }

        .school-header .nav-link.is-active {
            color: #0F3D62;
            background: #f1f5f9;
            font-weight: 700;
        }

        .school-header .nav-link.is-active::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 50%;
            transform: translateX(50%);
            width: 18px;
            height: 2px;
            border-radius: 4px;
            background: #B59052;
        }

        .school-header .mobile-nav-link.is-active {
            color: #0F3D62;
            background: #f1f5f9;
            font-weight: 700;
        }

        .school-header .user-dropdown {
            opacity: 0;
            visibility: hidden;
            transform: translateY(6px);
            transition: opacity .18s ease,
            transform .18s ease,
            visibility .18s ease;
        }

        .school-header .user-dropdown.is-open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .school-header .mobile-menu {
            max-height: 0;
            overflow: hidden;
            visibility: hidden;
            transition: max-height .25s ease,
            visibility .25s ease;
        }

        .school-header .mobile-menu.is-open {
            max-height: calc(100dvh - 64px);
            overflow-y: auto;
            visibility: visible;
        }

        .school-header .header-action {
            transition: background-color .2s ease,
            border-color .2s ease,
            color .2s ease,
            transform .2s ease;
        }

        .school-header .header-action:hover {
            transform: translateY(-1px);
        }

        #ab-programs,
        #ab-about,
        #ab-news,
        #ab-admissions,
        #ab-contact {
            scroll-margin-top: 100px;
        }

        @media (min-width: 640px) {
            .school-header .mobile-menu.is-open {
                max-height: calc(100dvh - 80px);
            }
        }

        @media (min-width: 1024px) {
            .school-header .mobile-menu {
                display: none !important;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .school-header *,
            .school-header *::before,
            .school-header *::after {
                transition: none !important;
            }
        }
    </style>
@endpush

<header id="school-header"
        class="school-header sticky top-0 z-50 border-b border-slate-100 bg-white/95 backdrop-blur-xl">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">

        <div class="flex h-16 items-center justify-between gap-4 sm:h-20">

            {{-- ================= LOGO ================= --}}
            <a href="{{ $homeUrl }}"
               class="flex min-w-0 shrink-0 items-center gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#0F3D62] shadow-sm shadow-[#0F3D62]/15 sm:h-11 sm:w-11">
                    <svg class="h-6 w-6 text-white"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.7"
                              d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.7"
                              d="M12 14l6.16-3.42A12 12 0 0112 21a12 12 0 01-6.16-10.42L12 14z"/>
                    </svg>
                </div>

                <div class="min-w-0">
                    <p class="truncate text-sm font-extrabold leading-tight text-slate-900 sm:text-base">
                        هنرستان علی بن حمزه
                    </p>
                    <p class="mt-1 truncate text-[10px] text-slate-500 sm:text-xs">
                        دانش، ایمان، آینده روشن
                    </p>
                </div>

            </a>

            {{-- ================= DESKTOP NAV ================= --}}
            <nav class="hidden items-center gap-0.5 lg:flex"
                 aria-label="منوی اصلی">

                @foreach($nav as $item)
                    <a href="{{ $item['url'] }}"
                       data-section="{{ $item['section'] }}"
                       class="nav-link rounded-lg px-3 py-2.5 text-[13px] font-medium xl:px-4 xl:text-sm">
                        {{ $item['title'] }}
                    </a>
                @endforeach

            </nav>

            {{-- ================= DESKTOP ACTIONS ================= --}}
            <div class="hidden shrink-0 items-center gap-2 lg:flex">

                @auth
                    <div class="relative" id="user-dropdown-container">

                        <button type="button"
                                id="user-dropdown-toggle"
                                aria-expanded="false"
                                aria-controls="user-dropdown-menu"
                                class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2 transition hover:border-[#0F3D62]/30 hover:bg-slate-50">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#0F3D62] text-sm font-bold text-white">
                                {{ $firstLetter }}
                            </div>

                            <div class="max-w-36 text-right">
                                <p class="text-[11px] text-slate-400">
                                    خوش آمدید
                                </p>
                                <p class="mt-0.5 truncate text-sm font-bold text-slate-800">
                                    {{ $fullName ?: 'کاربر' }}
                                </p>
                            </div>

                            <svg class="h-4 w-4 text-slate-400"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>

                        {{-- Dropdown --}}

                        <div id="user-dropdown-menu"
                             class="hidden absolute left-0 top-full z-50 w-60 pt-2">

                            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-xl shadow-slate-900/10">

                                <div class="border-b border-slate-100 px-3 py-3">
                                    <p class="truncate text-sm font-bold text-slate-800">
                                        {{ $fullName ?: 'کاربر' }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $isStudent ? 'دانش‌آموز' : 'ولی دانش‌آموز' }}
                                    </p>
                                </div>

                                <a href="{{ $dashboardUrl }}"
                                   class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-[#0F3D62]">

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
                                            class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-right text-sm font-medium text-rose-600 transition hover:bg-rose-50">

                                        <svg class="h-5 w-5"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.7"
                                             viewBox="0 0 24 24">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                            <path d="m16 17 5-5-5-5M21 12H9"/>
                                        </svg>

                                        خروج از حساب
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login.showLoginForm') }}"
                       class="header-action rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-[#0F3D62] hover:border-[#0F3D62]">
                        ورود به سامانه
                    </a>

                    <a href="{{ route('pre-register.create') }}"
                       class="header-action rounded-xl bg-[#0F3D62] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#0a2d4a]">
                        پیش‌ثبت‌نام
                    </a>
                @endauth

            </div>

            {{-- ================= MOBILE TOGGLE ================= --}}
            <button type="button"
                    id="menu-toggle"
                    aria-label="باز کردن منو"
                    aria-controls="mobile-menu"
                    aria-expanded="false"
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-slate-700 transition hover:bg-slate-100 lg:hidden">

                <svg id="menu-icon-open"
                     class="h-6 w-6"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>

                <svg id="menu-icon-close"
                     class="hidden h-6 w-6"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6 6l12 12M18 6L6 18"/>
                </svg>

            </button>

        </div>
    </div>

    {{-- ================= MOBILE MENU ================= --}}
    <div id="mobile-menu"
         class="mobile-menu border-t border-slate-100 bg-white lg:hidden">

        <div class="px-4 py-5 sm:px-6">

            @auth
                <div class="mb-4 flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#0F3D62] font-bold text-white">
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
                   class="mb-4 flex items-center justify-between rounded-xl bg-[#0F3D62]/5 px-4 py-3.5 text-sm font-bold text-[#0F3D62]">
                    پنل من
                    <span aria-hidden="true">←</span>
                </a>
            @endauth

            {{-- Mobile Navigation --}}
            <nav class="flex flex-col gap-1" aria-label="منوی موبایل">

                @foreach($nav as $item)
                    <a href="{{ $item['url'] }}"
                       data-section="{{ $item['section'] }}"
                       class="mobile-nav-link rounded-xl px-4 py-3.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-[#0F3D62]">
                        {{ $item['title'] }}
                    </a>
                @endforeach

            </nav>

            {{-- Mobile Actions --}}
            <div class="mt-5 border-t border-slate-100 pt-5">

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="w-full rounded-xl border border-rose-200 bg-rose-50 py-3.5 text-center text-sm font-semibold text-rose-600 transition hover:bg-rose-100">
                            خروج از حساب کاربری
                        </button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-3">

                        <a href="{{ route('login.showLoginForm') }}"
                           class="rounded-xl border border-slate-200 py-3.5 text-center text-sm font-semibold text-[#0F3D62] transition hover:bg-slate-50">
                            ورود
                        </a>

                        <a href="{{ route('pre-register.create') }}"
                           class="rounded-xl bg-[#0F3D62] py-3.5 text-center text-sm font-semibold text-white transition hover:bg-[#0a2d4a]">
                            پیش‌ثبت‌نام
                        </a>

                    </div>
                @endauth

            </div>
        </div>
    </div>

</header>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const header = document.getElementById('school-header');

            if (!header) return;

            const menuToggle = document.getElementById('menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIconOpen = document.getElementById('menu-icon-open');
            const menuIconClose = document.getElementById('menu-icon-close');

            const dropdownContainer = document.getElementById('user-dropdown-container');
            const dropdownToggle = document.getElementById('user-dropdown-toggle');
            const dropdownMenu = document.getElementById('user-dropdown-menu');

            function closeMobileMenu() {
                if (!menuToggle || !mobileMenu) return;

                mobileMenu.classList.remove('is-open');
                menuToggle.setAttribute('aria-expanded', 'false');
                menuToggle.setAttribute('aria-label', 'باز کردن منو');

                menuIconOpen?.classList.remove('hidden');
                menuIconClose?.classList.add('hidden');
            }

            function toggleMobileMenu() {
                if (!menuToggle || !mobileMenu) return;

                const isOpen = mobileMenu.classList.toggle('is-open');

                menuToggle.setAttribute('aria-expanded', String(isOpen));
                menuToggle.setAttribute(
                    'aria-label',
                    isOpen ? 'بستن منو' : 'باز کردن منو'
                );

                menuIconOpen?.classList.toggle('hidden', isOpen);
                menuIconClose?.classList.toggle('hidden', !isOpen);
            }

            menuToggle?.addEventListener('click', toggleMobileMenu);

            mobileMenu?.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', closeMobileMenu);
            });

            function closeDropdown() {
                if (!dropdownMenu || !dropdownToggle) return;

                dropdownMenu.classList.add('hidden');
                dropdownToggle.setAttribute('aria-expanded', 'false');
            }

            dropdownToggle?.addEventListener('click', function (event) {
                event.stopPropagation();

                if (!dropdownMenu) return;

                const isHidden = dropdownMenu.classList.toggle('hidden');

                dropdownToggle.setAttribute(
                    'aria-expanded',
                    String(!isHidden)
                );
            });

            document.addEventListener('click', function (event) {
                if (menuToggle && mobileMenu &&
                    !menuToggle.contains(event.target) &&
                    !mobileMenu.contains(event.target)) {
                    closeMobileMenu();
                }

                if (dropdownContainer &&
                    !dropdownContainer.contains(event.target)) {
                    closeDropdown();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeMobileMenu();
                    closeDropdown();
                }
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) {
                    closeMobileMenu();
                }
            });

            // ================= ACTIVE NAVIGATION =================
            const navLinks = header.querySelectorAll('[data-section]');
            const homeElement = document.getElementById('school-home');

            function setActiveSection(sectionId) {
                navLinks.forEach(function (link) {
                    const active = link.dataset.section === sectionId;

                    link.classList.toggle('is-active', active);

                    if (active) {
                        link.setAttribute('aria-current', 'location');
                    } else {
                        link.removeAttribute('aria-current');
                    }
                });
            }

            // On pages other than Home, do not highlight a section.
            if (!homeElement) return;

            const sectionIds = [
                'ab-programs',
                'ab-about',
                'ab-news',
                'ab-admissions',
                'ab-contact'
            ];

            let scrollScheduled = false;

            function updateActiveNavigation() {
                const offset = header.offsetHeight + 120;
                const scrollPosition = window.scrollY + offset;

                let activeSection = 'school-home';

                sectionIds.forEach(function (id) {
                    const section = document.getElementById(id);

                    if (!section) return;

                    const sectionTop =
                        section.getBoundingClientRect().top + window.scrollY;

                    if (scrollPosition >= sectionTop) {
                        activeSection = id;
                    }
                });

                setActiveSection(activeSection);
            }

            function scheduleNavigationUpdate() {
                if (scrollScheduled) return;

                scrollScheduled = true;

                requestAnimationFrame(function () {
                    updateActiveNavigation();
                    scrollScheduled = false;
                });
            }

            updateActiveNavigation();

            window.addEventListener('scroll', scheduleNavigationUpdate, {
                passive: true
            });

            window.addEventListener('resize', scheduleNavigationUpdate);

            window.addEventListener('hashchange', function () {
                requestAnimationFrame(updateActiveNavigation);
            });
        });
    </script>
@endpush
