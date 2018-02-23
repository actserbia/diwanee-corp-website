<div>
<select class="form-control" id="search_types_{{ $field }}" name="searchTypes_{{ $field }}[]"
    @if(Auth::user()->role !== 'admin') style="display:none;" @endif
>
    @foreach ($model->getSearchTypesForDropdown($field) as $typeValue => $typeText)
        <option
            value="{{ $typeValue }}"
            @if(isset($index))
                @if(FiltersUtils::checkFormSelectValueByIndex('searchTypes_' . $field, $typeValue, $index, Filters::DefaultSearchType))
                    selected
                @endif
            @else
                @if($typeValue === $model->filterDefaultSearchType($field))
                    selected
                @endif
            @endif
        >
            {{ $typeText }}
        </option>
    @endforeach
</select>
<input class="form-control {{ $model->filterIsTextType($field) ? 'typeahead' : ''}}"
    type="{{ $model->filterAttributeType($field) }}"
    value="{{ isset($index) ? FiltersUtils::formValue($field, $index) : '' }}"
    id="{{ $field }}"
    name="{{ $field }}[]"
    data-model="{{ $model->modelClass }}"
    data-model-type="{{ $model->modelTypeIdValue() }}"
    @if ($model->filterIsTextType($field)) data-provide="typeahead" @endif
/>
<a href="javascript:" class="search-remove"><i class="fa fa-times" aria-hidden="true"></i></a>
    @if (isset($index) && $errors->has($field . '.' . $index))
        <span class="help-block">{{ $errors->first($field . '.' . $index) }}</span>
    @endif
</div>