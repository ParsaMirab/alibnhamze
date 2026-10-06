@extends('alibnhamze.layouts.app')

@section('title', 'ورود به سامانه | هنرستان علی بن حمزه')

@section('content')
    <div dir="rtl" class="font-sans bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">
        {{-- ================= LOGIN ================= --}}
        <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-10 py-10 sm:py-14">
            <div class="w-full max-w-md">

                {{-- Card --}}
                <div class="bg-white border border-slate-200 rounded-2xl shadow-xl shadow-slate-900/5
                            p-6 sm:p-8 lg:p-10">

                    {{-- Icon --}}
                    <div class="flex justify-center mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-[#0F3D62]/10 flex items-center justify-center">
                            <svg class="w-7 h-7 text-[#0F3D62]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="text-center mb-8">
                        {{-- Title --}}
                        <h1 class="text-2xl sm:text-[28px] font-black text-slate-900 tracking-tight">
                            ورود به سامانه دانش آموزی
                        </h1>

                        {{-- Subtitle --}}
                        <p class="mt-3 text-sm text-slate-500 leading-6 max-w-xs mx-auto">
                            برای ورود، شماره تلفن همراه خود را وارد کنید
                        </p>

                        {{-- Notice --}}
                        <div class="mt-6 text-right rounded-2xl
                bg-amber-50/70 border border-amber-200/70
                px-4 py-3.5
                flex items-start gap-3">
                            <div class="shrink-0 w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.33 16a2 2 0 001.74 3z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-[13px] font-bold text-amber-900 mb-1">
                                    دسترسی محدود
                                </p>
                                <p class="text-[12.5px] text-amber-800/90 leading-6">
                                    فقط کادر مدرسه، اولیا و دانش‌آموزان هنرستان اجازه‌ی ورود دارند.
                                    در صورت درخواست ثبت‌نام، به هنرستان مراجعه کنید یا
                                    <a href="#" class="font-bold text-amber-900 underline underline-offset-2 hover:text-amber-950">
                                        فرم پیش‌ثبت‌نام
                                    </a>
                                    را ارسال نمایید.
                                </p>
                            </div>
                        </div>

                    </div>

                    {{-- ================= STEP 1: Phone ================= --}}
                    <form id="phone-form" method="POST" action="#" class="space-y-5">
                        @csrf

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">
                                شماره تلفن همراه
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 5a2 2 0 012-2h2.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.24 1.05L7.6 9.6a12 12 0 005.8 5.8l1.57-1.49a1 1 0 011.05-.24l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2A16 16 0 013 5z"/>
                                    </svg>
                                </span>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    inputmode="numeric"
                                    autocomplete="tel"
                                    placeholder="۰۹۱۲۳۴۵۶۷۸۹"
                                    dir="ltr"
                                    value="{{ old('phone') }}"
                                    class="w-full pr-12 pl-4 py-3.5 rounded-xl text-left
                                           bg-slate-50 border border-slate-200
                                           text-slate-900 placeholder:text-slate-400
                                           focus:bg-white focus:border-[#0F3D62]
                                           focus:ring-2 focus:ring-[#0F3D62]/15 focus:outline-none
                                           transition-all duration-200"
                                >
                            </div>

                            @error('phone')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2
                                       py-3.5 rounded-xl bg-[#0F3D62] text-white text-sm font-bold
                                       shadow-lg shadow-[#0F3D62]/25
                                       hover:bg-[#0a2d4a] active:bg-[#0a2d4a]
                                       transition-all duration-200">
                            دریافت کد تأیید
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                    </form>

                    {{-- ================= STEP 2: OTP ================= --}}
                    <form id="otp-form" method="POST" action="#"
                          class="space-y-5 hidden">
                        @csrf
                        <input type="hidden" name="phone" id="otp-phone" value="{{ old('phone') }}">

                        <div class="text-center">
                            <p class="text-sm text-slate-600 leading-7">
                                کد تأیید به شماره
                                <span id="phone-display" dir="ltr"
                                      class="inline-block font-bold text-slate-900"></span>
                                ارسال شد
                            </p>
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-semibold text-slate-700 mb-2">
                                کد تأیید ۶ رقمی
                            </label>
                            <input
                                type="text"
                                id="code"
                                name="code"
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                maxlength="6"
                                placeholder="- - - - - -"
                                dir="ltr"
                                class="w-full px-4 py-4 rounded-xl text-center tracking-[0.6em]
                                       text-2xl font-black
                                       bg-slate-50 border border-slate-200
                                       text-slate-900 placeholder:text-slate-300
                                       focus:bg-white focus:border-[#0F3D62]
                                       focus:ring-2 focus:ring-[#0F3D62]/15 focus:outline-none
                                       transition-all duration-200"
                            >
                            @error('code')
                            <p class="mt-2 text-xs text-red-600 text-center">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2
                                       py-3.5 rounded-xl bg-[#0F3D62] text-white text-sm font-bold
                                       shadow-lg shadow-[#0F3D62]/25
                                       hover:bg-[#0a2d4a] active:bg-[#0a2d4a]
                                       transition-all duration-200">
                            ورود به سامانه
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        {{-- Resend --}}
                        <div class="flex items-center justify-between text-xs pt-1">
                            <button type="button" id="edit-phone"
                                    class="inline-flex items-center gap-1 text-slate-500
                                           hover:text-[#0F3D62] transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                ویرایش شماره
                            </button>

                            <button type="button" id="resend-code"
                                    class="font-semibold text-[#0F3D62] disabled:text-slate-400
                                           disabled:cursor-not-allowed transition-colors">
                                ارسال مجدد کد
                                <span id="resend-timer"></span>
                            </button>
                        </div>
                    </form>

                    {{-- Divider --}}
                    <div class="my-7 flex items-center gap-3">
                        <span class="flex-1 h-px bg-slate-100"></span>
                        <span class="text-xs text-slate-400">یا</span>
                        <span class="flex-1 h-px bg-slate-100"></span>
                    </div>

                    {{-- Back home --}}
                    <a href="{{ url('/') }}"
                       class="w-full inline-flex items-center justify-center gap-2
                              py-3.5 rounded-xl bg-white text-slate-700 text-sm font-semibold
                              border border-slate-200
                              hover:border-[#0F3D62]/40 hover:text-[#0F3D62]
                              active:bg-slate-50 transition-all duration-200">
                        بازگشت به صفحه اصلی
                    </a>
                </div>

                {{-- Footer note --}}
                <p class="mt-6 text-center text-xs text-slate-500 leading-6">
                    با ورود به سامانه،
                    <a href="#" class="text-[#0F3D62] font-semibold hover:underline">قوانین و مقررات</a>
                    را می‌پذیرید.
                </p>

            </div>
        </main>
    </div>

    {{-- ================= Scripts ================= --}}
    <script>
        (function () {
            const phoneForm = document.getElementById('phone-form');
            const otpForm = document.getElementById('otp-form');
            const phoneInput = document.getElementById('phone');
            const otpPhone = document.getElementById('otp-phone');
            const phoneDisplay = document.getElementById('phone-display');
            const codeInput = document.getElementById('code');
            const editPhone = document.getElementById('edit-phone');
            const resendBtn = document.getElementById('resend-code');
            const resendTimer = document.getElementById('resend-timer');

            const hasOtpError = {{ $errors->has('code') ? 'true' : 'false' }};
            const oldPhone = @json(old('phone'));

            function goToOtp(phone) {
                phoneForm.classList.add('hidden');
                otpForm.classList.remove('hidden');

                otpPhone.value = phone || '';
                phoneDisplay.textContent = phone || '';
                codeInput.focus();
                startResendTimer(60);
            }

            function goToPhone() {
                otpForm.classList.add('hidden');
                phoneForm.classList.remove('hidden');
                phoneInput.focus();
            }

            // ارسال فرم شماره (فقط برای نمایش مرحله ۲ - ارسال واقعی توسط سرور)
            phoneForm.addEventListener('submit', function (e) {
                const value = phoneInput.value.trim();
                // اعتبارسنجی سبک سمت کلاینت (سرور هم باید چک کند)
                if (!/^09\d{9}$/.test(value)) {
                    e.preventDefault();
                    phoneInput.focus();
                    return;
                }
                otpPhone.value = value;
            });

            // اگر در حالت OTP هستیم، مرحله را تنظیم کن
            if (hasOtpError && oldPhone) {
                goToOtp(oldPhone);
            }

            // ویرایش شماره
            editPhone?.addEventListener('click', goToPhone);

            // فقط عدد در فیلد کد
            codeInput?.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 6);
            });

            // فقط عدد در فیلد شماره
            phoneInput?.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 11);
            });

            // تایمر ارسال مجدد
            let timerId = null;

            function startResendTimer(seconds) {
                if (timerId) clearInterval(timerId);
                resendBtn.disabled = true;
                let remaining = seconds;

                const tick = () => {
                    resendTimer.textContent = `(${remaining})`;
                    if (remaining <= 0) {
                        clearInterval(timerId);
                        resendBtn.disabled = false;
                        resendTimer.textContent = '';
                        return;
                    }
                    remaining--;
                };
                tick();
                timerId = setInterval(tick, 1000);
            }

            // ارسال مجدد (نمونه - درخواست به سرور)
            resendBtn?.addEventListener('click', async function () {
                const phone = otpPhone.value;
                if (!phone) return;

                resendBtn.disabled = true;
                try {
                    await fetch('#', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({phone}),
                    });
                } catch (e) {

                }
                startResendTimer(60);
            });
        })();
    </script>
@endsection
