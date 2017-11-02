<div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element', !empty($template) ? $template : 'admin') }}" for="{{ $name }}">
        {{ $label }} @if(!empty($required) && $required)<span class="required">*</span>@endif
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label', !empty($template) ? $template : 'admin') }}">
        <input class="form-control"
            type="{{ !empty($type) ? $type : 'text' }}"
            value="{{ $value }}" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            @if(!empty($required) && $required) required @endif 
            @if(!empty($readonly) && $readonly) readonly @endif 
        />
        @if ($errors->has($name))
            <span class="help-block">{{ $errors->first($name) }}</span>
        @endif
    </div>
</div>