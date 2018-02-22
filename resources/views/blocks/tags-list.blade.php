@foreach($tags as $tag)
    <div id="tag-item-{{ $tag->id }}-{{ Utils::autoincrement('tags') }}" class="tag-item" draggable="true" data-level="{{ isset($level) ? $level : 1 }}" data-tag-id="{{ $tag->id }}" data-moving-disabled="{{ $tag->movingDisabled ? '1' : '0' }}">
        <div class="{{ $tag->movingDisabled ? 'moving-disable' : '' }}">
            {{ $tag->name }} ( {{ count($tag->nodes) }} )
            <a href="{{ route('tags.edit', ['id' => $tag->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="@lang('blade_templates.global.edit')"></i> </a>
            <a href="{{ route('tags.show', ['id' => $tag->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="@lang('blade_templates.global.delete')"></i> </a>
        </div>
        <div class="tags-list">
            @include('blocks.tags-list', ['tags' => $tag->children, 'level' => isset($level) ? $level + 1 : 2])
        </div>
    </div>
@endforeach