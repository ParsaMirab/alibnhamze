<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="rtl" lang="fa">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | پنل مدیریت هنرستان</title>
    <link rel="icon" href="{{ asset('admin/media/app/mini-logo-circle-success.svg') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/keenicons/styles.bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/metronic.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/admin.css') }}">
</head>
<body class="antialiased flex h-full text-base text-foreground bg-background">
    @yield('content')

    <script src="{{ asset('admin/js/core.bundle.js') }}"></script>
    <script src="{{ asset('admin/vendors/ktui/ktui.min.js') }}"></script>
    <script src="{{ asset('admin/js/admin.js') }}"></script>
</body>
</html>
