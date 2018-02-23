<div class="form-group{{ $errors->has('statistic') ? ' has-error' : '' }}">
    <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="statistic">
        {{ __('blade_templates.admin.statistics.statistic_label') }} <span class="required">*</span>
    </label>
    <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
        <select class="form-control" id="statistic" name="statistic" required>
            <option value=""></option>
            @foreach ($model->getStatisticFieldsWithLabels() as $itemValue => $itemTitle)
                <option value="{{ $itemValue }}" @if(isset($statisticName) && $statisticName == $itemValue)) selected @endif>{{ $itemTitle }}</option>
            @endforeach
        </select>
    </div>
</div>