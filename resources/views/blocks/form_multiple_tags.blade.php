@if (empty($tags)) {
    @include('blocks.form_select_object', ['name' => $name, 'label' => $label, 'itemKey' => 'id', 'itemTitle' => 'name'])
@else
    @include('blocks.form_select_object', ['name' => $name, 'label' => $label, 'items' => $tags, 'itemKey' => 'id', 'itemTitle' => 'name'])
@endif

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div id="selected-{{ $name }}">
                @if (isset($selectedTags))
                    @foreach ($selectedTags as $tag)
                        <div>{{ $tag->name }} <a href="#" class="{{ $name }}-remove-selected" data-tags-type="{{ $name }}" data-id="{{ $tag->id }}">x</a></div>
                    @endforeach
                @endif
            </div>
            <div id="selected-{{ $name }}-hidden"></div>
        </div>
</div>