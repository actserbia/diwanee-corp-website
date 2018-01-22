@extends('layouts.app')

@section('heading')
    @lang('blade_templates.auth.reset_password_title')
@endsection

@section('content')
    <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
        {{ csrf_field() }}

        <input type="hidden" name="token" value="{{ $token }}">

        @include('blocks.model', ['field' => 'email'])
        
        @include('blocks.model', ['field' => 'password', 'confirm' => true])

        <div class="form-group">
            <div class="{{ HtmlElementsClasses::getHtmlClassForElement('element_div_without_label') }}">
                <button type="submit" class="btn btn-primary">
                    @lang('blade_templates.auth.reset_password_button_text')
                </button>
            </div>
        </div>
    </form>
@endsection
