@extends('layouts.app')

@section('heading')
    @lang('blade_templates.auth.login_title')
@endsection

@section('content')
    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        @include('blocks.form_input', ['name' => 'email', 'label' => __('blade_templates.users.email'), 'type' => 'email', 'value' => Request::old('email') ?: '', 'required' => true, 'template' => 'app'])

        @include('blocks.form_input', ['name' => 'password', 'label' => __('blade_templates.users.password'), 'type' => 'password', 'value' => Request::old('password') ?: '', 'required' => true, 'template' => 'app'])

        <div class="form-group">
            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_without_label', 'app') }}">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> @lang('blade_templates.auth.remember_me')
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('button', 'app') }}">
                <button type="submit" class="btn btn-primary">
                    @lang('blade_templates.auth.login_button_text')
                </button>

                <a class="btn btn-link" href="{{ route('password.request') }}">
                    @lang('blade_templates.auth.forgot_password')
                </a>
            </div>
        </div>
    </form>
@endsection
