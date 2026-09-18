<div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            @lang("{{moduleNameLower}}::text.name") <span class="text-red-500">*</span>
        </label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $$module_name_singular->name ?? '') }}"
            placeholder="@lang('{{moduleNameLower}}::text.name')"
            class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
            required
        />
        @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="slug" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            @lang("{{moduleNameLower}}::text.slug")
        </label>
        <input
            type="text"
            id="slug"
            name="slug"
            value="{{ old('slug', $$module_name_singular->slug ?? '') }}"
            placeholder="@lang('{{moduleNameLower}}::text.slug')"
            class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
        />
        @error('slug') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="status" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            @lang("{{moduleNameLower}}::text.status") <span class="text-red-500">*</span>
        </label>
        <select
            id="status"
            name="status"
            class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
            required
        >
            <option value="1" {{ old('status', $$module_name_singular->status ?? 1) == 1 ? 'selected' : '' }}>{{ __('Published') }}</option>
            <option value="0" {{ old('status', $$module_name_singular->status ?? 1) == 0 ? 'selected' : '' }}>{{ __('Unpublished') }}</option>
            <option value="2" {{ old('status', $$module_name_singular->status ?? 1) == 2 ? 'selected' : '' }}>{{ __('Draft') }}</option>
        </select>
        @error('status') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6">
    <label for="note" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
        {{ __('Note') }}
    </label>
    <textarea
        id="note"
        name="note"
        rows="4"
        class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
    >{{ old('note', $$module_name_singular->note ?? '') }}</textarea>
    @error('note') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
</div>
