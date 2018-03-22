<select class="form-control relation {{$object->checkDependsOn($field) ? 'depending-field' : ''}} {{$object->hasMultipleValues($field) ? 'relation-multiple' : ''}}"
    id="{{ $object->formFieldName($field, $fieldPrefix) }}"
    name="{{ $object->formFieldName($field, $fieldPrefix) }}"
    data-relation="{{ $field }}"
    data-model="{{ $object->modelClass }}"
    data-model-type="{{ $object->modelTypeIdValue() }}"
    data-model-id="{{ $object->id }}"
    data-depends-on="{{ $object->dependsOn($field) }}"
    data-sortable="{{ $object->isSortable($field) }}"
    data-full-data="{{ isset($fullData) ?: false }}"
    @if($object->isRequired($field) && !$object->hasMultipleValues($field)) required @endif
>
    <option value=""></option>
    @foreach ($object->formRelationValues($field, $fieldPrefix) as $item)
        <option value="{{ $item->id }}"
            @if($object->checkFormSelectRelationValue($field, $item, $fieldPrefix)) selected @endif
            @if($object->checkFormDisabledRelationValue($field, $item)) disabled @endif
        >{{ $item[$item->representationField] }}</option>
    @endforeach
</select>