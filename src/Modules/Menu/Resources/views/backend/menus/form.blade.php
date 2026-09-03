{{-- Basic Information --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <div>
        <?php
        $field_name = 'name';
        $field_lable = label_case($field_name);
        $field_placeholder = 'e.g., Header Menu, Footer Menu';
        $required = "required";
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }} {!! field_required($required) !!}
        </label>
        {{ html()->text($field_name)->placeholder($field_placeholder)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500')->attributes(["$required"]) }}
    </div>
    <div>
        <?php
        $field_name = 'slug';
        $field_lable = label_case($field_name);
        $field_placeholder = 'e.g., header-menu, footer-menu';
        $required = "";
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }}
        </label>
        {{ html()->text($field_name)->placeholder($field_placeholder)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500') }}
    </div>
    <div>
        <?php
        $field_name = 'location';
        $field_lable = label_case($field_name);
        $field_placeholder = "-- Select location --";
        $required = "required";
        $select_options = [
            'frontend-header' => 'Frontend Header',
            'frontend-footer' => 'Frontend Footer',
            'admin-sidebar'   => 'Admin Sidebar',
        ];
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }} {!! field_required($required) !!}
        </label>
        {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500')->attributes(["$required"]) }}
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div>
        <?php
        $field_name = 'description';
        $field_lable = label_case($field_name);
        $field_placeholder = 'Brief description of this menu';
        $required = "";
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }}
        </label>
        {{ html()->textarea($field_name)->placeholder($field_placeholder)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500')->rows(3) }}
    </div>
    <div>
        <?php
        $field_name = 'note';
        $field_lable = 'Admin Notes';
        $field_placeholder = 'Internal notes for administrators';
        $required = "";
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }}
        </label>
        {{ html()->textarea($field_name)->placeholder($field_placeholder)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500')->rows(3) }}
    </div>
</div>

{{-- Display & Theme --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <div>
        <?php
        $field_name = 'theme';
        $field_lable = label_case($field_name);
        $required = "";
        $select_options = [
            'default'   => 'Default',
            'bootstrap' => 'Bootstrap',
            'minimal'   => 'Minimal',
            'dark'      => 'Dark Theme',
            'custom'    => 'Custom',
        ];
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }}
        </label>
        {{ html()->select($field_name, $select_options)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500') }}
    </div>
    <div>
        <?php
        $field_name = 'css_classes';
        $field_lable = 'CSS Classes';
        $field_placeholder = 'e.g., navbar navbar-expand-lg';
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }}
        </label>
        {{ html()->text($field_name)->placeholder($field_placeholder)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500') }}
    </div>
    <div>
        <?php
        $field_name = 'locale';
        $field_lable = label_case($field_name);
        $field_placeholder = "-- Select language --";
        $select_options = [
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'ar' => 'Arabic',
            'hi' => 'Hindi',
        ];
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }}
        </label>
        {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500') }}
    </div>
</div>

{{-- Access Control --}}
<p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">@lang('Access Control')</p>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <div>
        <?php
        $field_name = 'is_public';
        $field_lable = 'Public Menu';
        $field_placeholder = "-- Select visibility --";
        $select_options = [
            '1' => 'Yes - Allow guests',
            '0' => 'No - Require login',
        ];
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }}
        </label>
        {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500') }}
    </div>
    <div>
        <?php
        $field_name = 'permissions';
        $field_lable = 'Required Permissions';
        $select_options = [
            'view_backend'    => 'View Backend',
            'edit_content'    => 'Edit Content',
            'manage_users'    => 'Manage Users',
            'manage_settings' => 'Manage Settings',
        ];
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }}
        </label>
        {{ html()->select($field_name . '[]', $select_options)->class('select2-permissions bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500')->multiple() }}
    </div>
    <div>
        <?php
        $field_name = 'roles';
        $field_lable = 'Required Roles';
        $select_options = [
            'super admin' => 'Super Admin',
            'admin'       => 'Admin',
            'editor'      => 'Editor',
            'user'        => 'User',
        ];
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }}
        </label>
        {{ html()->select($field_name . '[]', $select_options)->class('select2-roles bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500')->multiple() }}
    </div>
</div>

{{-- Status & Visibility --}}
<p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">@lang('Status & Visibility')</p>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <div>
        <?php
        $field_name = 'status';
        $field_lable = label_case($field_name);
        $field_placeholder = "-- Select status --";
        $required = "required";
        $select_options = [
            '1' => 'Published',
            '0' => 'Disabled',
            '2' => 'Draft',
        ];
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }} {!! field_required($required) !!}
        </label>
        {{ html()->select($field_name, $select_options)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500')->attributes(["$required"]) }}
    </div>
    <div>
        <?php
        $field_name = 'is_active';
        $field_lable = 'Active Status';
        $field_placeholder = "-- Select status --";
        $select_options = [
            '1' => 'Yes - Active',
            '0' => 'No - Inactive',
        ];
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }}
        </label>
        {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500') }}
    </div>
    <div>
        <?php
        $field_name = 'is_visible';
        $field_lable = 'Visibility';
        $field_placeholder = "-- Select visibility --";
        $select_options = [
            '1' => 'Yes - Visible',
            '0' => 'No - Hidden',
        ];
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }}
        </label>
        {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500') }}
    </div>
</div>

{{-- Advanced Settings --}}
<p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">@lang('Advanced Settings')</p>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div>
        <?php
        $field_name = 'settings[max_depth]';
        $field_lable = 'Maximum Depth';
        $field_placeholder = '3';
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }}
        </label>
        {{ html()->number($field_name)->placeholder($field_placeholder)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500')->attributes(['min' => '1', 'max' => '10']) }}
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maximum nesting level for menu items</p>
    </div>
    <div>
        <?php
        $field_name = 'settings[cache_duration]';
        $field_lable = 'Cache Duration (minutes)';
        $field_placeholder = '60';
        ?>
        <label for="{{ $field_name }}" class="block mb-1.5 text-sm font-medium text-gray-900 dark:text-white">
            {{ $field_lable }}
        </label>
        {{ html()->number($field_name)->placeholder($field_placeholder)->class('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500')->attributes(['min' => '0']) }}
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">How long to cache this menu (0 = no cache)</p>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.TomSelect === 'undefined') return;
        document.querySelectorAll('.select2-permissions, .select2-roles').forEach(function (el) {
            if (!el.tomselect) {
                new window.TomSelect(el, { plugins: ['remove_button'] });
            }
        });
    });
</script>
@endpush
