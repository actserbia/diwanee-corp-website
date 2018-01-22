<div class="form-group">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->required($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        @foreach ($object->formReadonlyText($field, isset($column) ? $column : null) as $value)
            @if($readonly === 'label')
                <div class="model-label">{{ $value }}</div>
            @else
                <input class="form-control" type="text" value="{{ $value }}" id="{{ $field }}_text" name="{{ $field }}_text" readonly />
            @endif
        @endforeach
        @foreach ($object->formReadonlyValue($field) as $value)
            <input class="form-control" type="hidden" value="{{ $value }}" id="{{ $field }}" name="{{ $field }}" />
        @endforeach
        
    </div>
</div>