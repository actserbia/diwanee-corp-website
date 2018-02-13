<form id="data_form" method="post" action="{{ route('nodes.store') }}" data-parsley-validate class="form-horizontal form-label-left">
    {{ csrf_field() }}

    <input name="nodeType" type="hidden" value="{{ $nodeType }}" />

    @foreach($object->getFillableFields() as $field)
        @include('blocks.model', ['field' => $field])
    @endforeach

    @foreach($object->getFillableRelations() as $relation)
        @include('blocks.model', ['field' => $relation])
    @endforeach

    <div class="ln_solid"></div>

    <div class="form-group">
        <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_without_label') }}">
            <button type="submit" class="btn btn-success">{{ Utils::translateModelData('blade_templates.admin.global.create_button_text')}}</button>
        </div>
    </div>
</form>