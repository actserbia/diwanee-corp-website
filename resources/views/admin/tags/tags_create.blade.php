@extends('layouts.admin')

@section('content')
<div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('blade_templates.admin.tags.create_tag_title') <a href="{{ route('tags.index') }}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('blade_templates.global.back') </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('tags.store') }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}
                        
                        @include('blocks.form_input', ['name' => 'name', 'label' => __('blade_templates.tags.name'), 'value' => Request::old('name') ?: '', 'required' => true])

                        @include('blocks.form_select', ['name' => 'type', 'label' => __('blade_templates.tags.type'), 'items' => $types, 'selected' => Request::old('type') ?: '', 'required' => true])

                        @include('blocks.form_multiple_tags', ['name' => 'parents', 'label' => __('blade_templates.tags.parents')])

                        @include('blocks.form_multiple_tags', ['name' => 'children', 'label' => __('blade_templates.tags.children')])

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('button', 'admin') }}">
                                <button type="submit" class="btn btn-success">@lang('blade_templates.admin.tags.create_tag_button_text')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
