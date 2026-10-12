@extends('alibnhamze.layouts.app')

@section('title', 'داشبورد اولیا | هنرستان علی بن حمزه')

@section('content')
    <div dir="rtl" class="font-sans bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">
        <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-10 py-10 sm:py-14">
            <div class="w-full max-w-md">
                <div class="bg-white border border-slate-200 rounded-2xl shadow-xl shadow-slate-900/5 p-6 sm:p-8 lg:p-10">
                    <div class="text-center">
                        <h1 class="text-2xl sm:text-[28px] font-black text-slate-900 tracking-tight">
                            داشبورد اولیا
                        </h1>
                        <p class="mt-4 text-sm text-slate-500 leading-6">
                            سایت در حالت Beta است بنابرین این بخش کامل نیست این بخش پنل مدیریت والدین برای نمرات , غیبت ها , و ارتباط معلمین با اولیا و بلعکس است
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
