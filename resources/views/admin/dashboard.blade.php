@extends('admin.layouts.app')

@section('title', 'داشبورد')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/vendors/apexcharts/apexcharts.css') }}">
@endpush

@section('content')
    <div class="pb-5">
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
            <div class="flex flex-col gap-1">
                <h1 class="font-semibold text-xl text-mono">داشبورد</h1>
                <div class="text-sm text-secondary-foreground">
                    خوش آمدید،
                </div>
            </div>
        </div>
    </div>

@endsection
