<div class="form-group{{ $errors->has($field) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $object->fieldLabel($field) }}
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">

        <div class="input-group date">
            <input class="form-control"
                type="text"
                value="{{ $object->formValue($field) }}"
                id="{{ $field }}"
                name="{{ $field }}"
            />
            <span class="input-group-addon">
               <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
        
        @if ($errors->has($field))
            <span class="help-block">{{ $errors->first($field) }}</span>
        @endif
    </div>
</div>
