
@extends('alibnhamze.layouts.app')

@section('title', 'داشبورد دانش‌آموز | هنرستان علی بن حمزه')

@section('content')

    @php
        $fullName = trim(
            ($student->first_name ?? '') . ' ' .
            ($student->last_name ?? '')
        );

        $gradeLabel = match ((int) $student->grade) {
            10 => 'پایه دهم',
            11 => 'پایه یازدهم',
            12 => 'پایه دوازدهم',
            default => 'ثبت نشده',
        };
    @endphp

    <main dir="rtl"
          class="min-h-screen bg-[#F8FAFC] text-slate-800 antialiased">

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-10 lg:py-12">

            {{-- ================= PAGE HEADER ================= --}}
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="mb-3 flex items-center gap-2 text-xs text-slate-500">
                        <span>پنل دانش‌آموزی</span>
                        <span>/</span>
                        <span class="font-semibold text-[#0F3D62]">
                        داشبورد
                    </span>
                    </div>

                    <h1 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">
                        سلام، {{ $student->first_name }}!
                        <span aria-hidden="true">👋</span>
                    </h1>

                    <p class="mt-2 text-sm leading-7 text-slate-500">
                        به پنل دانش‌آموزی هنرستان علی بن حمزه خوش اومدی.
                    </p>
                </div>

                <div class="inline-flex items-center gap-3 self-start rounded-xl border border-slate-200 bg-white px-4 py-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#0F3D62]/10">
                        <svg class="h-5 w-5 text-[#0F3D62]"
                             fill="none" stroke="currentColor"
                             stroke-width="1.7" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 9 12 4l9 5-9 5-9-5Zm4 3v5c3 3 7 3 10 0v-5"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-slate-800">
                            {{ $gradeLabel }}
                        </p>
                        <p class="text-xs text-slate-500">
                            دانش‌آموز هنرستان
                        </p>
                    </div>

                </div>
            </div>

            {{-- ================= WELCOME BANNER ================= --}}
            <section class="relative mb-7 overflow-hidden rounded-2xl bg-[#0F3D62] p-6 text-white sm:p-8 lg:p-10">

                <div class="pointer-events-none absolute -left-20 -top-24 h-64 w-64 rounded-full border border-white/10"></div>
                <div class="pointer-events-none absolute -bottom-32 left-20 h-72 w-72 rounded-full border border-white/10"></div>

                <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

                    <div class="max-w-2xl">

                        <p class="mb-4 flex items-center gap-3 text-xs font-bold text-[#C7D9E6]">
                            <span class="h-0.5 w-7 bg-[#B59052]"></span>
                            فضای آموزشی من
                        </p>

                        <h2 class="text-xl font-extrabold leading-relaxed sm:text-2xl lg:text-3xl">
                            هر روز، فرصتی تازه
                            <br>
                            برای یادگیری و پیشرفت.
                        </h2>

                        <p class="mt-4 max-w-xl text-sm leading-8 text-[#D0DDE7]">
                            اطلاعات تحصیلی، وضعیت حضور و غیاب
                            و امکانات آموزشی خودت رو از همین صفحه دنبال کن.
                        </p>

                    </div>

                    <div class="flex flex-wrap gap-3">

                        <div class="rounded-xl border border-white/20 bg-white/10 px-5 py-4 backdrop-blur-sm">

                            <p class="text-xs text-[#C7D9E6]">
                                نام دانش‌آموز
                            </p>

                            <p class="mt-2 text-sm font-bold text-white">
                                {{ $fullName ?: 'دانش‌آموز' }}
                            </p>

                        </div>

                        <div class="rounded-xl border border-white/20 bg-white/10 px-5 py-4 backdrop-blur-sm">

                            <p class="text-xs text-[#C7D9E6]">
                                پایه تحصیلی
                            </p>

                            <p class="mt-2 text-sm font-bold text-white">
                                {{ $gradeLabel }}
                            </p>

                        </div>

                    </div>

                </div>

            </section>

            {{-- ================= SECTION TITLE ================= --}}
            <div class="mb-5 flex items-center justify-between gap-3">

                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">
                        وضعیت حضور و غیاب
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        خلاصه وضعیت غیبت‌های ثبت‌شده شما
                    </p>
                </div>

            </div>

            {{-- ================= STATISTICS ================= --}}
            <section class="mb-9 grid grid-cols-1 gap-4 sm:grid-cols-3">

                {{-- Total --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 transition-all hover:border-[#0F3D62]/20 hover:shadow-lg hover:shadow-slate-900/5">

                    <div class="mb-5 flex items-center justify-between">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#EAF1F6]">
                            <svg class="h-5 w-5 text-[#0F3D62]"
                                 viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.7">
                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                <path d="M7 3v4M17 3v4M3 10h18M8 15h3"/>
                            </svg>
                        </div>

                        <span class="text-xs text-slate-400">
                        کل سوابق
                    </span>

                    </div>

                    <p class="text-3xl font-black text-[#0F3D62]">
                        {{ number_format($totalAbsences) }}
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-700">
                        مجموع غیبت‌ها
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        تعداد سوابق ثبت‌شده
                    </p>

                </div>

                {{-- Excused --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 transition-all hover:border-emerald-200 hover:shadow-lg hover:shadow-slate-900/5">

                    <div class="mb-5 flex items-center justify-between">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50">
                            <svg class="h-5 w-5 text-emerald-600"
                                 viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.7">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="m8 12 3 3 5-6"/>
                            </svg>
                        </div>

                        <span class="text-xs text-emerald-600">
                        موجه
                    </span>

                    </div>

                    <p class="text-3xl font-black text-emerald-600">
                        {{ number_format($excusedAbsences) }}
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-700">
                        غیبت‌های موجه
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        غیبت‌های تأییدشده به‌عنوان موجه
                    </p>

                </div>

                {{-- Unexcused --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 transition-all hover:border-rose-200 hover:shadow-lg hover:shadow-slate-900/5">

                    <div class="mb-5 flex items-center justify-between">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50">
                            <svg class="h-5 w-5 text-rose-600"
                                 viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.7">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7v6m0 4h.01"/>
                            </svg>
                        </div>

                        <span class="text-xs text-rose-600">
                        غیرموجه
                    </span>

                    </div>

                    <p class="text-3xl font-black text-rose-600">
                        {{ number_format($unexcusedAbsences) }}
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-700">
                        غیبت‌های غیرموجه
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        غیبت‌هایی که موجه ثبت نشده‌اند
                    </p>

                </div>

            </section>

            {{-- ================= MAIN GRID ================= --}}
            <div class="grid gap-6 lg:grid-cols-3">

                {{-- Recent Absences --}}

                {{-- ================= RECENT ABSENCES ================= --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white lg:col-span-2">

                    <div class="flex items-center justify-between gap-3 border-b border-slate-100 p-5 sm:p-6">
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900">
                                آخرین غیبت‌های من
                            </h2>

                            <p class="mt-1 text-xs text-slate-500">
                                جزئیات آخرین سوابق حضور و غیاب شما
                            </p>
                        </div>

                        <span class="rounded-lg bg-slate-50 px-3 py-2 text-xs font-medium text-slate-500">
            ۵ مورد آخر
        </span>
                    </div>

                    <div class="divide-y divide-slate-100 px-5 sm:px-6">

                        @forelse($recentAbsences as $absence)
                            @php
                                $assignment = $absence->classTeacher;
                                $teacher = $assignment?->teacher;

                                $teacherName = $teacher
                                    ? trim($teacher->first_name . ' ' . $teacher->last_name)
                                    : null;
                            @endphp

                            <div class="flex flex-col gap-4 py-5 sm:flex-row sm:items-center sm:justify-between">

                                <div class="flex items-start gap-3">

                                    {{-- Subject icon --}}
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#0F3D62]/10">
                                        <svg class="h-5 w-5 text-[#0F3D62]"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.7"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                                            <path d="M9 7h7M9 11h5"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0">

                                        {{-- Subject --}}
                                        <h3 class="text-sm font-extrabold text-slate-800">
                                            {{ $assignment?->subject ?? 'غیبت ثبت‌شده' }}
                                        </h3>

                                        {{-- Teacher --}}
                                        <div class="mt-2 flex items-center gap-2 text-xs text-slate-500">
                                            <svg class="h-4 w-4 shrink-0"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="1.7">
                                                <circle cx="12" cy="8" r="4"/>
                                                <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                                            </svg>

                                            <span>
                                معلم:
                                <span class="font-semibold text-slate-700">
                                    {{ $teacherName ?: 'ثبت نشده' }}
                                </span>
                            </span>
                                        </div>

                                        {{-- Date --}}
                                        <div class="mt-2 flex items-center gap-2 text-xs text-slate-500">
                                            <svg class="h-4 w-4 shrink-0"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="1.7">
                                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                                <path d="M7 3v4M17 3v4M3 10h18"/>
                                            </svg>

                                            <span>
                                تاریخ غیبت:
                                <span class="font-medium text-slate-700">
                                    {{ \App\Helpers\Jalali::format($absence->date) }}
                                </span>
                            </span>
                                        </div>

                                    </div>

                                </div>

                                {{-- Status --}}
                                <div class="shrink-0 self-start sm:self-center">

                                    @if($absence->is_excused)
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-100 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            غیبت موجه
                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-rose-100 bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                            غیبت غیرموجه
                        </span>
                                    @endif

                                </div>

                            </div>

                        @empty

                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50">
                                    <svg class="h-7 w-7 text-emerald-600"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.7">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="m8 12 3 3 5-6"/>
                                    </svg>
                                </div>

                                <h3 class="text-sm font-bold text-slate-800">
                                    غیبتی ثبت نشده است
                                </h3>

                                <p class="mt-2 text-xs leading-7 text-slate-500">
                                    در حال حاضر سابقه غیبتی برای شما در سامانه وجود ندارد.
                                </p>
                            </div>

                        @endforelse

                    </div>

                </section>

                {{-- Student Information --}}
                <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">

                    <div class="mb-6">
                        <h2 class="text-base font-extrabold text-slate-900">
                            اطلاعات تحصیلی من
                        </h2>

                        <p class="mt-1 text-xs text-slate-500">
                            مشخصات ثبت‌شده در سامانه
                        </p>
                    </div>

                    <div class="space-y-5">

                        <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <span class="text-xs text-slate-500">
                            نام و نام خانوادگی
                        </span>

                            <span class="text-xs font-bold text-slate-800">
                            {{ $fullName ?: '—' }}
                        </span>
                        </div>

                        <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <span class="text-xs text-slate-500">
                            پایه تحصیلی
                        </span>

                            <span class="text-xs font-bold text-slate-800">
                            {{ $gradeLabel }}
                        </span>
                        </div>

                        <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <span class="text-xs text-slate-500">
                            شماره دانش‌آموزی
                        </span>

                            <span class="text-xs font-bold text-slate-800" dir="ltr">
                            #{{ $student->id }}
                        </span>
                        </div>

                        <div class="flex items-center justify-between gap-3">
                        <span class="text-xs text-slate-500">
                            شماره همراه
                        </span>

                            <span class="text-xs font-bold text-slate-800" dir="ltr">
                            {{ $student->phone ?: 'ثبت نشده' }}
                        </span>
                        </div>

                    </div>

                    <div class="mt-7 rounded-xl bg-[#F3F6F8] p-4">

                        <p class="text-xs font-semibold text-[#0F3D62]">
                            نکته آموزشی
                        </p>

                        <p class="mt-2 text-xs leading-7 text-slate-600">
                            وضعیت حضور و غیاب خود را به‌صورت منظم بررسی کن
                            و در صورت وجود مغایرت، موضوع را با مسئولان هنرستان مطرح کن.
                        </p>

                    </div>

                </section>

            </div>

            {{-- ================= FUTURE MODULES ================= --}}
            <section class="mt-9">

                <div class="mb-5">
                    <h2 class="text-lg font-extrabold text-slate-900">
                        امکانات پنل دانش‌آموزی
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        بخش‌هایی که به‌مرور به پنل اضافه می‌شوند
                    </p>
                </div>

                @php
                    $futureModules = [
                        ['title' => 'همکلاسی‌های من', 'description' => 'آشنایی با دانش‌آموزان کلاس'],
                        ['title' => 'معلم‌های من', 'description' => 'مشاهده معلمان کلاس'],
                        ['title' => 'برنامه هفتگی', 'description' => 'برنامه درس‌ها و کلاس‌ها'],
                        ['title' => 'اطلاعیه‌ها', 'description' => 'اخبار و پیام‌های هنرستان'],
                    ];
                @endphp

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    @foreach($futureModules as $module)

                        <div class="rounded-2xl border border-slate-200 bg-white p-5">

                            <div class="mb-5 flex items-center justify-between">

                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EAF1F6]">
                                    <svg class="h-5 w-5 text-[#0F3D62]"
                                         viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.7">
                                        <rect x="4" y="4" width="16" height="16" rx="3"/>
                                        <path d="M8 9h8M8 13h8M8 17h5"/>
                                    </svg>
                                </div>

                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-medium text-slate-500">
                                به‌زودی
                            </span>

                            </div>

                            <h3 class="text-sm font-bold text-slate-800">
                                {{ $module['title'] }}
                            </h3>

                            <p class="mt-2 text-xs leading-6 text-slate-500">
                                {{ $module['description'] }}
                            </p>

                        </div>

                    @endforeach

                </div>

            </section>

        </div>

    </main>

@endsection
