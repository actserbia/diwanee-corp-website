<div class="form-group{{ $object->formHasError($errors, $field, $fieldPrefix) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        <input class="form-control {{ $object->defaultDropdownColumn === $field ? 'default-dropdown' : '' }}"
            type="{{ $object->attributeType($field) }}"
            value="{{ $object->formValue($field, $fieldPrefix) }}"
            id="{{ $object->formFieldName($field, $fieldPrefix) }}"
            name="{{ $object->formFieldName($field, $fieldPrefix) }}"
            @if($object->isRequired($field)) required @endif
            @if($object->attributeType($field) === 'checkbox' && $object->formValue($field, $fieldPrefix)) checked @endif
        />
        @if ($object->formHasError($errors, $field, $fieldPrefix))
            <span class="help-block">{{ $object->formErrorMessage($errors, $field, $fieldPrefix) }}</span>
        @endif
    </div>
</div>