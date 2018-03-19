<div class="form-group{{ $errors->has($field) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        <select class="form-control relation {{$object->checkDependsOn($field) ? 'depending-field' : ''}} {{$object->hasMultipleValues($field) ? 'relation-multiple' : ''}}"
            id="{{ $object->formFieldName($field, isset($fieldPrefix) ? $fieldPrefix : '') }}" name="{{ $object->formFieldName($field, isset($fieldPrefix) ? $fieldPrefix : '') }}"
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
                @foreach ($object->formRelationValues($field) as $item)
                    <option value="{{ $item->id }}"
                        @if($object->checkFormSelectRelationValue($field, $item)) selected @endif
                        @if($object->checkFormDisabledRelationValue($field, $item)) disabled @endif
                    >
                        {{ $item[$item->defaultDropdownColumn] }}
                    </option>
                @endforeach

        </select>
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
                    @include('blocks.model.relation.form_relation_item', ['item' => $item, 'withCategory' => false])
                @endforeach
            </div>
            @if (isset($addNewItem) && $addNewItem)
                <a href=":javascript" class="add-new-relation-item"
                    data-relation="{{ $field }}"
                    data-model="{{ $object->modelClass }}"
                    data-model-type="{{ $object->modelTypeIdValue() }}"
                    data-last-index="0"
                ><i class="fa fa-plus" aria-hidden="true"></i></a>
            @endif
        </div>
    </div>
@endif