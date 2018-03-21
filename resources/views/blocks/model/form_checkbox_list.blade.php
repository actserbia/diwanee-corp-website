<div class="form-group{{ $object->formHasError($errors, $field, $fieldPrefix) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        {{ __('blade_templates.admin.types.multiple_list_hierarchy') }}
        <input class="form-control hierarchy" type="checkbox"
            id="{{ $object->formFieldName($field, $fieldPrefix) }}"
            name="{{ $object->formFieldName($field, $fieldPrefix) }}[hierarchy]"
            value="{{ $object->formValue($field, $fieldPrefix)['hierarchy'] }}"
            @if($object->formValue($field, $fieldPrefix)['hierarchy']) checked @endif
            @if(!$object->formValue($field, $fieldPrefix)['hierarchy'] && !$object->checkIfCanRemove()) disabled @endif
            data-model="{{ $object->modelClass }}"
            data-model-id="{{ $object->id }}"
            data-field="{{ $field }}"
            data-field-prefix="{{ $fieldPrefix }}"
        />
        
        <div id="checkbox-list">
            @if($object->formValue($field, $fieldPrefix)['hierarchy'])
                @foreach ($object->formValue($field, $fieldPrefix)['value'] as $itemFieldValue)
                    @include('blocks.model.form_checkbox_list_item', ['removeCheckbox' => $object->checkIfCanRemove()])
                @endforeach
            @else
                @include('blocks.model.form_checkbox_list_item', ['removeCheckbox' => false, 'itemFieldValue' => $object->formValue($field, $fieldPrefix)['value'][0]])
            @endif  
        </div>
        
        <div class="checkbox-add" @if(!$object->formValue($field, $fieldPrefix)['hierarchy']) style="display:none;" @endif>
            <a href="javascript:" class="add-checkbox"
                data-model="{{ $object->modelClass }}"
                data-model-id="{{ $object->id }}"
                data-field="{{ $field }}"
                data-field-prefix="{{ $fieldPrefix }}"
                data-maximum-count="{{ $object->getMaximumCheckboxItemsCount($field) }}"
                data-type-id="{{ isset($object->pivot->id) ? $object->pivot->id : $object->id }}"
            ><i class="fa fa-plus" aria-hidden="true"></i></a>
        </div>
        
        @if ($object->formHasError($errors, $field, $fieldPrefix))
            <span class="help-block">{{ $object->formErrorMessage($errors, $field, $fieldPrefix) }}</span>
        @endif
    </div>
</div>