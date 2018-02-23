<div class="search-connection-type" @if(Auth::user()->role !== 'admin') style="display:none;" @endif>
    <input type="checkbox" id="connection_type_negation_{{ $field }}" name="connectionTypeNegation_{{ $field }}" value="true"
        @if (Request::post('connectionTypeNegation_' . $field) !== null || Request::old('connectionTypeNegation_' . $field) !== null)) checked @endif
        ><span class="select-value">{{ __('blade_templates.admin.search.negation') }}</span>

    <select class="form-control" id="connection_type_{{ $field }}" name="connectionType_{{ $field }}">
        @foreach (Filters::getConnectionTypesForDropdown() as $typeValue => $typeText)
            <option
                value="{{ $typeValue }}"
                @if(FiltersUtils::checkFormSelectValue('connectionType_' . $field, $typeValue, Filters::DefaultConnectionType))
                    selected
                @endif
            >
                {{ $typeText }}
            </option>
        @endforeach
    </select>
</div>