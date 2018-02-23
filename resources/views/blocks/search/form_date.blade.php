<div class="form-group{{ $errors->has($field . '.*') ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="{{ $field }}">
        {{ $model->fieldLabel($field) }}
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">

        <div class="search-date input-group date" id="datetimepicker-start-date-{{ Utils::autoincrement('datetimepicker-start-date') }}">
            <input class="form-control"
                type="text"
                value="{{ FiltersUtils::formValue($field, 0) }}"
                id="{{ $field }}"
                name="{{ $field }}[]"
            />
            <span class="input-group-addon">
               <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>

        <div class="search-date input-group date" id="datetimepicker-end-date-{{ Utils::autoincrement('datetimepicker-end-date') }}">
            <input class="form-control"
                type="text"
                value="{{ FiltersUtils::formValue($field, 1) }}"
                id="{{ $field }}"
                name="{{ $field }}[]"
            />
            <span class="input-group-addon">
               <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
        
        @if ($errors->has($field . '.0'))
            <span class="help-block">{{ $model->replaceWithAttributtesNames($errors->first($field . '.0'), $field) }}</span>
        @endif
        @if ($errors->has($field . '.1'))
            <span class="help-block">{{ $model->replaceWithAttributtesNames($errors->first($field . '.1'), $field) }}</span>
        @endif

        <input type="hidden" name="searchTypes_{{ $field }}[]" value="{{ Filters::SearchGreaterOrEqual }}" />
        <input type="hidden" name="searchTypes_{{ $field }}[]" value="{{ Filters::SearchLessOrEqual }}" />
        <input type="hidden" name="connectionType_{{ $field }}" value="{{ Filters::ConnectionAnd }}" />
    </div>
</div>
