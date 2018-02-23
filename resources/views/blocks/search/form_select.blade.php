<div class="form-group{{ $errors->has($field . '.*') ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $model->fieldLabel($field) }}
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        @if (!isset($onlyAnd) && $model->fullIsMultipleRelation($field))
            @include('blocks.search.form_select_connection_type')
        @endif

        @foreach ($model->getEnumListForDropdown($field) as $itemValue => $itemTitle)
            <input type="checkbox" id="{{ $field }}" name="{{ $field }}[]" value="{{ $itemValue }}"
                @if(FiltersUtils::checkFormSelectValue($field, $itemValue)) checked @endif
                ><span class="select-value">{{ $itemTitle }}</span>
        @endforeach

        @if ($errors->has($field . '.0'))
            <span class="help-block">{{ $errors->first($field . '.0') }}</span>
        @endif
    </div>
</div>