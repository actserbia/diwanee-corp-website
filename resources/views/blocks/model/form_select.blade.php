<div class="form-group{{ $object->formHasError($errors, $field, $fieldPrefix) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        <select class="form-control" 
            id="{{ $object->formFieldName($field, $fieldPrefix) }}"
            name="{{ $object->formFieldName($field, $fieldPrefix) }}"
            data-field="{{ $field }}"
            @if($object->isRequired($field)) required @endif
        >
            <option value=""></option>
            @foreach ($object->getEnumListForDropdown($field) as $itemValue => $itemTitle)
                <option value="{{ $itemValue }}" @if($object->checkFormSelectValue($field, $itemValue, $fieldPrefix)) selected @endif>{{ $itemTitle }}</option>
            @endforeach
        </select>

        @if ($object->formHasError($errors, $field, $fieldPrefix))
            <span class="help-block">{{ $object->formErrorMessage($errors, $field, $fieldPrefix) }}</span>
        @endif
    </div>
</div>