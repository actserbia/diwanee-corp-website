<div class="checkbox-item-container">
    <input class="form-control checkbox-item" type="checkbox"
        value="{{ $itemFieldValue }}"
        id="{{ $object->formFieldName($field, isset($fieldPrefix) ? $fieldPrefix : '') }}" 
        name="{{ $object->formFieldName($field, isset($fieldPrefix) ? $fieldPrefix : '') }}[]"
        @if($itemFieldValue) checked @endif
        @if($itemFieldValue && !$object->checkIfCanRemove()) disabled @endif
        data-model-id="{{ $object->id }}"
    />
    @if($removeCheckbox) 
        <a href=":javascript" class="remove-checkbox"><i class="fa fa-times"></i></a>
    @endif
</div>