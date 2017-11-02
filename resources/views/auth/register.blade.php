@extends('layouts.app')

@section('heading')
    @lang('blade_templates.auth.register_title')
@endsection

@section('content')
    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        @include('blocks.form_input', ['name' => 'name', 'label' => __('blade_templates.users.name'), 'value' => Request::old('name') ?: '', 'required' => true, 'template' => 'app'])

        @include('blocks.form_input', ['name' => 'email', 'label' => __('blade_templates.users.email'), 'type' => 'email', 'value' => Request::old('email') ?: '', 'required' => true, 'template' => 'app'])

        @include('blocks.form_input', ['name' => 'password', 'label' => __('blade_templates.users.password'), 'type' => 'password', 'value' => Request::old('password') ?: '', 'required' => true, 'template' => 'app'])

        @include('blocks.form_input', ['name' => 'password_confirmation', 'label' => __('blade_templates.auth.confirm_password'), 'type' => 'password', 'value' => Request::old('password_confirmation') ?: '', 'required' => true, 'template' => 'app'])


        <div class="form-group">
            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_without_label', 'app') }}">
                <button type="submit" class="btn btn-primary">
                    @lang('blade_templates.auth.register_button_text')
                </button>
            </div>
        </div>
    </form>
@endsection
