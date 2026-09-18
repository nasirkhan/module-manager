<div class="mb-5">
    <p class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ __($field['label']) }}
        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $field['name'] }})</span>
        @if (Str::contains($field['rules'], 'required'))
            <span class="text-red-500 font-bold ml-0.5">*</span>
        @endif
    </p>
    <input name="{{ $field['name'] }}" type="hidden" value="0" />
    <x-cube::checkbox
        name="{{ $field['name'] }}"
        value="{{ Arr::get($field, 'value', '1') }}"
        :checked="old($field['name'], setting($field['name']))"
        :required="Str::contains($field['rules'], 'required')"
    >{{ __($field['label']) }}</x-cube::checkbox>
    <x-cube::error :messages="$errors->get($field['name'])" />
</div>
