<div class="form-group{{ $errors->has($field) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        <input class="form-control relation {{$object->checkDependsOn($field) ? 'depending-field' : ''}} {{$object->hasMultipleValues($field) ? 'relation-multiple' : ''}}"
            type="text"
            value="{{ $object->formInputRelationValue($field, $object->getDefaultDropdownColumn($field)) }}"
            id="{{ $object->formFieldName($field, isset($fieldPrefix) ? $fieldPrefix : '') }}-input" name="{{ $object->formFieldName($field, isset($fieldPrefix) ? $fieldPrefix : '') }}-input"
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
                value="{{ $object->formInputRelationValue($field, 'id') }}"
            />
        @endif

        @if ($errors->has($field))
            <span class="help-block">{{ $errors->first($field) }}</span>
        @endif
    </div>
</div>


@if ($object->hasMultipleValues($field))
    <div class="form-group">
        <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}"></label>
        <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
            <div id="selected-{{ $field }}">
                @foreach ($object->formSelectedValues($field) as $item)
                    @include('blocks.model.relation.form_relation_item', ['item' => $item, 'withCategory' => true])
                @endforeach
            </div>
        </div>
    </div>
@endif