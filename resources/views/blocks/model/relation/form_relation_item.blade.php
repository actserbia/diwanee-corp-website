<div id="relation-item-{{ $field }}-{{ $item->id }}" class="relation-item" @if($object->isSortable($field)) draggable="true" @endif>
    <input type="hidden" value="{{ $item->id }}" id="{{ $field }}" name="{{ $field }}[]" />
    @if (isset($fullData) && $fullData)
        @foreach ($item->getAutomaticRenderAtributesAndRelations() as $itemFieldName)
            @include('blocks.model', ['readonly' => 'label', 'fieldPrefix' => '_', 'field' => $itemFieldName, 'object' => $item])
        @endforeach

        @foreach ($object->extraFields($field) as $itemFieldName)
            @include('blocks.model', ['fieldPrefix' => 'relation_items[' . $field . '][' . $item->id  . '][pivot]', 'field' => $itemFieldName, 'object' => $item])
        @endforeach
    @else
        @include('blocks.model.form_label', ['fieldPrefix' => '_', 'field' => $item->representationField, 'object' => $item, 'withCategory' => $withCategory])
    @endif

    @if ($object->checkIfCanRemoveSelectedRelationItem($field, $item))
        <a href=":javascript" 
            id="{{ $field }}-remove-selected" 
            class="remove-selected" 
            data-id="{{ $item->id }}" 
            data-field="{{ $field }}">
            <i class="fa fa-times"></i>
        </a>
    @endif
    
    @if (isset($fullData) && $fullData)
        <div class="ln_solid"></div>
    @endif
</div>