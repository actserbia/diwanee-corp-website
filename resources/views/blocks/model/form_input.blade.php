<div class="form-group{{ $errors->has($field) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        <input class="form-control "
            type="{{ $object->attributeType($field) }}"
            value="{{ $object->formValue($field) }}"
            id="{{ $object->formFieldName($field, isset($fieldPrefix) ? $fieldPrefix : '') }}" 
            name="{{ $object->formFieldName($field, isset($fieldPrefix) ? $fieldPrefix : '') }}"
            @if($object->isRequired($field)) required @endif
            @if($object->attributeType($field) === 'checkbox' && $object->formValue($field)) checked @endif
        />
        @if ($errors->has($field))
            <span class="help-block">{{ $errors->first($field) }}</span>
        @endif
    </div>
</div>