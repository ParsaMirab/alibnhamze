@extends('alibnhamze.layouts.app')

@section('title', 'پیش‌ثبت‌نام | هنرستان علی بن حمزه')

@section('content')
    <div dir="rtl" class="font-sans bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">
        <main class="flex-1 flex items-start sm:items-center justify-center px-3 sm:px-6 lg:px-10 py-6 sm:py-10 lg:py-14">
            <div class="w-full max-w-2xl">

                {{-- Card --}}
                <div class="bg-white border border-slate-200 rounded-2xl shadow-xl shadow-slate-900/5
                            p-4 sm:p-8 lg:p-10">

                    {{-- Icon --}}
                    <div class="flex justify-center mb-4 sm:mb-6">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#0F3D62]/10 flex items-center justify-center">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#0F3D62]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="text-center mb-6 sm:mb-8">
                        <h1 class="text-xl sm:text-[28px] font-black text-slate-900 tracking-tight">
                            فرم پیش‌ثبت‌نام
                        </h1>

                        <p class="mt-2 sm:mt-3 text-xs sm:text-sm text-slate-500 leading-6 max-w-md mx-auto">
                            دانش‌آموز عزیز، خوشحالیم که ما را انتخاب کردید 🌟
                        </p>

                        {{-- Notice --}}
                        <div class="mt-4 sm:mt-6 text-right rounded-xl sm:rounded-2xl
                                    bg-amber-50/70 border border-amber-200/70
                                    px-3 sm:px-4 py-3 sm:py-3.5
                                    flex items-start gap-2.5 sm:gap-3">
                            <div class="shrink-0 w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-amber-100 flex items-center justify-center mt-0.5">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.33 16a2 2 0 001.74 3z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-[13px] font-bold text-amber-900 mb-1">
                                    توجه مهم
                                </p>
                                <p class="text-[11.5px] sm:text-[12.5px] text-amber-800/90 leading-5 sm:leading-6">
                                    نسبت به درست بودن اطلاعات مطمئن باشید؛ زیرا در صورت
                                    <b>تأیید درخواست پیش‌ثبت‌نام</b> و <b>اشتباه بودن اطلاعات</b>،
                                    باید این فرم دوباره پر شود.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- ================= STEP 1: Form ================= --}}
                    <div id="step-form">
                        <form id="pre-register-form" method="POST" action="#" class="space-y-4 sm:space-y-5">
                            @csrf
                            <input type="hidden" name="phone" id="final-phone" value="{{ old('phone') }}">
                            <input type="hidden" name="otp_verified" id="otp-verified" value="0">

                            {{-- نام و نام خانوادگی --}}
                            <div>
                                <label for="full_name" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">
                                    نام و نام خانوادگی دانش‌آموز
                                </label>
                                <div class="relative">
                                    <span class="hidden sm:flex absolute inset-y-0 right-0 items-center pr-4 pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </span>
                                    <input type="text" id="full_name" name="full_name"
                                           value="{{ old('full_name') }}"
                                           placeholder="مثلاً: علی محمدی"
                                           class="w-full pr-3.5 sm:pr-12 pl-3.5 sm:pl-4 py-3 sm:py-3.5 rounded-xl
                                                  text-sm sm:text-base
                                                  bg-slate-50 border border-slate-200
                                                  text-slate-900 placeholder:text-slate-400
                                                  focus:bg-white focus:border-[#0F3D62]
                                                  focus:ring-2 focus:ring-[#0F3D62]/15 focus:outline-none
                                                  transition-all duration-200">
                                </div>
                                @error('full_name')
                                <p class="mt-1.5 text-[11px] sm:text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- نام پدر و نام مادر --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                                <div>
                                    <label for="father_name" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">
                                        نام پدر
                                    </label>
                                    <div class="relative">
                                        <span class="hidden sm:flex absolute inset-y-0 right-0 items-center pr-4 pointer-events-none">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </span>
                                        <input type="text" id="father_name" name="father_name"
                                               value="{{ old('father_name') }}"
                                               placeholder="مثلاً: محمد"
                                               class="w-full pr-3.5 sm:pr-12 pl-3.5 sm:pl-4 py-3 sm:py-3.5 rounded-xl
                                                      text-sm sm:text-base
                                                      bg-slate-50 border border-slate-200
                                                      text-slate-900 placeholder:text-slate-400
                                                      focus:bg-white focus:border-[#0F3D62]
                                                      focus:ring-2 focus:ring-[#0F3D62]/15 focus:outline-none
                                                      transition-all duration-200">
                                    </div>
                                    @error('father_name')
                                    <p class="mt-1.5 text-[11px] sm:text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="mother_full_name" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">
                                        نام و نام خانوادگی مادر
                                    </label>
                                    <div class="relative">
                                        <span class="hidden sm:flex absolute inset-y-0 right-0 items-center pr-4 pointer-events-none">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </span>
                                        <input type="text" id="mother_full_name" name="mother_full_name"
                                               value="{{ old('mother_full_name') }}"
                                               placeholder="مثلاً: زهرا احمدی"
                                               class="w-full pr-3.5 sm:pr-12 pl-3.5 sm:pl-4 py-3 sm:py-3.5 rounded-xl
                                                      text-sm sm:text-base
                                                      bg-slate-50 border border-slate-200
                                                      text-slate-900 placeholder:text-slate-400
                                                      focus:bg-white focus:border-[#0F3D62]
                                                      focus:ring-2 focus:ring-[#0F3D62]/15 focus:outline-none
                                                      transition-all duration-200">
                                    </div>
                                    @error('mother_full_name')
                                    <p class="mt-1.5 text-[11px] sm:text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- کد ملی --}}
                            <div>
                                <label for="national_code" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">
                                    کد ملی دانش‌آموز
                                </label>
                                <div class="relative">
                                    <span class="hidden sm:flex absolute inset-y-0 right-0 items-center pr-4 pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.418.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                        </svg>
                                    </span>
                                    <input type="text" id="national_code" name="national_code"
                                           inputmode="numeric" maxlength="10" dir="ltr"
                                           value="{{ old('national_code') }}"
                                           placeholder="0012345678"
                                           class="w-full pr-3.5 sm:pr-12 pl-3.5 sm:pl-4 py-3 sm:py-3.5 rounded-xl
                                                  text-sm sm:text-base text-left tracking-widest
                                                  bg-slate-50 border border-slate-200
                                                  text-slate-900 placeholder:text-slate-400
                                                  focus:bg-white focus:border-[#0F3D62]
                                                  focus:ring-2 focus:ring-[#0F3D62]/15 focus:outline-none
                                                  transition-all duration-200">
                                </div>
                                <p class="mt-1.5 text-[11px] sm:text-xs text-slate-400">۱۰ رقم بدون خط تیره</p>
                                @error('national_code')
                                <p class="mt-1.5 text-[11px] sm:text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- پایه تحصیلی --}}
                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">
                                    پایه تحصیلی
                                </label>

                                <input type="hidden" name="grade" id="grade-input" value="{{ old('grade') }}">

                                <div class="grid grid-cols-3 gap-1.5 sm:gap-2 p-1 sm:p-1.5 rounded-xl bg-slate-50 border border-slate-200">
                                    @foreach (['دهم' => 10, 'یازدهم' => 11, 'دوازدهم' => 12] as $label => $val)
                                        <button type="button"
                                                data-grade="{{ $val }}"
                                                class="grade-btn py-2.5 sm:py-3 rounded-lg
                                                       text-xs sm:text-sm font-bold
                                                       text-slate-600 hover:text-[#0F3D62]
                                                       transition-all duration-200
                                                       {{ old('grade') == $val ? 'bg-white text-[#0F3D62] shadow-sm' : '' }}">
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>

                                @error('grade')
                                <p class="mt-1.5 text-[11px] sm:text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- معدل و انضباط --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                                <div>
                                    <label for="gpa" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">
                                        معدل کل سال تحصیلی قبل
                                    </label>
                                    <div class="relative">
                                        <span class="hidden sm:flex absolute inset-y-0 right-0 items-center pr-4 pointer-events-none">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </span>
                                        <input type="text" id="gpa" name="gpa"
                                               inputmode="decimal" dir="ltr"
                                               value="{{ old('gpa') }}"
                                               placeholder="18.75"
                                               class="w-full pr-3.5 sm:pr-12 pl-3.5 sm:pl-4 py-3 sm:py-3.5 rounded-xl
                                                      text-sm sm:text-base text-left
                                                      bg-slate-50 border border-slate-200
                                                      text-slate-900 placeholder:text-slate-400
                                                      focus:bg-white focus:border-[#0F3D62]
                                                      focus:ring-2 focus:ring-[#0F3D62]/15 focus:outline-none
                                                      transition-all duration-200">
                                    </div>
                                    <p class="mt-1.5 text-[11px] sm:text-xs text-slate-400">بین ۰ تا ۲۰</p>
                                    @error('gpa')
                                    <p class="mt-1.5 text-[11px] sm:text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="discipline_score" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">
                                        نمره انضباط سال قبل
                                    </label>
                                    <div class="relative">
                                        <span class="hidden sm:flex absolute inset-y-0 right-0 items-center pr-4 pointer-events-none">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                            </svg>
                                        </span>
                                        <input type="text" id="discipline_score" name="discipline_score"
                                               inputmode="decimal" dir="ltr"
                                               value="{{ old('discipline_score') }}"
                                               placeholder="20"
                                               class="w-full pr-3.5 sm:pr-12 pl-3.5 sm:pl-4 py-3 sm:py-3.5 rounded-xl
                                                      text-sm sm:text-base text-left
                                                      bg-slate-50 border border-slate-200
                                                      text-slate-900 placeholder:text-slate-400
                                                      focus:bg-white focus:border-[#0F3D62]
                                                      focus:ring-2 focus:ring-[#0F3D62]/15 focus:outline-none
                                                      transition-all duration-200">
                                    </div>
                                    <p class="mt-1.5 text-[11px] sm:text-xs text-slate-400">بین ۰ تا ۲۰</p>
                                    @error('discipline_score')
                                    <p class="mt-1.5 text-[11px] sm:text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- شماره موبایل --}}
                            <div>
                                <label for="phone" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">
                                    شماره تلفن همراه
                                </label>
                                <div class="relative">
                                    <span class="hidden sm:flex absolute inset-y-0 right-0 items-center pr-4 pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 5a2 2 0 012-2h2.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.24 1.05L7.6 9.6a12 12 0 005.8 5.8l1.57-1.49a1 1 0 011.05-.24l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2A16 16 0 013 5z"/>
                                        </svg>
                                    </span>
                                    <input type="tel" id="phone" name="phone"
                                           inputmode="numeric" maxlength="11" dir="ltr"
                                           value="{{ old('phone') }}"
                                           placeholder="09123456789"
                                           class="w-full pr-3.5 sm:pr-12 pl-3.5 sm:pl-4 py-3 sm:py-3.5 rounded-xl
                                                  text-sm sm:text-base text-left tracking-widest
                                                  bg-slate-50 border border-slate-200
                                                  text-slate-900 placeholder:text-slate-400
                                                  focus:bg-white focus:border-[#0F3D62]
                                                  focus:ring-2 focus:ring-[#0F3D62]/15 focus:outline-none
                                                  transition-all duration-200">
                                </div>
                                <p class="mt-1.5 text-[11px] sm:text-xs text-slate-400">
                                    کد تأیید ۶ رقمی به این شماره پیامک می‌شود
                                </p>
                                @error('phone')
                                <p class="mt-1.5 text-[11px] sm:text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- دکمه‌ی ادامه --}}
                            <button type="button" id="go-to-otp"
                                    class="w-full inline-flex items-center justify-center gap-2
                                           py-3 sm:py-3.5 rounded-xl bg-[#0F3D62] text-white
                                           text-sm font-bold
                                           shadow-lg shadow-[#0F3D62]/25
                                           hover:bg-[#0a2d4a] active:bg-[#0a2d4a]
                                           transition-all duration-200">
                                تأیید شماره و ادامه
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    {{-- ================= STEP 2: OTP ================= --}}
                    <div id="step-otp" class="hidden">
                        <div class="text-center mb-6">
                            <div class="flex justify-center mb-4">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#0F3D62]/10 flex items-center justify-center">
                                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#0F3D62]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>

                            <h2 class="text-lg sm:text-xl font-black text-slate-900">
                                تأیید شماره تلفن همراه
                            </h2>

                            <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-6">
                                کد تأیید ۶ رقمی به شماره
                                <span id="phone-display" dir="ltr"
                                      class="inline-block font-bold text-slate-900"></span>
                                ارسال شد
                            </p>
                        </div>

                        <div class="space-y-4 sm:space-y-5">
                            <div>
                                <label for="otp_code" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2 text-center">
                                    کد تأیید
                                </label>
                                <input
                                    type="text"
                                    id="otp_code"
                                    inputmode="numeric"
                                    autocomplete="one-time-code"
                                    maxlength="6"
                                    placeholder="- - - - - -"
                                    dir="ltr"
                                    class="w-full px-4 py-3.5 sm:py-4 rounded-xl text-center
                                           tracking-[0.5em] sm:tracking-[0.6em]
                                           text-xl sm:text-2xl font-black
                                           bg-slate-50 border border-slate-200
                                           text-slate-900 placeholder:text-slate-300
                                           focus:bg-white focus:border-[#0F3D62]
                                           focus:ring-2 focus:ring-[#0F3D62]/15 focus:outline-none
                                           transition-all duration-200">
                                <p id="otp-error" class="hidden mt-2 text-xs text-red-600 text-center"></p>
                            </div>

                            <button type="button" id="verify-otp"
                                    class="w-full inline-flex items-center justify-center gap-2
                                           py-3 sm:py-3.5 rounded-xl bg-[#0F3D62] text-white
                                           text-sm font-bold
                                           shadow-lg shadow-[#0F3D62]/25
                                           hover:bg-[#0a2d4a] active:bg-[#0a2d4a]
                                           transition-all duration-200">
                                تأیید و ثبت نهایی
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>

                            {{-- Resend / Edit --}}
                            <div class="flex items-center justify-between text-[11px] sm:text-xs pt-1">
                                <button type="button" id="edit-info"
                                        class="inline-flex items-center gap-1 text-slate-500
                                               hover:text-[#0F3D62] transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    ویرایش اطلاعات
                                </button>

                                <button type="button" id="resend-code"
                                        class="font-semibold text-[#0F3D62] disabled:text-slate-400
                                               disabled:cursor-not-allowed transition-colors">
                                    ارسال مجدد کد
                                    <span id="resend-timer"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Divider --}}
                <div class="mt-4 sm:mt-6 flex items-center gap-3">
                    <span class="flex-1 h-px bg-slate-200"></span>
                    <span class="text-[11px] sm:text-xs text-slate-400">یا</span>
                    <span class="flex-1 h-px bg-slate-200"></span>
                </div>

                {{-- Back home --}}
                <a href="{{ url('/') }}"
                   class="mt-4 sm:mt-6 w-full inline-flex items-center justify-center gap-2
                          py-3 sm:py-3.5 rounded-xl bg-white text-slate-700
                          text-sm font-semibold
                          border border-slate-200
                          hover:border-[#0F3D62]/40 hover:text-[#0F3D62]
                          active:bg-slate-50 transition-all duration-200">
                    بازگشت به صفحه اصلی
                </a>

                {{-- Footer note --}}
                <p class="mt-4 sm:mt-6 text-center text-[11px] sm:text-xs text-slate-500 leading-6">
                    با ارسال این فرم،
                    <a href="#" class="text-[#0F3D62] font-semibold hover:underline">قوانین و مقررات</a>
                    هنرستان را می‌پذیرید.
                </p>

            </div>
        </main>
    </div>

    {{-- ================= Scripts ================= --}}
    <script>
        (function () {
            // ===== مراجع DOM =====
            const stepForm  = document.getElementById('step-form');
            const stepOtp   = document.getElementById('step-otp');

            const phoneInput = document.getElementById('phone');
            const finalPhone = document.getElementById('final-phone');

            const goToOtpBtn  = document.getElementById('go-to-otp');
            const editInfoBtn = document.getElementById('edit-info');
            const verifyBtn   = document.getElementById('verify-otp');
            const resendBtn   = document.getElementById('resend-code');
            const resendTimer = document.getElementById('resend-timer');

            const phoneDisplay = document.getElementById('phone-display');
            const otpCode      = document.getElementById('otp_code');

            const gradeInput = document.getElementById('grade-input');
            const buttons    = document.querySelectorAll('.grade-btn');

            // ===== سوییچ پایه تحصیلی =====
            function setActive(btn) {
                buttons.forEach(b => {
                    b.classList.remove('bg-white', 'text-[#0F3D62]', 'shadow-sm');
                    b.classList.add('text-slate-600');
                });
                btn.classList.remove('text-slate-600');
                btn.classList.add('bg-white', 'text-[#0F3D62]', 'shadow-sm');
            }

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    gradeInput.value = btn.dataset.grade;
                    setActive(btn);
                });
            });

            // ===== مرحله ۱ → ۲ =====
            goToOtpBtn?.addEventListener('click', function () {
                const phone = phoneInput.value.trim();
                finalPhone.value = phone;

                stepForm.classList.add('hidden');
                stepOtp.classList.remove('hidden');

                phoneDisplay.textContent = phone;
                otpCode?.focus();

                startResendTimer(60);

                fetch('#', {...})
            });

            // ===== بازگشت به فرم =====
            editInfoBtn?.addEventListener('click', function () {
                stepOtp.classList.add('hidden');
                stepForm.classList.remove('hidden');
            });

            // ===== تأیید کد و ارسال نهایی =====
            verifyBtn?.addEventListener('click', function () {
                // TODO: ارسال درخواست به سرور برای تأیید کد
                // بعد از تأیید موفق، فرم نهایی را submit کن:
                // preForm.submit();
            });

            // ===== تایمر ارسال مجدد =====
            let timerId = null;

            function startResendTimer(seconds) {
                if (timerId) clearInterval(timerId);
                resendBtn.disabled = true;
                let remaining = seconds;

                const tick = () => {
                    resendTimer.textContent = remaining > 0 ? ` (${remaining})` : '';
                    if (remaining <= 0) {
                        clearInterval(timerId);
                        resendBtn.disabled = false;
                        return;
                    }
                    remaining--;
                };
                tick();
                timerId = setInterval(tick, 1000);
            }

            resendBtn?.addEventListener('click', function () {
                // TODO: درخواست ارسال مجدد کد
                startResendTimer(60);
            });
        })();
    </script>
@endsection
