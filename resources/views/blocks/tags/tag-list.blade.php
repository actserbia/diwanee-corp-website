@foreach($tags as $tag)
    <div id="tag-item-{{ $tag->id }}-{{ Utils::autoincrement('tags') }}" class="tag-item {{ count($tag->parents) > 1 ? 'moving-disable' : '' }}" draggable="true" data-level="{{ isset($level) ? $level : 1 }}" data-tag-id="{{ $tag->id }}" data-parents-count="{{ count($tag->parents) }}">
        <div>
            {{ $tag->name }}
            <a href="{{ route('tags.edit', ['id' => $tag->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="@lang('blade_templates.global.edit')"></i> </a>
            <a href="{{ route('tags.show', ['id' => $tag->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="@lang('blade_templates.global.delete')"></i> </a>
        </div>
        <div class="tag-list">
            @include('blocks.tags.tag-list', ['tags' => $tag->children, 'level' => isset($level) ? $level + 1 : 2])
        </div>
    </div>
@endforeach