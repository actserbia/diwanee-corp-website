<div class="form-group{{ $errors->has($field . '.*') ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $model->fieldLabel($field) }}
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        @include('blocks.search.form_select_connection_type')

        <div class="search-elements">
            @foreach (FiltersUtils::formValue($field) as $index => $value)
                @include('blocks.search.form_input_detail', ['index' => $index])
            @endforeach
        </div>
        <div class="search-add-input">
            <a href="javascript:"
                data-field="{{ $field }}"
                data-model="{{ $model->modelClass }}"
                data-model-type="{{ $model->modelTypeIdValue() }}">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>