{{-- Basic Information --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <x-cube::group name="name" :label="label_case('name')" required>
        <x-cube::input type="text" name="name" :value="old('name', optional($data)->name ?? '')" placeholder="e.g., Header Menu, Footer Menu" required />
    </x-cube::group>
    <x-cube::group name="slug" :label="label_case('slug')">
        <x-cube::input type="text" name="slug" :value="old('slug', optional($data)->slug ?? '')" placeholder="e.g., header-menu, footer-menu" />
    </x-cube::group>
    <x-cube::group name="location" :label="label_case('location')" required>
        <x-cube::select name="location" required>
            <option value="">-- Select location --</option>
            @foreach(['frontend-header' => 'Frontend Header', 'frontend-footer' => 'Frontend Footer', 'admin-sidebar' => 'Admin Sidebar'] as $locValue => $locLabel)
                <option value="{{ $locValue }}" @selected(old('location', optional($data)->location ?? '') == $locValue)>{{ $locLabel }}</option>
            @endforeach
        </x-cube::select>
    </x-cube::group>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <x-cube::group name="description" :label="label_case('description')">
        <x-cube::textarea name="description" placeholder="Brief description of this menu" :rows="3">{{ old('description', optional($data)->description ?? '') }}</x-cube::textarea>
    </x-cube::group>
    <x-cube::group name="note" label="Admin Notes">
        <x-cube::textarea name="note" placeholder="Internal notes for administrators" :rows="3">{{ old('note', optional($data)->note ?? '') }}</x-cube::textarea>
    </x-cube::group>
</div>

{{-- Display & Theme --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <x-cube::group name="theme" :label="label_case('theme')">
        <x-cube::select name="theme">
            @foreach(['default' => 'Default', 'bootstrap' => 'Bootstrap', 'minimal' => 'Minimal', 'dark' => 'Dark Theme', 'custom' => 'Custom'] as $themeValue => $themeLabel)
                <option value="{{ $themeValue }}" @selected(old('theme', optional($data)->theme ?? '') == $themeValue)>{{ $themeLabel }}</option>
            @endforeach
        </x-cube::select>
    </x-cube::group>
    <x-cube::group name="css_classes" label="CSS Classes">
        <x-cube::input type="text" name="css_classes" :value="old('css_classes', optional($data)->css_classes ?? '')" placeholder="e.g., navbar navbar-expand-lg" />
    </x-cube::group>
    <x-cube::group name="locale" :label="label_case('locale')">
        <x-cube::select name="locale">
            <option value="">-- Select language --</option>
            @foreach(['en' => 'English', 'es' => 'Spanish', 'fr' => 'French', 'de' => 'German', 'ar' => 'Arabic', 'hi' => 'Hindi'] as $localeValue => $localeLabel)
                <option value="{{ $localeValue }}" @selected(old('locale', optional($data)->locale ?? '') == $localeValue)>{{ $localeLabel }}</option>
            @endforeach
        </x-cube::select>
    </x-cube::group>
</div>

{{-- Access Control --}}
<p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">@lang('Access Control')</p>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <x-cube::group name="is_public" label="Public Menu">
        <x-cube::select name="is_public">
            <option value="">-- Select visibility --</option>
            @foreach(['1' => 'Yes - Allow guests', '0' => 'No - Require login'] as $pubValue => $pubLabel)
                <option value="{{ $pubValue }}" @selected(old('is_public', optional($data)->is_public ?? '') == $pubValue)>{{ $pubLabel }}</option>
            @endforeach
        </x-cube::select>
    </x-cube::group>
    <x-cube::group name="permissions" label="Required Permissions">
        <x-cube::tom-select name="permissions[]" multiple>
            @foreach(['view_backend' => 'View Backend', 'edit_content' => 'Edit Content', 'manage_users' => 'Manage Users', 'manage_settings' => 'Manage Settings'] as $permValue => $permLabel)
                <option value="{{ $permValue }}" @selected(in_array($permValue, (array) old('permissions', optional($data)->permissions ?? [])))>{{ $permLabel }}</option>
            @endforeach
        </x-cube::tom-select>
    </x-cube::group>
    <x-cube::group name="roles" label="Required Roles">
        <x-cube::tom-select name="roles[]" multiple>
            @foreach(['super admin' => 'Super Admin', 'admin' => 'Admin', 'editor' => 'Editor', 'user' => 'User'] as $roleValue => $roleLabel)
                <option value="{{ $roleValue }}" @selected(in_array($roleValue, (array) old('roles', optional($data)->roles ?? [])))>{{ $roleLabel }}</option>
            @endforeach
        </x-cube::tom-select>
    </x-cube::group>
</div>

{{-- Status & Visibility --}}
<p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">@lang('Status & Visibility')</p>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <x-cube::group name="status" :label="label_case('status')" required>
        <x-cube::select name="status" required>
            <option value="">-- Select status --</option>
            @foreach(['1' => 'Published', '0' => 'Disabled', '2' => 'Draft'] as $statusValue => $statusLabel)
                <option value="{{ $statusValue }}" @selected(old('status', optional($data)->status ?? '') == $statusValue)>{{ $statusLabel }}</option>
            @endforeach
        </x-cube::select>
    </x-cube::group>
    <x-cube::group name="is_active" label="Active Status">
        <x-cube::select name="is_active">
            <option value="">-- Select status --</option>
            @foreach(['1' => 'Yes - Active', '0' => 'No - Inactive'] as $activeValue => $activeLabel)
                <option value="{{ $activeValue }}" @selected(old('is_active', optional($data)->is_active ?? '') == $activeValue)>{{ $activeLabel }}</option>
            @endforeach
        </x-cube::select>
    </x-cube::group>
    <x-cube::group name="is_visible" label="Visibility">
        <x-cube::select name="is_visible">
            <option value="">-- Select visibility --</option>
            @foreach(['1' => 'Yes - Visible', '0' => 'No - Hidden'] as $visibleValue => $visibleLabel)
                <option value="{{ $visibleValue }}" @selected(old('is_visible', optional($data)->is_visible ?? '') == $visibleValue)>{{ $visibleLabel }}</option>
            @endforeach
        </x-cube::select>
    </x-cube::group>
</div>

{{-- Advanced Settings --}}
<p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">@lang('Advanced Settings')</p>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <x-cube::group name="settings[max_depth]" label="Maximum Depth" help="Maximum nesting level for menu items">
        <x-cube::input type="number" name="settings[max_depth]" :value="old('settings.max_depth', is_array(optional($data)->settings) ? optional($data)->settings['max_depth'] ?? 3 : 3)" placeholder="3" min="1" max="10" />
    </x-cube::group>
    <x-cube::group name="settings[cache_duration]" label="Cache Duration (minutes)" help="How long to cache this menu (0 = no cache)">
        <x-cube::input type="number" name="settings[cache_duration]" :value="old('settings.cache_duration', is_array(optional($data)->settings) ? optional($data)->settings['cache_duration'] ?? 60 : 60)" placeholder="60" min="0" />
    </x-cube::group>
</div>
