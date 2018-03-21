<div class="form-group{{ $object->hasError($errors, $field, $fieldPrefix) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
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

        @if ($object->formHasError($errors, $field, $fieldPrefix))
            <span class="help-block">{{ $object->formErrorMessage($errors, $field, $fieldPrefix) }}</span>
        @endif
    </div>
</div>


@if ($object->hasMultipleValues($field))
    <div class="form-group">
        <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}"></label>
        <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
            <div id="selected-{{ $field }}">
                @foreach ($object->formSelectedValues($field, $fieldPrefix) as $item)
                    @include('blocks.model.relation.form_relation_item', ['item' => $item, 'withCategory' => true])
                @endforeach
            </div>
        </div>
    </div>
@endif