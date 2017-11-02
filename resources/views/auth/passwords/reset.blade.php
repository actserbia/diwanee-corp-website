@extends('layouts.app')

@section('heading')
    @lang('blade_templates.auth.reset_password_title')
@endsection

@section('content')
    <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
        {{ csrf_field() }}

        <input type="hidden" name="token" value="{{ $token }}">

        @include('blocks.form_input', ['name' => 'email', 'label' => __('blade_templates.users.email'), 'type' => 'email', 'value' => Request::old('email') ?: '', 'required' => true, 'template' => 'app'])

        @include('blocks.form_input', ['name' => 'password', 'label' => __('blade_templates.users.password'), 'type' => 'password', 'value' => Request::old('password') ?: '', 'required' => true, 'template' => 'app'])

        @include('blocks.form_input', ['name' => 'password_confirmation', 'label' => __('blade_templates.auth.confirm_password'), 'type' => 'password', 'value' => Request::old('confirm_password') ?: '', 'required' => true, 'template' => 'app'])


        <div class="form-group">
            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('button', 'app') }}">
                <button type="submit" class="btn btn-primary">
                    @lang('blade_templates.auth.reset_password_button_text')
                </button>
            </div>
        </div>
    </form>
@endsection
