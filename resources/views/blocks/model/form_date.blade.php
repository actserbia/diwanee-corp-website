<div class="form-group{{ $object->formHasError($errors, $field, $fieldPrefix) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }}
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">

        <div class="input-group date">
            <input class="form-control"
                type="text"
                value="{{ $object->formValue($field, $fieldPrefix) }}"
                id="{{ $field }}"
                name="{{ $field }}"
            />
            <span class="input-group-addon">
               <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
        
        @if ($object->formHasError($errors, $field, $fieldPrefix))
            <span class="help-block">{{ $object->formErrorMessage($errors, $field, $fieldPrefix) }}</span>
        @endif
    </div>
</div>
