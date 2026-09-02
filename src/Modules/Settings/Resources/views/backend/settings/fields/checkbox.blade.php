@php
    $required = Str::contains($field["rules"], "required") ? "required" : "";
    $required_mark = $required != "" ? '<span class="text-red-500 font-bold ml-0.5">*</span>' : "";
@endphp

<div class="mb-5">
    <p class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ __($field["label"]) }}
        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $field["name"] }})</span>
        {!! $required_mark !!}
    </p>

    <div class="flex items-center gap-2">
        <input name="{{ $field["name"] }}" type="hidden" value="0" />
        <input
            id="{{ $field["name"] }}"
            name="{{ $field["name"] }}"
            type="checkbox"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
            value="{{ \Illuminate\Support\Arr::get($field, "value", "1") }}"
            @if (old($field['name'], setting($field['name']))) checked @endif
        />
        <label for="{{ $field["name"] }}" class="text-sm font-medium text-gray-900 dark:text-gray-300">
            {{ __($field["label"]) }}
        </label>
    </div>

    @if ($errors->has($field["name"]))
        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $errors->first($field["name"]) }}</p>
    @endif
</div>
