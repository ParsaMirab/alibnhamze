
@extends('alibnhamze.layouts.app')

@section('title', 'تیم توسعه | هنرستان علی بن حمزه')

@section('content')
    @php
        $team = [
            [
                'number' => '01',
                'name' => 'Parsa Mirab',
                'role' => 'Developer',
                'description' => 'توسعه و پیاده‌سازی فنی وب‌سایت',
                'icon' => 'code',
            ],
            [
                'number' => '02',
                'name' => 'AmirH Sahra Gard',
                'role' => 'Web Designer',
                'description' => 'طراحی رابط کاربری و تجربه کاربری',
                'icon' => 'design',
            ],
        ];
    @endphp

    <main dir="rtl" class="min-h-screen overflow-hidden bg-white font-sans text-[#102C43] antialiased">

        {{-- ================= HERO ================= --}}
        <section class="relative isolate overflow-hidden border-b border-slate-100">

            {{-- Decorative background --}}
            <div aria-hidden="true"
                 class="pointer-events-none absolute -left-40 -top-48 -z-10 h-[550px] w-[550px] rounded-full bg-[#F1F6FA] blur-3xl">
            </div>

            <div aria-hidden="true"
                 class="pointer-events-none absolute right-0 top-0 -z-10 h-full w-1/2 bg-gradient-to-l from-[#F8FAFC] to-transparent">
            </div>

            <div class="mx-auto max-w-7xl px-5 pb-20 pt-20 sm:px-8 sm:pb-28 sm:pt-28 lg:px-10 lg:pb-32 lg:pt-32">

                <div class="grid items-end gap-12 lg:grid-cols-[1.2fr_.8fr] lg:gap-20">

                    {{-- Hero title --}}
                    <div>
                        <div class="mb-9 flex items-center gap-4">
                            <span class="h-[2px] w-9 rounded-full bg-[#B59052]"></span>
                            <span class="text-xs font-bold tracking-wide text-[#0F3D62]">
                                هنرستان علی بن حمزه
                            </span>
                        </div>

                        <div dir="ltr" class="text-right">
                            <p class="mb-4 text-xs font-bold uppercase tracking-[.3em] text-[#90A0AF]">
                                The people behind the project
                            </p>

                            <h1 class="text-[clamp(58px,9vw,120px)] font-black leading-[1.02] tracking-[-.075em] text-[#102C43]">
                                Behind<br>
                                <span class="text-[#0F3D62]">The</span> Website<span class="text-[#B59052]">.</span>
                            </h1>
                        </div>
                    </div>

                    {{-- Intro --}}
                    <div class="lg:pb-3">
                        <span class="mb-6 inline-flex items-center gap-2 rounded-full border border-[#DDE6EC] bg-white px-4 py-2 text-xs font-semibold text-[#0F3D62]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#B59052]"></span>
                            معرفی تیم پروژه
                        </span>

                        <h2 class="text-2xl font-extrabold leading-[1.7] sm:text-3xl">
                            آدم‌های پشت این وب‌سایت
                        </h2>

                        <p class="mt-5 max-w-md text-sm leading-[2.5] text-[#718396] sm:text-base">
                            هر پروژه موفق، نتیجه همکاری افرادی است که ایده‌ها
                            را به واقعیت تبدیل می‌کنند. این وب‌سایت با همراهی
                            تیم توسعه، طراحی و پشتیبانی مالی شکل گرفته است.
                        </p>

                        <a href="#team"
                           class="group mt-8 inline-flex items-center gap-3 text-sm font-bold text-[#0F3D62] transition hover:text-[#1C6497]">
                            آشنایی با اعضای تیم

                            <svg class="h-5 w-5 transition-transform group-hover:translate-y-1"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 4v16m-6-6 6 6 6-6"/>
                            </svg>
                        </a>
                    </div>

                </div>
            </div>

            {{-- Bottom hero metadata --}}
            <div class="border-t border-slate-100 bg-white/70">
                <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-5 py-5 text-xs text-[#91A0AD] sm:px-8 lg:px-10">
                    <span dir="ltr" class="font-semibold tracking-[.2em]">
                        ALI BN HAMZE / PROJECT CREDITS
                    </span>
                    <span>طراحی، توسعه و پشتیبانی</span>
                </div>
            </div>

        </section>

        {{-- ================= TEAM ================= --}}
        <section id="team" class="scroll-mt-28 py-20 sm:py-28">

            <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

                {{-- Section heading --}}
                <div class="mb-10 flex flex-wrap items-end justify-between gap-5 sm:mb-14">

                    <div>
                        <div class="mb-4 flex items-center gap-3">
                            <span class="h-[2px] w-7 bg-[#B59052]"></span>
                            <span class="text-xs font-bold text-[#0F3D62]">
                                افراد پروژه
                            </span>
                        </div>

                        <h2 class="text-3xl font-black tracking-tight sm:text-4xl">
                            اعضای تیم توسعه
                        </h2>
                    </div>

                    <span dir="ltr" class="text-xs font-bold tracking-[.2em] text-slate-400">
                        03 / MEMBERS
                    </span>

                </div>

                {{-- Team cards --}}
                <div class="overflow-hidden rounded-[24px] border border-[#E2E9EF] bg-white">

                    @foreach($team as $member)
                        <article class="group grid grid-cols-[auto_1fr] items-center gap-x-5 gap-y-5 border-b border-[#E2E9EF] px-6 py-9 transition-all duration-300 last:border-b-0 hover:bg-[#F8FAFC] sm:grid-cols-[65px_minmax(0,1fr)_180px_64px] sm:gap-6 sm:px-9 sm:py-11 lg:grid-cols-[90px_minmax(0,1fr)_240px_70px] lg:px-12 lg:py-12">

                            {{-- Number --}}
                            <div class="self-start pt-2 sm:self-center sm:pt-0">
                                <span dir="ltr" class="text-sm font-semibold tracking-widest text-[#A2B0BD]">
                                    {{ $member['number'] }}
                                </span>
                            </div>

                            {{-- Name and mobile role --}}
                            <div class="min-w-0">
                                <h3 dir="ltr"
                                    class="text-left text-[clamp(23px,3vw,42px)] font-extrabold leading-tight tracking-[-.04em] text-[#102C43] transition-colors duration-300 group-hover:text-[#0F3D62]">
                                    {{ $member['name'] }}
                                </h3>

                                <p dir="ltr"
                                   class="mt-3 text-left text-sm font-semibold tracking-wide text-[#73889A] sm:hidden">
                                    {{ $member['role'] }}
                                </p>

                                <p class="mt-4 text-sm leading-7 text-[#8192A1] sm:hidden">
                                    {{ $member['description'] }}
                                </p>
                            </div>

                            {{-- Desktop role --}}
                            <div class="hidden sm:block">
                                <span dir="ltr"
                                      class="block text-left text-sm font-bold tracking-wide text-[#0F3D62]">
                                    {{ $member['role'] }}
                                </span>

                                <p class="mt-2 text-xs leading-7 text-[#8192A1]">
                                    {{ $member['description'] }}
                                </p>
                            </div>

                            {{-- Icon --}}
                            <div class="col-span-2 flex justify-end sm:col-span-1">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-[#E0E9F0] bg-[#F4F8FB] text-[#0F3D62] transition-all duration-300 group-hover:-rotate-6 group-hover:border-[#0F3D62] group-hover:bg-[#0F3D62] group-hover:text-white sm:h-16 sm:w-16">

                                    @if($member['icon'] === 'code')
                                        <svg class="h-7 w-7"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.8"
                                             stroke-linecap="round"
                                             stroke-linejoin="round"
                                             viewBox="0 0 24 24">
                                            <path d="m8 17-5-5 5-5m8 0 5 5-5 5m-3-12-2 18"/>
                                        </svg>

                                    @elseif($member['icon'] === 'design')
                                        <svg class="h-7 w-7"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.7"
                                             stroke-linecap="round"
                                             stroke-linejoin="round"
                                             viewBox="0 0 24 24">
                                            <path d="M12 3a9 9 0 1 0 0 18h1.5a2 2 0 0 0 2-2c0-.8-.5-1.3-.5-2s.5-1.5 1.5-1.5H18a3 3 0 0 0 3-3A9 9 0 0 0 12 3Z"/>
                                            <circle cx="7.5" cy="11.5" r=".8" fill="currentColor" stroke="none"/>
                                            <circle cx="10" cy="7.5" r=".8" fill="currentColor" stroke="none"/>
                                            <circle cx="15" cy="8" r=".8" fill="currentColor" stroke="none"/>
                                            <circle cx="17" cy="12" r=".8" fill="currentColor" stroke="none"/>
                                        </svg>

                                    @else
                                        <svg class="h-7 w-7"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.7"
                                             stroke-linecap="round"
                                             stroke-linejoin="round"
                                             viewBox="0 0 24 24">
                                            <rect x="2" y="5" width="20" height="14" rx="3"/>
                                            <path d="M2 10h20M6 15h4"/>
                                        </svg>
                                    @endif

                                </div>
                            </div>

                        </article>
                    @endforeach

                </div>

                <p class="mt-6 text-center text-xs text-[#9AABB8]">
                    با همکاری، ایده‌ها به تجربه‌های واقعی تبدیل می‌شوند.
                </p>

            </div>
        </section>

        {{-- ================= BOTTOM CTA ================= --}}
        <section class="border-t border-[#E3EAF0] bg-[#F7F9FB] py-14 sm:py-20">
            <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-8 px-5 sm:px-8 lg:flex-row lg:items-center lg:px-10">

                <div>
                    <p dir="ltr" class="mb-3 text-xs font-bold tracking-[.25em] text-[#A1AFBA]">
                        BUILT WITH PURPOSE.
                    </p>

                    <h2 class="text-xl font-extrabold leading-relaxed text-[#102C43] sm:text-2xl">
                        ساخته‌شده برای آینده‌ای روشن‌تر.
                    </h2>

                    <p class="mt-3 text-sm leading-8 text-[#778A9A]">
                        وب‌سایت هنرستان علی بن حمزه، حاصل همراهی و تلاش تیم پروژه.
                    </p>
                </div>

                <a href="{{ route('home') }}"
                   class="inline-flex min-h-12 shrink-0 items-center justify-center gap-3 rounded-xl bg-[#0F3D62] px-6 py-3 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#0B2E4B] hover:shadow-lg hover:shadow-[#0F3D62]/15">

                    بازگشت به صفحه اصلی

                    <svg class="h-5 w-5"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         viewBox="0 0 24 24">
                        <path d="M19 12H5m6-6-6 6 6 6"/>
                    </svg>

                </a>

            </div>
        </section>

    </main>
@endsection
