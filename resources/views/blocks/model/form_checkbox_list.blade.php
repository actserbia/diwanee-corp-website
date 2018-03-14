<div class="form-group{{ $errors->has($field) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        {{ __('blade_templates.admin.types.multiple_list_has_levels') }}
        <input class="form-control has-levels" type="checkbox"
            id="{{ $object->formFieldName($field, isset($fieldPrefix) ? $fieldPrefix : '') }}" 
            name="{{ $object->formFieldName($field, isset($fieldPrefix) ? $fieldPrefix : '') }}[]"
            @if($object->formCheckboxListValue($field, 0)) checked @endif
            @if(!$object->checkIfCanRemove()) disabled @endif
            data-model="{{ $object->modelClass }}"
            data-model-id="{{ $object->id }}"
            data-field="{{ $field }}"
            data-field-prefix="{{ $fieldPrefix }}"
        />
        
        <div id="checkbox-list">
            @if($object->formCheckboxListValue($field, 0))
                @foreach ($object->formCheckboxListValue($field, 1, 'list') as $itemFieldValue)
                    @include('blocks.model.form_checkbox_list_item', ['removeCheckbox' => $object->checkIfCanRemove()])
                @endforeach
            @else
                @include('blocks.model.form_checkbox_list_item', ['removeCheckbox' => false, 'itemFieldValue' => $object->formCheckboxListValue($field, 1)])
            @endif  
        </div>
        
        <div class="checkbox-add" @if(!$object->formCheckboxListValue($field, 0)) style="display:none;" @endif>
            <a href="javascript:" class="add-checkbox"
                data-model="{{ $object->modelClass }}"
                data-model-id="{{ $object->id }}"
                data-field="{{ $field }}"
                data-field-prefix="{{ $fieldPrefix }}"
                data-maximum-count="{{ $object->getMaximumCheckboxItemsCount($field) }}"
            ><i class="fa fa-plus" aria-hidden="true"></i></a>
        </div>
        
        @if ($errors->has($field))
            <span class="help-block">{{ $errors->first($field) }}</span>
        @endif
    </div>
</div>