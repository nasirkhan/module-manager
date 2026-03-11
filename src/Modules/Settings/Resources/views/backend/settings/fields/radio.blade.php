@php
    $required = Str::contains($field["rules"], "required") ? "required" : "";
    $required_mark = $required != "" ? '<span class="text-danger"> <strong>*</strong> </span>' : "";
@endphp

<div class="form-group {{ $errors->has($field["name"]) ? " has-error" : "" }} mt-3">
    <div class="radio-group">
        <label class="form-label" for="{{ $field["name"] }}">
            <strong>{{ __($field["label"]) }}</strong>
            ({{ $field["name"] }})
        </label>
        {!! $required_mark !!}
        <br />

        <div class="d-flex flex-wrap gap-3 align-items-center">
            @foreach ($field["options"] as $value => $label)
                <div class="form-check mb-0">
                    <label class="form-check-label d-inline-flex align-items-center gap-1">
                        <input
                            class="form-check-input"
                            name="{{ $field["name"] }}"
                            type="radio"
                            value="{{ $value }}"
                            @if(old($field['name'], setting($field['name'])) == $value) checked="checked" @endif
                        />
                        {{ __($label) }}
                    </label>
                </div>
            @endforeach
        </div>

        @if ($errors->has($field["name"]))
            <small class="help-block">{{ $errors->first($field["name"]) }}</small>
        @endif
    </div>
</div>
