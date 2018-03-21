<div class="checkbox-item-container">
    <input class="form-control checkbox-item" type="checkbox"
        value="{{ $itemFieldValue }}"
        id="{{ $object->formFieldName($field, $fieldPrefix) }}"
        name="{{ $object->formFieldName($field, $fieldPrefix) }}[value][]"
        @if($itemFieldValue) checked @endif
        @if($itemFieldValue && !$object->checkIfCanRemove()) disabled @endif
        data-type-id="{{ isset($object->pivot->id) ? $object->pivot->id : $object->id }}"
    />
    @if($removeCheckbox) 
        <a href=":javascript" class="remove-checkbox"><i class="fa fa-times"></i></a>
    @endif
</div>