<div class="form-group{{ $errors->has($field) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }} @if($object->required($field))<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        <select class="form-control" id="{{ $field }}" name="{{ $field }}"
            @if($object->required($field)) required @endif
        >
            <option value=""></option>
            @foreach ($object->getEnumListForDropdown($field) as $itemValue => $itemTitle)
                <option value="{{ $itemValue }}" @if($object->checkFormSelectValue($field, $itemValue)) selected @endif>{{ $itemTitle }}</option>
            @endforeach
        </select>

        @if ($errors->has($field))
            <span class="help-block">{{ $errors->first($field) }}</span>
        @endif
    </div>
</div>