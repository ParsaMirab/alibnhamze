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
                        <h1 class="text-2xl sm:text-[28px] font-black text-slate-900 tracking-tight">
                            ورود به سامانه دانش آموزی
                        </h1>

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
                    <form id="phone-form" method="POST" action="{{ route('login.sendOtp') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
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
                                           transition-all duration-200 sm:text-base"
                                >
                            </div>

                            @error('phone')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" id="send-otp-btn"
                                class="w-full inline-flex items-center justify-center gap-3
                                       py-3.5 rounded-xl bg-[#0F3D62] text-white text-sm font-bold
                                       shadow-lg shadow-[#0F3D62]/25
                                       hover:bg-[#0a2d4a] active:bg-[#0a2d4a]
                                       disabled:opacity-60 disabled:cursor-not-allowed
                                       transition-all duration-200 sm:text-base">
                            <span id="send-otp-text">دریافت کد تأیید</span>
                            <svg id="send-otp-spinner" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <svg id="send-otp-arrow" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                    </form>

                    {{-- ================= STEP 2: OTP ================= --}}
                    <form id="otp-form" method="POST" action="{{ route('login.verifyOtp') }}"
                          class="space-y-7 hidden">
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

                        {{-- Beta Mode OTP Display --}}
                        <div id="beta-otp-box" class="hidden mb-5 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-xl">
                            <p class="text-sm font-medium text-blue-800 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.33 16a2 2 0 001.74 3z"/>
                                </svg>
                                Beta mode — Your code: <span id="beta-otp-code" class="font-bold text-blue-600"></span>
                            </p>
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-slate-700 mb-3">
                                کد تأیید ۶ رقمی
                            </label>
                            <div class="relative">
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
                                           transition-all duration-200 sm:text-base"
                                >
                                @error('code')
                                <p class="mt-2 text-xs text-red-600 text-center">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-4
                                       py-3.5 rounded-xl bg-[#0F3D62] text-white text-sm font-bold
                                       shadow-lg shadow-[#0F3D62]/25
                                       hover:bg-[#0a2d4a] active:bg-[#0a2d4a]
                                       transition-all duration-200 sm:text-base">
                            ورود به سامانه
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        {{-- Resend --}}
                        <div class="flex items-center justify-between text-xs pt-3">
                            <button type="button" id="edit-phone"
                                    class="inline-flex items-center gap-3 text-slate-500
                                           hover:text-[#0F3D62] transition-colors sm:text-base">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                ویرایش شماره
                            </button>

                            <button type="button" id="resend-code"
                                    class="font-semibold text-[#0F3D62] disabled:text-slate-400
                                           disabled:cursor-not-allowed transition-colors sm:text-base">
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
            const phoneForm    = document.getElementById('phone-form');
            const otpForm      = document.getElementById('otp-form');
            const phoneInput   = document.getElementById('phone');
            const otpPhone     = document.getElementById('otp-phone');
            const phoneDisplay = document.getElementById('phone-display');
            const codeInput    = document.getElementById('code');
            const editPhone    = document.getElementById('edit-phone');
            const resendBtn    = document.getElementById('resend-code');
            const resendTimer  = document.getElementById('resend-timer');

            const sendOtpBtn     = document.getElementById('send-otp-btn');
            const sendOtpText    = document.getElementById('send-otp-text');
            const sendOtpSpinner = document.getElementById('send-otp-spinner');
            const sendOtpArrow   = document.getElementById('send-otp-arrow');

            const betaOtpBox  = document.getElementById('beta-otp-box');
            const betaOtpCode = document.getElementById('beta-otp-code');

            const hasOtpError = {{ $errors->has('code') ? 'true' : 'false' }};
            const oldPhone    = @json(old('phone'));

            // ====== نمایش مرحله OTP ======
            function goToOtp(phone) {
                phoneForm.classList.add('hidden');
                otpForm.classList.remove('hidden');

                otpPhone.value = phone || '';
                phoneDisplay.textContent = phone || '';
                codeInput?.focus();
                startResendTimer(60);
            }

            function goToPhone() {
                otpForm.classList.add('hidden');
                phoneForm.classList.remove('hidden');
                phoneInput?.focus();
            }

            // ====== اگر با خطای code برگشتیم، مرحله OTP را نشان بده ======
            if (hasOtpError && oldPhone) {
                goToOtp(oldPhone);
            }

            // ====== ارسال فرم شماره (بدون رفرش) ======
            phoneForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                let value = phoneInput.value.trim();

                // تبدیل اعداد فارسی/عربی به انگلیسی
                value = value
                    .replace(/[۰-۹]/g, d => String.fromCharCode(d.charCodeAt(0) - 1728))
                    .replace(/[٠-٩]/g, d => String.fromCharCode(d.charCodeAt(0) - 1584));

                if (!/^09\d{9}$/.test(value)) {
                    phoneInput.focus();
                    phoneInput.classList.add('border-red-400');
                    setTimeout(() => phoneInput.classList.remove('border-red-400'), 1500);
                    return;
                }

                // UI: حالت loading
                sendOtpBtn.disabled = true;
                sendOtpText.textContent = 'در حال ارسال...';
                sendOtpArrow.classList.add('hidden');
                sendOtpSpinner.classList.remove('hidden');

                try {
                    const res = await fetch('{{ route('login.sendOtp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ phone: value }),
                    });

                    const data = await res.json().catch(() => ({}));

                    if (!res.ok) {
                        // در صورت خطا، برگرد به فرم شماره
                        sendOtpBtn.disabled = false;
                        sendOtpText.textContent = 'دریافت کد تأیید';
                        sendOtpArrow.classList.remove('hidden');
                        sendOtpSpinner.classList.add('hidden');

                        alert(data.message || 'خطا در ارسال کد. لطفاً دوباره تلاش کنید.');
                        return;
                    }

                    // نمایش کد بتا (اگر سرور داده بود)
                    if (data.otp_code) {
                        betaOtpCode.textContent = data.otp_code;
                        betaOtpBox.classList.remove('hidden');
                    } else {
                        betaOtpBox.classList.add('hidden');
                    }

                    // برو به مرحله OTP
                    goToOtp(value);

                    // برگرداندن دکمه به حالت عادی
                    sendOtpBtn.disabled = false;
                    sendOtpText.textContent = 'دریافت کد تأیید';
                    sendOtpArrow.classList.remove('hidden');
                    sendOtpSpinner.classList.add('hidden');

                } catch (err) {
                    sendOtpBtn.disabled = false;
                    sendOtpText.textContent = 'دریافت کد تأیید';
                    sendOtpArrow.classList.remove('hidden');
                    sendOtpSpinner.classList.add('hidden');

                    alert('خطا در ارتباط با سرور. لطفاً دوباره تلاش کنید.');
                }
            });

            // ====== ویرایش شماره ======
            editPhone?.addEventListener('click', function () {
                goToPhone();
                codeInput.value = '';
                if (timerId) {
                    clearInterval(timerId);
                    resendBtn.disabled = false;
                    resendTimer.textContent = '';
                }
                betaOtpBox.classList.add('hidden');
            });

            // ====== فقط عدد در فیلد کد ======
            codeInput?.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 6);
            });

            // ====== فقط عدد در فیلد شماره ======
            phoneInput?.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 11);
            });

            // ====== تایمر ارسال مجدد ======
            let timerId = null;

            function startResendTimer(seconds) {
                if (!resendBtn || !resendTimer) return;
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

            // ====== ارسال مجدد کد ======
            resendBtn?.addEventListener('click', async function () {
                const phone = otpPhone.value;
                if (!phone) return;

                resendBtn.disabled = true;
                try {
                    const response = await fetch('{{ route('login.sendOtp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ phone }),
                    });

                    const data = await response.json().catch(() => ({}));

                    if (data.otp_code) {
                        betaOtpCode.textContent = data.otp_code;
                        betaOtpBox.classList.remove('hidden');
                    }
                } catch (e) {
                    alert('خطا در ارتباط با سرور.');
                }
                startResendTimer(90);
            });
        })();
    </script>
@endsection
