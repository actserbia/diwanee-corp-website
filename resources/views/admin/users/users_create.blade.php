@extends('layouts.admin')

@section('content')
<div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('blade_templates.admin.users.create_user_title') <a href="{{ route('users.index') }}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('blade_templates.global.back') </a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('users.store') }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}
                        
                        @include('blocks.form_input', ['name' => 'name', 'label' => __('blade_templates.users.name'), 'value' => Request::old('name') ?: '', 'required' => true])

                        @include('blocks.form_input', ['name' => 'email', 'label' => __('blade_templates.users.email'), 'type' => 'email', 'value' => Request::old('email') ?: '', 'required' => true])

                        @include('blocks.form_input', ['name' => 'password', 'label' => __('blade_templates.users.password'), 'type' => 'password', 'value' => Request::old('password') ?: '', 'required' => true])

                        @include('blocks.form_input', ['name' => 'confirm_password', 'label' => __('blade_templates.auth.confirm_password'), 'type' => 'password', 'value' => Request::old('confirm_password') ?: '', 'required' => true])

                        @include('blocks.form_select', ['name' => 'role', 'label' => __('blade_templates.users.role'), 'items' => $roles, 'selected' => Request::old('role') ?: '', 'required' => true])

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('button', 'admin') }}">
                                <button type="submit" class="btn btn-success">@lang('blade_templates.admin.users.create_user_button_text')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop