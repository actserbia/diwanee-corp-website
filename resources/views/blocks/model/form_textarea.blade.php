<div class="form-group{{ $object->formHasError($errors, $field, $fieldPrefix) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        <textarea class="form-control" rows="4"
            id="{{ $object->formFieldName($field, $fieldPrefix) }}"
            name="{{ $object->formFieldName($field, $fieldPrefix) }}"
            data-field="{{ $field }}"
            @if($object->isRequired($field)) required @endif
        >{{ $object->formValue($field, $fieldPrefix) }}</textarea>
        @if ($object->formHasError($errors, $field, $fieldPrefix))
            <span class="help-block">{{ $object->formErrorMessage($errors, $field, $fieldPrefix) }}</span>
        @endif
    </div>
</div>