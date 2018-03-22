<div id="relation-item-{{ $field }}-{{ isset($level) ? $level : 1 }}-{{ $item->id }}" class="relation-item" draggable="true">
    <input type="hidden" value="{{ $item->id }}" id="{{ $field }}" name="{{ $field }}[]" />
    @include('blocks.model.form_label', ['fieldPrefix' => '_', 'field' => $item->representationField, 'object' => $item])

    <a href=":javascript"
        id="{{ $field }}-remove-selected"
        class="remove-selected"
        data-id="{{ $item->id }}"
        data-field="{{ $field }}"
        data-level="{{ isset($level) ? $level : 1}}">
        <i class="fa fa-times"></i>
    </a>
</div>