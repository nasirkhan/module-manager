@php
    $required = Str::contains($field["rules"], "required") ? "required" : "";
    $required_mark = $required != "" ? '<span class="text-red-500 font-bold ml-0.5">*</span>' : "";
    $input_class = $errors->has($field["name"])
        ? "border-red-500 bg-red-50 text-red-900 placeholder-red-700 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:bg-gray-700 dark:text-red-500"
        : "border-gray-300 bg-gray-50 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500";
@endphp

<div class="mb-5">
    <label for="{{ $field["name"] }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ __($field["label"]) }}
        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $field["name"] }})</span>
    </label>
    {!! $required_mark !!}
    <textarea
        name="{{ $field["name"] }}"
        class="block w-full rounded-lg border p-2.5 text-sm {{ $input_class }} {{ Arr::get($field, "class") }}"
        id="{{ $field["name"] }}"
        placeholder="{{ __($field["label"]) }}"
        rows="6"
        {{ $required }}
    >@if (isset($field["display"]) && $field["display"] == "raw"){!! old($field["name"], setting($field["name"])) !!}@else{{ old($field["name"], setting($field["name"])) }}@endif</textarea>

    @if ($errors->has($field["name"]))
        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $errors->first($field["name"]) }}</p>
    @endif

    @if (isset($field["help"]))
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $field["help"] }}</p>
    @endif
</div>
