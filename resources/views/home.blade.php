@extends('alibnhamze.layouts.app')

@section('title', 'هنرستان علی بن حمزه | دانش، مهارت و آینده')

@section('content')
    @php
        $homeSettings = array_replace([
            'hero_image' => asset('images/hero/shrine-banner.jpg'),
            'classroom_image' => asset('images/news/camputer.jpg'),
            'library_image' => asset('images/news/electrical.jpg'),
            'quote_image' => asset('images/quote-banner.png'),
            'registration_url' => null,
            'contact_url' => null,
            'portal_url' => \Illuminate\Support\Facades\Route::has('login') ? route('login') : null,
            'phone' => null,
            'phone_label' => null,
            'address' => null,
            'admissions_note' => 'برای اطلاع از زمان ثبت‌نام، ظرفیت و مدارک مورد نیاز، با دفتر هنرستان هماهنگ کنید.',
        ], $homeSettings ?? []);
    @endphp

    <main id="school-home" dir="rtl" class="overflow-x-hidden bg-white font-sans text-[#142C40] antialiased">

        {{-- ================= HERO ================= --}}
        <section class="bg-gradient-to-b from-[#F7F9FA] to-white py-10 sm:py-14 lg:py-20" aria-labelledby="home-title">
            <div class="mx-auto grid max-w-[1180px] grid-cols-1 items-center gap-10 px-5 sm:px-8 lg:grid-cols-2 lg:gap-16">
                <div>
                    <div class="mb-5 flex items-center gap-3 text-xs font-bold text-[#0F3D62]">
                        <span class="h-0.5 w-7 bg-[#B59052]"></span>
                        هنرستان علی بن حمزه
                    </div>
                    <h1 id="home-title" class="text-[38px] font-black leading-[1.5] tracking-tight text-[#142C40] sm:text-5xl lg:text-[58px] lg:leading-[1.35]">
                        آینده‌ای روشن،<br>با <span class="text-[#0F3D62]">دانش و ایمان.</span>
                    </h1>
                    <p class="mt-6 max-w-lg text-sm leading-9 text-[#596B7A] sm:text-base">
                        مسیر آینده از همین‌جا آغاز می‌شود؛ با یادگیری، تجربه و مهارت‌هایی که از کلاس درس فراتر می‌روند.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="#ab-programs" class="inline-flex min-h-12 items-center justify-center gap-4 rounded-xl bg-[#0F3D62] px-6 py-3 text-sm font-bold text-white transition-all hover:-translate-y-0.5 hover:bg-[#0B2E4B] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#B59052]">
                            کشف مسیر تحصیلی <span aria-hidden="true">←</span>
                        </a>
                        <a href="#ab-admissions" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-[#E3E9ED] bg-white px-6 py-3 text-sm font-bold text-[#0F3D62] transition-colors hover:border-[#0F3D62] hover:bg-slate-50">
                            راهنمای ثبت‌نام
                        </a>
                    </div>
                    <p class="mt-8 flex items-center gap-2 text-xs text-[#596B7A]">
                        <span class="h-2 w-2 rounded-full bg-[#B59052]"></span>
                        آموزش برای آینده؛ پرورش برای زندگی
                    </p>
                </div>

                <div class="relative pb-7">
                    <div class="relative h-[350px] overflow-hidden rounded-2xl rounded-tr-[75px] bg-slate-200 sm:h-[430px] lg:h-[480px] lg:rounded-tr-[105px]">
                        <img src="{{ $homeSettings['hero_image'] }}" alt="نمای حرم علی بن حمزه" class="h-full w-full object-cover" width="700" height="800" fetchpriority="high" decoding="async">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#061B2B]/75 via-transparent to-transparent"></div>
                        <span class="absolute left-5 top-5 rounded-full border border-slate-100 bg-white px-4 py-2 text-[11px] font-bold text-[#0F3D62]">دانش · مهارت · اخلاق</span>
                        <div class="absolute bottom-12 right-7 text-white">
                            <span class="text-xs">هنرستان علی بن حمزه</span>
                            <p class="mt-1 text-xl font-extrabold">آغاز یک مسیر تازه</p>
                        </div>
                    </div>
                    <div class="relative z-10 mx-3 -mt-9 flex items-center gap-4 rounded-2xl border border-[#E3E9ED] bg-white p-4 shadow-xl shadow-slate-900/5 sm:mx-6 sm:p-5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#F3F6F8] text-[#0F3D62]">
                            <svg aria-hidden="true" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h6a3 3 0 0 1 3 3v12a4 4 0 0 0-4-2H4V5Zm16 0h-4a3 3 0 0 0-3 3m0 12a4 4 0 0 1 4-2h3V5Z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <strong class="block text-sm">انتخاب رشته، شروع آینده</strong>
                            <span class="mt-1 block text-xs text-[#596B7A]">آشنایی با مسیرهای کامپیوتر و برق</span>
                        </div>
                        <a href="#ab-programs" class="shrink-0 text-xs font-bold text-[#0F3D62]">بیشتر ←</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= PILLARS ================= --}}
        <section class="border-y border-[#E3E9ED]" aria-label="ویژگی‌های مسیر آموزشی">
            <div class="mx-auto grid max-w-[1180px] grid-cols-2 px-5 sm:px-8 lg:grid-cols-4">
                @foreach ([
                    ['title' => 'یادگیری هدفمند', 'desc' => 'از دانستن تا توانستن', 'icon' => 'book'],
                    ['title' => 'مهارت‌های کاربردی', 'desc' => 'تجربه در کنار آموزش', 'icon' => 'screen'],
                    ['title' => 'دانش و اخلاق', 'desc' => 'رشد فردی و تحصیلی', 'icon' => 'shield'],
                    ['title' => 'همراهی خانواده', 'desc' => 'گفت‌وگو در مسیر پیشرفت', 'icon' => 'users'],
                ] as $pillar)
                    <div class="flex items-center gap-3 border-b border-[#E3E9ED] py-6 last:border-b-0 odd:border-l odd:pl-3 even:pr-3 lg:border-b-0 lg:border-l lg:px-5 lg:last:border-l-0">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#F3F6F8] text-[#0F3D62]">
                        @if($pillar['icon'] === 'book')
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m3 8 9-5 9 5-9 5-9-5Zm4 3v6c3 3 7 3 10 0v-6M21 8v8"/></svg>
                        @elseif($pillar['icon'] === 'screen')
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21h8m-4-4v4m-4-12 2 2-2 2m5 0h3"/></svg>
                        @elseif($pillar['icon'] === 'shield')
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m12 3 8 4v6c0 4-8 8-8 8S4 17 4 13V7l8-4Zm-4 9 3 3 5-6"/></svg>
                        @else
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="7" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3m1-17a3 3 0 0 1 0 6m2 5a5 5 0 0 1 3 4v2"/></svg>
                        @endif
                    </span>
                        <div class="min-w-0">
                            <h2 class="text-xs font-bold sm:text-sm">{{ $pillar['title'] }}</h2>
                            <p class="mt-1 text-[11px] leading-5 text-[#596B7A]">{{ $pillar['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ================= PROGRAMS ================= --}}
        <section id="ab-programs" class="scroll-mt-24 py-16 sm:py-20 lg:py-24" aria-labelledby="programs-title">
            <div class="mx-auto max-w-[1180px] px-5 sm:px-8">
                <div class="mb-9 flex flex-col justify-between gap-5 md:flex-row md:items-end">
                    <div>
                        <p class="mb-4 flex items-center gap-3 text-xs font-bold text-[#0F3D62]"><span class="h-0.5 w-7 bg-[#B59052]"></span>رشته‌های تحصیلی</p>
                        <h2 id="programs-title" class="text-2xl font-black leading-relaxed tracking-tight sm:text-3xl lg:text-4xl">علاقه‌ات را به مهارت تبدیل کن.</h2>
                    </div>
                    <p class="max-w-sm text-sm leading-8 text-[#596B7A]">با مسیرهای آموزشی آشنا شوید و برای انتخاب رشته، قدم اول را آگاهانه بردارید.</p>
                </div>
                <div class="grid gap-6 md:grid-cols-2">
                    <article class="group overflow-hidden rounded-2xl border border-[#E3E9ED] bg-white transition-all hover:border-[#A9BCCB] hover:shadow-xl hover:shadow-slate-900/5">
                        <div class="relative flex h-[205px] items-center justify-center overflow-hidden bg-[#EAF0F5]">
                            <div class="absolute inset-0 opacity-50 [background-image:linear-gradient(#0f3d620b_1px,transparent_1px),linear-gradient(90deg,#0f3d620b_1px,transparent_1px)] [background-size:24px_24px]"></div>
                            <span class="absolute right-6 top-5 text-[11px] tracking-widest text-[#5A748A]" dir="ltr">01 / COMPUTER</span>
                            <div class="relative w-52 -rotate-3 rounded-xl border-[6px] border-white bg-[#123B59] p-5 shadow-xl shadow-[#123B59]/20 transition-transform group-hover:rotate-0">
                                <div class="mb-4 flex gap-1" dir="ltr"><i class="h-1.5 w-1.5 rounded-full bg-[#91B4C8]"></i><i class="h-1.5 w-1.5 rounded-full bg-[#91B4C8]"></i><i class="h-1.5 w-1.5 rounded-full bg-[#91B4C8]"></i></div>
                                <div class="mb-2 h-1 w-3/4 rounded bg-[#91B4C8]"></div><div class="mb-2 h-1 w-1/2 rounded bg-[#D2B07A]"></div><div class="mb-2 h-1 w-5/6 rounded bg-[#91B4C8]"></div><div class="h-1 w-2/3 rounded bg-[#D2B07A]"></div>
                            </div>
                            <span class="absolute bottom-5 left-6 text-[10px] tracking-widest text-[#4A687E]" dir="ltr">BUILD. LEARN. CREATE.</span>
                        </div>
                        <div class="p-6 sm:p-8">
                            <h3 class="text-2xl font-extrabold">کامپیوتر</h3>
                            <p class="mt-3 text-sm leading-8 text-[#596B7A]">برای ذهن‌های کنجکاوی که دوست دارند فناوری را بهتر بشناسند؛ از منطق برنامه‌نویسی تا شناخت سیستم‌ها و شبکه.</p>
                            <div class="my-5 flex flex-wrap gap-2"><span class="rounded-md bg-[#F3F6F8] px-3 py-1.5 text-[11px] text-[#536C7E]">حل مسئله</span><span class="rounded-md bg-[#F3F6F8] px-3 py-1.5 text-[11px] text-[#536C7E]">برنامه‌نویسی</span><span class="rounded-md bg-[#F3F6F8] px-3 py-1.5 text-[11px] text-[#536C7E]">فناوری اطلاعات</span></div>
                            <a href="#ab-admissions" class="inline-flex items-center gap-3 text-xs font-bold text-[#0F3D62]">راهنمای انتخاب این رشته <span aria-hidden="true">←</span></a>
                        </div>
                    </article>
                    <article class="group overflow-hidden rounded-2xl border border-[#E3E9ED] bg-white transition-all hover:border-[#A9BCCB] hover:shadow-xl hover:shadow-slate-900/5">
                        <div class="relative flex h-[205px] items-center justify-center overflow-hidden bg-[#F3F0E8]">
                            <div class="absolute inset-0 opacity-50 [background-image:linear-gradient(#92764b12_1px,transparent_1px),linear-gradient(90deg,#92764b12_1px,transparent_1px)] [background-size:24px_24px]"></div>
                            <span class="absolute right-6 top-5 text-[11px] tracking-widest text-[#8A7451]" dir="ltr">02 / ELECTRICAL</span>
                            <div class="relative flex h-28 w-56 rotate-3 items-center justify-center rounded-xl border border-[#C7B99D] bg-[#FAF9F5] shadow-xl shadow-[#806D45]/10 transition-transform group-hover:rotate-0">
                                <svg class="h-20 w-44 fill-none stroke-[#92764B] stroke-[2]" viewBox="0 0 180 90"><path d="M20 45h35l7-12 10 24 10-24 10 24 7-12h30M20 45V15h130v60H20V45m110 0h20"/><circle cx="20" cy="45" r="9"/><circle cx="150" cy="45" r="9"/><path d="M16 45h8m-4-4v8m125-4h10"/></svg>
                            </div>
                            <span class="absolute bottom-5 left-6 text-[10px] tracking-widest text-[#8A7451]" dir="ltr">EXPLORE. CONNECT. MAKE.</span>
                        </div>
                        <div class="p-6 sm:p-8">
                            <h3 class="text-2xl font-extrabold">برق</h3>
                            <p class="mt-3 text-sm leading-8 text-[#596B7A]">برای کسانی که به ساختن و کشف سازوکارها علاقه دارند؛ آشنایی با مدارها، تجهیزات الکتریکی و تفکر فنی.</p>
                            <div class="my-5 flex flex-wrap gap-2"><span class="rounded-md bg-[#F3F6F8] px-3 py-1.5 text-[11px] text-[#536C7E]">مدارهای الکتریکی</span><span class="rounded-md bg-[#F3F6F8] px-3 py-1.5 text-[11px] text-[#536C7E]">مهارت فنی</span><span class="rounded-md bg-[#F3F6F8] px-3 py-1.5 text-[11px] text-[#536C7E]">دقت و ایمنی</span></div>
                            <a href="#ab-admissions" class="inline-flex items-center gap-3 text-xs font-bold text-[#0F3D62]">راهنمای انتخاب این رشته <span aria-hidden="true">←</span></a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        {{-- ================= LIFE AT SCHOOL ================= --}}
        <section id="ab-about" class="scroll-mt-24 bg-[#F3F6F8] py-16 sm:py-20 lg:py-24" aria-labelledby="about-title">
            <div class="mx-auto grid max-w-[1180px] items-center gap-10 px-5 sm:px-8 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16">
                <div>
                    <p class="mb-4 flex items-center gap-3 text-xs font-bold text-[#0F3D62]"><span class="h-0.5 w-7 bg-[#B59052]"></span>نگاه ما به آموزش</p>
                    <h2 id="about-title" class="text-2xl font-black leading-[1.6] sm:text-3xl lg:text-4xl">مدرسه، فقط<br>یک کلاس درس نیست.</h2>
                    <p class="mt-5 text-sm leading-9 text-[#596B7A]">یادگیری با پرسیدن آغاز می‌شود، با تجربه شکل می‌گیرد و با همکاری رشد می‌کند. دانش و ایمان، دو همراه در مسیری هستند که به آینده می‌رسد.</p>
                    <div class="mt-7 space-y-5">
                        @foreach ([['01','فرصت تجربه و ساختن','پیوند مفاهیم درسی با تمرین و مهارت‌آموزی.'],['02','رشد در کنار یکدیگر','تمرین مسئولیت‌پذیری، گفت‌وگو و کار گروهی.'],['03','نگاه رو به آینده','شناخت علاقه‌ها و انتخاب آگاهانه مسیر تحصیلی.']] as $value)
                            <div class="flex items-start gap-4"><span dir="ltr" class="pt-1 text-sm text-[#B59052]">{{ $value[0] }}</span><div><h3 class="text-sm font-bold">{{ $value[1] }}</h3><p class="mt-1 text-xs leading-7 text-[#596B7A]">{{ $value[2] }}</p></div></div>
                        @endforeach
                    </div>
                </div>
                <div class="grid grid-cols-[1.2fr_0.8fr] grid-rows-[180px_170px] gap-3 sm:grid-rows-[205px_175px] sm:gap-4">
                    <figure class="group relative row-span-2 overflow-hidden rounded-2xl bg-slate-200">
                        <img src="{{ $homeSettings['classroom_image'] }}" alt="فضای کارگاه کامپیوتر" width="700" height="900" loading="lazy" decoding="async" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#112E43]/85 to-transparent px-5 pb-5 pt-16 text-xs font-bold text-white">یادگیری، تجربه، رشد</figcaption>
                    </figure>
                    <figure class="relative overflow-hidden rounded-2xl bg-slate-200"><img src="{{ $homeSettings['library_image'] }}" alt="فضای کارگاه برق" width="500" height="400" loading="lazy" decoding="async" class="h-full w-full object-cover"><figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#112E43]/85 to-transparent px-3 pb-4 pt-10 text-xs text-white">هر روز، فرصتی برای دانستن</figcaption></figure>
                    <div class="flex flex-col justify-between rounded-2xl bg-[#0F3D62] p-4 text-white sm:p-6">
                        <svg aria-hidden="true" class="h-7 w-7 fill-none stroke-[#C7AA72] stroke-[1.5]" viewBox="0 0 24 24"><path d="m12 3 2.8 5.7L21 10l-4.5 4.4 1.1 6.3L12 17.8l-5.6 2.9 1.1-6.3L3 10l6.2-1.3L12 3Z"/></svg>
                        <strong class="text-sm leading-7 sm:text-lg sm:leading-9">آموختن برای امروز،<br>ساختن برای فردا.</strong>
                        <span class="text-[11px] text-[#C4D4DF]">هنرستان علی بن حمزه</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= NEWS / EDITORIAL HIGHLIGHTS ================= --}}
        {{-- Static editorial content, not dated or presented as published school announcements. --}}
        <section id="ab-news" class="scroll-mt-24 bg-white py-16 sm:py-20 lg:py-24" aria-labelledby="news-title">
            <div class="mx-auto max-w-[1180px] px-5 sm:px-8">
                <div class="mb-9 flex flex-col justify-between gap-4 sm:flex-row sm:items-end lg:mb-11">
                    <div>
                        <p class="mb-4 flex items-center gap-3 text-xs font-bold text-[#0F3D62]"><span class="h-0.5 w-7 bg-[#B59052]"></span>تجربه‌های آموزشی</p>
                        <h2 id="news-title" class="text-2xl font-black leading-relaxed tracking-tight sm:text-3xl lg:text-4xl">در هنرستان چه می‌گذرد؟</h2>
                        <p class="mt-3 max-w-xl text-sm leading-8 text-[#596B7A]">از فناوری تا کارگاه‌های عملی؛ نگاهی به موضوعاتی که مسیر یادگیری و مهارت‌آموزی را جذاب‌تر می‌کنند.</p>
                    </div>
                    @if($homeSettings['portal_url'])
                        <a href="{{ $homeSettings['portal_url'] }}" class="inline-flex shrink-0 items-center gap-3 text-xs font-bold text-[#0F3D62] hover:underline">ورود به سامانه <span aria-hidden="true">←</span></a>
                    @endif
                </div>

                <div class="grid gap-5 lg:grid-cols-[1.1fr_1fr] lg:gap-6">
                    {{-- Large feature --}}
                    <article class="group flex min-h-[420px] flex-col overflow-hidden rounded-2xl border border-[#DCE7EE] bg-[#EAF0F5] sm:min-h-[450px]">
                        <div class="relative flex h-52 items-center justify-center overflow-hidden bg-[#DFEAF2] sm:h-60">
                            <div aria-hidden="true" class="absolute inset-0 opacity-60 [background-image:linear-gradient(#0f3d620b_1px,transparent_1px),linear-gradient(90deg,#0f3d620b_1px,transparent_1px)] [background-size:28px_28px]"></div>
                            <span class="absolute right-6 top-5 text-[11px] font-semibold tracking-widest text-[#5A748A]" dir="ltr">TECH / 01</span>
                            <div aria-hidden="true" class="relative w-[235px] -rotate-3 overflow-hidden rounded-xl border-[7px] border-white bg-[#123B59] p-5 shadow-2xl shadow-[#123B59]/20 transition-transform duration-500 group-hover:rotate-0 sm:w-[270px]">
                                <div class="mb-5 flex gap-1.5" dir="ltr"><span class="h-1.5 w-1.5 rounded-full bg-[#A6C5D7]"></span><span class="h-1.5 w-1.5 rounded-full bg-[#A6C5D7]"></span><span class="h-1.5 w-1.5 rounded-full bg-[#A6C5D7]"></span></div>
                                <div class="mb-3 h-1.5 w-4/5 rounded bg-[#91B4C8]"></div><div class="mb-3 h-1.5 w-1/2 rounded bg-[#D2B07A]"></div><div class="mb-3 h-1.5 w-full rounded bg-[#91B4C8]"></div><div class="h-1.5 w-2/3 rounded bg-[#D2B07A]"></div>
                            </div>
                            <span class="absolute bottom-5 left-6 text-[10px] tracking-widest text-[#5A748A]" dir="ltr">LEARN / BUILD / CREATE</span>
                        </div>
                        <div class="flex flex-1 flex-col bg-white p-6 sm:p-8">
                            <span class="mb-3 inline-flex w-fit rounded-md bg-[#EDF3F7] px-3 py-1.5 text-[11px] font-bold text-[#0F3D62]">کامپیوتر و فناوری</span>
                            <h3 class="text-xl font-extrabold leading-9 text-[#142C40] sm:text-2xl">سیستم‌های جدید، فرصت‌های تازه برای یادگیری</h3>
                            <p class="mt-3 text-sm leading-8 text-[#596B7A]">آشنایی با ابزارهای نوین، برنامه‌نویسی و دنیای فناوری، افق‌های تازه‌ای برای تجربه کردن، ساختن و حل مسئله پیش روی هنرجویان قرار می‌دهد.</p>
                            <div class="mt-auto flex items-center justify-between border-t border-[#E3E9ED] pt-5 text-xs font-bold text-[#0F3D62]"><span>فناوری و مهارت</span><span aria-hidden="true">←</span></div>
                        </div>
                    </article>

                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-1">
                        <article class="group flex min-h-[215px] flex-col overflow-hidden rounded-2xl border border-[#E3E9ED] bg-white sm:min-h-[240px] lg:flex-row">
                            <div class="relative flex h-36 items-center justify-center overflow-hidden bg-[#F3F0E8] sm:h-40 lg:h-auto lg:w-[38%] lg:shrink-0">
                                <div aria-hidden="true" class="absolute inset-0 opacity-40 [background-image:linear-gradient(#92764b17_1px,transparent_1px),linear-gradient(90deg,#92764b17_1px,transparent_1px)] [background-size:22px_22px]"></div>
                                <svg aria-hidden="true" viewBox="0 0 100 100" class="relative h-20 w-20 fill-none stroke-[#92764B] stroke-[2.5] transition-transform group-hover:scale-110"><path d="M15 50h20l9-18 13 36 12-26 7 8h12"/><circle cx="15" cy="50" r="7"/><circle cx="88" cy="50" r="7"/></svg>
                                <span class="absolute bottom-3 left-3 text-[10px] tracking-widest text-[#8A7451]" dir="ltr">PRACTICE / 02</span>
                            </div>
                            <div class="flex flex-1 flex-col justify-center p-5 sm:p-6">
                                <span class="mb-2 text-[11px] font-bold text-[#B59052]">آموزش عملی</span>
                                <h3 class="text-base font-extrabold leading-8 text-[#142C40]">یادگیری فراتر از کتاب‌های درسی</h3>
                                <p class="mt-2 text-xs leading-7 text-[#596B7A]">کارگاه‌ها فرصتی برای تبدیل مفاهیم نظری به تجربه‌های واقعی و تقویت توانایی حل مسئله هستند.</p>
                                <span class="mt-4 text-xs font-bold text-[#0F3D62]">تجربه، تمرین، یادگیری ←</span>
                            </div>
                        </article>
                        <article class="group flex min-h-[215px] flex-col overflow-hidden rounded-2xl border border-[#E3E9ED] bg-white sm:min-h-[240px] lg:flex-row">
                            <div class="relative flex h-36 items-center justify-center overflow-hidden bg-[#EAF0F5] sm:h-40 lg:h-auto lg:w-[38%] lg:shrink-0">
                                <div aria-hidden="true" class="absolute inset-0 opacity-50 [background-image:linear-gradient(#0f3d620b_1px,transparent_1px),linear-gradient(90deg,#0f3d620b_1px,transparent_1px)] [background-size:22px_22px]"></div>
                                <svg aria-hidden="true" viewBox="0 0 100 100" class="relative h-20 w-20 fill-none stroke-[#0F3D62] stroke-[3] transition-transform group-hover:scale-110"><path d="M15 75 42 48l16 15 27-35M65 28h20v20"/><path d="M15 85h70"/></svg>
                                <span class="absolute bottom-3 left-3 text-[10px] tracking-widest text-[#5A748A]" dir="ltr">FUTURE / 03</span>
                            </div>
                            <div class="flex flex-1 flex-col justify-center p-5 sm:p-6">
                                <span class="mb-2 text-[11px] font-bold text-[#B59052]">مهارت و آینده</span>
                                <h3 class="text-base font-extrabold leading-8 text-[#142C40]">امروز مهارت، فردا فرصت‌های بیشتر</h3>
                                <p class="mt-2 text-xs leading-7 text-[#596B7A]">خلاقیت، همکاری و یادگیری مداوم، در کنار مهارت‌های تخصصی به ساختن مسیر آینده کمک می‌کنند.</p>
                                <span class="mt-4 text-xs font-bold text-[#0F3D62]">نگاهی رو به آینده ←</span>
                            </div>
                        </article>
                    </div>
                </div>

                {{-- Quote banner --}}
                <div class="relative mt-8 overflow-hidden rounded-2xl border border-[#E3E9ED] bg-[#F3F6F8] px-6 py-8 sm:mt-11 sm:px-12 sm:py-11">
                    <img src="{{ $homeSettings['quote_image'] }}" alt="" loading="lazy" decoding="async" width="1200" height="300" class="absolute inset-0 h-full w-full object-cover opacity-15">
                    <div class="absolute inset-0 bg-gradient-to-l from-[#F7F9FB]/95 via-[#F7F9FB]/90 to-[#F7F9FB]/55"></div>
                    <div class="relative flex items-center gap-5 sm:gap-8"><span aria-hidden="true" class="self-start font-serif text-6xl leading-none text-[#B59052] sm:text-7xl">“</span><div><blockquote class="text-lg font-extrabold leading-9 sm:text-2xl sm:leading-[1.9]">علم، چراغ راه است؛<br>و ایمان، همراه مسیر زندگی.</blockquote><p class="mt-3 text-xs text-[#596B7A]">باور ما در هنرستان علی بن حمزه</p></div></div>
                </div>
            </div>
        </section>

        {{-- ================= ADMISSIONS ================= --}}
        <section id="ab-admissions" class="scroll-mt-24 pb-16 sm:pb-20 lg:pb-24" aria-labelledby="admissions-title">
            <div class="mx-auto max-w-[1180px] px-5 sm:px-8">
                <div class="relative grid gap-9 overflow-hidden rounded-[20px] bg-[#0F3D62] p-7 text-white sm:p-10 lg:grid-cols-2 lg:gap-16 lg:p-12">
                    <div class="pointer-events-none absolute -left-28 -top-28 h-72 w-72 rounded-full border border-white/10"></div>
                    <div class="relative flex flex-col justify-center">
                        <p class="mb-4 flex items-center gap-3 text-xs font-bold text-[#C7D9E6]"><span class="h-0.5 w-7 bg-[#B59052]"></span>شروع یک مسیر تازه</p>
                        <h2 id="admissions-title" class="text-2xl font-extrabold leading-[1.6] sm:text-3xl">برای قدم بعدی،<br>کنارتان هستیم.</h2>
                        <p class="mt-4 max-w-lg text-sm leading-8 text-[#D0DDE7]">{{ $homeSettings['admissions_note'] }}</p>
                        <div class="mt-7 flex flex-wrap gap-3">
                            @if($homeSettings['registration_url'])
                                <a href="{{ $homeSettings['registration_url'] }}" class="inline-flex min-h-12 items-center gap-3 rounded-xl bg-white px-6 py-3 text-xs font-bold text-[#0F3D62] hover:bg-[#EAF0F5]">شروع ثبت‌نام ←</a>
                            @endif
                            <a href="{{route('pre-register.create')}}" class="inline-flex min-h-12 items-center gap-3 rounded-xl border border-white/40 px-6 py-3 text-xs font-bold text-white transition-colors hover:bg-white/10">پیش ثبت نام</a>
                        </div>
                    </div>
                    <div class="relative divide-y divide-white/20">

                        {{-- Registration Steps --}}
                        <div class="relative divide-y divide-white/20">
                            @foreach ([
                                [
                                    '۱',
                                    'آشنایی با رشته‌ها',
                                    'رشته‌های تحصیلی هنرستان را بررسی کنید و با توجه به علاقه و توانایی خود، مسیر مناسب را انتخاب کنید.'
                                ],
                                [
                                    '۲',
                                    'ارسال فرم پیش‌ثبت‌نام',
                                    'فرم پیش‌ثبت‌نام اینترنتی را تکمیل کرده و اطلاعات مورد نیاز را برای بررسی به هنرستان ارسال کنید.'
                                ],
                                [
                                    '۳',
                                    'در انتظار تأیید هنرستان',
                                    'اطلاعات ثبت‌شده توسط مسئولان هنرستان بررسی می‌شود. پس از اعلام نتیجه، می‌توانید مراحل بعدی را انجام دهید.'
                                ],
                                [
                                    '۴',
                                    'مراجعه به هنرستان و تحویل مدارک',
                                    'پس از تأیید پیش‌ثبت‌نام، برای تحویل مدارک مورد نیاز و تکمیل فرایند ثبت‌نام، به هنرستان مراجعه کنید.'
                                ]
                            ] as $step)

                                <div class="flex items-start gap-4 py-4 first:pt-0 last:pb-0">

                                    {{-- Step Number --}}
                                    <span
                                        class="flex h-9 w-9 shrink-0 items-center justify-center
                       rounded-full border border-[#7690A5]
                       text-xs font-semibold text-[#DED0B3]"
                                    >
                {{ $step[0] }}
            </span>

                                    {{-- Step Content --}}
                                    <div class="min-w-0 flex-1">

                                        <h3 class="text-sm font-bold text-white leading-7">
                                            {{ $step[1] }}
                                        </h3>

                                        <p class="mt-1 text-xs leading-6 text-[#C2D3DF]">
                                            {{ $step[2] }}
                                        </p>

                                    </div>

                                </div>

                            @endforeach
                        </div>

                    </div>
                </div>

                {{-- FAQ --}}
                <div class="mt-16 grid gap-8 lg:grid-cols-[0.7fr_1.3fr] lg:gap-16">
                    <div>
                        <p class="mb-4 flex items-center gap-3 text-xs font-bold text-[#0F3D62]"><span class="h-0.5 w-7 bg-[#B59052]"></span>پرسش‌های متداول</p>
                        <h2 class="text-2xl font-black leading-relaxed sm:text-3xl">قبل از شروع،<br>بیشتر بدانید.</h2>
                        <p class="mt-4 text-sm leading-8 text-[#596B7A]">پاسخ چند پرسش رایج درباره انتخاب رشته و ثبت‌نام.</p>
                    </div>
                    <div class="divide-y divide-[#E3E9ED]">
                        <details class="group py-5 first:pt-0"><summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold marker:hidden [&::-webkit-details-marker]:hidden">چه رشته‌هایی معرفی شده‌اند؟ <span class="text-xl font-normal text-[#0F3D62] group-open:rotate-45">+</span></summary><p class="pt-3 text-xs leading-8 text-[#596B7A]">در این صفحه با دو مسیر کامپیوتر و برق آشنا می‌شوید. عنوان رسمی رشته‌ها، شرایط پذیرش و ظرفیت هر پایه را از دفتر هنرستان بپرسید.</p></details>
                        <details class="group py-5"><summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold marker:hidden [&::-webkit-details-marker]:hidden">چطور از شرایط ثبت‌نام مطلع شویم؟ <span class="text-xl font-normal text-[#0F3D62] group-open:rotate-45">+</span></summary><p class="pt-3 text-xs leading-8 text-[#596B7A]">برای دریافت اطلاعات دقیق و به‌روز درباره شرایط و زمان ثبت‌نام، با دفتر هنرستان هماهنگ شوید.</p></details>
                        <details class="group py-5"><summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold marker:hidden [&::-webkit-details-marker]:hidden">برای انتخاب رشته از کجا شروع کنیم؟ <span class="text-xl font-normal text-[#0F3D62] group-open:rotate-45">+</span></summary><p class="pt-3 text-xs leading-8 text-[#596B7A]">علاقه‌ها، توانایی‌ها و هدف تحصیلی خود را بررسی کنید. گفت‌وگو با خانواده و مشاور تحصیلی می‌تواند به انتخاب آگاهانه کمک کند.</p></details>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
