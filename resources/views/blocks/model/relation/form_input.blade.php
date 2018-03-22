<input class="form-control relation {{$object->checkDependsOn($field) ? 'depending-field' : ''}} {{$object->hasMultipleValues($field) ? 'relation-multiple' : ''}}"
    type="text"
    value="{{ $object->formInputRelationValue($field, $object->getDefaultDropdownColumn($field), $fieldPrefix) }}"
    id="{{ $object->formFieldName($field, $fieldPrefix) }}-input" name="{{ $object->formFieldName($field, $fieldPrefix) }}-input"
    data-relation="{{ $field }}"
    data-model="{{ $object->modelClass }}"
    data-model-type="{{ $object->modelTypeIdValue() }}"
    data-model-id="{{ $object->id }}"
    data-depends-on="{{ $object->dependsOn($field) }}"
    data-sortable="{{ $object->isSortable($field) }}"
    data-full-data="{{ isset($fullData) ?: false }}"
    @if($object->isRequired($field) && !$object->hasMultipleValues($field)) required @endif
/>
@if (!$object->hasMultipleValues($field))
    <input type="hidden" id="{{ $field }}" name="{{ $field }}" class="single-relation"
        value="{{ $object->formInputRelationValue($field, 'id', $fieldPrefix) }}"
    />
@endif