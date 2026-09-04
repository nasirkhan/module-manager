<div>
    {{-- Validation Summary --}}
    @if ($errors->any())
        <div class="mb-4 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
            <i class="fas fa-exclamation-triangle mt-0.5 shrink-0"></i>
            <div>
                <p class="font-semibold">{{ __('Please fix the following errors:') }}</p>
                <ul class="mt-1 list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @error('general')
        <div class="mb-4 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
            <i class="fas fa-exclamation-triangle mt-0.5 shrink-0"></i>
            {{ $message }}
        </div>
    @enderror

    {{-- Form Mode Indicator --}}
    @if ($menuItem)
        <div class="mb-5 flex items-start gap-3 rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
            <i class="fas fa-edit mt-0.5 shrink-0"></i>
            <div>
                {{ __('Editing menu item:') }} <strong>{{ $menuItem->name }}</strong>
                <p class="mt-0.5 text-xs opacity-75">
                    {{ __('Created:') }} {{ $menuItem->created_at->format('M j, Y') }}
                    &middot;
                    {{ __('Last updated:') }} {{ $menuItem->updated_at->diffForHumans() }}
                </p>
            </div>
        </div>
    @else
        <div class="mb-5 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
            <i class="fas fa-plus-circle mt-0.5 shrink-0"></i>
            {{ __('Creating new menu item') }}
        </div>
    @endif

    {{-- ── Section: Basic Information ───────────────────────────────── --}}
    <div class="mb-2 border-b border-gray-200 pb-2 dark:border-gray-700">
        <h5 class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
            {{ __('Basic Information') }}
        </h5>
    </div>

    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

        <x-cube::group name="menu_id" label="{{ __('Menu') }}" :required="true">
            <x-cube::select wire:model.live="menu_id" name="menu_id" required>
                <option value="">-- {{ __('Select Menu') }} --</option>
                @foreach ($menus as $id => $menuName)
                    <option value="{{ $id }}">{{ $menuName }}</option>
                @endforeach
            </x-cube::select>
        </x-cube::group>

        <x-cube::group name="parent_id" label="{{ __('Parent Item') }}">
            <x-cube::select wire:model="parent_id" name="parent_id" wire:key="parent-{{ $menu_id }}" {{ !$menu_id ? 'disabled' : '' }}>
                <option value="">-- {{ __('No Parent (Root Level)') }} --</option>
                @if ($menu_id)
                    @forelse ($parent_items as $id => $parentName)
                        <option value="{{ $id }}">{{ $parentName }}</option>
                    @empty
                        <option value="" disabled>{{ __('No parent items available') }}</option>
                    @endforelse
                @else
                    <option value="" disabled>{{ __('Select a menu first') }}</option>
                @endif
            </x-cube::select>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ $menu_id ? __('Select parent item to create dropdown menu') : __('Select a menu first') }}
            </p>
        </x-cube::group>

        <x-cube::group name="type" label="{{ __('Item Type') }}" :required="true">
            <x-cube::select wire:model.live="type" name="type" required>
                <option value="">-- {{ __('Select Type') }} --</option>
                <option value="link">{{ __('Link') }}</option>
                <option value="dropdown">{{ __('Dropdown') }}</option>
                <option value="divider">{{ __('Divider') }}</option>
                <option value="heading">{{ __('Heading') }}</option>
                <option value="external">{{ __('External Link') }}</option>
            </x-cube::select>
        </x-cube::group>

    </div>

    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

        <x-cube::group name="name" label="{{ __('Display Name') }}" :required="true">
            <x-cube::input wire:model.blur="name" type="text" name="name" placeholder="{{ __('e.g., Home, About Us, Contact') }}" required />
        </x-cube::group>

        <x-cube::group name="slug" label="{{ __('Slug') }}">
            <div class="flex gap-2">
                <x-cube::input wire:model="slug" type="text" name="slug" placeholder="{{ __('e.g., home, about-us') }}" class="flex-1" />
                <button
                    type="button"
                    wire:click="generateSlug"
                    title="{{ __('Generate from name') }}"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                >
                    <i class="fas fa-magic"></i>
                </button>
            </div>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Auto-generated from name if left empty') }}</p>
        </x-cube::group>

        <x-cube::group name="sort_order" label="{{ __('Sort Order') }}">
            <x-cube::input wire:model="sort_order" type="number" name="sort_order" placeholder="0" min="0" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Lower numbers appear first') }}</p>
        </x-cube::group>

    </div>

    {{-- ── Section: Navigation ─────────────────────────────────────── --}}
    @if (! in_array($type, ['divider', 'heading']))

        <div class="mb-2 border-b border-gray-200 pb-2 dark:border-gray-700">
            <h5 class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                {{ __('Navigation') }}
            </h5>
        </div>

        <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2">

            <x-cube::group name="url" label="{{ __('Direct URL') }}">
                <x-cube::input wire:model="url" type="text" name="url" placeholder="{{ __('e.g., /about-us or https://external.com') }}" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Use either URL or Route Name, not both') }}</p>
            </x-cube::group>

            <x-cube::group name="route_name" label="{{ __('Laravel Route Name') }}">
                <x-cube::input wire:model="route_name" type="text" name="route_name" placeholder="{{ __('e.g., frontend.pages.about') }}" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Laravel route name (preferred over direct URL)') }}</p>
            </x-cube::group>

            <x-cube::group name="route_parameters" label="{{ __('Route Parameters (JSON)') }}">
                <x-cube::textarea wire:model="route_parameters" name="route_parameters" rows="2" placeholder='{"id": 1, "slug": "example"}' />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('JSON format for route parameters') }}</p>
            </x-cube::group>

            <x-cube::group name="description" label="{{ __('Description / Tooltip') }}">
                <x-cube::textarea wire:model="description" name="description" rows="2" placeholder="{{ __('Brief description or tooltip text') }}" />
            </x-cube::group>

        </div>

    @endif

    {{-- ── Section: Display Properties ────────────────────────────── --}}
    <div class="mb-2 border-b border-gray-200 pb-2 dark:border-gray-700">
        <h5 class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
            {{ __('Display Properties') }}
        </h5>
    </div>

    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-4">

        <x-cube::group name="icon" label="{{ __('Icon Class') }}">
            <x-cube::input wire:model="icon" type="text" name="icon" placeholder="{{ __('e.g., fas fa-home') }}" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('FontAwesome or similar') }}</p>
        </x-cube::group>

        <x-cube::group name="badge_text" label="{{ __('Badge Text') }}">
            <x-cube::input wire:model="badge_text" type="text" name="badge_text" placeholder="{{ __('New, Hot, 5') }}" />
        </x-cube::group>

        <x-cube::group name="badge_color" label="{{ __('Badge Color') }}">
            <x-cube::select wire:model="badge_color" name="badge_color">
                <option value="">-- {{ __('Select Color') }} --</option>
                <option value="blue">{{ __('Blue') }}</option>
                <option value="green">{{ __('Green') }}</option>
                <option value="red">{{ __('Red') }}</option>
                <option value="yellow">{{ __('Yellow') }}</option>
                <option value="gray">{{ __('Gray') }}</option>
                <option value="indigo">{{ __('Indigo') }}</option>
            </x-cube::select>
        </x-cube::group>

        <x-cube::group name="opens_new_tab" label="{{ __('Open in New Tab') }}">
            <x-cube::select wire:model="opens_new_tab" name="opens_new_tab">
                <option value="0">{{ __('No') }}</option>
                <option value="1">{{ __('Yes') }}</option>
            </x-cube::select>
        </x-cube::group>

    </div>

    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2">

        <x-cube::group name="css_classes" label="{{ __('CSS Classes') }}">
            <x-cube::input wire:model="css_classes" type="text" name="css_classes" placeholder="{{ __('nav-item active highlighted') }}" />
        </x-cube::group>

        <x-cube::group name="html_attributes" label="{{ __('HTML Attributes (JSON)') }}">
            <x-cube::textarea wire:model="html_attributes" name="html_attributes" rows="2" placeholder='{"data-toggle": "tooltip", "title": "Click me"}' />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Custom HTML attributes in JSON format') }}</p>
        </x-cube::group>

    </div>

    {{-- ── Section: Access Control ─────────────────────────────────── --}}
    <div class="mb-2 border-b border-gray-200 pb-2 dark:border-gray-700">
        <h5 class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
            {{ __('Access Control') }}
        </h5>
    </div>

    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2">

        <x-cube::group name="permissions" label="{{ __('Required Permissions') }}">
            <x-cube::select wire:model="permissions" name="permissions" multiple class="h-28">
                @foreach ($available_permissions as $permission => $permName)
                    <option value="{{ $permission }}">{{ $permName }}</option>
                @endforeach
            </x-cube::select>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Users must have these permissions') }}</p>
        </x-cube::group>

        <x-cube::group name="roles" label="{{ __('Required Roles') }}">
            <x-cube::select wire:model="roles" name="roles" multiple class="h-28">
                @foreach ($available_roles as $role => $roleName)
                    <option value="{{ $role }}">{{ $roleName }}</option>
                @endforeach
            </x-cube::select>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Users must have one of these roles') }}</p>
        </x-cube::group>

    </div>

    {{-- ── Section: Status & Visibility ───────────────────────────── --}}
    <div class="mb-2 border-b border-gray-200 pb-2 dark:border-gray-700">
        <h5 class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
            {{ __('Status & Visibility') }}
        </h5>
    </div>

    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-4">

        <x-cube::group name="status" label="{{ __('Status') }}" :required="true">
            <x-cube::select wire:model="status" name="status" required>
                <option value="">-- {{ __('Select Status') }} --</option>
                <option value="1">{{ __('Published') }}</option>
                <option value="0">{{ __('Disabled') }}</option>
                <option value="2">{{ __('Draft') }}</option>
            </x-cube::select>
        </x-cube::group>

        <x-cube::group name="is_active" label="{{ __('Active') }}">
            <x-cube::select wire:model="is_active" name="is_active">
                <option value="1">{{ __('Yes') }}</option>
                <option value="0">{{ __('No') }}</option>
            </x-cube::select>
        </x-cube::group>

        <x-cube::group name="is_visible" label="{{ __('Visible') }}">
            <x-cube::select wire:model="is_visible" name="is_visible">
                <option value="1">{{ __('Yes') }}</option>
                <option value="0">{{ __('No') }}</option>
            </x-cube::select>
        </x-cube::group>

        <x-cube::group name="locale" label="{{ __('Language') }}">
            <x-cube::select wire:model="locale" name="locale">
                <option value="">-- {{ __('All Languages') }} --</option>
                @foreach (config('app.available_locales', ['en' => 'English']) as $code => $localeName)
                    <option value="{{ $code }}">{{ $localeName }}</option>
                @endforeach
            </x-cube::select>
        </x-cube::group>

    </div>

    {{-- ── Section: SEO ────────────────────────────────────────────── --}}
    <div class="mb-2 border-b border-gray-200 pb-2 dark:border-gray-700">
        <h5 class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
            {{ __('SEO (Optional)') }}
        </h5>
    </div>

    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2">

        <x-cube::group name="meta_title" label="{{ __('Meta Title') }}">
            <x-cube::input wire:model="meta_title" type="text" name="meta_title" placeholder="{{ __('Page title for SEO') }}" />
        </x-cube::group>

    </div>

    {{-- ── Section: Additional Data ────────────────────────────────── --}}
    <div class="mb-2 border-b border-gray-200 pb-2 dark:border-gray-700">
        <h5 class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
            {{ __('Additional Data & Notes') }}
        </h5>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">

        <x-cube::group name="custom_data" label="{{ __('Custom Data (JSON)') }}">
            <x-cube::textarea wire:model="custom_data" name="custom_data" rows="3" placeholder='{"priority": "high", "category": "main"}' />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Store additional custom properties in JSON format') }}</p>
        </x-cube::group>

        <x-cube::group name="note" label="{{ __('Admin Notes') }}">
            <x-cube::textarea wire:model="note" name="note" rows="3" placeholder="{{ __('Internal notes for administrators') }}" />
        </x-cube::group>

    </div>

    {{-- ── Form Actions ─────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">
        <button
            type="button"
            wire:click="save"
            wire:loading.attr="disabled"
            class="{{ $menuItem ? 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600' : 'bg-green-600 hover:bg-green-700 focus:ring-green-300' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white focus:ring-4 disabled:opacity-60"
        >
            <span wire:loading.remove wire:target="save">
                <i class="fas {{ $menuItem ? 'fa-save' : 'fa-plus-circle' }}"></i>
                {{ $menuItem ? __('Update Menu Item') : __('Create Menu Item') }}
            </span>
            <span wire:loading wire:target="save">
                <i class="fas fa-spinner fa-spin"></i>
                {{ __('Saving…') }}
            </span>
        </button>

        @if (! $menuItem)
            <button
                type="button"
                wire:click="resetForm"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
                <i class="fas fa-undo"></i>
                {{ __('Reset Form') }}
            </button>
        @endif

        @if ($menuItem)
            <a
                href="{{ route('backend.menuitems.show', $menuItem) }}"
                wire:navigate
                class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600"
            >
                <i class="fas fa-eye"></i>
                {{ __('View') }}
            </a>
        @endif

        <div class="ml-auto">
            @if ($menu_id)
                <a
                    href="{{ route('backend.menus.show', $menu_id) }}"
                    wire:navigate
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    <i class="fas fa-times-circle"></i>
                    {{ __('Cancel') }}
                </a>
            @else
                <a
                    href="{{ route('backend.menuitems.index') }}"
                    wire:navigate
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    <i class="fas fa-times-circle"></i>
                    {{ __('Cancel') }}
                </a>
            @endif
        </div>
    </div>

</div>
