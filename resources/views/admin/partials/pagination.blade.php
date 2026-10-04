@if ($paginator->hasPages())
    <nav class="kt-datatable-pagination" aria-label="ناوبری صفحه‌ها">
        @if ($paginator->onFirstPage())
            <button class="kt-datatable-pagination-button kt-datatable-pagination-prev" type="button" disabled aria-label="صفحه قبل">
                <i class="ki-filled ki-right"></i>
            </button>
        @else
            <a class="kt-datatable-pagination-button kt-datatable-pagination-prev" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="صفحه قبل">
                <i class="ki-filled ki-right"></i>
            </a>
        @endif

        @foreach ($paginator->onEachSide(1)->linkCollection() as $link)
            @continue($loop->first || $loop->last)
            @if ($link['url'] === null)
                <span class="kt-datatable-pagination-button" aria-hidden="true">…</span>
            @elseif ($link['active'])
                <span class="kt-datatable-pagination-button active" aria-current="page">{{ $link['label'] }}</span>
            @else
                <a class="kt-datatable-pagination-button" href="{{ $link['url'] }}" aria-label="صفحه {{ $link['label'] }}">{{ $link['label'] }}</a>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="kt-datatable-pagination-button kt-datatable-pagination-next" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="صفحه بعد">
                <i class="ki-filled ki-left"></i>
            </a>
        @else
            <button class="kt-datatable-pagination-button kt-datatable-pagination-next" type="button" disabled aria-label="صفحه بعد">
                <i class="ki-filled ki-left"></i>
            </button>
        @endif
    </nav>
@endif
