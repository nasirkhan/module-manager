@php
    $required = Str::contains($field["rules"], "required") ? "required" : "";
    $required_mark = $required != "" ? '<span class="text-red-500 font-bold ml-0.5">*</span>' : "";
@endphp

<div class="mb-5">
    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ __($field["label"]) }}
        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $field["name"] }})</span>
        {!! $required_mark !!}
    </label>

    <div class="flex flex-wrap gap-4">
        @foreach ($field["options"] as $value => $label)
            <div class="flex items-center gap-2">
                <input
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                    id="{{ $field["name"] }}-{{ $value }}"
                    name="{{ $field["name"] }}"
                    type="radio"
                    value="{{ $value }}"
                    @if(old($field['name'], setting($field['name'])) == $value) checked @endif
                />
                <label for="{{ $field["name"] }}-{{ $value }}" class="text-sm font-medium text-gray-900 dark:text-gray-300">
                    {{ __($label) }}
                </label>
            </div>
        @endforeach
    </div>

    @if ($errors->has($field["name"]))
        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $errors->first($field["name"]) }}</p>
    @endif
</div>
