<div class="form-group">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        @foreach ($object->formReadonlyData($field) as $data)
            @if($readonly === 'readonly_field')
                <input class="form-control" type="text" value="{{ $data['label'] }}"
                    id="{{ $object->formFieldName($field, $fieldPrefix) }}_text"
                    name="{{ $object->formFieldName($field, $fieldPrefix) }}_text"
                    readonly
                />
            @elseif($readonly === 'label_with_link' && isset($data['url']))
                <div class="model-label">
                    <a href="{{ $data['url'] }}">{{ $data['label'] }}</a>
                </div>
            @else
                <div class="model-label">{{ $data['label'] }}</div>
            @endif
            <input class="form-control {{ $object->representationField === $field ? 'default-dropdown' : '' }}" type="hidden" value="{{ $data['value'] }}" 
                id="{{ $object->formFieldName($field, $fieldPrefix) }}"
                name="{{ $object->formFieldName($field, $fieldPrefix) }}"
            />
        @endforeach
    </div>
</div>