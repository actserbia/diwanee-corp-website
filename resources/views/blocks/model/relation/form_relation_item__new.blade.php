<div id="relation-item-{{ $field }}-{{ $index }}-new" class="relation-item" data-field="{{ $field }}" @if($object->isSortable($field)) draggable="true" @endif>
    <input type="hidden" value="{{ $index }}-new" id="{{ $field }}" name="{{ $field }}[]" />
    @foreach ($item->getAutomaticRenderAtributesAndRelations() as $itemFieldName)
        @include('blocks.model', ['fieldPrefix' => 'new_items[' . $field . '][' . $index . '-new]', 'field' => $itemFieldName, 'object' => $item])
    @endforeach
        
    @foreach ($object->extraFields($field) as $itemFieldName)
        @include('blocks.model', ['fieldPrefix' => 'new_items[' . $field . '][' . $index . '-new][pivot]', 'field' => $itemFieldName, 'object' => $item])
    @endforeach
        
    <a href=":javascript" class="remove-added-relation-item">
        <i class="fa fa-times"></i>
    </a>
        
    <div class="ln_solid"></div>
</div>