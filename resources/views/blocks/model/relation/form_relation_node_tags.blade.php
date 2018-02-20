<div class="form-group{{ $errors->has($field) ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        @if(!isset($level) || $level === 1)
            {{ $object->fieldLabel($field) }} @if($object->isRequired($field))<span class="required">*</span>@endif
        @endif
    </label>
    
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        <select class="form-control relation node-tags-relation {{$object->hasMultipleValues($field, isset($level) ? $level : 1) ? 'relation-multiple' : ''}}"
            id="{{ $field }}-{{ isset($level) ? $level : 1 }}" name="{{ $field }}[]"
            data-relation="{{ $field }}"
            data-model-id="{{ $object->id }}"
            data-node-type="{{ isset($nodeType) ? $nodeType : 1 }}"
            data-column="{{ isset($column) ? $column : $object->getDefaultDropdownColumn($field) }}"
            data-sortable="1"
            data-full-data="0"
            data-level="{{ isset($level) ? $level : 1 }}"
            @if(!isset($checkSelected) || $checkSelected)
                data-selected-values="{{ $object->formTagsRelationValuesIdsList($field, isset($level) ? $level : 1) }}"
            @endif
            @if($object->isRequired($field) && !$object->hasMultipleValues($field, isset($level) ? $level : 1)) required @endif
        >
                <option value=""></option>
                @foreach ($object->formTagsRelationValuesByLevel($field, isset($level) ? $level : 1, isset($tags) ? $tags : null) as $item)
                    <option value="{{ $item->id }}"
                        @if(!isset($checkSelected) && $object->checkFormSelectRelationValue($field, $item, isset($level) ? $level : 1)) selected @endif
                        @if(!isset($checkSelected) && $object->checkFormDisabledRelationValue($field, $item, isset($level) ? $level : 1)) disabled @endif
                    >
                        {{ isset($column) ? $item->$column : $item[$item->defaultDropdownColumn] }}
                    </option>
                @endforeach

        </select>
        @if ($errors->has($field))
            <span class="help-block">{{ $errors->first($field) }}</span>
        @endif
    </div>
</div>


@if ($object->hasMultipleValues($field, isset($level) ? $level : 1))
    <div class="form-group">
        <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}"></label>
        <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
            <div id="selected-{{ $field }}-{{ isset($level) ? $level : 1 }}">
                @foreach ($object->formTagsSelectedValuesByLevel($field, isset($level) ? $level : 1, isset($checkSelected) ? $checkSelected : true) as $item)
                    @include('blocks.model.relation.form_relation_node_tags_item', ['item' => $item])
                @endforeach
            </div>
        </div>
    </div>
@endif

<div id="separator-{{ $field }}-{{ isset($level) ? $level : 1 }}"></div>