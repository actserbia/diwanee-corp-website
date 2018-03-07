@extends('layouts.admin')

@if (isset($object->modelType))
    @push('stylesheets')
        {!! SirTrevor::stylesheets() !!}
    @endpush
@endif
@section('content')
<div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ Utils::translateModelData('blade_templates.admin.global.create_title')}} <a href="{{ route('nodes.index') }}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('blade_templates.global.back') </a></h2>
                    <div class="clearfix"></div>
                </div>
                
                <div id="node-create" class="x_content form-horizontal">
                    <br />
                    <form id="data_form" method="post" action="{{ route('nodes.store') }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}

                        @include('blocks.model', ['field' => 'model_type'])

                        <div class="ln_solid"></div>
                        
                        @if (isset($object->modelType))
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
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@if (isset($object->modelType))
    @push('scripts')
        {!! SirTrevor::scripts($stFields, $stReqFields) !!}
    @endpush
@endif