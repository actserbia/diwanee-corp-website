<div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element', !empty($template) ? $template : 'admin') }}" for="{{ $name }}">{{ $label }} @if(!empty($required) && $required)<span class="required">*</span>@endif</label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label', !empty($template) ? $template : 'admin') }}">
        <select class="form-control" id="{{ $name }}" name="{{ $name }}" @if(!empty($required) && $required) required @endif>
            @if (!empty($tags))
                <option value=""></option>
                @foreach ($tags as $tag)
                    @if ($tag['type'] === $name)
                        <option value="{{ $tag['id'] }}" @if(isset($selected) && $selected == $tag['id']) selected @endif>{{ $tag['name'] }}</option>
                    @endif
                @endforeach
            @endif
        </select>
        @if ($errors->has($name))
            <span class="help-block">{{ $errors->first($name) }}</span>
        @endif
    </div>
</div>