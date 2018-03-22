<div class="form-group{{ $object->formHasError($errors, $field, $fieldPrefix) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        @include('blocks.model.relation.form_' . $object->relationFormRenderType($field))
        
        @if ($object->formHasError($errors, $field, $fieldPrefix))
            <span class="help-block">{{ $object->formErrorMessage($errors, $field, $fieldPrefix) }}</span>
        @endif
    </div>
</div>

@if ($object->hasMultipleValues($field))
    <div class="form-group">
        <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}"></label>
        <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
            <div id="selected-{{ $object->formFieldName($field, $fieldPrefix) }}">
                @foreach ($object->formSelectedValues($field, $fieldPrefix) as $key => $item)
                    @if (strpos($key, '-new') === false)
                        @include('blocks.model.relation.form_relation_item', ['item' => $item, 'withCategory' => ($object->relationFormRenderType($field) === 'input') ? true : false])
                    @else
                        @include('blocks.model.relation.form_relation_item__new', ['item' => $item, 'index' => str_replace('-new', '', $key)])
                    @endif
                @endforeach
            </div>
            @if (isset($addNewItem) && $addNewItem)
                <a href=":javascript" class="add-new-relation-item"
                    data-field="{{ $field }}"
                    data-model="{{ $object->modelClass }}"
                    data-model-type="{{ $object->modelTypeIdValue() }}"
                    data-sortable="{{ $object->isSortable($field) }}"
                    data-last-index="0"
                ><i class="fa fa-plus" aria-hidden="true"></i></a>
            @endif
        </div>
    </div>
@endif