<div class="row">
    <div class="col-12 col-sm-5 mb-3">
        <div class="form-group">
            <?php
            $field_name = "name";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
        </div>
    </div>

    <div class="col-12 col-sm-3 mb-3">
        <div class="form-group">
            <?php
            $field_name = "slug";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
        </div>
    </div>

    <div class="col-12 col-sm-4 mb-3">
        <div class="form-group">
            <?php
            $field_name = "created_by_alias";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = "Hide Author User's Name and use Alias";
            $required = "";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mb-3">
        <div class="form-group">
            <?php
            $field_name = "intro";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->textarea($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mb-3">
        <div class="form-group">
            <?php
            $field_name = "content";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            <x-jodit::editor
                name="{{ $field_name }}"
                id="{{ $field_name }}"
                :value="old($field_name, $data->$field_name ?? '')"
                :placeholder="$field_placeholder"
                :required="(bool) $required"
            />
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-8">
        <div class="form-group">
            <?php
            $field_name = "image";
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label") }} {!! field_required($required) !!}
            {{ html()->input("file", $field_name)->class("form-control")->attributes(["$required"]) }}
        </div>
    </div>
    @if (isset($$module_name_singular) && $$module_name_singular->getMedia($module_name)->first())
        <div class="col-4">
            <div class="float-end">
                <figure class="figure">
                    <a
                        href="{{ asset($$module_name_singular->$field_name) }}"
                        data-lightbox="image-set"
                        data-title="Path: {{ asset($$module_name_singular->$field_name) }}"
                    >
                        <img
                            src="{{ asset($$module_name_singular->getMedia($module_name)->first()->getUrl("thumb300"),) }}"
                            class="figure-img img-fluid img-thumbnail rounded"
                            alt=""
                        />
                    </a>
                    <!-- <figcaption class="figure-caption">Path: </figcaption> -->
                </figure>
            </div>
        </div>
        <x-library.lightbox />
    @endif
</div>

<div class="row">
    <div class="col-12 col-sm-4 mb-3">
        <div class="form-group">
            <?php
            $field_name = "category_id";
            $field_lable = __("post::$module_name.$field_name");
            $field_options = ! empty($data) ? optional($data->category())->pluck("name", "id") : "";
            $selected = ! empty($data)
                ? optional($data->category())
                    ->pluck("id")
                    ->toArray()
                : "";
            $field_placeholder = __("Select an option");
            $required = "required";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->select($field_name, $field_options, $selected)->placeholder($field_placeholder)->class("form-select select2-category")->attributes(["$required"]) }}
        </div>
    </div>
    <div class="col-12 col-sm-4 mb-3">
        <div class="form-group">
            <?php
            $field_name = "type";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = __("Select an option");
            $required = "required";
            $select_options = \Nasirkhan\ModuleManager\Modules\Post\Enums\PostType::toArray();
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->select($field_name, $select_options)->class("form-select")->attributes(["$required"]) }}
        </div>
    </div>
    <div class="col-12 col-sm-4 mb-3">
        <div class="form-group">
            <?php
            $field_name = "is_featured";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = __("Select an option");
            $required = "required";
            $select_options = [
                "0" => "No",
                "1" => "Yes",
            ];
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->select($field_name, $select_options)->class("form-select")->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mb-3">
        <div class="form-group">
            <?php
            $field_name = "tags_list[]";
            $field_lable = __("post::$module_name.tags");
            $field_options = ! empty($data) ? optional($data->tags)->pluck("name", "id") : "";
            $selected = ! empty($data)
                ? optional($data->tags)
                    ->pluck("id")
                    ->toArray()
                : "";
            $field_placeholder = __("Select an option");
            $required = "";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->multiselect($field_name, $field_options, $selected)->class("form-control select2-tags")->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6 mb-3">
        <div class="form-group">
            <?php
            $field_name = "status";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = __("Select an option");
            $required = "required";
            $select_options = \Nasirkhan\ModuleManager\Modules\Post\Enums\PostStatus::toArray();
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class("form-select")->attributes(["$required"]) }}
        </div>
    </div>
    <div class="col-12 col-sm-6 mb-3">
        <div class="form-group">
            <?php
            $field_name = "published_at";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->datetime($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-5 mb-3">
        <div class="form-group">
            <?php
            $field_name = "meta_title";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
        </div>
    </div>
    <div class="col-12 col-sm-5 mb-3">
        <div class="form-group">
            <?php
            $field_name = "meta_keywords";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
        </div>
    </div>
    <div class="col-12 col-sm-2 mb-3">
        <div class="form-group">
            <?php
            $field_name = "order";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6 mb-3">
        <div class="form-group">
            <?php
            $field_name = "meta_description";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
        </div>
    </div>
    <div class="col-12 col-sm-6 mb-3">
        <div class="form-group">
            <?php
            $field_name = "meta_og_image";
            $field_lable = __("post::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "";
            ?>

            {{ html()->label($field_lable, $field_name)->class("form-label")->for($field_name) }}
            {!! field_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
        </div>
    </div>
</div>

@push("after-scripts")
    <!-- Select2 Library -->
    <x-library.select2 />
    <script type="module">
        $(document).ready(function () {
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
                document.querySelector('.select2-container--open .select2-search__field').focus();
            });

            $('.select2-category').select2({
                theme: 'bootstrap-5',
                placeholder: '@lang("Select an option")',
                minimumInputLength: 2,
                allowClear: true,
                ajax: {
                    url: '{{ route("backend.categories.index_list") }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data,
                        };
                    },
                    cache: true,
                },
            });

            $('.select2-tags').select2({
                theme: 'bootstrap-5',
                placeholder: '@lang("Select an option")',
                minimumInputLength: 2,
                allowClear: true,
                ajax: {
                    url: '{{ route("backend.tags.index_list") }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data,
                        };
                    },
                    cache: true,
                },
            });
        });
    </script>
@endpush
