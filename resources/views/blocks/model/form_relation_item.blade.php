<div id="relation-item-{{ $field }}-{{ $item->id }}" class="relation-item" @if($object->isSortable($field)) draggable="true" @endif>
    <input type="hidden" value="{{ $item->id }}" id="{{ $field }}" name="{{ $field }}[]" />
    @if (isset($fullData) && $fullData)
        @foreach ($item->getFillableAtributesAndRelations() as $itemFieldName)
            @include('blocks.model', ['readonly' => 'label', 'fieldPrefix' => '_', 'field' => $itemFieldName, 'object' => $item])
        @endforeach

        @foreach ($object->extraFields($field) as $itemFieldName)
            @include('blocks.model', ['multiple' => true, 'fieldPrefix' => $field, 'field' => $itemFieldName, 'object' => $item])
        @endforeach
    @else
        @include('blocks.model.form_label', ['fieldPrefix' => '_', 'field' => $item->defaultDropdownColumn, 'object' => $item])
    @endif

    @if (isset($isNew) && $isNew)
        <a href=":javascript" id="{{ $field }}-remove-selected" class="remove-selected" data-id="{{ $item->id }}" data-field="{{ $field }}">
            <i class="fa fa-times"></i>
        </a>
    @endif
    
    @if (isset($fullData) && $fullData)
        <div class="ln_solid"></div>
    @endif
</div>