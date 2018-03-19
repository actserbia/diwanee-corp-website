<div id="relation-item-{{ $field }}-{{ $index }}" class="relation-item" @if($object->isSortable($field)) draggable="true" @endif>
    @foreach ($item->getAutomaticRenderAtributesAndRelations() as $itemFieldName)
        @include('blocks.model', ['fieldPrefix' => 'new_item[' . $field . '][' . $index . ']', 'field' => $itemFieldName, 'object' => $item])
    @endforeach
        
    @foreach ($object->extraFields($field) as $itemFieldName)
        @include('blocks.model', ['fieldPrefix' => 'pivot_' . $field . '[' . $index . ']', 'field' => $itemFieldName, 'object' => $item])
    @endforeach
        
    <a href=":javascript" class="remove-added-relation-item">
        <i class="fa fa-times"></i>
    </a>
        
    <div class="ln_solid"></div>
</div>