@extends('layouts.app')

@section('title', 'هنرستان علی بن حمزه')

@section('content')
    <div dir="rtl" class="font-sans bg-white text-slate-800 antialiased overflow-x-hidden">

        {{-- ================= HEADER ================= --}}
        <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
                <div class="flex items-center justify-between h-16 sm:h-20">

                    {{-- Logo --}}
                    <a href="#" class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#0F3D62] flex items-center justify-center shrink-0">
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
                        <a href="#"
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

        {{-- ================= HERO ================= --}}
        <section class="relative overflow-hidden flex items-center bg-slate-50
                        min-h-[560px] sm:min-h-[640px] lg:min-h-[720px]">

            {{-- Background Image --}}
            <div class="absolute inset-0">
                <img src="{{ asset('images/hero/shrine-banner.jpg') }}"
                     alt="هنرستان علی بن حمزه"
                     class="w-full h-full object-cover object-center">

                {{-- موبایل: overlay قوی‌تر برای خوانایی --}}
                <div class="absolute inset-0 bg-white/85 sm:bg-white/70 lg:hidden"></div>

                {{-- دسکتاپ: گرادیان از راست --}}
                <div class="hidden lg:block absolute inset-0 bg-gradient-to-l from-white via-white/60 to-transparent"></div>

                {{-- اتصال نرم به بخش بعد --}}
                <div class="absolute inset-x-0 bottom-0 h-16 sm:h-24 bg-gradient-to-t from-white/90 to-transparent"></div>
            </div>

            {{-- Content --}}
            <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-14 sm:py-20 lg:py-0">
                <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 items-center">

                    {{-- ===== Text Column ===== --}}
                    <div class="lg:col-span-7 text-right">

                        {{-- Eyebrow --}}
                        <div class="inline-flex items-center gap-2 sm:gap-3 mb-5 sm:mb-6">
                            <span class="w-6 sm:w-8 h-px bg-[#0F3D62]/40"></span>
                            <span class="text-[11px] sm:text-xs font-bold tracking-wider text-[#0F3D62]">
                                هنرستان علی بن حمزه
                            </span>
                        </div>

                        {{-- Heading --}}
                        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black
                                   leading-[1.25] sm:leading-[1.2] lg:leading-[1.15] text-slate-900">
                            آینده‌ای روشن
                            <br>
                            با
                            <span class="relative inline-block">
                                <span class="relative z-10 text-[#0F3D62]">دانش و ایمان</span>
                            </span>
                        </h1>

                        {{-- Description --}}
                        <p class="mt-5 sm:mt-7 text-sm sm:text-base lg:text-lg leading-7 sm:leading-8 lg:leading-9
                                  text-slate-600 max-w-xl">
                            هنرستان علی بن حمزه با تکیه بر اساتید مجرب، محیطی امن و برنامه‌های
                            آموزشی به‌روز، دانش‌آموزانی متعهد و متخصص برای فردای ایران عزیز پرورش می‌دهد.
                        </p>

                        {{-- CTAs --}}
                        <div class="mt-7 sm:mt-9 flex flex-col sm:flex-row flex-wrap gap-3">
                            <a href="#"
                               class="inline-flex items-center justify-center gap-2 px-6 sm:px-7 py-3.5 sm:py-4 rounded-xl
                                      bg-[#0F3D62] text-white text-sm font-bold
                                      shadow-lg shadow-[#0F3D62]/25
                                      hover:bg-[#0a2d4a] sm:hover:-translate-y-0.5
                                      active:bg-[#0a2d4a]
                                      transition-all duration-200">
                                آشنایی با مدرسه
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>

                            <a href="#"
                               class="inline-flex items-center justify-center gap-2 px-6 sm:px-7 py-3.5 sm:py-4 rounded-xl
                                      bg-white text-slate-800 text-sm font-bold
                                      border border-slate-200
                                      hover:border-[#0F3D62]/40 hover:text-[#0F3D62]
                                      active:bg-slate-50
                                      transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5a2 2 0 012-2h2.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.24 1.05L7.6 9.6a12 12 0 005.8 5.8l1.57-1.49a1 1 0 011.05-.24l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2A16 16 0 013 5z"/>
                                </svg>
                                تماس با مشاور
                            </a>
                        </div>

                        {{-- Trust badges --}}
                        <div class="mt-8 sm:mt-10 flex flex-wrap gap-2">
                            @php
                                $trust = [
                                    'رتبه‌های برتر کنکور',
                                    'محیط امن و پویا',
                                    'اساتید مجرب',
                                ];
                            @endphp
                            @foreach ($trust as $t)
                                <span class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full
                                             bg-white border border-slate-200 text-[11px] sm:text-xs font-medium text-slate-700">
                                    <svg class="w-3.5 h-3.5 text-[#0F3D62]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    {{ $t }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- ===== Info Card ===== --}}
                    <div class="lg:col-span-5">
                        <div class="relative">

                            {{-- Decorative blur --}}
                            <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full
                                        bg-[#0F3D62]/5 blur-2xl"></div>

                            {{-- Card --}}
                            <div class="relative bg-white/90 sm:bg-white/85 backdrop-blur-xl
                                        border border-white/60
                                        rounded-2xl shadow-2xl shadow-slate-900/5
                                        p-5 sm:p-7 lg:p-8">

                                <div class="flex items-center gap-3 mb-5 sm:mb-6">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-[#0F3D62]/10
                                                flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-[#0F3D62]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">سال تحصیلی جدید</p>
                                        <p class="text-xs text-slate-500">۱۴۰۴ - ۱۴۰۵</p>
                                    </div>
                                </div>

                                <div class="space-y-3.5 sm:space-y-4">
                                    <div class="flex justify-between items-center pb-3.5 sm:pb-4 border-b border-slate-100">
                                        <span class="text-xs sm:text-sm text-slate-600">شروع ثبت‌نام</span>
                                        <span class="text-xs sm:text-sm font-bold text-slate-900">۱۵ خرداد</span>
                                    </div>
                                    <div class="flex justify-between items-center pb-3.5 sm:pb-4 border-b border-slate-100">
                                        <span class="text-xs sm:text-sm text-slate-600">ظرفیت هر کلاس</span>
                                        <span class="text-xs sm:text-sm font-bold text-slate-900">۲۴ نفر</span>
                                    </div>
                                    <div class="flex justify-between items-center pb-3.5 sm:pb-4 border-b border-slate-100">
                                        <span class="text-xs sm:text-sm text-slate-600">پایه‌های تحصیلی</span>
                                        <span class="text-xs sm:text-sm font-bold text-slate-900">دهم تا دوازدهم</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs sm:text-sm text-slate-600">رشته‌ها</span>
                                        <span class="text-xs sm:text-sm font-bold text-slate-900">کامپیوتر، برق</span>
                                    </div>
                                </div>

                                <a href="#"
                                   class="mt-5 sm:mt-6 w-full inline-flex items-center justify-center gap-2
                                          py-3 rounded-xl bg-[#0F3D62] text-white text-sm font-bold
                                          hover:bg-[#0a2d4a] active:bg-[#0a2d4a] transition-colors">
                                    تکمیل فرم ثبت‌نام
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ================= STATS ================= --}}
        <section class="border-y border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
                <div class="grid grid-cols-2 lg:grid-cols-4">
                    @php
                        $stats = [
                            ['value' => '۱۲۰۰+', 'label' => 'دانش‌آموز'],
                            ['value' => '۸۵', 'label' => 'استاد مجرب'],
                            ['value' => '۹۸٪', 'label' => 'قبولی دانشگاه'],
                            ['value' => '۲۵', 'label' => 'سال سابقه'],
                        ];
                    @endphp
                    @foreach ($stats as $i => $s)
                        <div class="py-7 sm:py-10 text-center
                                    {{ $i % 2 === 0 ? 'border-l border-slate-100 lg:border-l' : '' }}
                                    {{ $i < 2 ? 'border-b border-slate-100 lg:border-b-0' : '' }}
                                    lg:border-l lg:last:border-l-0">
                            <p class="text-2xl sm:text-3xl lg:text-4xl font-black text-[#0F3D62]">{{ $s['value'] }}</p>
                            <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-slate-500">{{ $s['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ================= ABOUT ================= --}}
        <section class="py-16 sm:py-20 lg:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
                <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">

                    <div class="order-2 lg:order-1">
                        <span class="text-xs font-bold tracking-wider text-[#0F3D62]">درباره ما</span>
                        <h2 class="mt-3 sm:mt-4 text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 leading-tight">
                            جایی که دانش و اخلاق
                            <br>
                            در کنار هم رشد می‌کنند
                        </h2>
                        <p class="mt-5 sm:mt-6 text-sm sm:text-base leading-7 sm:leading-8 text-slate-600">
                            هنرستان علی بن حمزه با هدف تربیت نسلی متعهد، متخصص و اخلاق‌مدار
                            فعالیت خود را از سال ۱۳۷۸ آغاز کرده است. ما باور داریم آموزش زمانی
                            ارزشمند است که در کنار پرورش اخلاق و ایمان باشد.
                        </p>

                        <ul class="mt-6 sm:mt-8 space-y-3 sm:space-y-4">
                            @php
                                $items = [
                                    'برنامه‌های آموزشی به‌روز و هدفمند',
                                    'اساتید مجرب و دلسوز',
                                    'محیطی امن و پویا برای یادگیری',
                                    'توجه ویژه به تربیت دینی و اخلاقی',
                                ];
                            @endphp
                            @foreach ($items as $item)
                                <li class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-[#0F3D62]/10
                                                flex items-center justify-center mt-0.5">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-[#0F3D62]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-slate-700 text-sm leading-7">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <a href="#"
                           class="mt-7 sm:mt-9 inline-flex items-center gap-2 px-6 sm:px-7 py-3.5 rounded-lg
                                  bg-[#0F3D62] text-white text-sm font-semibold
                                  hover:bg-[#0a2d4a] active:bg-[#0a2d4a] transition-colors">
                            بیشتر بدانید
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                    </div>

                    <div class="order-1 lg:order-2 grid grid-cols-2 gap-3 sm:gap-4">
                        <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=800&q=80"
                             alt="کلاس درس مدرن"
                             class="w-full h-36 sm:h-48 lg:h-56 object-cover rounded-xl">

                        <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&q=80"
                             alt="دانش‌آموزان در کتابخانه"
                             class="w-full h-36 sm:h-48 lg:h-56 object-cover rounded-xl sm:mt-8">

                        <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800&q=80"
                             alt="کتاب و منابع آموزشی"
                             class="w-full h-36 sm:h-48 lg:h-56 object-cover rounded-xl sm:-mt-4">

                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&q=80"
                             alt="دانش‌آموز در حال مطالعه"
                             class="w-full h-36 sm:h-48 lg:h-56 object-cover rounded-xl sm:mt-4">
                    </div>

                </div>
            </div>
        </section>

        {{-- ================= FEATURES ================= --}}
        <section class="py-16 sm:py-20 lg:py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">

                <div class="text-center max-w-2xl mx-auto">
                    <span class="text-xs font-bold tracking-wider text-[#0F3D62]">مزیت‌های ما</span>
                    <h2 class="mt-3 sm:mt-4 text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900">
                        چرا هنرستان علی بن حمزه؟
                    </h2>
                    <p class="mt-3 sm:mt-4 text-sm sm:text-base text-slate-600 leading-7 sm:leading-8">
                        ما با تمرکز بر کیفیت آموزش و پرورش اخلاقی، محیطی متفاوت برای دانش‌آموزان فراهم کرده‌ایم.
                    </p>
                </div>

                @php
                    $features = [
                        ['title' => 'آموزش با کیفیت',
                         'desc' => 'برنامه‌های آموزشی به‌روز و منطبق با استانداردهای روز کشور.',
                         'icon' => 'M12 14l9-5-9-5-9 5 9 5z'],
                        ['title' => 'اساتید مجرب',
                         'desc' => 'کادری از اساتید با تجربه، متعهد و دلسوز در کنار دانش‌آموزان.',
                         'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['title' => 'فضای معنوی',
                         'desc' => 'توجه ویژه به تربیت دینی و اخلاقی در کنار آموزش علمی.',
                         'icon' => 'M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6L12 2z'],
                        ['title' => 'محیط امن',
                         'desc' => 'فضایی امن، پویا و بانشاط برای رشد و شکوفایی دانش‌آموزان.',
                         'icon' => 'M12 2l8 4v6c0 5-3.5 9.5-8 10-4.5-.5-8-5-8-10V6l8-4z'],
                        ['title' => 'مشاوره تحصیلی',
                         'desc' => 'همراهی مشاوران متخصص در مسیر انتخاب رشته و برنامه‌ریزی درسی.',
                         'icon' => 'M8 10h8M8 14h5M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['title' => 'فعالیت‌های فوق‌برنامه',
                         'desc' => 'اردوها، مسابقات علمی، فرهنگی و ورزشی برای رشد همه‌جانبه.',
                         'icon' => 'M12 8v13m0-13a3 3 0 100-6 3 3 0 000 6zm-9 4h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ];
                @endphp

                <div class="mt-10 sm:mt-14 grid gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($features as $f)
                        <div class="group bg-white rounded-xl p-5 sm:p-7 border border-slate-100
                                    hover:border-[#0F3D62]/20 hover:shadow-lg
                                    transition-all duration-300">
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-lg bg-[#0F3D62]/10
                                        flex items-center justify-center
                                        group-hover:bg-[#0F3D62] transition-colors duration-300">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#0F3D62] group-hover:text-white transition-colors"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="{{ $f['icon'] }}"/>
                                </svg>
                            </div>
                            <h3 class="mt-4 sm:mt-5 text-base sm:text-lg font-bold text-slate-900">{{ $f['title'] }}</h3>
                            <p class="mt-2 text-sm text-slate-600 leading-7">{{ $f['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ================= NEWS ================= --}}
        <section class="py-16 sm:py-20 lg:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-3 sm:gap-4 mb-8 sm:mb-12">
                    <div>
                        <span class="text-xs font-bold tracking-wider text-[#0F3D62]">اخبار</span>
                        <h2 class="mt-2 sm:mt-3 text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900">
                            آخرین اخبار و اطلاعیه‌ها
                        </h2>
                    </div>
                    <a href="#"
                       class="group inline-flex items-center gap-2 text-sm font-semibold text-[#0F3D62]
                              hover:gap-3 transition-all">
                        مشاهده همه اخبار
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 5l-7 7 7 7"/>
                        </svg>
                    </a>
                </div>

                @php
                    $featured = [
                        'img'   => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=1200&q=80',
                        'date'  => '۱۵ مهر ۱۴۰۳',
                        'tag'   => 'آموزشی',
                        'title' => 'برگزاری آزمون‌های میان‌ترم',
                        'desc'  => 'امتحانات میان‌ترم برای تمام پایه‌ها از هفته آینده آغاز خواهد شد. دانش‌آموزان می‌توانند برنامه‌ی کامل امتحانات را از سامانه‌ی مدرسه دریافت کنند.',
                    ];

                    $news = [
                        [
                            'img'   => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&q=80',
                            'date'  => '۱۰ مهر ۱۴۰۳',
                            'tag'   => 'مسابقات',
                            'title' => 'ثبت‌نام المپیاد علمی دانش‌آموزی',
                            'desc'  => 'ثبت‌نام المپیادهای علمی استانی تا پایان ماه جاری ادامه دارد.',
                        ],
                        [
                            'img'   => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800&q=80',
                            'date'  => '۵ مهر ۱۴۰۳',
                            'tag'   => 'مذهبی',
                            'title' => 'برگزاری مراسم شهادت امام رضا (ع)',
                            'desc'  => 'مراسم عزاداری با حضور دانش‌آموزان و اساتید برگزار می‌شود.',
                        ],
                    ];

                    $tagColors = [
                        'آموزشی'  => 'bg-blue-50 text-blue-700',
                        'مسابقات' => 'bg-amber-50 text-amber-700',
                        'مذهبی'   => 'bg-emerald-50 text-emerald-700',
                    ];
                @endphp

                <div class="grid lg:grid-cols-2 gap-5 sm:gap-7">

                    {{-- Featured Card --}}
                    <article class="group relative overflow-hidden rounded-2xl">
                        <div class="relative h-[360px] sm:h-[420px] lg:h-full overflow-hidden">
                            <img src="{{ $featured['img'] }}"
                                 alt="{{ $featured['title'] }}"
                                 class="w-full h-full object-cover group-hover:scale-105
                                        transition-transform duration-700">

                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/95 via-slate-900/50 to-transparent"></div>

                            <div class="absolute inset-x-0 bottom-0 p-5 sm:p-7 lg:p-9">
                                <div class="flex items-center gap-2 mb-3 flex-wrap">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                                 bg-white/90 backdrop-blur text-xs font-bold
                                                 {{ $tagColors[$featured['tag']] ?? 'text-slate-700' }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                                        {{ $featured['tag'] }}
                                    </span>
                                    <span class="text-xs text-white/80">{{ $featured['date'] }}</span>
                                </div>

                                <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-white leading-snug">
                                    {{ $featured['title'] }}
                                </h3>

                                <p class="mt-3 text-sm text-white/85 leading-7 line-clamp-2 max-w-lg">
                                    {{ $featured['desc'] }}
                                </p>

                                <a href="#"
                                   class="group/btn mt-5 inline-flex items-center gap-2 px-5 py-2.5 rounded-lg
                                          bg-white text-[#0F3D62] text-sm font-bold
                                          hover:bg-slate-100 transition-colors">
                                    مطالعه کامل خبر
                                    <svg class="w-4 h-4 transition-transform group-hover/btn:-translate-x-0.5"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 5l-7 7 7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>

                    {{-- Small Cards --}}
                    <div class="grid gap-5 sm:gap-7">
                        @foreach ($news as $item)
                            <article class="group flex flex-col sm:flex-row gap-0 sm:gap-5
                                            bg-slate-50 rounded-2xl overflow-hidden
                                            border border-slate-100
                                            hover:border-[#0F3D62]/20 hover:bg-white
                                            hover:shadow-lg hover:shadow-slate-900/5
                                            transition-all duration-300">

                                <div class="sm:w-44 sm:flex-shrink-0 overflow-hidden">
                                    <img src="{{ $item['img'] }}"
                                         alt="{{ $item['title'] }}"
                                         class="w-full h-44 sm:h-full object-cover
                                                group-hover:scale-105 transition-transform duration-500">
                                </div>

                                <div class="p-5 sm:py-6 sm:pl-6 sm:pr-0 flex flex-col justify-center flex-1">
                                    <div class="flex items-center gap-2 mb-2.5 flex-wrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5
                                                     rounded-full text-[11px] font-bold
                                                     {{ $tagColors[$item['tag']] ?? 'bg-slate-100 text-slate-700' }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                                            {{ $item['tag'] }}
                                        </span>
                                        <span class="text-xs text-slate-500">{{ $item['date'] }}</span>
                                    </div>

                                    <h3 class="text-base lg:text-lg font-bold text-slate-900 leading-7
                                               group-hover:text-[#0F3D62] transition-colors">
                                        {{ $item['title'] }}
                                    </h3>

                                    <p class="mt-2 text-sm text-slate-600 leading-7 line-clamp-2">
                                        {{ $item['desc'] }}
                                    </p>

                                    <a href="#"
                                       class="group/btn mt-4 inline-flex items-center gap-1.5
                                              text-sm font-semibold text-[#0F3D62]">
                                        مطالعه بیشتر
                                        <svg class="w-4 h-4 transition-transform group-hover/btn:-translate-x-0.5"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 5l-7 7 7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                </div>
            </div>
        </section>

        {{-- ================= QUOTE ================= --}}
        <section class="py-12 sm:py-16 lg:py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">

                <div class="relative overflow-hidden rounded-2xl sm:rounded-[28px]
                            bg-white border border-slate-100
                            min-h-[200px] sm:min-h-[220px]">

                    <img
                        src="{{ asset('images/quote-banner.png') }}"
                        alt=""
                        class="absolute inset-0 w-full h-full object-cover object-center"
                    >

                    <div class="absolute inset-0
                                bg-gradient-to-l
                                from-white/85 via-white/85 to-white/95
                                lg:from-transparent lg:via-white/50 lg:to-white/90">
                    </div>

                    <div class="relative z-10 flex items-center min-h-[200px] sm:min-h-[220px] py-8 sm:py-10 lg:py-0">
                        <div class="w-full lg:w-[60%] mx-auto lg:mr-auto lg:ml-0
                                    px-5 sm:px-8 lg:px-16
                                    text-center lg:text-right">
                            <blockquote class="text-base sm:text-lg lg:text-2xl font-bold
                                               text-slate-900 leading-[1.9]">
                                «علم، چراغ راه است؛
                                <br>
                                و ایمان، بهترین همراه در مسیر زندگی.»
                            </blockquote>

                            <p class="mt-3 text-xs lg:text-sm text-slate-500">
                                — شعار هنرستان علی بن حمزه
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        {{-- ================= CTA ================= --}}
        <section class="py-16 sm:py-20 lg:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
                <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden bg-[#0F3D62]
                            px-6 py-10 sm:px-10 sm:py-14 lg:px-16 lg:py-20">

                    <div class="absolute top-0 left-0 w-56 sm:w-72 h-56 sm:h-72 rounded-full bg-white/5 -translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 right-0 w-72 sm:w-96 h-72 sm:h-96 rounded-full bg-white/5 translate-x-1/3 translate-y-1/3"></div>

                    <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-6 sm:gap-8">
                        <div class="max-w-xl">
                            <h2 class="text-xl sm:text-2xl lg:text-4xl font-black text-white leading-snug sm:leading-tight">
                                آماده‌ی ثبت‌نام فرزندتان در هنرستان علی بن حمزه هستید؟
                            </h2>
                            <p class="mt-3 sm:mt-4 text-sm sm:text-base text-white/70 leading-7 sm:leading-8">
                                همین امروز با ما تماس بگیرید یا به صورت آنلاین ثبت‌نام کنید
                                و آینده‌ی روشن فرزند خود را بسازید.
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                            <a href="#"
                               class="inline-flex items-center justify-center gap-2 px-6 sm:px-7 py-3.5 rounded-lg
                                      bg-white text-[#0F3D62] text-sm font-bold
                                      hover:bg-slate-100 active:bg-slate-200 transition-colors">
                                ثبت‌نام آنلاین
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                            <a href="#"
                               class="inline-flex items-center justify-center gap-2 px-6 sm:px-7 py-3.5 rounded-lg
                                      border border-white/30 text-white text-sm font-semibold
                                      hover:bg-white/10 active:bg-white/15 transition-colors">
                                تماس با ما
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= FOOTER ================= --}}
        <footer class="bg-slate-900 text-slate-400 pt-12 sm:pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 sm:gap-10">

                    <div class="sm:col-span-2">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 14l9-5-9-5-9 5 9 5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-bold text-base sm:text-lg">هنرستان علی بن حمزه</p>
                                <p class="text-xs text-slate-500">دانش، ایمان، آینده روشن</p>
                            </div>
                        </div>
                        <p class="mt-4 sm:mt-5 text-sm leading-7 max-w-md">
                            هنرستان علی بن حمزه با هدف تربیت نسلی متعهد، متخصص و اخلاق‌مدار،
                            از سال ۱۳۷۸ در خدمت دانش‌آموزان این مرز و بوم است.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-white font-bold mb-4 sm:mb-5 text-sm">دسترسی سریع</h3>
                        <ul class="space-y-2.5 sm:space-y-3 text-sm">
                            <li><a href="#" class="hover:text-white transition-colors">درباره ما</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">اخبار و اطلاعیه‌ها</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">آموزش‌ها</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">گالری تصاویر</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-white font-bold mb-4 sm:mb-5 text-sm">تماس با ما</h3>
                        <ul class="space-y-2.5 sm:space-y-3 text-sm">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>تهران، خیابان ولیعصر، پلاک ۱۲۳</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5a2 2 0 012-2h2.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.24 1.05L7.6 9.6a12 12 0 005.8 5.8l1.57-1.49a1 1 0 011.05-.24l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2A16 16 0 013 5z"/>
                                </svg>
                                <span>۰۲۱-۱۲۳۴۵۶۷۸</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>info@alibenhamzeh.sch.ir</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-10 sm:mt-14 pt-6 sm:pt-8 border-t border-slate-800
                            flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4
                            text-xs text-slate-500 text-center sm:text-right">
                    <p>© ۱۴۰۴ هنرستان علی بن حمزه. تمامی حقوق محفوظ است.</p>
                    <div class="flex gap-5">
                        <a href="#" class="hover:text-white transition-colors">قوانین</a>
                        <a href="#" class="hover:text-white transition-colors">حریم خصوصی</a>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    {{-- Mobile menu toggle --}}
    <script>
        document.getElementById('menu-toggle')?.addEventListener('click', function () {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>
@endsection
