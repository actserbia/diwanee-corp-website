<div class="form-group{{ $object->formHasError($errors, $field, $fieldPrefix) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        @if(!isset($level) || $level === 1)
            {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
        @endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        <select class="form-control relation tags-parenting-relation {{$object->hasMultipleValues($field, isset($level) ? $level : 1) ? 'relation-multiple' : ''}}"
            id="{{ $object->formFieldName($field, $fieldPrefix) }}-{{ isset($level) ? $level : 1 }}"
            name="{{ $object->formFieldName($field, $fieldPrefix) }}[]"
            data-relation="{{ $field }}"
            data-model="{{ $object->modelClass }}"
            data-model-type="{{ $object->modelTypeIdValue() }}"
            data-model-id="{{ $object->id }}"
            data-sortable="{{ $object->isSortable($field) }}"
            data-full-data="{{ isset($fullData) ?: false }}"
            data-level="{{ isset($level) ? $level : 1 }}"
            @if(!isset($checkSelected) || $checkSelected)
                data-selected-values="{{ $object->formRelationValuesIdsList($field, isset($level) ? $level : 1) }}"
            @endif
            @if($object->isRequired($field) && (!isset($level) || $level == 1) && !$object->hasMultipleValues($field, isset($level) ? $level : 1)) required @endif
        >
                <option value=""></option>
                @foreach ($object->formRelationValuesByLevel($field, isset($level) ? $level : 1, isset($tags) ? $tags : null) as $item)
                    <option value="{{ $item->id }}"
                        @if($object->checkFormSelectRelationValue($field, $item, $fieldPrefix, isset($level) ? $level : 1)) selected @endif
                        @if($object->checkFormDisabledRelationValue($field, $item, isset($level) ? $level : 1)) disabled @endif
                    >
                        {{ $item[$item->representationField] }}
                    </option>
                @endforeach

        </select>
        @if ($object->formHasError($errors, $field, $fieldPrefix))
            <span class="help-block">{{ $object->formErrorMessage($errors, $field, $fieldPrefix) }}</span>
        @endif
    </div>
</div>


@if ($object->hasMultipleValues($field, isset($level) ? $level : 1))
    <div class="form-group">
        <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}"></label>
        <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
            <div id="selected-{{ $field }}-{{ isset($level) ? $level : 1 }}">
                @foreach ($object->formSelectedValuesByLevel($field, isset($level) ? $level : 1, isset($checkSelected) ? $checkSelected : true) as $item)
                    @include('blocks.model.relation.form_relation_tags_parenting_item', ['item' => $item])
                @endforeach
            </div>
        </div>
    </div>
@endif

<div id="separator-{{ $field }}-{{ isset($level) ? $level : 1 }}"></div>