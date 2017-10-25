@if (empty($items))
    @include('blocks.form_select_object', ['name' => $name, 'label' => $label, 'itemKey' => 'id', 'itemTitle' => 'name'])
@else
    @include('blocks.form_select_object', ['name' => $name, 'label' => $label, 'items' => $items, 'itemKey' => 'id', 'itemTitle' => 'name'])
@endif

<div class="form-group">
    <label class="control-label col-md-3 col-sm-2 col-xs-12"></label>
        <div class="col-md-6 col-sm-8 col-xs-12">
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