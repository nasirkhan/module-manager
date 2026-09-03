<div class="mb-5">
    <x-cube::label :required="Str::contains($field['rules'], 'required')">
        {{ __($field['label']) }}
        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $field['name'] }})</span>
    </x-cube::label>
    <div class="flex flex-wrap gap-4">
        @foreach ($field['options'] as $value => $label)
            <div class="flex items-center gap-2">
                <input
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                    id="{{ $field['name'] }}-{{ $value }}"
                    name="{{ $field['name'] }}"
                    type="radio"
                    value="{{ $value }}"
                    @if (old($field['name'], setting($field['name'])) == $value) checked @endif
                />
                <label for="{{ $field['name'] }}-{{ $value }}" class="text-sm font-medium text-gray-900 dark:text-gray-300">
                    {{ __($label) }}
                </label>
            </div>
        @endforeach
    </div>
    <x-cube::error :messages="$errors->get($field['name'])" />
</div>
