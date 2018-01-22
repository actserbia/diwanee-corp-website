<div class="form-group{{ $errors->has($field) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->required($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        <select class="form-control {{$object->checkDependsOn($field) ? 'depending-field' : ''}} {{$object->isMultiple($field) ? 'relation-multiple' : ''}}" 
            id="{{ $field }}" name="{{ $field }}"
            data-model="{{ $object->modelClass }}"
            data-column="{{ isset($column) ? $column : $object->getDefaultDropdownColumn($field) }}"
            data-depends-on="{{ $object->dependsOn($field) }}"
            data-sortable="{{ $object->isSortable($field) }}"
            @if($object->required($field) && !$object->isMultiple($field)) required @endif
        >
                <option value=""></option>
                @foreach ($object->formRelationValues($field) as $item)
                    <option value="{{ $item->id }}" @if($object->checkFormSelectRelationValue($field, $item)) selected @endif>{{ isset($column) ? $item->$column : $item[$item->defaultDropdownColumn] }}</option>
                @endforeach

        </select>
        @if ($errors->has($field))
            <span class="help-block">{{ $errors->first($field) }}</span>
        @endif
    </div>
</div>


@if ($object->isMultiple($field))
    <div class="form-group">
        <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}"></label>
        <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
            <div id="selected-{{ $field }}">
                @foreach ($object->formSelectedValues($field) as $item)
                    @include('blocks.model.form_selected_item', ['item' => $item, 'sortable' => $object->isSortable($field)])
                @endforeach
            </div>
            <div id="selected-{{ $field }}-hidden"></div>
        </div>
    </div>
@endif