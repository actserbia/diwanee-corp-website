@if (empty($items))
    @include('blocks.form_select_object', ['name' => $name, 'label' => $label, 'itemKey' => 'id', 'itemTitle' => 'name'])
@else
    @include('blocks.form_select_object', ['name' => $name, 'label' => $label, 'items' => $items, 'itemKey' => 'id', 'itemTitle' => 'name'])
@endif

<div class="form-group">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element', !empty($template) ? $template : 'admin') }}"></label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label', !empty($template) ? $template : 'admin') }}">
        <div id="selected-{{ $name }}">
            @if (isset($selectedItems))
                @foreach ($selectedItems as $tag)
                    <div>{{ $tag->name }} <a href="#" class="{{ $name }}-remove-selected" data-id="{{ $tag->id }}" data-tags-type="{{ $name }}">x</a></div>
                @endforeach
            @endif
        </div>
        <div id="selected-{{ $name }}-hidden"></div>
    </div>
</div>