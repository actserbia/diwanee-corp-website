<div class="form-group{{ $errors->has($field) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        @foreach ($object->formValue($field) as $itemFieldValue)
            @include('blocks.model.form_checkbox_list_item', ['removeCheckbox' => $object->checkIfCanRemove()])
        @endforeach
        
        <div class="checkbox-add">
            <a href="javascript:" class="add-checkbox"
                data-model="{{ $object->modelClass }}"
                data-model-id="{{ $object->id }}"
                data-field="{{ $field }}"
                data-field-prefix="{{ $fieldPrefix }}"
            ><i class="fa fa-plus" aria-hidden="true"></i></a>
        </div>
        
        @if ($errors->has($field))
            <span class="help-block">{{ $errors->first($field) }}</span>
        @endif
    </div>
</div>