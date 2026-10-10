@php
    $name = $name ?? 'value';
    $placeholder = $placeholder ?? 'جستجو...';
    $options = collect($options ?? []);
    $selectedId = (string) ($selectedId ?? '');
    $selectedLabel = $selectedLabel ?? '';
@endphp

<div class="admin-combobox" data-combobox>
    <input
        type="text"
        class="kt-input w-full"
        data-combobox-search
        placeholder="{{ $placeholder }}"
        autocomplete="off"
        value="{{ $selectedLabel }}"
    >

    <input type="hidden" name="{{ $name }}" data-combobox-value value="{{ $selectedId }}">

    <div class="admin-combobox-panel hidden" data-combobox-panel>
        <div class="admin-combobox-empty hidden" data-combobox-empty>
            موردی یافت نشد
        </div>

        @foreach ($options as $option)
            @php
                $label = trim(($option->first_name ?? '').' '.($option->last_name ?? ''));
                $haystack = trim($label.' '.($option->national_code ?? ''));
            @endphp

            <button
                type="button"
                class="admin-combobox-option"
                data-combobox-option
                data-value="{{ $option->id }}"
                data-label="{{ $label }}"
                data-search="{{ $haystack }}"
                aria-selected="{{ $selectedId === (string) $option->id ? 'true' : 'false' }}"
            >
                <span>{{ $label }}</span>

                @if ($option->national_code)
                    <span class="admin-combobox-hint" dir="ltr">{{ $option->national_code }}</span>
                @endif
            </button>
        @endforeach
    </div>
</div>
