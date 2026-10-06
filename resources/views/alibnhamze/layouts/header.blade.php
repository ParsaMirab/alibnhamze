<header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="flex items-center justify-between h-16 sm:h-20">

            {{-- Logo --}}
            <a href="{{route('home')}}" class="flex items-center gap-3 min-w-0">
                <div
                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#0F3D62] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 14l6.16-3.42A12 12 0 0112 21a12 12 0 01-6.16-10.42L12 14z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-slate-900 text-sm sm:text-base leading-tight truncate">
                        هنرستان علی بن حمزه
                    </p>
                    <p class="text-[10px] sm:text-xs text-slate-500 truncate">دانش، ایمان، آینده روشن</p>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-1">
                @php
                    $nav = ['صفحه اصلی','درباره ما','آموزش‌ها','اخبار','گالری','تماس با ما'];
                @endphp
                @foreach ($nav as $i => $item)
                    <a href="#"
                       class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                                  {{ $i === 0
                                     ? 'text-[#0F3D62] bg-slate-100'
                                     : 'text-slate-600 hover:text-[#0F3D62] hover:bg-slate-50' }}">
                        {{ $item }}
                    </a>
                @endforeach
            </nav>

            {{-- Desktop CTA --}}
            <div class="hidden lg:flex items-center gap-3">
                <a href="{{route('login.showLoginForm')}}"
                   class="px-5 py-2.5 text-sm font-semibold rounded-lg
                              text-[#0F3D62] border border-slate-200
                              hover:border-[#0F3D62] transition-colors">
                    ورود به سامانه
                </a>
                <a href="#"
                   class="px-5 py-2.5 text-sm font-semibold rounded-lg
                              bg-[#0F3D62] text-white
                              hover:bg-[#0a2d4a] transition-colors">
                    ثبت‌نام
                </a>
            </div>

            {{-- Mobile Toggle --}}
            <button id="menu-toggle" aria-label="منو"
                    class="lg:hidden w-11 h-11 flex items-center justify-center
                                   rounded-xl text-slate-700 hover:bg-slate-100 active:bg-slate-200
                                   transition-colors shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-100 bg-white
                                         max-h-[calc(100vh-4rem)] overflow-y-auto">
        <nav class="px-4 py-4 flex flex-col gap-1">
            @foreach ($nav as $i => $item)
                <a href="#"
                   class="py-3.5 px-4 rounded-xl text-sm font-medium transition
                              {{ $i === 0
                                 ? 'bg-slate-100 text-[#0F3D62]'
                                 : 'text-slate-700 hover:bg-slate-50 hover:text-[#0F3D62]' }}">
                    {{ $item }}
                </a>
            @endforeach
            <div class="grid grid-cols-2 gap-3 pt-4 mt-2 border-t border-slate-100">
                <a href="#" class="py-3 text-center text-sm font-semibold rounded-xl
                                       border border-slate-200 text-[#0F3D62] active:bg-slate-50">
                    ورود
                </a>
                <a href="#" class="py-3 text-center text-sm font-semibold rounded-xl
                                       bg-[#0F3D62] text-white active:bg-[#0a2d4a]">
                    ثبت‌نام
                </a>
            </div>
        </nav>
    </div>
</header>
