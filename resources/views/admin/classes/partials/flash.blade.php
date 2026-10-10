@if (session('success'))
    <div class="kt-alert kt-alert-success mb-5" role="status">
        <i class="ki-filled ki-check-circle text-lg"></i>
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="kt-alert kt-alert-destructive mb-5" role="alert">
        <i class="ki-filled ki-information-2 text-lg"></i>
        {{ session('error') }}
    </div>
@endif
