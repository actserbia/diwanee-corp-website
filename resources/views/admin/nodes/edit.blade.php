@extends('layouts.admin')

@push('stylesheets')
{!! SirTrevor::stylesheets() !!}
@endpush

@section('content')
<div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ Utils::translateModelData('blade_templates.admin.global.edit_title')}} <a href="{{ route('nodes.index') }}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('blade_templates.global.back') </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form id="data_form" method="post" action="{{ route('nodes.update', ['id' => $object->id]) }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}
                        
                        <input name="nodeType" type="hidden" value="{{ $object->nodeType->id }}" />
                        
                        @foreach($object->getFillableFields() as $field)
                            @include('blocks.model', ['field' => $field])
                        @endforeach
                        
                        @foreach($object->getFillableRelations() as $relation)
                            @include('blocks.model', ['field' => $relation])
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
                                <input name="_method" type="hidden" value="PUT">
                                <button type="submit" class="btn btn-success">{{ Utils::translateModelData('blade_templates.admin.global.edit_button_text')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{!! SirTrevor::scripts() !!}
@endpush