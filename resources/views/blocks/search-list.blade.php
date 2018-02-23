<div id="filters">
    @foreach ($model->getFilterFieldsWithLabels() as $field => $fieldTitle)
        @if(FiltersUtils::checkIfIsSetFormValue($field))
            @include('blocks.search.' . $model->formFieldType($field))
        @endif
   @endforeach
</div>
<div class="form-group{{ $errors->has('search') ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="search">
        {{ __('blade_templates.admin.search.search_label') }}
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        <select class="form-control" 
            id="search" name="search"
            data-model="{{ $model->modelClass }}"
            data-model-type="{{ $model->modelTypeIdValue() }}"
        >
            <option value=""></option>
            @foreach ($model->getFilterFieldsWithLabels() as $field => $fieldTitle)
                <option value="{{ $field }}" @if(FiltersUtils::checkIfIsSetFormValue($field)) disabled @endif>{{ $fieldTitle }}</option>
            @endforeach
        </select>
    </div>
</div>