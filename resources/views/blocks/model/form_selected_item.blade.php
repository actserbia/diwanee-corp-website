<div class="selected-item" id="selected-item-{{ $field }}-{{ $item['id'] }}"
    @if($sortable) draggable="true" @endif
>
    {{ $item['name'] }} 
    <a href=":javascript" id="{{ $field }}-remove-selected" class="remove-selected" data-id="{{ $item['id'] }}" data-field="{{ $field }}">
        <i class="fa fa-times"></i>
    </a>
</div>