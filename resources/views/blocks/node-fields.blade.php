{!! SirTrevor::stylesheets() !!}
<form id="data_form" method="post" action="{{ route('nodes.store') }}" data-parsley-validate class="form-horizontal form-label-left">
    {{ csrf_field() }}
                        
    <input name="node_type" type="hidden" value="{{ $nodeType }}" />
                        
    @foreach($object->getAutomaticRenderAtributesAndRelations() as $field)
        @include('blocks.model', ['field' => $field])
    @endforeach

    <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
        <label class="{{ HtmlElementsClasses::getHtmlClassForElement('label_for_element') }}" for="content">@lang('models_labels.Node.content')</label>
        <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_with_label') }}">
            <textarea id="content" name="content" class="sir-trevor editable">{{ Request::old('content') ?: '' }}</textarea>
            @if ($errors->has('content'))
                <span class="help-block">{{ $errors->first('content') }}</span>
            @endif
        </div>
    </div>

    <div class="ln_solid"></div>

    <div class="form-group">
        <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_without_label') }}">
            <button type="submit" class="btn btn-success">{{ Utils::translateModelData('blade_templates.admin.global.create_button_text')}}</button>
        </div>
    </div>
</form>
{!! SirTrevor::scripts($stFields) !!}