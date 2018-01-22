@extends('layouts.admin')

@section('content')
<div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('blade_templates.admin.users.edit_user_title') <a href="{{ route('users.index') }}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('blade_templates.global.back') </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form id="data_form" method="post" action="{{ route('users.update', ['id' => $object->id]) }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}

                        @include('blocks.model', ['field' => 'name'])

                        @include('blocks.model', ['field' => 'email', 'readonly' => true])

                        @include('blocks.model', ['field' => 'role'])

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_without_label') }}">
                                <input name="_method" type="hidden" value="PUT">
                                <button type="submit" class="btn btn-success">@lang('blade_templates.admin.users.edit_user_button_text')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
